<?php

// inicia a sessão para armazenar dados do usuário
session_start();

// verifica se o usuário está logado
$_SESSION = array();

// se você estiver usando cookies, limpe o cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params(); // obtém os parâmetros do cookie
    setcookie(session_name(), '', time() - 42000, // set o cookie para expirar no passado
        $params["path"], $params["domain"], // define o caminho, domínio, seguro e httponly
        $params["secure"], $params["httponly"] // define se o cookie é seguro e httponly
    );
}

session_destroy(); // destrói a sessão

header("Location: ./login.html"); // redireciona para a página de login
exit;
?>
