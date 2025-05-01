const cpf = document.getElementById("cpf");
const telefone = document.getElementById("telefone");
const dataNasc = document.getElementById("dataNasc");

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

document.getElementById('cadastroForm').addEventListener('submit', e => {
    e.preventDefault();

    const senha = document.getElementById('senha').value;
    const senha2 = document.getElementById('senha2').value;

    const senhaValida = validarSenha(senha);

    if (!senhaValida) {
        alert("A senha deve ter pelo menos 8 caracteres, incluindo letras, números e símbolos.");
        return;
    }

    if (senha !== senha2) {
        alert("As senhas não coincidem.");
        return;
    }

    const dataFormatada = formatarData(dataNasc.value); // formata a data para o formato desejado

    const formData = new FormData(e.target); // cria um objeto FormData com os dados do formulário
    formData.set('dataNasc', dataFormatada); // adiciona a data formatada ao FormData

    fetch('../paginas/cadastrar.php', { // envia os dados para o servidor
        method: 'POST',
        body: formData // envia os dados do formulário
    })

    .then(r => r.text()) // espera a resposta do servidor
    .then(alert) // exibe a resposta em um alerta
    .then(() => {
        // limpa os campos do formulário
        e.target.reset();
    });
});

function validarSenha(senha) {
    const temTamanhoMinimo = senha.length >= 8; // verifica se a senha tem pelo menos 8 caracteres
    const temLetra = /[a-zA-Z]/.test(senha); // verifica se a senha contém letras
    const temNumero = /[0-9]/.test(senha); // verifica se a senha contém números
    const temSimbolo = /[!@#$%^&*(),.?":{}|<>]/.test(senha); // verifica se a senha contém símbolos
    return temTamanhoMinimo && temLetra && temNumero && temSimbolo; // retorna true se todas as condições forem atendidas
}

function formatarData(data) {
    const partes = data.split('-'); // divide a data em partes (ano, mês, dia)

    return `${partes[0]}-${partes[1]}-${partes[2]}`; // formata a data no formato desejado (ano-mês-dia)
}