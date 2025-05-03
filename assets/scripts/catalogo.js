function confirmar_aluguel(nome_filme, preco) {
    const confirmar = confirm(`Você tem certeza que deseja alugar o filme "${nome_filme}" por R$ ${preco}?\n\nVocê terá 15 dias para assistir ao filme.`);

    return confirmar;
}

document.querySelectorAll('alugar_form').forEach(form => { // seleciona todos os formulários de aluguel
    form.addEventListener('submit', e => { // adiciona um evento de submit a cada formulário
        e.preventDefault(); // previne o comportamento padrão do formulário

        const nome_filme = e.target.elements['nome_filme'].value; // obtém o nome do filme
        const preco = e.target.elements['preco'].value; // obtém o preço do aluguel

        if (!confirmar_aluguel(nome_filme, preco)) { // chama a função de confirmação
            alert("Aluguel cancelado."); // exibe mensagem de cancelamento
            return; // usuário cancelou
        }

        const form_data = new form_data(e.target); // cria um objeto form_data com os dados do formulário

        fetch('../paginas/aluguel.php', { // envia os dados para o servidor
            method: 'POST', 
            body: form_data
        })
            .then(response => response.text()) // espera a resposta do servidor
            .then(message => { // exibe a resposta em um alerta
                alert(message); 
            }) 
            .catch(error => { // trata erros
                alert("Erro ao processar o aluguel. Tente novamente mais tarde.");
            });
    });
});

function mostrar_detalhes(idFilme, titulo, generos, resumo) {
    var api_key = "7d76651465970372fcd6d406b5b325ee";  
    var url = `https://api.themoviedb.org/3/movie/${idFilme}?api_key=${api_key}&language=pt-BR&append_to_response=videos`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            var releaseDate = data.release_date || 'Data não disponível';
            var idade = data.adult ? '18+' : 'Livre';
            var trailerUrl = data.videos && data.videos.results.length ? `https://www.youtube.com/watch?v=${data.videos.results[0].key}` : null;

            // formatar a data de lançamento no formato DD-MM-YYYY
            if (releaseDate !== 'Data não disponível') {
                var dateParts = releaseDate.split('-');  
                releaseDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;  // Formata para DD-MM-YYYY
            }

            // atualiza os detalhes no modal
            document.getElementById('modalTitulo').innerText = titulo;
            document.getElementById('modalGeneros').innerText = generos;
            document.getElementById('modalDataLancamento').innerText = releaseDate;
            document.getElementById('modalIdade').innerText = idade;
            
            // atribui o resumo
            var filmeResumo = data.overview || resumo || 'Resumo não disponível';
            document.getElementById('modalResumo').innerText = filmeResumo;

            // exibe ou oculta o link do trailer
            if (trailerUrl) {
                document.getElementById('trailerLink').classList.remove('d-none');  // remove a classe d-none para mostrar o link
                document.getElementById('trailerUrl').href = trailerUrl; // define o link do trailer
            } else {
                document.getElementById('trailerLink').classList.add('d-none');  // adiciona a classe d-none para ocultar o link
            }
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error);
        });
}