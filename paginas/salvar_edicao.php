<?php

// inicia a sessão
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projetoLocadora/paginas/login.php'); 
    exit();
}

// conexão com o database de dados
$host = "localhost";
$usuario = "root";  
$password = "";
$database = "castwave";

$conn = new mysqli($host, $usuario, $password, $database);

// verificação de conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

// obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// busca os dados atuais do usuário
$sql = "SELECT nome, email, telefone, senha FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql); // prepara a consulta
$stmt->bind_param("i", $usuario_id); // vincula o parâmetro
$stmt->execute(); // executa a consulta
$stmt->bind_result($nome_atual, $email_atual, $telefone_atual, $senha_atual);
$stmt->fetch();
$stmt->close();

// obtém os novos dados enviados via POST
$nome = !empty($_POST['nome']) ? $_POST['nome'] : $nome_atual;
$email = !empty($_POST['email']) ? $_POST['email'] : $email_atual;
$telefone = !empty($_POST['telefone']) ? $_POST['telefone'] : $telefone_atual;
$senha = !empty($_POST['senha']) ? $_POST['senha'] : $senha_atual;

// verifica se já existe outro usuário com o mesmo email ou telefone
$sql_verifica = "SELECT * FROM usuarios WHERE (email = ? OR telefone = ?) AND id != ?";
$stmt = $conn->prepare($sql_verifica); // prepara a consulta
$stmt->bind_param("ssi", $email, $telefone, $usuario_id); // vincula os parâmetros
$stmt->execute(); // executa a consulta
$result = $stmt->get_result(); // obtém o resultado da consulta

if ($result->num_rows > 0) { // se já existe outro usuário com o mesmo email ou telefone
    $mensagem = "Dados já cadastrados:";
    while ($row = $result->fetch_assoc()) { // percorre os resultados, verificando quais dados já existem. fetch_assoc retorna uma linha como um array associativo
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

$stmt->close();

// atualiza a senha se uma nova foi fornecida
if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
} else {
    $senha_hash = $senha_atual;
}

// atualiza os dados do usuário
$sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id = ?";
$stmt = $conn->prepare($sql); // prepara a consulta
$stmt->bind_param("ssssi", $nome, $email, $telefone, $senha_hash, $usuario_id); // vincula os parâmetros
$stmt->execute(); // executa a consulta

if ($stmt->affected_rows > 0) { // verifica se houve alteração
    echo "Dados atualizados com sucesso!";
} else {
    echo "Nenhuma alteração foi feita.";
}

$stmt->close();
$conn->close();

?>
