<?php
// requisita cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// inclui banco de dados e objeto produto
include_once '../config/database.php';
include_once '../objects/product.php';
 
// conecta com o banco
$database = new Database();
$db = $database->getConnection();
 
// prepara objeto produto
$product = new Product($db);
 
// pega id do produto
$data = json_decode(file_get_contents("php://input"));
 
// define id do produto para ser deletado
$product->id = $data->id;
 
// deleta o produto
if($product->delete()){
 
    // define codigo de resposta - 200 ok
    http_response_code(200);
 
    // responde usuario
    echo json_encode(array("message" => "Product was deleted."));
}
 
// se for possivel deletar o produto
else{
 
    // define código de resposta - 503 servico indisponivel
    http_response_code(503);
 
    // responde usuario
    echo json_encode(array("message" => "Unable to delete product."));
}
?>