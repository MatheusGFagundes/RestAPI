<?php
// requisita cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// inclui base de dados e arquivos de objeto produto
include_once '../config/database.php';
include_once '../objects/product.php';
 
// instancia banco de dados e objeto de produto
$database = new Database();
$db = $database->getConnection();
 
// initializa objeto
$product = new Product($db);
 
// seleciona produtos
$stmt = $product->read();
$num = $stmt->rowCount();
 
// checa se há mais de 0 registros
if($num>0){
 
    // cria array de produtos
    $products_arr=array();
 
    // recupera conteudo das tabelas
    // fetch() é mais rapido que fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extrai linha, transforma $row['name'] 
        // para $name
        extract($row);
 
        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
 
        array_push($products_arr, $product_item);
    }
 
    // define código de resposta - 200 OK
    http_response_code(200);
 
    // mostra dados de produtos no formato JSON
    echo json_encode($products_arr);
}
 
else{
 
    // define código de resposta - 404 Não encontrado
    http_response_code(404);
 
    // responde usuario
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>