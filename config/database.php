<?php
class Database{
 
    // especificar as credenciais de seu banco de dados
    private $host = "localhost";
    private $nome_banco = "api_db";
    private $usuario = "root";
    private $senha = "root";
    public $conn;
 
    // realizar conexão com o banco
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->nome_banco, $this->usuario, $this->senha);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>