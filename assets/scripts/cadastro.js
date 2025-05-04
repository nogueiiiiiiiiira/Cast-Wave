const cpf = document.getElementById("cpf"); // obtém CPF
const telefone = document.getElementById("telefone"); // obtém telefone
const data_nasc = document.getElementById("data_nasc"); // obtém data de nascimento

// mascaras para cpf e telefone
cpf.addEventListener("input", () => {
    let value = cpf.value.replace(/\D/g, "").slice(0, 11); // remove caracteres não numéricos e limita a 11 dígitos
    value = value.replace(/(\d{3})(\d)/, "$1.$2"); // adiciona o primeiro ponto
    value = value.replace(/(\d{3})(\d)/, "$1.$2"); // adiciona o segundo ponto
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // adiciona o traço
    cpf.value = value; // atualiza o valor do campo
});

telefone.addEventListener("input", () => {
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remove caracteres não numéricos e limita a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adiciona o primeiro parêntese e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adiciona o traço
    telefone.value = value; // atualiza o valor do campo
});

document.getElementById('cadastro_form').addEventListener('submit', e => { // adiciona um evento de submit ao formulário
    e.preventDefault(); // previne o comportamento padrão do formulário

    const senha = document.getElementById('senha').value; // obtém senha
    const senha2 = document.getElementById('senha2').value; // obtém a confirmação da senha

    const senha_valida = validar_senha(senha); // valida a senha

    if (!senha_valida) { // verifica se a senha é válida
        alert("A senha deve ter pelo menos 8 caracteres, incluindo letras, números e símbolos.");
        return;
    }

    if (senha !== senha2) { // verifica se as senhas coincidem
        alert("As senhas não coincidem.");
        return;
    }

    const data_formatada = formatar_data(data_nasc.value); // formata a data para o formato desejado

    const form_data = new FormData(e.target);
// cria um objeto form_data com os dados do formulário
    form_data.set('data_nasc', data_formatada); // adiciona a data formatada ao form_data

    fetch('../paginas/cadastrar.php', { // envia os dados para o servidor
        method: 'POST',
        body: form_data // envia os dados do formulário
    })

    .then(r => r.text()) // espera a resposta do servidor
    .then(alert) // exibe a resposta em um alerta
    .then(() => {
        // limpa os campos do formulário
        e.target.reset();
    });
});

function validar_senha(senha) {
    const tamanho_minimo = senha.length >= 8; // verifica se a senha tem pelo menos 8 caracteres
    const letras = /[a-zA-Z]/.test(senha); // verifica se a senha contém letras
    const numeros = /[0-9]/.test(senha); // verifica se a senha contém números
    const simbolos = /[!@#$%^&*(),.?":{}|<>]/.test(senha); // verifica se a senha contém símbolos
    return tamanho_minimo && letras && numeros && simbolos; // retorna true se todas as condições forem atendidas
}

function formatar_data(data) {
    const partes = data.split('-'); // divide a data em partes (ano, mês, dia)

    return `${partes[0]}-${partes[1]}-${partes[2]}`; // formata a data no formato desejado (ano-mês-dia)
}