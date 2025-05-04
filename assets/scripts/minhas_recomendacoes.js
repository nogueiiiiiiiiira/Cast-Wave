function buscar_recomendacoes() {
    const resultado = document.getElementById("resultado");

    // Limpa o conteúdo anterior e adiciona a barra de progresso
    resultado.innerHTML = `
        <p>Carregando recomendações...</p>
        <div id="progress-container">
            <div id="progress-bar">0%</div>
        </div>
    `;

    const barra = document.getElementById("progress-bar");
    const container = document.getElementById("progress-container");

    // Torna a barra visível assim que a busca começa
    container.style.visibility = 'visible';

    let progresso = 0;

    // Simulação de progresso
    const intervalo = setInterval(() => {
        if (progresso < 95) {
            progresso += Math.random() * 5;
            barra.style.width = progresso + "%";
            barra.innerText = Math.floor(progresso) + "%";
        }
    }, 200);

    const api_key = '7d76651465970372fcd6d406b5b325ee';
    const baseUrl = 'https://api.themoviedb.org/3/search/movie?api_key=' + api_key + '&language=en-US&query=';
    const genreUrl = 'https://api.themoviedb.org/3/genre/movie/list?api_key=' + api_key + '&language=en-US';

    // Fetch genre list from TMDb API
    fetch(genreUrl)
    .then(response => response.json())
    .then(genreData => {
        const genreMap = {};
        if (genreData.genres) {
            genreData.genres.forEach(g => {
                genreMap[g.id] = g.name;
            });
        }

        // Now fetch recommendations from backend
        fetch("../paginas/recomendacoes.php", {
            method: "POST",
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(intervalo);
            barra.style.width = "100%";
            barra.innerText = "100%";
            barra.style.backgroundColor = "#28a745"; // Verde de sucesso

            setTimeout(() => {
                if (data.erro) {
                    resultado.innerHTML = `<strong>Erro:</strong> ${data.erro}`;
                } else if (data.recomendacoes) {
                    const recs = data.recomendacoes.split('\n').filter(line => line.trim() !== '');
                    let cardsHtml = '';

                    // Function to create card with movie details
                    function criarCardDetalhes(filme) {
                        const imagem = filme.poster_path ? 'https://image.tmdb.org/t/p/w500' + filme.poster_path : 'caminho/para/imagem/default.jpg';
                        const nota = filme.vote_average;
                        const generos = filme.genre_ids.map(id => genreMap[id] || "Desconhecido").join(", ");

                        return `
                            <div class="card" style="width: 18rem; margin: 10px; display: inline-block; vertical-align: top;">
                                <img src="${imagem}" class="card-img-top" alt="${filme.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${filme.title}</h5>
                                    <p class="card-text">Nota: ${nota}</p>
                                    <p class="card-text">Gêneros: ${generos}</p>
                                    <button type="button" 
                                        class="btn btn-custom me-4" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#detalhesModal"
                                        onclick="buscar_detalhes(${filme.id})">
                                        Detalhes
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    // Function to create simple card for titles not found in TMDb
                    function criarCardSimples(titulo) {
                        return `
                            <div class="card" style="width: 18rem; margin: 10px; display: inline-block; vertical-align: top; background-color: #f8d7da; color: #721c24;">
                                <div class="card-body">
                                    <p class="card-text">${titulo}</p>
                                    <p><strong>TMDb:</strong> não</p>
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
                                } else {
                                    cardsHtml += criarCardSimples(titulo.trim());
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
    const api_key = '7d76651465970372fcd6d406b5b325ee';
    const url = `https://api.themoviedb.org/3/movie/${filme_id}?api_key=${api_key}&language=pt-BR&append_to_response=videos`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const modalTitulo = document.getElementById('modal_titulo');
            const modalGeneros = document.getElementById('modal_generos');
            const modalLancamento = document.getElementById('modal_lancamento');
            const modalResumo = document.getElementById('modal_resumo');
            const modalIdade = document.getElementById('modal_idade');
            const trailerSection = document.getElementById('trailer_link');
            const trailerUrl = document.getElementById('trailer_url');

            if (!modalTitulo || !modalGeneros || !modalLancamento || !modalResumo || !modalIdade || !trailerSection || !trailerUrl) {
                console.error('Elementos do modal não encontrados no DOM.');
                return;
            }

            modalTitulo.textContent = data.title || 'Título não disponível';
            const generos = data.genres ? data.genres.map(g => g.name).join(', ') : 'Gêneros não disponíveis';
            modalGeneros.textContent = generos;
            modalLancamento.textContent = data.release_date || 'Data não disponível';
            modalResumo.textContent = data.overview || 'Resumo não disponível';
            modalIdade.textContent = data.adult ? '18+' : 'Livre';

            trailerSection.classList.add('d-none');
            trailerUrl.href = '#';

            if (data.videos && data.videos.results) {
                const trailer = data.videos.results.find(video => video.type === 'Trailer' && video.site === 'YouTube');
                if (trailer) {
                    trailerUrl.href = `https://www.youtube.com/watch?v=${trailer.key}`;
                    trailerSection.classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error('Erro ao buscar detalhes do filme:', error);
        });
}
