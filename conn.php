<?php

$host = "localhost";
$db = "pizzaria";
$user = "root";
$pass = "";

try {
    $conn = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    error_log("Erro de conexão: " . $e->getMessage());
    die("Erro ao conectar com o banco de dados.");
}
