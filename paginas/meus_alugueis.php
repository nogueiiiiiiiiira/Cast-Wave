<?php
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: /projetoLocadora/paginas/login.php'); 
  exit();
}

// conexão com o banco de dados
$host = "localhost"; 
$usuario = "root";  
$senha = ""; 
$banco = "castwave";  

$conn = new mysqli($host, $usuario, $senha, $banco);

// verificação de conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// consulta SQL para obter os filmes alugados pelo usuário com base no ID do filme
$sqlAlugueis = "SELECT filme_id, nome_filme, data_fim, data_inicio FROM alugueis WHERE usuario_id = ?";
$stmtAlugueis = $conn->prepare($sqlAlugueis);
if ($stmtAlugueis === false) {
    echo "Erro ao preparar a consulta SQL: " . $conn->error;
}
$stmtAlugueis->bind_param("i", $usuario_id);
$stmtAlugueis->execute();
$stmtAlugueis->bind_result($filmeId, $nomeFilme, $data_fim, $data_inicio);
$alugueis = [];
while ($stmtAlugueis->fetch()) {
    $alugueis[] = ['filme_id' => $filmeId, 'nome_filme' => $nomeFilme, 'data_fim' => $data_fim, 'data_inicio' => $data_inicio];
}
$stmtAlugueis->close();

// URL da API do The Movie Database (TMDb) e chave de API
$apiKey = "7d76651465970372fcd6d406b5b325ee";
$apiUrl = 'https://api.themoviedb.org/3/movie/';

// buscar os detalhes do filme, incluindo a imagem
function getFilmeDetails($filmeId, $apiKey) {
    $url = $GLOBALS['apiUrl'] . $filmeId . '?api_key=' . $apiKey . '&language=pt-BR';
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    return $data;
}

// obter o preço de aluguel com base no filme, usuário e data
function getPrecoAluguel($filmeId, $usuarioId, $data_inicio, $conn) {
    $sqlPreco = "SELECT preco FROM alugueis WHERE filme_id = ? AND usuario_id = ? AND data_inicio = ?";
    $stmtPreco = $conn->prepare($sqlPreco);
    $stmtPreco->bind_param("iis", $filmeId, $usuarioId, $data_inicio);
    $stmtPreco->execute();
    $stmtPreco->bind_result($preco);
    $stmtPreco->fetch();
    $stmtPreco->close();
    return $preco;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Aluguéis</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- importa a font do google -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> <!-- importa o CSS do bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" /> <!-- importa o CSS do font awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- importa o JS do bootstrap -->

  <link rel="stylesheet" href="../assets/css/meus_alugueis.css" /> <!-- importa o CSS do meus_alugueis -->
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

<div class="container">
  <br>
  <br>
    <h4>Meus Aluguéis</h4>
    <br>
    <?php if (empty($alugueis)): ?>
        <p>Você não tem filmes alugados.</p>
    <?php else: ?>
      <div class="card-container">
        <div class="row">
            <?php foreach ($alugueis as $aluguel): 
                // busca os detalhes do filme na API
                $filmeDetails = getFilmeDetails($aluguel['filme_id'], $apiKey);
                // URL da imagem do filme
                $imageUrl = 'https://image.tmdb.org/t/p/w500' . $filmeDetails['poster_path'];
                // busca o preço do aluguel
                $preco = getPrecoAluguel($aluguel['filme_id'], $usuario_id, $aluguel['data_inicio'], $conn);
            ?>
                <div class="col-md-2 mb-4">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="Imagem do filme">
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($aluguel['nome_filme']); ?></h5>
                            <br>
                            <p><strong>Data de Aluguel:</strong> <?php echo date('d/m/Y', strtotime($aluguel['data_inicio'])); ?></p>
                            <p><strong>Data de Vencimento:</strong> <?php echo date('d/m/Y', strtotime($aluguel['data_fim'])); ?></p>
                            <p><strong>Preço:</strong> R$ <?php echo number_format($preco, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>

</html>
