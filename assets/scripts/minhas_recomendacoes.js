function buscar_recomendacoes() {
    const resultado = document.getElementById("resultado");

    resultado.innerHTML = `
        <p>Carregando recomendações...</p>
        <div id="progress-container">
            <div id="progress-bar">0%</div>
        </div>
    `;

    const barra = document.getElementById("progress-bar");
    const container = document.getElementById("progress-container");
    container.style.visibility = 'visible';

    let progresso = 0;
    const intervalo = setInterval(() => {
        if (progresso < 95) {
            progresso += Math.random() * 5;
            barra.style.width = progresso + "%";
            barra.innerText = Math.floor(progresso) + "%";
        }
    }, 200);

    const api_key = '7d76651465970372fcd6d406b5b325ee';
    const baseUrl = 'https://api.themoviedb.org/3/search/movie?api_key=' + api_key + '&language=pt-BR&query=';
    const genreUrl = 'https://api.themoviedb.org/3/genre/movie/list?api_key=' + api_key + '&language=pt-BR';

    fetch(genreUrl)
    .then(response => response.json())
    .then(genreData => {
        const genreMap = {};
        if (genreData.genres) {
            genreData.genres.forEach(g => {
                genreMap[g.id] = g.name;
            });
        }

        fetch("../paginas/recomendacoes.php", {
            method: "POST",
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(intervalo);
            barra.style.width = "100%";
            barra.innerText = "100%";
            barra.style.backgroundColor = "#28a745";

            setTimeout(() => {
                if (data.erro) {
                    resultado.innerHTML = `<strong>Erro:</strong> ${data.erro}`;
                } else if (data.recomendacoes) {
                    const recs = data.recomendacoes.split('\n').filter(line => line.trim() !== '');
                    let cardsHtml = '';

                    function criarCardDetalhes(filme) {
                        const imagem = filme.poster_path ? 'https://image.tmdb.org/t/p/w500' + filme.poster_path : 'caminho/para/imagem/default.jpg';
                        const nota = filme.vote_average;
                        const generos = filme.genre_ids.map(id => genreMap[id] || "Desconhecido").join(", ");

                        return `
                        <div class="card-container">
                            <div class="card">
                                <img src="${imagem}" class="card-img-top" alt="${filme.title}">
                                    <h4>${filme.title}</h4>
                                    <p><strong>Gênero:</strong> ${generos}</p>
                                    <p><strong>Nota do Público:</strong> ${nota}</p>
                                    <button type="button" 
                                        class="btn btn-custom me-4" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#detalhesModal"
                                        onclick="buscar_detalhes(${filme.id});">
                                        Detalhes
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    let fetches = recs.map(titulo => {
                        return fetch(baseUrl + encodeURIComponent(titulo))
                            .then(response => response.json())
                            .then(data => {
                                if (data.results && data.results.length > 0) {
                                    const filme = data.results[0];
                                    cardsHtml += criarCardDetalhes(filme);
                                }
                            })
                            .catch(error => {
                                console.error("Erro ao buscar no TMDb:", error);
                                cardsHtml += criarCardSimples(titulo.trim());
                            });
                    });

                    Promise.all(fetches).then(() => {
                        resultado.innerHTML = cardsHtml || "Nenhuma recomendação disponível.";
                    });
                } else {
                    resultado.innerHTML = "Nenhuma recomendação disponível.";
                }
            }, 500);
        })
        .catch(error => {
            clearInterval(intervalo);
            barra.style.width = "100%";
            barra.style.backgroundColor = "#dc3545";
            barra.innerText = "Erro";

            resultado.innerHTML = `<p>Erro ao buscar recomendações.</p>`;
            console.error("Erro na requisição:", error);
        });
    })
    .catch(error => {
        clearInterval(intervalo);
        barra.style.width = "100%";
        barra.style.backgroundColor = "#dc3545";
        barra.innerText = "Erro";

        resultado.innerHTML = `<p>Erro ao buscar gêneros.</p>`;
        console.error("Erro ao buscar gêneros:", error);
    });
}

function buscar_detalhes(filme_id) {
    const api_key = "7d76651465970372fcd6d406b5b325ee";
    const url = `https://api.themoviedb.org/3/movie/${filme_id}?api_key=${api_key}&language=pt-BR&append_to_response=videos`;
    const release_dates_url = `https://api.themoviedb.org/3/movie/${filme_id}/release_dates?api_key=${api_key}`;

    Promise.all([
        fetch(url).then(response => response.json()),
        fetch(release_dates_url).then(response => response.json())
    ])
    .then(([data, release_data]) => {
        let release_date = data.release_date || 'Data não disponível';
        let idade = 'Não informada';

        // Classificação indicativa para o Brasil
        if (release_data && release_data.results) {
            const br_release = release_data.results.find(r => r.iso_3166_1 === 'BR');
            if (br_release && br_release.release_dates.length > 0) {
                const cert = br_release.release_dates.find(rd => rd.certification && rd.certification.trim() !== '');
                if (cert) idade = cert.certification;
            }
        }

        // Formatando data
        if (release_date !== 'Data não disponível') {
            const [ano, mes, dia] = release_date.split("-");
            release_date = `${dia}-${mes}-${ano}`;
        }

        // Trailer
        const trailer_url = data.videos?.results?.[0]?.key 
            ? `https://www.youtube.com/watch?v=${data.videos.results[0].key}` 
            : null;

        // Atualizando os elementos no modal
        document.getElementById('modal_titulo').innerText = data.title || "Título não disponível";
        document.getElementById('modal_generos').innerText = data.genres?.map(g => g.name).join(", ") || "Gêneros não disponíveis";
        document.getElementById('modal_lancamento').innerText = release_date;
        document.getElementById('modal_idade').innerText = idade;
        document.getElementById('modal_resumo').innerText = data.overview || "Resumo não disponível";

        if (trailer_url) {
            document.getElementById('trailer_link').classList.remove('d-none');
            document.getElementById('trailer_url').href = trailer_url;
        } else {
            document.getElementById('trailer_link').classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Erro ao carregar detalhes:', error);
    });
}
