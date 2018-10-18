<?php
    class Product{
     
        // conexão com o banco de dados e tabela usada
        private $conn;
        private $table_name = "products";
     
        //propriedades do objeto
        public $id;
        public $name;
        public $description;
        public $price;
        public $category_id;
        public $category_name;
        public $created;
     
        //  construtor da conexão de Database
        public function __construct($db){
            $this->conn = $db;
        }
        // le produtos
        function read(){
     
            // seleciona todos os produtos
            $query = "SELECT
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                    FROM
                        " . $this->table_name . " p
                        LEFT JOIN
                            categories c
                                ON p.category_id = c.id
                    ORDER BY
                        p.created DESC";
         
            // prepara statement da query 
            $stmt = $this->conn->prepare($query);
         
            // executa query
            $stmt->execute();
         
            return $stmt;
        }
        // cria product
        function create(){
         
            // criação do insert
            $query = "INSERT INTO
                        " . $this->table_name . "
                    SET
                        name=:name, price=:price, description=:description, category_id=:category_id, created=:created";
         
            // prepara query
            $stmt = $this->conn->prepare($query);
         
            // limpa formatacao
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->price=htmlspecialchars(strip_tags($this->price));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->category_id=htmlspecialchars(strip_tags($this->category_id));
            $this->created=htmlspecialchars(strip_tags($this->created));
         
            // vincula valores
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":category_id", $this->category_id);
            $stmt->bindParam(":created", $this->created);
         
            // executa query
            if($stmt->execute()){
                return true;
            }
         
            return false;
             
        }
        // atualiza o produto
        function update(){
         
            // cria query
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        name = :name,
                        price = :price,
                        description = :description,
                        category_id = :category_id
                    WHERE
                        id = :id";
         
            // prepara statement da query 
            $stmt = $this->conn->prepare($query);
         
            // limpa formatacao
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->price=htmlspecialchars(strip_tags($this->price));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->category_id=htmlspecialchars(strip_tags($this->category_id));
            $this->id=htmlspecialchars(strip_tags($this->id));
         
            // vincula novos valores
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);
         
            // executa a query
            if($stmt->execute()){
                return true;
            }
         
            return false;
        }
        // função auxiliar para preencher o formulário de atualização de produto
        function readOne(){
         
            // leitura de unico produto
            $query = "SELECT
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                    FROM
                        " . $this->table_name . " p
                        LEFT JOIN
                            categories c
                                ON p.category_id = c.id
                    WHERE
                        p.id = ?
                    LIMIT
                        0,1";
         
            // prepara statement da query 
            $stmt = $this->conn->prepare( $query );
         
            // vincula id do produto para atualização
            $stmt->bindParam(1, $this->id);
         
            // executa query
            $stmt->execute();
         
            // obtem linha recurerada
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            // define os valores das propriedades do objeto
            $this->name = $row['name'];
            $this->price = $row['price'];
            $this->description = $row['description'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
        }
        // deleta o produto
        function delete(){
         
            // cria query de delete
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
         
            // prepara query
            $stmt = $this->conn->prepare($query);
         
            // limpa formatacao
            $this->id=htmlspecialchars(strip_tags($this->id));
         
            // vincula id do registro para realizar delete
            $stmt->bindParam(1, $this->id);
         
            // executa query
            if($stmt->execute()){
                return true;
            }
         
            return false;
             
        }
        // procura produtos
        function search($keywords){
         
            // seleciona todos produtos
            $query = "SELECT
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                    FROM
                        " . $this->table_name . " p
                        LEFT JOIN
                            categories c
                                ON p.category_id = c.id
                    WHERE
                        p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                    ORDER BY
                        p.created DESC";
         
            // prepara statement da query 
            $stmt = $this->conn->prepare($query);
         
            // limpa formatacao
            $keywords=htmlspecialchars(strip_tags($keywords));
            $keywords = "%{$keywords}%";
         
            // vincula parametros 
            $stmt->bindParam(1, $keywords);
            $stmt->bindParam(2, $keywords);
            $stmt->bindParam(3, $keywords);
         
            // executa query
            $stmt->execute();
         
            return $stmt;
        }
        // le produtos com paginacao
        public function readPaging($from_record_num, $records_per_page){
         
            // cria select
            $query = "SELECT
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                    FROM
                        " . $this->table_name . " p
                        LEFT JOIN
                            categories c
                                ON p.category_id = c.id
                    ORDER BY p.created DESC
                    LIMIT ?, ?";
         
            // prepara statement da query 
            $stmt = $this->conn->prepare( $query );
         
            // vincula valores das variaveis
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
         
            // executa query
            $stmt->execute();
         
            // return valores do banco
            return $stmt;
        }
        // funcao auxiliar para paginacao dos produtos
        public function count(){
            $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
         
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
            return $row['total_rows'];
        }
    }
?>