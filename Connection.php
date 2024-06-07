<?php
    class Connection {

        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $database = "crud_dss";

        private $connection;
    
        // Declaramos el constructor
        public function __construct() {
            $this->connect();
        }
    
        // Declaramos el metodo para generar la conexion
        private function connect() {
            try {
                $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
                if ($this->connection->connect_error) {
                    throw new Exception($this->connection->connect_error);
                }
            } catch (Exception $e) {
                $exception_error = "Error de conexión: " . $e->getMessage();
                header("Location: 500.php?error=" . urlencode($exception_error));
                exit(); // Nos aseguramos que el script se detenga después de la redirección
            }                    
        }
    
        // Declaramos un metodo get para regresar la conexion
        public function getConnection() {
            return $this->connection;
        }
    
        // Declaramos un metodo para cerrar la conexion
        public function closeConnection() {
            $this->connection->close();
        }
    }    