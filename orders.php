<?php

include_once("conn.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {

  $pedidos = $conn->query("SELECT * FROM pedidos")->fetchAll(PDO::FETCH_ASSOC);

  $pizzas = [];

  foreach ($pedidos as $pedido) {

    $pizzaId = $pedido["pizza_id"];

    $pizzaQuery = $conn->prepare("SELECT * FROM pizzas WHERE id = :id");
    $pizzaQuery->bindParam(":id", $pizzaId, PDO::PARAM_INT);
    $pizzaQuery->execute();
    $pizzaData = $pizzaQuery->fetch(PDO::FETCH_ASSOC);

    $bordaQuery = $conn->prepare("SELECT tipo FROM bordas WHERE id = :id");
    $bordaQuery->bindParam(":id", $pizzaData["borda_id"], PDO::PARAM_INT);
    $bordaQuery->execute();
    $borda = $bordaQuery->fetchColumn();

    $massaQuery = $conn->prepare("SELECT tipo FROM massas WHERE id = :id");
    $massaQuery->bindParam(":id", $pizzaData["massa_id"], PDO::PARAM_INT);
    $massaQuery->execute();
    $massa = $massaQuery->fetchColumn();

    $saboresQuery = $conn->prepare("
      SELECT s.nome 
      FROM pizza_sabor ps
      JOIN sabores s ON s.id = ps.sabor_id
      WHERE ps.pizza_id = :pizza_id
    ");

    $saboresQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);
    $saboresQuery->execute();
    $sabores = $saboresQuery->fetchAll(PDO::FETCH_COLUMN);

    $pizzas[] = [
      "id" => $pizzaId,
      "borda" => $borda,
      "massa" => $massa,
      "sabores" => $sabores,
      "status" => $pedido["status_id"]
    ];
  }

  $status = $conn->query("SELECT * FROM status")->fetchAll(PDO::FETCH_ASSOC);
}

if ($method === "POST") {

  $type = $_POST["type"] ?? null;

  if ($type === "delete") {

    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM pedidos WHERE pizza_id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION["msg"] = "Pedido removido com sucesso!";
    $_SESSION["status"] = "success";
  }

  if ($type === "update") {

    $id = $_POST["id"];
    $statusId = $_POST["status"];

    $stmt = $conn->prepare("
      UPDATE pedidos 
      SET status_id = :status 
      WHERE pizza_id = :id
    ");

    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":status", $statusId, PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION["msg"] = "Pedido atualizado com sucesso!";
    $_SESSION["status"] = "success";
  }

  header("Location: ../dashboard.php");
  exit;
}
