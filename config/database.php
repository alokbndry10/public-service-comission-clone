<?php
/**
 * Database Connection Configuration
 * 
 * This file contains the database connection settings
 * using PDO (PHP Data Objects) for secure database operations
 */

class Database {
    // Database credentials
    private $host = "localhost";
    private $db_name = "egov_db";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    
    public $conn;

    /**
     * Get database connection
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed'
            ]);
            exit;
        }

        return $this->conn;
    }
}
?>
