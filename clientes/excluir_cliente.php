<?php

$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $conn->prepare("DELETE FROM cliente WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
if ($stmt->execute()) {
    echo "<script>alert('Cliente excluído com sucesso!'); window.location='index.php';</script>";
    exit;
} else {
    echo "<script>alert('Erro ao excluir cliente.'); window.location='index.php';</script>";
    exit;
}

?>