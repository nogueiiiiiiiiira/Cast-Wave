<?php

// inicia a sessão
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projetoLocadora/paginas/login.php'); 
    exit();
}

// conexão com o database de dados 
$host = "localhost"; 
$usuario = "root";  
$password = ""; 
$database = "castwave";  

$conn = new mysqli($host, $usuario, $password, $database);

// verificação de conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// consulta SQL para obter os dados do usuário
$sql = "SELECT nome, email, telefone FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($usuario_nome, $usuario_email, $usuario_telefone);
$stmt->fetch();
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Conta</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- importa a font do google -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> <!-- importa o CSS do bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" /> <!-- importa o CSS do font awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- importa o JS do bootstrap -->

  <link rel="stylesheet" href="../assets/css/editar_conta.css" /> <!-- importa o CSS do editar_conta -->
</head>
<body>

<br>
<br>
<br>
<br>
<br>
<br>

<div class="container mt-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header text-center">
      <i class="fa-solid fa-user-circle me-2"></i>Editar Conta
    </div>

    <br>
    <div class="card-body">
    <form id="form_conta" action="../paginas/salvar_edicao.php" method="POST">
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu novo nome">
          </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu novo e-mail">
          </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Digite seu novo telefone">
          </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua nova senha">
          </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control" id="senha2" name="senha2" placeholder="Confirme sua nova senha">
        </div>

        <br>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-custom">
                Salvar alterações
            </button>
        </div>
      </form>
      <div class="text-center mt-3">
        <button class="btn btn-custom" onclick="window.location.href='./minha_conta.php';">
            Cancelar alterações
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../assets/scripts/editar_conta.js"></script> <!-- importa o JS do editar_conta -->

</body>

</html>
