<?php
// requisição de cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//conecta com o banco de dados
include_once '../config/database.php';
 
// instantancia objeto produto
include_once '../objects/product.php';
 
$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 
// pega dados postados
$data = json_decode(file_get_contents("php://input"));
 
// verifica se dados não estão vazios
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
){
 
    // determina propriedades do produto
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');
 
    // creia o produto
    if($product->create()){
 
        // define codigo de resposta - 201 criado
        http_response_code(201);
 
        // responde usuario
        echo json_encode(array("message" => "Product was created."));
    }
 
    // responde usuario se produto nao for criado
    else{
 
        // define codigo de resposta - 503 servico indisponivel
        http_response_code(503);
 
        // responde usuario
        echo json_encode(array("message" => "Unable to create product."));
    }
}
 
// responde usuario se dados estão incompletos
else{
 
    // define codigo de resposta - 400 pedidoc invalido 
    http_response_code(400);
 
    // responde usuario
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
?>