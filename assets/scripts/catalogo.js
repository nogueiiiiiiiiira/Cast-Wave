function confirmar_aluguel(nome_filme, preco) {
    const confirmar = confirm(`Você tem certeza que deseja alugar o filme "${nome_filme}" por R$ ${preco}?\n\nVocê terá 15 dias para assistir ao filme.`);

    return confirmar;
}

document.querySelectorAll('.alugar_form').forEach(form => { // seleciona todos os formulários de aluguel
    form.addEventListener('submit', e => { // adiciona um evento de submit a cada formulário
        e.preventDefault(); // previne o comportamento padrão do formulário

        const nome_filme = e.target.elements['nome_filme'].value; // obtém o nome do filme
        const preco = e.target.elements['preco'].value; // obtém o preço do aluguel

        if (confirmar_aluguel(nome_filme, preco)) { // chama a função de confirmação

            const form_data = new FormData(e.target);
        // cria um objeto form_data com os dados do formulário

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
    }});
});

function mostrar_detalhes(filme_id, titulo, generos, resumo) {
    var api_key = "7d76651465970372fcd6d406b5b325ee";  
    var url = `https://api.themoviedb.org/3/movie/${filme_id}?api_key=${api_key}&language=pt-BR&append_to_response=videos`;

    var release_dates_url = `https://api.themoviedb.org/3/movie/${filme_id}/release_dates?api_key=${api_key}`;

    Promise.all([ // busca os dados do filme e as datas de lançamento. promise serve para esperar as duas requisições serem concluídas
        fetch(url).then(response => response.json()), // busca os dados do filme
        fetch(release_dates_url).then(response => response.json()) // busca as datas de lançamento
    ])
    .then(([data, release_data]) => { // desestrutura o array de promessas resolvidas
        var release_date = data.release_date || 'Data não disponível';
        var idade = 'Não informada';

        // Busca a classificação indicativa para o Brasil
        if (release_data && release_data.results) {
            var br_release = release_data.results.find(r => r.iso_3166_1 === 'BR'); // procura a data de lançamento do Brasil
            if (br_release && br_release.release_dates && br_release.release_dates.length > 0) { // verifica se existem datas de lançamento
                for (var i = 0; i < br_release.release_dates.length; i++) { // percorre as datas de lançamento
                    var cert = br_release.release_dates[i].certification; // obtém a classificação indicativa
                    if (cert && cert.trim() !== '') {  // verifica se a classificação não está vazia
                        idade = cert; // atribui a classificação à variável idade
                        break;
                    }
                }
            }
        }

        var trailer_url = data.videos && data.videos.results.length ? `https://www.youtube.com/watch?v=${data.videos.results[0].key}` : null;

        // formatar a data de lançamento no formato DD-MM-YYYY
        if (release_date !== 'Data não disponível') {
            var dateParts = release_date.split('-');  
            release_date = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;  // Formata para DD-MM-YYYY
        }

        // atualiza os detalhes no modal
        document.getElementById('modal_titulo').innerText = titulo;
        document.getElementById('modal_generos').innerText = generos;
        document.getElementById('moda_lancamento').innerText = release_date;
        document.getElementById('moda_idade').innerText = idade;
        
        // atribui o resumo
        var filmeResumo = data.overview || resumo || 'Resumo não disponível';
        document.getElementById('modal_resumo').innerText = filmeResumo;

        // exibe ou oculta o link do trailer
        if (trailer_url) {
            document.getElementById('trailer_link').classList.remove('d-none');  // remove a classe d-none para mostrar o link
            document.getElementById('trailer_url').href = trailer_url; // define o link do trailer
        } else {
            document.getElementById('trailer_link').classList.add('d-none');  // adiciona a classe d-none para ocultar o link
        }
    })
    .catch(error => {
        console.error('Erro ao carregar detalhes:', error);
    });
}
