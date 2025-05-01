function validarFormulario() {
    const nomeNovo = document.getElementById("nome").value;
    const emailNovo = document.getElementById("email").value;
    const telefoneNovo = document.getElementById("telefone").value;
    const senhaNovo = document.getElementById("senha").value;
    const senha2Novo = document.getElementById("senha2").value;

    //  mensagem de confirmação com os dados alterados
    let mensagem = `Tem certeza que deseja alterar os seus dados?`;

    // confirmar a alteração com o usuário
    const confirmacao = confirm(mensagem);
    if (!confirmacao) {
        return false;  // Impede o envio do formulário
    }

    // se tudo estiver correto, realiza a validação da senha
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