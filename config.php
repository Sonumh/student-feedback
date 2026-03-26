<?php
// ============================================
// DATABASE CONFIGURATION
// Edit these values in cPanel before use
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'gateway_feedback');   // Your cPanel DB name (usually prefixed: user_gateway_feedback)
define('DB_USER', 'root');               // Your cPanel DB username
define('DB_PASS', '');                   // Your cPanel DB password
define('DB_CHARSET', 'utf8mb4');

// Admin session secret (change this to something random)
define('SESSION_SECRET', 'gw_elec_2025_secret_key_change_me');

// Site base path (match your subfolder)
define('BASE_PATH', '/student-feedback');

// ============================================
// PDO Connection
// ============================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['success' => false, 'message' => 'Database connection failed. Please check config.php']));
        }
    }
    return $pdo;
}
?>
