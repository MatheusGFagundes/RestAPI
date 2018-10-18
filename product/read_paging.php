<?php
// requisita cabeçadlhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// inclui base de dados e arquivos do objeto produto
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/product.php';
 
// utilidades
$utilities = new Utilities();
 
// inicializa base de dados e objeto produto
$database = new Database();
$db = $database->getConnection();
 
// inicializa objeto
$product = new Product($db);
 
// seleciona produtos
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// checa se há mais de 0  registros encontrados
if($num>0){
 
    // array produtos
    $products_arr=array();
    $products_arr["records"]=array();
    $products_arr["paging"]=array();
 
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
 
 
    // inclui paginação
    $total_rows=$product->count();
    $page_url="{$home_url}product/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"]=$paging;
 
    // define código de resposta - 200 OK
    http_response_code(200);
 
    // cria formato json
    echo json_encode($products_arr);
}
 
else{
 
    // define código de resposta - 404 não encontrado
    http_response_code(404);
 
    // responde usuario
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>