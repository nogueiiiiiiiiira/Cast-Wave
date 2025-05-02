function confirmarAluguel(nomeFilme, preco) {
    const confirmar = confirm(`Você tem certeza que deseja alugar o filme "${nomeFilme}" por R$ ${preco}?\n\nVocê terá 15 dias para assistir ao filme.`);

    return confirmar; 
}

document.querySelectorAll('.alugarForm').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();

        const nomeFilme = e.target.elements['nome_filme'].value;
        const preco = e.target.elements['preco'].value;

        if (!confirmarAluguel(nomeFilme, preco)) {
            return; // usuário cancelou
        }

        const formData = new FormData(e.target);

        fetch('../paginas/aluguel.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            alert(message);
        })
        .catch(error => {
            alert("Erro ao processar o aluguel. Tente novamente mais tarde.");
        });
    });
});
