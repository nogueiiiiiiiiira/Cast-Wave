// confirmar o aluguel
function confirmarAluguel(nomeFilme, preco) {
    //  alerta de confirmação
    const confirmar = confirm(`Você tem certeza que deseja alugar o filme "${nomeFilme}" por R$ ${preco}?\n\nVocê terá 15 dias para assistir ao filme.`);
    if (!confirmacao) {
        event.preventDefault(); // cancela a ação do formulário se o usuário não confirmar
    }

    return confirmar; // retorna o resultado da confirmação
}