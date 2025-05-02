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

function mostrarDetalhes(idFilme, titulo, generos, resumo) {
    var apiKey = "7d76651465970372fcd6d406b5b325ee";  
    var url = `https://api.themoviedb.org/3/movie/${idFilme}?api_key=${apiKey}&language=pt-BR&append_to_response=videos`;

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
