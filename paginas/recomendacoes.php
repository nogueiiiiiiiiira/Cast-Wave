<?php

// evita que erros e avisos sejam enviados no output e causem problemas no JSON
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// inicia a sessão
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Usuário não está logado"]);
    exit();
}

// conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$database = "castwave";

$conn = new mysqli($host, $usuario, $senha, $database);

// verifica se a conexão foi bem sucedida
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Falha na conexão com o banco de dados: " . $conn->connect_error]);
    exit();
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// consulta SQL para obter os gêneros de filmes mais alugados pelo usuário
$query = "
    SELECT genero_filme, COUNT(*) as total
    FROM alugueis
    WHERE usuario_id = ?
    GROUP BY genero_filme
    ORDER BY total DESC
    LIMIT 3
";

// prepara a consulta
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();


$generos = [];

// verifica se a consulta retornou resultados
while ($row = $result->fetch_assoc()) {
    $generos[] = $row['genero_filme'];
}

// verificação de dados obtidos
error_log("Gêneros obtidos: " . implode(', ', $generos));  // log dos gêneros


if (empty($generos)) {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Você não tem nenhum filme alugado. Logo, não possui nenhuma recomendação."]);
    exit();
}

// cria a mensagem a ser enviada ao modelo Ollama
$mensagem = "O usuário gosta dos seguintes gêneros de filmes: " . implode(', ', $generos) . ". Recomende filmes que ele pode gostar.";

// log da mensagem
error_log("Mensagem para o Ollama: " . $mensagem);

// executa o comando Ollama CLI diretamente
$command = 'ollama run gemma ' . escapeshellarg($mensagem);
$output = shell_exec($command);

if ($output === null) {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Erro ao executar o modelo de IA"]);
    exit();
}

// log da resposta do Ollama
error_log("Resposta do Ollama: " . $output);

header('Content-Type: application/json');
echo json_encode(["recomendacoes" => $output]);
?>