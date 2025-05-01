<?php

// inicializa a sessão
session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projetoLocadora/paginas/login.php');
    exit();
}

// conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "castwave";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// exclui o usuário com base no ID da sessão
$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

// finaliza a sessão
session_unset();
session_destroy();

$stmt->close();
$conn->close();

// redireciona para página de login ou uma página de despedida
header("Location: /projetoLocadora/paginas/login.php");
exit();
?>
