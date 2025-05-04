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
    
    <div class="container pagina-conteudo">
        <br>
        <h4>Minhas Recomendações</h4>
        <br>
        <p>Baseado nos filmes que você mais alugou, aqui estão algumas recomendações:</p>
        <div class="botao-centro">
            <br>
            <button onclick="buscar_recomendacoes()">Ver Recomendações</button>
        </div>
        <br>
        <br>
        <div id="resultado">
            <div id="progress-container">
                <div id="progress-bar">
                    0%
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalhesModalLabel">Detalhes do Filme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <h4 id="modal_titulo"></h4>
                <p><strong>Gêneros:</strong> <span id="modal_generos"></span></p>
                <p><strong>Data de Lançamento:</strong> <span id="modal_lancamento"></span></p>
                <p><strong>Resumo:</strong> <span id="modal_resumo"></span></p>
                <p><strong>Classificação Indicativa:</strong> <span id="modal_idade"></span></p>
                <p id="trailer_link" class="d-none">
                    <strong>Trailer:</strong> <a id="trailer_url" href="#" target="_blank">Assistir no YouTube</a>
                </p>
            </div>
        </div>
    </div>
</div>


    <script src="../assets/scripts/minhas_recomendacoes.js"></script> <!-- importa o JS do minhas_recomendacoes -->

</body>
</html>