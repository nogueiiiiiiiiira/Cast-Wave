document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    const formData = new FormData();
    formData.append("email", email); // adiciona o email ao FormData
    formData.append("senha", senha); // adiciona a senha ao FormData

    const response = await fetch("/projetoLocadora/paginas/login.php", { // esperando o retorno do PHP
        method: "POST",
        body: formData
    });

    const result = await response.text();

    if (result === "success") { // se o login for bem-sucedido...
        window.location.href = "/projetoLocadora/paginas/catalogo.php";
    } else { // se o login falhar...
        alert(result);
        document.getElementById("email").value = ""; // limpa o campo de email
        document.getElementById("senha").value = ""; // limpa o campo de senha
    }
});