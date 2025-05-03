<?php

// inicia a sessão
session_start();

// obtém o ID do usuário antes de limpar a sessão
$usuario_id = $_SESSION['usuario_id'] ?? null;

// limpa a sessão
$_SESSION = array();

// se você estiver usando cookies, limpe o cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// conexão com o database de dados
$host = "localhost";
$usuario = "root";  
$password = "";
$database = "castwave";

$conn = new mysqli($host, $usuario, $password, $database);
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

if ($usuario_id !== null) {
    // exclui o usuário com base no ID da sessão
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// finaliza a sessão
session_destroy();

// redireciona para página de login ou uma página de despedida
header("Location: /projetoLocadora/html/login.html");
exit();
?>
