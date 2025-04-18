<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "castwave";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$dataNasc = $_POST['dataNasc'];
$senha = $_POST['senha'];

$dataNasc = DateTime::createFromFormat('Y-m-d', $dataNasc) ? $dataNasc : '0000-00-00';

$sqlVerifica = "SELECT * FROM usuarios WHERE cpf = ? OR email = ? OR telefone = ?";
$stmt = $conn->prepare($sqlVerifica);
$stmt->bind_param("sss", $cpf, $email, $telefone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $mensagem = "Dados já cadastrados:";
    while ($row = $result->fetch_assoc()) {
        if ($row['cpf'] === $cpf) {
            $mensagem .= " CPF";
        }
        if ($row['email'] === $email) {
            $mensagem .= " Email";
        }
        if ($row['telefone'] === $telefone) {
            $mensagem .= " Telefone";
        }
    }
    echo trim($mensagem);
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, cpf, email, telefone, dataNasc, senha) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $cpf, $email, $telefone, $dataNasc, $senhaHash);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$conn->close();
 