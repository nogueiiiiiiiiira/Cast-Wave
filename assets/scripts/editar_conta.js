const telefone = document.getElementById("telefone"); // obtém telefone

telefone.addEventListener("input", () => { // adiciona um evento de input ao campo telefone
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remove caracteres não numéricos e limitar a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adiciona parênteses e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adiciona hífen
    telefone.value = value; // atualiza o valor do input
});


function validar_formulario() {
    const form = document.getElementById("conta_form"); // obtém o formulário
    const inputs = form.querySelectorAll("input");  // pega todos os campos de input do formulário

    // verifica se algum campo está vazio
    for (let i = 0; i < inputs.length; i++) { // percorre todos os campos
        if (inputs[i].value.trim() === "") { // verifica se o campo está vazio
            alert("Por favor, preencha todos os campos.");
            return false;  // impede o envio do formulário
        }
    }

    const senha_nova = document.getElementById("senha_nova").value; // obtém a nova senha
    const senha_confirmacao = document.getElementById("senha_confirmacao").value; // obtém a confirmação da nova senha

    // mensagem de confirmação com os dados alterados
    let mensagem = `Tem certeza que deseja alterar os seus dados?`;

    // confirma a alteração com o usuário
    const confirmacao = confirm(mensagem);
    if (!confirmacao) {
        return false;  // impede o envio do formulário
    }

    // validação da senha
    if (senha_nova !== "") {
        if (!validar_senha(senha_nova)) {
            alert("A senha deve ter pelo menos 8 caracteres, incluindo letras, números e símbolos.");
            return false;
        }
        if (senha_nova !== senha_confirmacao) {
            alert("As senhas não coincidem.");
            return false;
        }
    }

    return true;  // permite o envio do formulário
}

// valida a senha de acordo com os critérios
function validar_senha(senha) {
    const tamanho_minimo = senha.length >= 8;
    const letras = /[a-zA-Z]/.test(senha);
    const numeros = /[0-9]/.test(senha);
    const simbolos = /[!@#$%^&*(),.?":{}|<>]/.test(senha);
    return tamanho_minimo && letras && numeros && simbolos;
}

document.getElementById('conta_form').addEventListener('submit', e => { // adiciona um evento de submit ao formulário
    e.preventDefault();  // impede o envio normal do formulário

    // valida o formulário antes de prosseguir
    if (!validar_formulario()) {
        return;  // se a validação falhar, não envia os dados
    }

    const form_data = new FormData(e.target);
 // cria um objeto form_data com os dados do formulário

    fetch('../paginas/salvar_edicao.php', {  // envia os dados para o servidor
        method: 'POST',
        body: form_data  // envia os dados do formulário
    })
    .then(response => response.text())  // espera a resposta do servidor
    .then(message => {
        alert(message);  // exibe a resposta do servidor como um alerta
        e.target.reset();  // limpa os campos do formulário
    })
    .catch(error => { // trata erros
        alert("Erro ao processar a edição. Tente novamente mais tarde.");
    });
});
