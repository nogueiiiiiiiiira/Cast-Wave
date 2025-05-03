<?php

// inicia a sessão para armazenar dados do usuário
session_start();

$host = "localhost";
$usuario = "root";  
$senha = "";      
$database = "castwave"; 

$conn = new mysqli($host, $usuario, $senha, $database);

// verifica se houve erro na conexão
if ($conn->connect_error) {
    echo "Erro de conexão: " . $conn->connect_error;
}

// verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") { // verifica se o método é POST
    // obtém os dados do formulário
    $email = $_REQUEST["email"] ?? '';
    $senha = $_REQUEST["senha"] ?? '';

    // validação dos campos
    $email = $conn->real_escape_string($email);

    // verifica se os campos obrigatórios estão preenchidos
    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Obtém o resultado diretamente do prepared statement
    $resultado = $stmt->get_result();

    if (!$resultado) {
        echo "Erro na consulta: " . $conn->error;
    }

    // verifica se o usuário existe
    if ($resultado->num_rows === 1) { // num_rows retorna o número de linhas do resultado
        $usuario = $resultado->fetch_assoc(); // obtém os dados do usuário

        // verifica se a senha está correta
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
