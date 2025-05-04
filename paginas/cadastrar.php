<?php

// conexão com o database de dados
$host = "localhost";
$usuario = "root";  
$senha = "";
$database = "castwave";

$conn = new mysqli($host, $usuario, $senha, $database);

// verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// recebe dados do formulário
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$data_nasc = $_POST['data_nasc'];
$senha = $_POST['senha'];

// validação dos campos
$date = DateTime::createFromFormat('Y-m-d', $data_nasc);
$data_nasc = $date ? $date->format('Y-m-d') : '0000-00-00';

// verifica se os campos obrigatórios estão preenchidos
$sql_verifica = "SELECT * FROM usuarios WHERE cpf = ? OR email = ? OR telefone = ?";
$stmt = $conn->prepare($sql_verifica); // prepara a consulta SQL
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

$senha_hash = password_hash($senha, PASSWORD_DEFAULT); // criptografa a senha

$sql = "INSERT INTO usuarios (nome, cpf, email, telefone, data_nasc, senha) VALUES (?, ?, ?, ?, ?, ?)"; // prepara a consulta SQL para inserir os dados
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $cpf, $email, $telefone, $data_nasc, $senha_hash);

if ($stmt->execute()) { // executa a consulta
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}

$conn->close();
 