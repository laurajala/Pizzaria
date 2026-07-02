<?php
session_start();
require_once("process/conn.php");

$msg = $_SESSION["msg"] ?? "";
$status = $_SESSION["status"] ?? "";

// limpa após exibir (boa prática)
unset($_SESSION["msg"], $_SESSION["status"]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Pizzaria do João 🍕</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <!-- CSS próprio -->
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <a class="navbar-brand" href="index.php">
      🍕 Pizzaria do João
    </a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Fazer pedido</a>
        </li>
      </ul>
    </div>

  </nav>
</header>

<?php if (!empty($msg)): ?>
  <div class="alert alert-<?= $status ?>">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>
