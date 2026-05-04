<?php
session_start();
require_once '../Conn.php';

$conn = new Conn();
$pdo = $conn->conectar();

$sql = "SELECT id, nome, cidade FROM fornecedor ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- resto do HTML igual ao que você já tem -->