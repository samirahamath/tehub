<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_name = 'shacartc_expert';
$db_user = 'shacartc_expert';
$db_pass = 'shacartc_expert';

$pdo = null;

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback to default XAMPP root credentials if specific user fails
    try {
        $pdo = new PDO("mysql:host={$db_host};charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$db_name}`");
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (Exception $e2) {
        $pdo = null;
    }
}

// Auto-create tables if DB connected
if ($pdo) {
    try {
        // Users Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'client') DEFAULT 'client',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Services Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(150) NOT NULL,
            short_description TEXT,
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            price_prefix VARCHAR(50) DEFAULT 'From ₹',
            duration_info VARCHAR(100) DEFAULT '',
            features TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Orders Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            client_id INT NOT NULL,
            service_id INT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            status ENUM('Pending', 'Active', 'Completed', 'Cancelled') DEFAULT 'Pending',
            payment_method VARCHAR(50) DEFAULT 'Gateway',
            payment_id VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Settings Table
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            setting_key VARCHAR(100) PRIMARY KEY,
            setting_value TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Seed default admin if not exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role='admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
            $stmtInsert = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@tehub.com', ?, 'admin')");
            $stmtInsert->execute([$adminPass]);
        }

        // Seed default services if empty
        $stmtSvc = $pdo->query("SELECT COUNT(*) FROM services");
        if ($stmtSvc->fetchColumn() == 0) {
            $defaultServices = [
                [
                    'title' => 'MVP & Automation',
                    'short_description' => 'For early-stage startups needing a functional prototype to showcase to investors, or businesses seeking custom workflow scripting and integrations.',
                    'price' => 10000.00,
                    'price_prefix' => 'From ₹',
                    'duration_info' => '1 week',
                    'features' => "1 Lead architect · 1 developer\nFully functional application prototype\nClean TypeScript backend & Next.js frontend\nCustom workflow integrations (Zapier, Make, custom APIs)\nZero-downtime cloud hosting setup (Vercel / AWS)\n3-month code warranty & updates"
                ],
                [
                    'title' => 'Custom App & Web',
                    'short_description' => 'Our core tier — custom web platforms, native or cross-platform mobile apps (Flutter / React Native), high-performance databases, and custom API systems built for scaling.',
                    'price' => 25000.00,
                    'price_prefix' => 'From ₹',
                    'duration_info' => '1-2 weeks',
                    'features' => "1 Project lead · 2 developers · 1 DevOps\nProduction-grade web or mobile app codebase\nAutomated unit and integration testing pipelines\nAdvanced cloud config (AWS / GCP / Docker)\nFull database architecture & schema migrations\n12-month hosting maintenance & backups\nComprehensive API docs & system runbooks"
                ],
                [
                    'title' => 'Enterprise Partnership',
                    'short_description' => 'A long-term engineering partnership. THE EXPERT HUB functions as your dedicated engineering and product squad, delivering weekly sprints and feature updates.',
                    'price' => 75000.00,
                    'price_prefix' => 'From ₹',
                    'duration_info' => 'retainer scale',
                    'features' => "Dedicated lead engineer, frontend, backend, & QA\nContinuous integration & deployment (CI/CD)\nPriority SLA on bug fixes and incident response\nBi-weekly sprint planning & demo presentations\nComprehensive security audits & code reviews\nFull access to private packages & shared modules\n24/7 server health and load monitoring"
                ]
            ];

            $stmtAdd = $pdo->prepare("INSERT INTO services (title, short_description, price, price_prefix, duration_info, features, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
            foreach ($defaultServices as $ds) {
                $stmtAdd->execute([$ds['title'], $ds['short_description'], $ds['price'], $ds['price_prefix'], $ds['duration_info'], $ds['features']]);
            }
        } else {
            // Update existing service records to new currency symbol and prices
            $pdo->exec("UPDATE services SET price = 10000.00, price_prefix = 'From ₹' WHERE id = 1 OR title LIKE '%MVP%'");
            $pdo->exec("UPDATE services SET price = 25000.00, price_prefix = 'From ₹' WHERE id = 2 OR title LIKE '%Custom%'");
            $pdo->exec("UPDATE services SET price = 75000.00, price_prefix = 'From ₹' WHERE id = 3 OR title LIKE '%Enterprise%'");
            $pdo->exec("UPDATE services SET price_prefix = REPLACE(price_prefix, '$', '₹') WHERE price_prefix LIKE '%$%'");
        }
    } catch (Exception $ex) {
        // Log or handle schema initialization errors if any
    }
}

function get_setting($key, $default = '')
{
    global $pdo;
    if (!$pdo)
        return $default;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $res = $stmt->fetchColumn();
        return $res !== false ? $res : $default;
    } catch (Exception $e) {
        return $default;
    }
}

function set_setting($key, $value)
{
    global $pdo;
    if (!$pdo)
        return false;
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch (Exception $e) {
        return false;
    }
}
