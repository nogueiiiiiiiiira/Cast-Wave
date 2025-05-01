<?php

$host = "localhost";
$usuario = "root";
$senha = ""; 
$banco = "castwave"; 

// conexao com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco);

// verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// recebe dados do formulário
$nome = $_POST['nome'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];
$telefone = $_POST['telefone'];

// prepara e executa o SQL
$sql = "INSERT INTO contatos (nome, assunto, mensagem, telefone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nome, $assunto, $mensagem, $telefone);

if ($stmt->execute()) {
    echo "Contato enviado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
