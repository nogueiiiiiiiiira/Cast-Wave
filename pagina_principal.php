<?php

session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: /projetoLocadora/login.html");
    exit;
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /projetoLocadora/login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Principal</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h1>
    <p>Você está logado.</p>

    <form method="post">
        <button type="submit" name="logout">Sair</button>
    </form>

</body>
</html>
