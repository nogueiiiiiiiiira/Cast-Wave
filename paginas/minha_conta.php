<?php

session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ./login.php'); 
  exit();
}

// conexão com o banco de dados 
$servername = "localhost"; 
$username = "root";  
$password = ""; 
$dbname = "castwave";  

// criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// verificação de conexão
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// consulta SQL para obter o nome, email, telefone e senha do usuário
$sql = "SELECT nome, email, telefone FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($usuario_nome, $usuario_email, $usuario_telefone);
$stmt->fetch();
$stmt->close();
$conn->close();

// caso algum dado não seja encontrado
if (!$usuario_nome) {
  $usuario_nome = "Usuário desconhecido";
}
if (!$usuario_email) {
  $usuario_email = "Email não encontrado";
}
if (!$usuario_telefone) {
  $usuario_telefone = "Telefone não encontrado";
}

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Minha Conta</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- importa a font do google -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> <!-- importa o CSS do bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" /> <!-- importa o CSS do font awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- importa o JS do bootstrap -->

  <link rel="stylesheet" href="../assets/css/minha_conta.css" /> <!-- importa o CSS do minha_conta -->
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">        
      <li class="nav-item"><a class="nav-link" href="./catalogo.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="./minha_conta.php">Minha Conta</a></li>
        <li class="nav-item"><a class="nav-link" href="./meus_alugueis.php">Meus Aluguéis</a></li>
        <li class="nav-item"><a class="nav-link" href="./logout.php">Sair</a></li>
      </ul>
    </div>
  </div>
</nav>

<br>
<br>

<div class="container mt-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header text-center">
      <i class="fa-solid fa-user-circle me-2"></i>Minha Conta
    </div>

    <br>
    <div class="card-body">
      <form>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario_nome); ?>" disabled>
        </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario_email); ?>" disabled>
        </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario_telefone); ?>" disabled>
        </div>

        <br>

        <div class="text-center mt-3">
        <a href="editarConta.php" class="btn btn-custom">
          Editar minha conta
        </a>
      </div>
      </form>

      <div class="text-center mt-3">
        <button class="btn btn-custom" onclick="confirmarExclusao()">
          Excluir minha conta
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../assets/scripts/minha_conta.js"></script> <!-- importa o JS do catalogo -->

</body>

</html>
