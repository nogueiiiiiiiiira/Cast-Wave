<?php 

// inicia a sessão
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para realizar o aluguel.";
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
    exit();
}

// obtém os dados do usuário e do filme
$usuario_id = $_SESSION['usuario_id'];
$filme_id = isset($_POST['filme_id']) ? (int)$_POST['filme_id'] : 0;
$preco = isset($_POST['preco']) ? (float)$_POST['preco'] : 0;
$nome_filme = isset($_POST['nome_filme']) ? $_POST['nome_filme'] : '';
$genero_filme = isset($_POST['genero_filme']) ? $_POST['genero_filme'] : [];
$classificacao = isset($_POST['classificacao']) ? $_POST['classificacao'] : '';

$generos_nomes = [];

$api_key = "7d76651465970372fcd6d406b5b325ee";

// Busca a data de nascimento do usuário para validação de idade
$sql_data_nasc = "SELECT data_nasc FROM usuarios WHERE id = ?";
$stmt_data_nasc = $conn->prepare($sql_data_nasc);
if (!$stmt_data_nasc) {
    echo "Erro na preparação da consulta de data de nascimento: " . $conn->error;
    exit();
}
$stmt_data_nasc->bind_param("i", $usuario_id); // vincula o parâmetro
$stmt_data_nasc->execute(); // executa a consulta
$result_data_nasc = $stmt_data_nasc->get_result(); // obtém o resultado da consulta
if ($result_data_nasc->num_rows === 0) { // verifica se o usuário existe
    echo "Erro: Usuário não encontrado.";
    exit();
}

$row_data_nasc = $result_data_nasc->fetch_assoc(); // obtém a data de nascimento
$data_nasc = $row_data_nasc['data_nasc']; // formata a data de nascimento
$stmt_data_nasc->close(); 

// calcula a idade do usuário
$hoje = new DateTime();
$data_nasc = new DateTime($data_nasc); 
$idade = $hoje->diff($data_nasc)->y; // calcula a idade do usuário em anos

// verifica se o gênero é um array e não está vazio
if (!empty($genero_filme) && is_array($genero_filme)) {
    // valida e converte os IDs para inteiros
    $genero_filme_id = array_map('intval', $genero_filme);

    // busca a lista de gêneros do TMDB
    $response_generos = file_get_contents("https://api.themoviedb.org/3/genre/movie/list?api_key=$api_key&language=pt-BR");
    if ($response_generos === FALSE) {
        echo "Erro ao tentar obter dados de gêneros do TMDB.";
        exit();
    }

    $data_generos = json_decode($response_generos, true);

    // cria um mapa associativo [id => nome]
    $generos_map = [];
    if (isset($data_generos['genres'])) {
        foreach ($data_generos['genres'] as $genero) {
            $generos_map[$genero['id']] = $genero['name'];
        }
    }

    // traduz os IDs para nomes
    foreach ($genero_filme_id as $id) {
        $generos_nomes[] = $generos_map[$id] ?? 'Desconhecido';
    }
} else {
    echo "Erro: Gênero do filme não informado.";
    exit();
}

// função para extrair a idade mínima da classificação indicativa
function verificar_idade_minima($classificacao) {
    $classificacao = trim($classificacao);
    if (is_numeric($classificacao)) {
        return (int)$classificacao;
    }
    // mapeia as classificações para idades mínimas
    $mapa = [
        'L' => 0,
        'Livre' => 0,
        '10' => 10,
        '12' => 12,
        '14' => 14,
        '16' => 16,
        '18' => 18
    ];
    return $mapa[$classificacao] ?? 0;
}

// verifica se a classificação é válida
$idade_minima = verificar_idade_minima($classificacao);

// verifica se o usuário tem idade suficiente para alugar o filme
if ($idade < $idade_minima) {
    echo "Erro: Você não tem idade suficiente para alugar este filme. Classificação indicativa: $classificacao. O aluguel foi cancelado.";
    exit();
}

// gera a string final dos gêneros separados por vírgula
$genero = implode(', ', $generos_nomes);

// valida os dados
if ($filme_id <= 0 || $preco <= 0 || empty($nome_filme)) {
    echo "Erro: Dados inválidos.";
    exit();
}

$data_inicio = date("Y-m-d H:i:s"); // data de início do aluguel
$data_fim_nova = date("Y-m-d H:i:s", strtotime("+15 days")); // data de fim do aluguel (15 dias a partir de agora)

// verifica se o valor de $genero não está vazio
if (empty($genero)) {
    echo "Erro: Gênero do filme não informado.";
    exit();
}

// verifica se o usuário já alugou este filme e ainda está válido
$sql_verifica = "SELECT * FROM alugueis WHERE filme_id = ? AND usuario_id = ? AND data_fim > NOW()";
$stmt_verifica = $conn->prepare($sql_verifica);
$stmt_verifica->bind_param("ii", $filme_id, $usuario_id);
$stmt_verifica->execute();
$result = $stmt_verifica->get_result();

if ($result->num_rows > 0) {
    echo "Você já alugou este filme e o período ainda está ativo.";
    exit();
} else {
    // novo aluguel
    $sql_aluguel = "INSERT INTO alugueis (usuario_id, filme_id, preco, nome_filme, genero_filme, classificacao, data_inicio, data_fim) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_aluguel = $conn->prepare($sql_aluguel);

    // verifica se o parâmetro genero foi preenchido corretamente
    if (!$stmt_aluguel) {
        echo "Erro na preparação da consulta: " . $conn->error;
        exit();
    }

    // vincula os parâmetros
    $stmt_aluguel->bind_param('iidsssss', $usuario_id, $filme_id, $preco, $nome_filme, $genero, $classificacao, $data_inicio, $data_fim_nova);

    // executa a consulta
    if ($stmt_aluguel->execute()) {
        echo "Aluguel realizado com sucesso!";
    } else {
        echo "Erro ao alugar: " . $stmt_aluguel->error;
    }
}
?>
