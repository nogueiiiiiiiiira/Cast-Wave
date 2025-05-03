document.getElementById("login_form").addEventListener("submit", async function (e) { // adiciona um evento de submit ao formulário de login
    e.preventDefault(); // previne o comportamento padrão do formulário

    const email = document.getElementById("email").value; // obtém o email
    const senha = document.getElementById("senha").value; // obtém a senha

    const form_data = new form_data();
    form_data.append("email", email); // adiciona o email ao form_data
    form_data.append("senha", senha); // adiciona a senha ao form_data

    const response = await fetch("/projetoLocadora/paginas/login.php", { // esperando o retorno do PHP
        method: "POST",
        body: form_data
    });

    const result = await response.text(); // espera a resposta do servidor

    if (result === "success") { // se o login for bem-sucedido...
        window.location.href = "/projetoLocadora/paginas/catalogo.php";
    } else { // se o login falhar...
        alert(result);
        document.getElementById("email").value = ""; // limpa o campo de email
        document.getElementById("senha").value = ""; // limpa o campo de senha
    }
});