<?php

session_start();

// verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /projetoLocadora/paginas/login.php'); 
    exit();
}

// conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$password = "";
$banco = "castwave";

$conn = new mysqli($host, $usuario, $password, $banco);
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
}

$usuario_id = $_SESSION['usuario_id'];

// busca os dados atuais do usuário
$sql = "SELECT nome, email, telefone, senha FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nomeAtual, $emailAtual, $telefoneAtual, $senhaAtual);
$stmt->fetch();
$stmt->close();

// obtém os novos dados enviados via POST
$nome = !empty($_POST['nome']) ? $_POST['nome'] : $nomeAtual;
$email = !empty($_POST['email']) ? $_POST['email'] : $emailAtual;
$telefone = !empty($_POST['telefone']) ? $_POST['telefone'] : $telefoneAtual;
$senha = !empty($_POST['senha']) ? $_POST['senha'] : $senhaAtual;

// verifica se já existe outro usuário com o mesmo email ou telefone
$sqlVerifica = "SELECT * FROM usuarios WHERE (email = ? OR telefone = ?) AND id != ?";
$stmt = $conn->prepare($sqlVerifica);
$stmt->bind_param("ssi", $email, $telefone, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $mensagem = "Dados já cadastrados:";
    while ($row = $result->fetch_assoc()) {
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
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
} else {
    $senhaHash = $senhaAtual;
}

// atualiza os dados do usuário
$sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nome, $email, $telefone, $senhaHash, $usuario_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Dados atualizados com sucesso!";
} else {
    echo "Nenhuma alteração foi feita.";
}

$stmt->close();
$conn->close();
?>
