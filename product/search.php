<?php
// requisita cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// inclui base de dados earquivos de objeto
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 
// instantancia banco de dados e objeto produto
$database = new Database();
$db = $database->getConnection();
 
// inicializa objeto
$product = new Product($db);
 
// pega palavras-chave
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
 
// seleciona produtos
$stmt = $product->search($keywords);
$num = $stmt->rowCount();
 
// checa se há mais de 0 registros
if($num>0){
 
    // array produtos
    $products_arr=array();
    $products_arr["records"]=array();
 
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
 
        array_push($products_arr["records"], $product_item);
    }
 
    // define código de resposta - 200 OK
    http_response_code(200);
 
    // cria dados em formato json
    echo json_encode($products_arr);
}
 
else{
    // define código de resposta - 404 não encontrado
    http_response_code(404);
 
    // responde usuário
    echo json_encode(
        array("message" => "No products found.")
    );
}