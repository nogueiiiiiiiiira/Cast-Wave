<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para realizar o aluguel.";
    exit();
}

// Conexão com o banco de dados
$host = "localhost"; 
$usuario = "root";  
$senha = ""; 
$banco = "castwave";  

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verificação de conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
    exit();
}

// Obtém os dados do usuário e do filme
$usuario_id = $_SESSION['usuario_id'];
$filme_id = isset($_POST['filme_id']) ? (int)$_POST['filme_id'] : 0;
$preco = isset($_POST['preco']) ? (float)$_POST['preco'] : 0;
$nome_filme = isset($_POST['nome_filme']) ? $_POST['nome_filme'] : '';

// Valida os dados
if ($filme_id <= 0 || $preco <= 0 || empty($nome_filme)) {
    echo "Erro: Dados inválidos.";
    exit();
}

$data_inicio = date("Y-m-d H:i:s");
$data_fim_nova = date("Y-m-d H:i:s", strtotime("+15 days"));

// Verifica se o usuário já alugou este filme e ainda está válido
$sqlVerifica = "SELECT * FROM alugueis WHERE filme_id = ? AND usuario_id = ? AND data_fim > NOW()";
$stmtVerifica = $conn->prepare($sqlVerifica);

$stmtVerifica->bind_param("ii", $filme_id, $usuario_id);
$stmtVerifica->execute();
$result = $stmtVerifica->get_result();

if ($result->num_rows > 0) {
    echo "Você já alugou este filme e o período ainda está ativo.";
    exit();
} else {
    // Novo aluguel
    $sqlAluguel = "INSERT INTO alugueis (usuario_id, filme_id, preco, nome_filme, data_inicio, data_fim) VALUES (?, ?, ?, ?, ? , ?)";
    $stmtAluguel = $conn->prepare($sqlAluguel);

    $stmtAluguel->bind_param("iidsss", $usuario_id, $filme_id, $preco, $nome_filme, $data_inicio, $data_fim_nova);

    // Executa a consulta
    if ($stmtAluguel->execute()) {
        echo "Aluguel realizado com sucesso!";
    } else {
        echo "Erro ao alugar: " . $stmtAluguel->error;
    }
}
?>
