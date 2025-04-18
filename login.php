<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost"; 
    $db_user = "root"; 
    $db_pass = ""; 
    $db_name = "castwave"; 

    $conn = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = $user['email'];
            echo "success"; 
            exit();
        } else {
            echo "Senha incorreta!";
            exit();
        }
    } else {
        echo "Usuário não encontrado!";
        exit();
    }

    $conn->close();
}
