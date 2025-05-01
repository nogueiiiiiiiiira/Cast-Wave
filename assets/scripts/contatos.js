const telefone = document.getElementById("telefone");

telefone.addEventListener("input", () => {
    let value = telefone.value.replace(/\D/g, "").slice(0, 11); // remover caracteres não numéricos e limitar a 11 dígitos
    value = value.replace(/^(\d{2})(\d)/, "($1) $2"); // adicionar parênteses e espaço
    value = value.replace(/(\d{5})(\d)/, "$1-$2"); // adicionar hífen
    telefone.value = value; // atualiza o valor do input
});

document.getElementById("contactForm2").addEventListener("submit", function (event) {
    const confirmar = confirm("Tem certeza que deseja enviar o formulário?");
    if (!confirmar) {
        event.preventDefault(); 
    }

    else {
        alert("Formulário enviado com sucesso!");
    }
});
