<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "castwave";

// conexao com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco);

// verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
// verifica se o formulário foi enviado
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$dataNasc = $_POST['dataNasc'];
$senha = $_POST['senha'];

// validação dos campos
$dataNasc = DateTime::createFromFormat('Y-m-d', $dataNasc) ? $dataNasc : '0000-00-00';

// verifica se os campos obrigatórios estão preenchidos
$sqlVerifica = "SELECT * FROM usuarios WHERE cpf = ? OR email = ? OR telefone = ?";
$stmt = $conn->prepare($sqlVerifica); // prepara a consulta SQL
$stmt->bind_param("sss", $cpf, $email, $telefone); // vincula os parâmetros; sss serve para indicar que os parâmetros são strings
$stmt->execute(); // executa a consulta
$result = $stmt->get_result(); // obtém o resultado da consulta

if ($result->num_rows > 0) { // verifica se já existe um usuário com os mesmos dados
    $mensagem = "Dados já cadastrados:";
    while ($row = $result->fetch_assoc()) { // percorre os resultados, verificando quais dados já existem
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
    echo trim($mensagem); // exibe a mensagem de erro
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT); // criptografa a senha

$sql = "INSERT INTO usuarios (nome, cpf, email, telefone, dataNasc, senha) VALUES (?, ?, ?, ?, ?, ?)"; // prepara a consulta SQL para inserir os dados
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $cpf, $email, $telefone, $dataNasc, $senhaHash);

if ($stmt->execute()) { // executa a consulta
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$conn->close();
 