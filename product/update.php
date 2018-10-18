<?php
// requisita cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// inclui base de dados e arquivos de objeto produto
include_once '../config/database.php';
include_once '../objects/product.php';
 
// conecta com banco de dados
$database = new Database();
$db = $database->getConnection();
 
// prepara objeto de produto
$product = new Product($db);
 
// pega id do produto a ser editado
$data = json_decode(file_get_contents("php://input"));
 
// define id do produto a ser editado
$product->id = $data->id;
 
// define valores do produto
$product->name = $data->name;
$product->price = $data->price;
$product->description = $data->description;
$product->category_id = $data->category_id;
 
// atualiza produto
if($product->update()){
 
    // define código de resposta - 200 ok
    http_response_code(200);
 
    // responde usuario
    echo json_encode(array("message" => "Product was updated."));
}
 
// se nao for possivel editar o produto, responda usuario
else{
 
    // define código de resposta - 503 serviço indisponivel
    http_response_code(503);
 
    // responde usuario
    echo json_encode(array("message" => "Unable to update product."));
}
?>