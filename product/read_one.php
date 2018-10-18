<?php
// requisita cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// inclui base de dados e objeto produto
include_once '../config/database.php';
include_once '../objects/product.php';

// conexão com o banco
$database = new Database();
$db = $database->getConnection();
 
// prepara obejto produto
$product = new Product($db);
 
// define id do registro para leitura
$product->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// le os destalhes do produto para edicao
$product->readOne();
 
if($product->name!=null){
    // cria array
    $product_arr = array(
        "id" =>  $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_name
 
    );
 
    // define código de resposta - 200 OK
    http_response_code(200);
 
    // cria formato json
    echo json_encode($product_arr);
}
 
else{
    // define código de resposta - 404 não encontrado
    http_response_code(404);
 
    // responde usuário
    echo json_encode(array("message" => "Product does not exist."));
}
?>