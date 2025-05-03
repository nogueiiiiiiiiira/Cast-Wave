<?php

// conexão com o database de dados
$host = "localhost";
$usuario = "root";  
$senha = ""; 
$database = "castwave"; 

$conn = new mysqli($host, $usuario, $senha, $database);

// verifica a conexão
if ($conn->connect_error) {
    echo "Conexão falhou: " . $conn->connect_error;
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
    echo "Contato enviado com sucesso!";  // retorna a mensagem de sucesso
} else {
    echo "Erro ao cadastrar: " . $stmt->error;  // em caso de erro, mostra a mensagem
}

$stmt->close();
$conn->close();
?>
