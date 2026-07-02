<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {

  $bordas = $conn->query("SELECT * FROM bordas")->fetchAll(PDO::FETCH_ASSOC);
  $massas = $conn->query("SELECT * FROM massas")->fetchAll(PDO::FETCH_ASSOC);
  $sabores = $conn->query("SELECT * FROM sabores")->fetchAll(PDO::FETCH_ASSOC);

} elseif ($method === "POST") {

  $borda = $_POST["borda"] ?? null;
  $massa = $_POST["massa"] ?? null;
  $sabores = $_POST["sabores"] ?? [];

  if (count($sabores) > 3) {

    $_SESSION["msg"] = "Selecione no máximo 3 sabores!";
    $_SESSION["status"] = "warning";

    header("Location: ../index.php");
    exit;
  }

  $stmt = $conn->prepare("
    INSERT INTO pizzas (borda_id, massa_id)
    VALUES (:borda, :massa)
  ");

  $stmt->bindParam(":borda", $borda, PDO::PARAM_INT);
  $stmt->bindParam(":massa", $massa, PDO::PARAM_INT);
  $stmt->execute();

  $pizzaId = $conn->lastInsertId();

  $stmt = $conn->prepare("
    INSERT INTO pizza_sabor (pizza_id, sabor_id)
    VALUES (:pizza, :sabor)
  ");

  foreach ($sabores as $sabor) {
    $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
    $stmt->bindParam(":sabor", $sabor, PDO::PARAM_INT);
    $stmt->execute();
  }

  $statusId = 1;

  $stmt = $conn->prepare("
    INSERT INTO pedidos (pizza_id, status_id)
    VALUES (:pizza, :status)
  ");

  $stmt->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
  $stmt->bindParam(":status", $statusId, PDO::PARAM_INT);
  $stmt->execute();

  $_SESSION["msg"] = "Pedido realizado com sucesso!";
  $_SESSION["status"] = "success";

  header("Location: ../index.php");
  exit;
}
