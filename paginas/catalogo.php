
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
$senha = ""; 
$database = "castwave";  

$conn = new mysqli($host, $usuario, $senha, $database);

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

if ($stmt->execute() === FALSE) {
    echo "Erro na consulta SQL: " . $stmt->error;
    exit();
}

$stmt->bind_result($usuario_nome);
$stmt->fetch();
$stmt->close();
$conn->close();

// caso o nome não seja encontrado
if (!$usuario_nome) {
    $usuario_nome = "Usuário desconhecido";
}

// configuração da API do TMDB
$api_key = "7d76651465970372fcd6d406b5b325ee";
$pagina_atual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$pesquisa = isset($_GET['busca']) ? urlencode($_GET['busca']) : '';
$base_url = "https://api.themoviedb.org/3/";

// verifica se a pesquisa foi feita
if ($pesquisa) {
    $url = $base_url . "search/movie?api_key=$api_key&language=pt-BR&query=$pesquisa&page=$pagina_atual";
} else {
    $url = $base_url . "discover/movie?api_key=$api_key&language=pt-BR&sort_by=popularity.desc&page=$pagina_atual";
}

// faz a requisição para a API
$response = file_get_contents($url);
if ($response === FALSE) {
    echo "Erro ao tentar recuperar dados da API.";
    exit();
}


// decodifica a resposta JSON
$data = json_decode($response, true);
$total_paginas = min($data['total_pages'], 100); 

// requisição para obter a lista de gêneros
$response_generos = file_get_contents("https://api.themoviedb.org/3/genre/movie/list?api_key=$api_key&language=pt-BR");
if ($response_generos === FALSE) {
    echo "Erro ao tentar obter dados de gêneros.";
    exit();
}

$data_generos = json_decode($response_generos, true); // decodifica a resposta JSON

// cria um mapa associativo
$generos_map = [];
if (isset($data_generos['genres'])) {
    foreach ($data_generos['genres'] as $genero) {
        $generos_map[$genero['id']] = $genero['name'];
    }
}

// função para gerar os links de paginação
function pagina_links($i, $pagina_atual, $pesquisa) { 
    $active = ($i == $pagina_atual) ? 'active' : ''; // verifica se a página atual é a mesma do link
    $busca_query = $pesquisa ? "&busca=" . urlencode($pesquisa) : ''; // verifica se a pesquisa foi feita
    return "<a href='?pagina=$i$busca_query' class='numero $active'>$i</a>"; // gera o link
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
  <script src="../assets/scripts/logout.js"></script> <!-- importa o JS do logout -->
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
<h4>Bem-vindo(a), <?php echo htmlspecialchars($usuario_nome); ?>!</h4> <!-- exibe o nome do usuário -->

<br>

<div class="card-container">
    <?php // verifica se há resultados
    if (isset($data['results'])) { // verifica se a resposta contém resultados
        foreach ($data['results'] as $filme): // verifica se cada filme tem poster
            $posterUrl = $filme['poster_path'] ? "https://image.tmdb.org/t/p/original{$filme['poster_path']}" : 'caminho/para/imagem/default.jpg';
            $preco = rand(10, 30);  // gera um preço aleatório entre 10 e 30 reais
            $filme_id = $filme['id']; 
    ?>
    <div class="card">
        <img src="<?php echo $posterUrl; ?>" alt="Poster"> 
        <br>
        <h5><?php echo htmlspecialchars($filme['title']); ?></h5> <!-- htmlspecialchars() protege contra XSS, que é uma vulnerabilidade de segurança -->
        <br>
        <?php
            // converte os IDs dos gêneros em nomes
            $generos_nomes = array_map(function ($id) use ($generos_map) {
                return $generos_map[$id] ?? 'Desconhecido';
            }, $filme['genre_ids']);

            $classificacao = 'Não informada'; // valor padrão
            $release_url = "https://api.themoviedb.org/3/movie/{$filme_id}/release_dates?api_key={$api_key}";
            $release_response = @file_get_contents($release_url);

            if ($release_response !== FALSE) { // verifica se a requisição foi bem-sucedida
                $release_data = json_decode($release_response, true); // decodifica a resposta JSON
                foreach ($release_data['results'] as $release) { // percorre os resultados
                    if ($release['iso_3166_1'] === 'BR') { // procura pelo país Brasil
                        foreach ($release['release_dates'] as $entry) { // percorre as datas de lançamento
                            if (!empty($entry['certification'])) { // verifica se a classificação está disponível
                                $classificacao = $entry['certification']; // atribui a classificação
                                break 2; // sai dos dois loops
                            }
                        }
                    }
                }
            }
        ?>

        <p><strong>Gênero:</strong> <?php echo implode(', ', $generos_nomes); ?></p> <!-- implode() junta os gêneros em uma string -->
        <p><strong>Nota do Público: </strong><?php echo $filme['vote_average']; ?></p> <!-- exibe a nota do filme -->
        <p><strong>Preço:</strong> R$ <?php echo number_format($preco, 2, ',', '.'); ?></p> <!-- formata o preço para o padrão brasileiro -->
        
        <form class="alugar_form" action="../paginas/aluguel.php" method="POST">
            <input type="hidden" name="preco" value="<?php echo $preco; ?>">
            <input type="hidden" name="nome_filme" value="<?php echo htmlspecialchars($filme['title']); ?>">
            <input type="hidden" name="filme_id" value="<?php echo $filme_id; ?>">
            <input type="hidden" name="classificacao" value="<?php echo htmlspecialchars($classificacao); ?>">
            
            <?php foreach ($filme['genre_ids'] as $generoId): ?> <!-- percorre os gêneros do filme -->
                <input type="hidden" name="genero_filme[]" value="<?= $generoId ?>"> <!-- armazena os IDs dos gêneros -->
            <?php endforeach; ?>

            <button type="submit" class="btn btn-custom me-4" id="alugar">Alugar</button>
            <button type="button" 
                class="btn btn-custom me-4" 
                data-bs-toggle="modal"
                data-bs-target="#detalhesModal"
                onclick="mostrar_detalhes(
                '<?php echo $filme_id; ?>',  // ecoa o ID do filme
                '<?php echo htmlspecialchars(addslashes($filme['title'])); ?>', // ecoa o título do filme 
                '<?php echo implode(', ', $generos_nomes); ?>',  // ecoa os gêneros do filme
                '<?php echo addslashes($filme['overview']); ?>')"> <!-- ecoa o resumo do filme -->
                    Detalhes
            </button>
        </form>
    </div>
    <?php endforeach; } ?>

</div>
<div class="paginacao">
    <?php
    $busca_query = $pesquisa ? "&busca=" . urlencode($pesquisa) : ''; // verifica se a pesquisa foi feita

    if ($pagina_atual > 1) { // verifica se há página anterior
        echo "<a href='?pagina=" . ($pagina_atual - 1) . "$busca_query' class='navegacao'>Anterior</a>"; // gera o link para a página anterior
    }
    
    for ($i = max(1, $pagina_atual - 2); $i <= min($total_paginas, $pagina_atual + 2); $i++) { // gera os links de paginação
        echo pagina_links($i, $pagina_atual, $pesquisa); // chama a função para gerar os links
    }
    
    if ($pagina_atual < $total_paginas) { // verifica se há próxima página
        echo "<a href='?pagina=" . ($pagina_atual + 1) . "$busca_query' class='navegacao'>Próxima</a>"; // gera o link para a próxima página
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
                <h4 id="modal_titulo"></h4>
                <p><strong>Gêneros:</strong> <span id="modal_generos"></span></p>
                <p><strong>Data de Lançamento:</strong> <span id="moda_lancamento"></span></p>
                <p><strong>Resumo:</strong> <span id="modal_resumo"></span></p>

                <p id="trailer_link" class="d-none">
                    <strong>Trailer:</strong> <a id="trailer_url" href="#" target="_blank">Assistir no YouTube</a>
                </p>

                <p><strong>Classificação Indicativa:</strong> <span id="moda_idade"></span></p>

            </div>
        </div>
    </div>
</div>

<script src="../assets/scripts/catalogo.js"></script> <!-- importa o JS do catalogo -->

</body>

</html>
