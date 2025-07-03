<?php
class Database {
    private $host = "localhost";
    private $db_name = "uas_241011700681_event_db";
    private $username = "root";
    private $password = "root1234";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Koneksi Gagal: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
