const telefone = document.getElementById("telefone");

telefone.addEventListener("input", () => {
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remover caracteres não numéricos e limitar a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adicionar parênteses e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adicionar hífen
    telefone.value = value; // atualiza o valor do input
});

// função para validar o formulário
function validarFormulario() {
    const form = document.getElementById("contactForm2");
    const inputs = form.querySelectorAll("input, textarea");  // pega todos os campos de input e textarea do formulário

    // verifica se algum campo está vazio
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === "") {
            alert("Por favor, preencha todos os campos.");
            return false;  // impede o envio do formulário
        }
    }

    return true;  // permite o envio do formulário
}

document.getElementById("contactForm2").addEventListener("submit", function (event) {
    // validação antes de submeter o formulário
    if (!validarFormulario()) {
        event.preventDefault();  // impede o envio do formulário
        return;
    }

    const confirmar = confirm("Tem certeza que deseja enviar o formulário?");
    if (!confirmar) {
        event.preventDefault();  // impede o envio do formulário
    } else {
        alert("Formulário enviado com sucesso!");
    }
});

// Envia o formulário via AJAX
document.getElementById('contactForm2').addEventListener('submit', e => {
    e.preventDefault();  // impede o envio normal do formulário

    // se a validação falhar, não envia os dados
    if (!validarFormulario()) {
        return;
    }

    const formData = new FormData(e.target);  // cria um objeto FormData com os dados do formulário

    fetch('../paginas/contatos.php', {  // envia os dados para o servidor
        method: 'POST',
        body: formData  // envia os dados do formulário
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
