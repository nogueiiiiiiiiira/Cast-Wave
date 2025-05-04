<?php

// inicia a sessão
session_start();

// conexão com o database de dados 
$host = "localhost"; 
$usuario = "root";  
$senha = ""; 
$database = "castwave";  

$conn = new mysqli($host, $usuario, $senha, $database);

// verificação de conexão
if ($conn->connect_error) {
  echo "Falha na conexão: " . $conn->connect_error;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Recomendações</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- importa a font do google -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> <!-- importa o CSS do bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" /> <!-- importa o CSS do font awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- importa o JS do bootstrap -->

  <link rel="stylesheet" href="../assets/css/minhas_recomendacoes.css" /> <!-- importa o CSS do minhas_recomendacoes -->
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
            <li class="nav-item"><a class="nav-link" href="./minhas_recomendacoes.php">Minhas Recomendações</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="logout">Sair</a></li>
        </ul>
        </div>
    </div>
    </nav>
    
    <h1>Recomendações de Filmes</h1>
    <p>Clique no botão abaixo para ver recomendações com base nos filmes que você mais alugou:</p>
    <button onclick="buscarRecomendacoes()">Ver Recomendações</button>
    <div class="resultado" id="resultado"></div>

    <script src="../assets/scripts/minhas_recomendacoes.js"></script> <!-- importa o JS do minhas_recomendacoes -->

</body>
</html>
