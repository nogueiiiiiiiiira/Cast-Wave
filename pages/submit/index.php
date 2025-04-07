<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
$nome = $_GET["nome"] ?? "Nome não informado";
$cpf = $_GET["cpf"] ?? "CPF não informado";
$email = $_GET["email"] ?? "Email e não informado";
$telefone = $_GET["telefone"] ?? "Telefone não informado";
$dataNasc = $_GET["dataNasc"] ?? "Nascimento não informado";
$senha = $_GET["senha"] ?? "Senha não informada";

echo json_encode([
"mensagem" => "Requisição GET recebida",
"parametros" => $_GET, // Captura todos os parâmetros do formulário
"especifico" => $nome, $cpf, $email, $telefone, $dataNasc, $senha
]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_GET["nome"] ?? "Nome não informado";
    $cpf = $_GET["cpf"] ?? "CPF não informado";
    $email = $_GET["email"] ?? "Email e não informado";
    $telefone = $_GET["telefone"] ?? "Telefone não informado";
    $dataNasc = $_GET["dataNasc"] ?? "Nascimento não informado";
    $senha = $_GET["senha"] ?? "Senha não informada";
    
echo json_encode([
"mensagem" => "Requisição POST recebida",
"parametros" => $_POST, // Captura todos os parâmetros do formulário
"especifico" => $nome, $cpf, $email, $telefone, $dataNasc, $senha
]);
}
?>
