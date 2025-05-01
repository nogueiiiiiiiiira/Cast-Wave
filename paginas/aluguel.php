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
    die("Falha na conexão: " . $conn->connect_error);
}

// obtém os dados do usuário e do filme
$usuario_id = $_SESSION['usuario_id'];
$filme_id = $_GET['filme_id'];
$preco = $_GET['preco'];
$nome_filme = $_GET['nome_filme'];

$data_inicio = date("Y-m-d H:i:s");
$data_fim_nova = date("Y-m-d H:i:s", strtotime("+15 days"));

// verifica se o usuário já alugou este filme e ainda está válido
$sqlVerifica = "SELECT * FROM alugueis WHERE filme_id = ? AND usuario_id = ? AND data_fim > NOW()";
$stmtVerifica = $conn->prepare($sqlVerifica);

// verifica se a preparação da query falhou
if (!$stmtVerifica) {
    die("Erro na preparação da consulta de verificação: " . $conn->error);
}

$stmtVerifica->bind_param("ii", $filme_id, $usuario_id);
$stmtVerifica->execute();
$result = $stmtVerifica->get_result();

if ($result->num_rows > 0) {
    // se já tem aluguel ativo
    echo "<script>alert('Você já alugou este filme e o aluguel ainda está ativo. Aluguel cancelado.'); window.location.href = '/projetoLocadora/paginas/catalogo.php';</script>";
} else {
    // se não, novo aluguel
    $sqlAluguel = "INSERT INTO alugueis (usuario_id, filme_id, preco, nome_filme, data_inicio, data_fim) VALUES (?, ?, ?, ?, ? , ?)";
    $stmtAluguel = $conn->prepare($sqlAluguel);

    // verifica se a preparação do comando de aluguel falhou
    if (!$stmtAluguel) {
        die("Erro na preparação da consulta SQL de inserção: " . $conn->error);
    }

    // vincula os parâmetros
    $stmtAluguel->bind_param("iidsss", $usuario_id, $filme_id, $preco, $nome_filme, $data_inicio, $data_fim_nova);

    // executa a consulta
    if ($stmtAluguel->execute()) {
        echo "<script>alert('Filme alugado com sucesso!'); window.location.href = '/projetoLocadora/paginas/catalogo.php';</script>";
    } else {
        echo "<script>alert('Erro ao tentar alugar o filme: " . $stmtAluguel->error . "'); window.location.href = '/projetoLocadora/paginas/catalogo.php';</script>";
    }
}

?>


