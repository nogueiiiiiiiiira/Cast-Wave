<?php
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php'); 
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
    die("Erro ao tentar recuperar dados da API.");
}

// decodifica a resposta JSON
$data = json_decode($response, true);
$totalPages = min($data['total_pages'], 100); // limite de 100 páginas

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">        
        <li class="nav-item"><a class="nav-link" href="./pagina_principal.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="./minhaConta.php">Minha Conta</a></li>
        <li class="nav-item"><a class="nav-link" href="./meusAlugueis.php">Meus Aluguéis</a></li>
        <li class="nav-item"><a class="nav-link" href="./logout.php">Sair</a></li>
      </ul>
      <form class="d-flex" method="get" action="">
        <input class="form-control me-2" type="search" name="busca" placeholder="Buscar filme..." value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>">
        <button class="btn btn-success" type="submit">Buscar</button>
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

<!-- exibe os filmes encontrados -->
<div class="card-container">
    <?php // verifica se há resultados
    if (isset($data['results'])) { // exibe os filmes
        foreach ($data['results'] as $filme): // verifica se o filme tem poster
            $posterUrl = $filme['poster_path'] ? "https://image.tmdb.org/t/p/original{$filme['poster_path']}" : 'caminho/para/imagem/default.jpg';
            $duration = isset($filme['runtime']) ? $filme['runtime'] . ' min' : 'Duração não disponível'; // verifica se o filme tem duração
            $preco = rand(10, 30);  // gera um preço aleatório entre 10 e 30 reais
    ?>
        <div class="card">
            <img src="<?php echo $posterUrl; ?>" alt="Poster"> 
            <br>
            <h5><?php echo htmlspecialchars($filme['title']); ?></h5>
            <br>
            <p><strong>Nota: </strong><?php echo $filme['vote_average']; ?></p>
            <p><strong>Duração:</strong> <?php echo $duration; ?></p>
            <p><strong>Preço:</strong> R$ <?php echo number_format($preco, 2, ',', '.'); ?></p>
            <div class="text-center mt-3">
                <a href="detalhes.php?filme=<?php echo urlencode($filme['title']); ?>" class="btn btn-custom me-4">Detalhes</a>
                <a href="aluguel.php?filme=<?php echo urlencode($filme['title']); ?>" class="btn btn-custom">Alugar</a>
            </div>
        </div>
    <?php 
        endforeach; // fecha o foreach, que percorre os filmes
    } else {
        echo "Nenhum filme encontrado.";
    }
    ?>
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

<style>

        body {
            scroll-behavior: smooth;
            font-family: 'Roboto', sans-serif;
            background-color: black;
        }

        #navbarNav ul li a {
            text-decoration: none;
            transition: color 0.3s;
        }

        #navbarNav ul li a:hover {
            color: #28a745; 
        }

        h1, h4 {
            text-align: center;
            color: #28a745; 
        }

        form {
            text-align: center;
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745 !important;
            color: white !important;
            border: none;
            transition: transform 0.3s ease;
        }

        button:hover {
            background-color: #218838 !important;
            transform: scale(1.05);
        }

        .btn {
            color: white !important;
            border: none;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: scale(1.05);
        }
        
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .card {
            width: 20rem;
            height: 700px; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #212529; 
            color: white; 
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            padding: 10px;
        }

        .card img {
            width: 100%;
            height: 400px; /
            object-fit: cover;
        }

        .card h4, .card p {
            color: #ecf0f1; 
        }

        .card p strong {
            color: #28a745; 
        }

        .card .d-flex {
            display: flex;
            justify-content: center;
            margin-top: auto; 
        }

        .card .btn-custom {
            background-color: #343a40; 
            color: #28a745; 
            border: 1px solid #28a745; 
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .card .btn-custom:hover {
            background-color: #28a745;
            color: white;
        }

        .paginacao {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .paginacao a {
            text-decoration: none;
            padding: 8px 14px;
            margin: 0 4px;
            border-radius: 6px;
            transition: background-color 0.3s, color 0.3s;
            font-weight: 500;
        }

        .paginacao a.navegacao {
            background-color: #343a40; 
            color: #ffffff;
        }

        .paginacao a.navegacao:hover {
            background-color: #28a745;
            color: #fff;
        }

        .paginacao a.numero {
            background-color: #343a40; 
            color: #28a745;
            border: 1px solid #28a745;
        }

        .paginacao a.numero:hover {
            background-color: #28a745;
            color: white;
        }

        .paginacao a.numero.active {
            background-color: #218838;
            color: white;
            border: 1px solid #1e7e34;
        }


    </style>

</body>
</html>
