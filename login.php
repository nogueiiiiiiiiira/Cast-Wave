<?php
session_start();

$host = "localhost";
$usuario = "root";
$senha = "";      
$banco = "castwave"; 

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_REQUEST["email"] ?? '';
    $senha = $_REQUEST["senha"] ?? '';

    $email = $conn->real_escape_string($email);

    $sql = "SELECT id, senha FROM usuarios WHERE email = '$email'";
    $resultado = $conn->query($sql);

    if (!$resultado) {
        die("Erro na consulta: " . $conn->error);
    }

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            echo "success";
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
} else {
    echo "Método inválido. O formulário não foi enviado via POST.";
}

$conn->close();
?>