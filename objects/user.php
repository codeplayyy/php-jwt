<?php
    class User {

        private $conn;
        private $table_name = "users";
    
        public $id;
        public $name;
        public $email;
        public $password;
    
        public function __construct($db) {
            $this->conn = $db;
        }

        function create() {
    
            $query = "INSERT INTO " . $this->table_name . "
            SET
                name        = :name,
                email       = :email,
                password    = :password";

            $statement = $this->conn->prepare($query);

            $this->name     = htmlspecialchars(strip_tags($this->name));
            $this->email    = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));

            $statement->bindParam(':name', $this->name);
            $statement->bindParam(':email', $this->email);

            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $statement->bindParam(':password', $password_hash);

            if($statement->execute()){
                return true;
            }

            return false;
        }

        function emailExists() {
            $query = "SELECT id, name, password
                    FROM " . $this->table_name . "
                    WHERE email = ?
                    LIMIT 0, 1";

            $statement = $this->conn->prepare( $query );
        
            $this->email=htmlspecialchars(strip_tags($this->email));
        
            $statement->bindParam(1, $this->email);
        
            $statement->execute();
        
            $num = $statement->rowCount();
        
            if($num > 0) {
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->password = $row['password'];
                return true;
            }
            return false;
        }
    }
?>