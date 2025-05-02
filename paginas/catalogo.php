
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

// criação da conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

// verificação de conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// consulta SQL para obter o nome do usuário
$sql = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($usuario_nome);
$stmt->fetch();
$stmt->close();
$conn->close();

// caso o nome não seja encontrado
if (!$usuario_nome) {
    $usuario_nome = "Usuário desconhecido";
}

// configuração da API do TMDB
$apiKey = "7d76651465970372fcd6d406b5b325ee";
$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$pesquisa = isset($_GET['busca']) ? urlencode($_GET['busca']) : '';
$baseUrl = "https://api.themoviedb.org/3/";

// verifica se a pesquisa foi feita
if ($pesquisa) {
    $url = $baseUrl . "search/movie?api_key=$apiKey&language=pt-BR&query=$pesquisa&page=$paginaAtual";
} else {
    $url = $baseUrl . "discover/movie?api_key=$apiKey&language=pt-BR&sort_by=popularity.desc&page=$paginaAtual";
}

// faz a requisição para a API
$response = @file_get_contents($url);
if ($response === FALSE) {
    echo "Erro ao tentar recuperar dados da API.";
}

// decodifica a resposta JSON
$data = json_decode($response, true);
$totalPages = min($data['total_pages'], 100); 

// requisição para obter a lista de gêneros (usado para mostrar os nomes no card)
$generosResponse = @file_get_contents("https://api.themoviedb.org/3/genre/movie/list?api_key=$apiKey&language=pt-BR");
$generosData = json_decode($generosResponse, true);

// cria um mapa associativo [id => nome]
$generosMap = [];
if (isset($generosData['genres'])) {
    foreach ($generosData['genres'] as $genero) {
        $generosMap[$genero['id']] = $genero['name'];
    }
}

// função para gerar os links de paginação
function paginaLink($i, $paginaAtual, $pesquisa) { 
    $active = ($i == $paginaAtual) ? 'active' : ''; // verifica se a página atual é a mesma do link
    $buscaQuery = $pesquisa ? "&busca=" . urlencode($pesquisa) : ''; // verifica se a pesquisa foi feita
    return "<a href='?pagina=$i$buscaQuery' class='numero $active'>$i</a>"; // gera o link
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Filmes</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- importa a font do google -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> <!-- importa o CSS do bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" /> <!-- importa o CSS do font awesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <!-- importa o JS do bootstrap -->

  <link rel="stylesheet" href="../assets/css/catalogo.css" /> <!-- importa o CSS do catalogo -->
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
      <form class="d-flex" method="get" action="">
        <input class="form-control me-2" type="search" name="busca" placeholder="Buscar filme..." value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>">
        <button class="btn btn-custom me-4" type="submit">Buscar</button>
    </form>
    </div>
  </div>
</nav>

<br>
<br>

<h1>CastWave</h1>
<br>
<h4>Bem-vindo(a) novamente, <?php echo htmlspecialchars($usuario_nome); ?>!</h4>

<br>

<div class="card-container">
    <?php // verifica se há resultados
    if (isset($data['results'])) { // exibe os filmes
        foreach ($data['results'] as $filme): // verifica se o filme tem poster
            $posterUrl = $filme['poster_path'] ? "https://image.tmdb.org/t/p/original{$filme['poster_path']}" : 'caminho/para/imagem/default.jpg';
            $preco = rand(10, 30);  // gera um preço aleatório entre 10 e 30 reais
            $filme_id = $filme['id'];
    ?>
    <div class="card">
        <img src="<?php echo $posterUrl; ?>" alt="Poster"> 
        <br>
        <h5><?php echo htmlspecialchars($filme['title']); ?></h5>
        <br>
        <?php
            // converte os IDs dos gêneros em nomes
            $generosNomes = array_map(function ($id) use ($generosMap) {
                return $generosMap[$id] ?? 'Desconhecido';
            }, $filme['genre_ids']);
        ?>
        <p><strong>Gênero:</strong> <?php echo implode(', ', $generosNomes); ?></p>
        <p><strong>Nota do Público: </strong><?php echo $filme['vote_average']; ?></p>
        <p><strong>Preço:</strong> R$ <?php echo number_format($preco, 2, ',', '.'); ?></p>
        
        <form class="alugarForm" action="../paginas/aluguel.php" method="POST">
            <input type="hidden" name="preco" value="<?php echo $preco; ?>">
            <input type="hidden" name="nome_filme" value="<?php echo htmlspecialchars($filme['title']); ?>">
            <input type="hidden" name="filme_id" value="<?php echo $filme_id; ?>">
            <input type="hidden" name="generos" value="<?php echo implode(',', $filme['genre_ids']); ?>">
            <button type="submit" class="btn btn-custom me-4" id="alugarBtn">Alugar</button>
            <button 
                type="button" 
                class="btn btn-custom me-4" 
                data-bs-toggle="modal" 
                data-bs-target="#detalhesModal"
                onclick="mostrarDetalhes('<?php echo $filme_id; ?>', '<?php echo htmlspecialchars(addslashes($filme['title'])); ?>', '<?php echo implode(', ', $generosNomes); ?>', '<?php echo addslashes($filme['overview']); ?>')"
            >
                Detalhes
            </button>
        </form>
    </div>
    <?php endforeach; } ?>

</div>

<div class="paginacao">
    <?php
    $buscaQuery = $pesquisa ? "&busca=" . urlencode($pesquisa) : ''; // verifica se a pesquisa foi feita

    if ($paginaAtual > 1) {
        echo "<a href='?pagina=" . ($paginaAtual - 1) . "$buscaQuery' class='navegacao'>Anterior</a>";
    }
    
    for ($i = max(1, $paginaAtual - 2); $i <= min($totalPages, $paginaAtual + 2); $i++) { // gera os links de paginação
        echo paginaLink($i, $paginaAtual, $pesquisa);
    }
    
    if ($paginaAtual < $totalPages) { // verifica se há próxima página
        echo "<a href='?pagina=" . ($paginaAtual + 1) . "$buscaQuery' class='navegacao'>Próxima</a>";
    }
    
    ?>
</div>
<br>
<br>

<div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalhesModalLabel">Detalhes do Filme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalTitulo"></h4>
                <p><strong>Gêneros:</strong> <span id="modalGeneros"></span></p>
                <p><strong>Data de Lançamento:</strong> <span id="modalDataLancamento"></span></p>
                <p><strong>Resumo:</strong> <span id="modalResumo"></span></p>

                <p id="trailerLink" class="d-none">
                    <strong>Trailer:</strong> <a id="trailerUrl" href="#" target="_blank">Assistir no YouTube</a>
                </p>

                <p><strong>Classificação Indicativa:</strong> <span id="modalIdade"></span></p>

            </div>
        </div>
    </div>
</div>

<script src="../assets/scripts/catalogo.js"></script> <!-- importa o JS do catalogo -->

</body>

</html>
