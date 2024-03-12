<?php

class Database {
     private $hostname = "74.234.11.226";
     private $database = "pasadena_online";
<<<<<<< HEAD
     private $username = "pasadena";
     private $password = "pasadena";
=======
     private $username = "root";
     private $password = "Miguel1723";
>>>>>>> 800a88ef68dd30d44532aef32ea788a5c4a54c03
     private $charset = "utf8";


     function conectar(){

        try{
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($conexion, $this->username, $this->password, $options);

            return $pdo;
        }catch(PDOException $e){
            echo 'Error conexion: ' . $e->getMessage();
            exit;
        }


     }
     
     

}

?>