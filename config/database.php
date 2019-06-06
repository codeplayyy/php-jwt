<?php
    class Database{
        private $host       = "localhost";
        private $db         = "api_db";
        private $username   = "root";
        private $password   = "";
        
        public $conn;
    
        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password);
            } catch(PDOException $exception) {
                echo $exception->getMessage();
                die(json_encode(array(
                    "success" => false,
                    "message" => "Connection failed"
                )));
            }
    
            return $this->conn;
        }
    }
?>