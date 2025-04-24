<?php
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: /projetoLocadora/login.html");
    exit;
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /projetoLocadora/login.html");
    exit;
}

// Pega o número da página atual (ou define como 1 se não tiver nada)
$paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

// Sua chave da API TMDB
$apiKey = "7d76651465970372fcd6d406b5b325ee";

// URL da API
$url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=pt-BR&page=$paginaAtual";

// Requisição da API
$response = file_get_contents($url);
$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filmes Populares</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            width: 200px;
            padding: 10px;
            text-align: center;
        }
        .card img {
            width: 100%;
            border-radius: 10px;
        }
        .card h3 {
            font-size: 1em;
            margin: 10px 0 5px;
        }
        .card p {
            font-size: 0.9em;
            color: #555;
        }
        .paginacao {
            text-align: center;
            margin-top: 30px;
        }
        .paginacao a {
            margin: 0 10px;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h1>
    <form method="post">
        <button type="submit" name="logout">Sair</button>
    </form>

    <h2>Filmes Populares</h2>
    <div class="card-container">
        <?php foreach ($data['results'] as $filme): ?>
            <div class="card">
                <img src="https://image.tmdb.org/t/p/w200<?php echo $filme['poster_path']; ?>" alt="<?php echo $filme['title']; ?>">
                <h3><?php echo $filme['title']; ?></h3>
                <p>Nota: <?php echo $filme['vote_average']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="paginacao">
        <?php if ($paginaAtual > 1): ?>
            <a href="?pagina=<?php echo $paginaAtual - 1; ?>">← Página anterior</a>
        <?php endif; ?>
        <a href="?pagina=<?php echo $paginaAtual + 1; ?>">Próxima página →</a>
    </div>
</body>
</html>
