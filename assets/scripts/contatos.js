const telefone = document.getElementById("telefone"); // obtém telefone

telefone.addEventListener("input", () => {
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remove caracteres não numéricos e limitar a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adiciona parênteses e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adiciona hífen
    telefone.value = value; // atualiza o valor do input
});

function validar_formulario() {
    const form = document.getElementById("contato_form"); // obtém o formulário
    const inputs = form.querySelectorAll("input, textarea");  // pega todos os campos de input e textarea do formulário

    // verifica se algum campo está vazio
    for (let i = 0; i < inputs.length; i++) { // percorre todos os campos
        if (inputs[i].value.trim() === "") { // verifica se o campo está vazio
            alert("Por favor, preencha todos os campos.");
            return false;  // impede o envio do formulário
        }
    }

    return true;  // permite o envio do formulário
}

document.getElementById("contato_form").addEventListener("submit", function (event) { // adiciona um evento de submit ao formulário
    // valida antes de submeter o formulário
    if (!validar_formulario()) { // se a validação falhar, não envia os dados
        event.preventDefault();  // impede o envio do formulário
        return;
    }

    const confirmar = confirm("Tem certeza que deseja enviar o formulário?");
    if (!confirmar) { // se o usuário não confirmar, não envia o formulário
        event.preventDefault();  // impede o envio do formulário
    } else {
        alert("Formulário enviado com sucesso!");
    }
});

// Envia o formulário via AJAX
document.getElementById('contato_form').addEventListener('submit', e => {
    e.preventDefault();  // impede o envio normal do formulário

    // se a validação falhar, não envia os dados
    if (!validar_formulario()) {
        return;
    }

    const form_data = new FormData(e.target);
 // cria um objeto form_data com os dados do formulário

    fetch('../paginas/contatos.php', {  // envia os dados para o servidor
        method: 'POST',
        body: form_data  // envia os dados do formulário
    })
    .then(r => r.text())  // espera a resposta do servidor
    .then(response => {
        alert(response);  // exibe a resposta do servidor como um alerta
        e.target.reset();  // limpa os campos do formulário
    })
    .catch(error => {
        alert("Erro ao enviar o formulário. Tente novamente mais tarde.");
    });
});
