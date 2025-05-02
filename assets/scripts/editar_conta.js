const telefone = document.getElementById("telefone");

telefone.addEventListener("input", () => {
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remover caracteres não numéricos e limitar a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adicionar parênteses e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adicionar hífen
    telefone.value = value; // atualiza o valor do input
});


function validarFormulario() {
    const form = document.getElementById("formConta");
    const inputs = form.querySelectorAll("input");  // pega todos os campos de input do formulário

    // verifica se algum campo está vazio
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === "") {
            alert("Por favor, preencha todos os campos.");
            return false;  // impede o envio do formulário
        }
    }

    const senhaNovo = document.getElementById("senha").value;
    const senha2Novo = document.getElementById("senha2").value;

    // mensagem de confirmação com os dados alterados
    let mensagem = `Tem certeza que deseja alterar os seus dados?`;

    // confirmar a alteração com o usuário
    const confirmacao = confirm(mensagem);
    if (!confirmacao) {
        return false;  // impede o envio do formulário
    }

    // validação da senha
    if (senhaNovo !== "") {
        if (!validarSenha(senhaNovo)) {
            alert("A senha deve ter pelo menos 8 caracteres, incluindo letras, números e símbolos.");
            return false;
        }
        if (senhaNovo !== senha2Novo) {
            alert("As senhas não coincidem.");
            return false;
        }
    }

    return true;  // permite o envio do formulário
}

// valida a senha de acordo com os critérios
function validarSenha(senha) {
    const temTamanhoMinimo = senha.length >= 8;
    const temLetra = /[a-zA-Z]/.test(senha);
    const temNumero = /[0-9]/.test(senha);
    const temSimbolo = /[!@#$%^&*(),.?":{}|<>]/.test(senha);
    return temTamanhoMinimo && temLetra && temNumero && temSimbolo;
}

document.getElementById('formConta').addEventListener('submit', e => {
    e.preventDefault();  // impede o envio normal do formulário

    // valida o formulário antes de prosseguir
    if (!validarFormulario()) {
        return;  // se a validação falhar, não envia os dados
    }

    const formData = new FormData(e.target);  // cria um objeto FormData com os dados do formulário

    fetch('../paginas/salvar_edicao.php', {  // envia os dados para o servidor
        method: 'POST',
        body: formData  // envia os dados do formulário
    })
    .then(response => response.text())  // espera a resposta do servidor
    .then(message => {
        alert(message);  // exibe a resposta do servidor como um alerta
        e.target.reset();  // limpa os campos do formulário
    })
    .catch(error => {
        alert("Erro ao processar a edição. Tente novamente mais tarde.");
    });
});
