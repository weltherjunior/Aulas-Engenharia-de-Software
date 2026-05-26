<?php 


try {
    $conn = new PDO("mysql:host=localhost;dbname=clinica_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"Erro ao conectar com o banco de dados."], JSON_UNESCAPED_UNICODE);
    exit;
}


?>