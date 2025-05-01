// salvarEdicao.php

<?php

// inicializa a sessão
session_start();

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
$senha = $_POST['senha']; // pode estar vazia

// verifica se o email já está em uso por outro usuário
$sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Este e-mail já está em uso por outro usuário.'); window.history.back();</script>";
    $stmt->close();
    exit();
}
$stmt->close();

// verifica se o telefone já está em uso por outro usuário
$sql = "SELECT id FROM usuarios WHERE telefone = ? AND id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $telefone, $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Este telefone já está em uso por outro usuário.'); window.history.back();</script>";
    $stmt->close();
    exit();
}
$stmt->close();

// se senha nova foi preenchida, atualiza com hash; senão, mantém senha atual
if (!empty($senha)) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
} else {
    $senhaHash = $senhaAtual;
}

// atualiza os dados no banco
$sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nome, $email, $telefone, $senhaHash, $usuario_id);
$stmt->execute();

// verifica se a atualização foi bem-sucedida
if ($stmt->affected_rows >= 0) {
    header('Location: /projetoLocadora/paginas/minha_conta.php'); // redireciona para a página da conta
    exit();
} else {
    echo "Erro ao atualizar os dados.";
}

$stmt->close();
$conn->close();

?>
