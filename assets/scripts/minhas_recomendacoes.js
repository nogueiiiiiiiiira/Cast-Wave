function buscar_recomendacoes() {

    const resultado = document.getElementById("resultado");

    // limpa o conteúdo anterior
    resultado.innerHTML = `
        <p>Carregando recomendações...</p>
        <div id="progress-container">
            <div id="progress-bar">0%</div>
        </div>
    `;

    const barra = document.getElementById("progress-bar"); // barra de progresso
    const container = document.getElementById("progress-container"); // container da barra de progresso
    container.style.visibility = 'visible'; // torna o container visível

    let progresso = 0; // variável para controlar o progresso
    barra.style.width = progresso + "%"; // inicializa a largura da barra
    const intervalo = setInterval(() => { // atualiza a barra de progresso
        if (progresso < 95) { // enquanto o progresso for menor que 95%
            progresso += Math.random() * 5; // aumenta o progresso aleatoriamente
            barra.style.width = progresso + "%"; // atualiza a largura da barra
            barra.innerText = Math.floor(progresso) + "%"; // atualiza o texto da barra
        }
    }, 200);

    const api_key = '7d76651465970372fcd6d406b5b325ee';
    const base_url = 'https://api.themoviedb.org/3/search/movie?api_key=' + api_key + '&language=pt-BR&query='; // URL base para busca de filmes
    const genero_url = 'https://api.themoviedb.org/3/genre/movie/list?api_key=' + api_key + '&language=pt-BR'; // URL para busca de gêneros de filmes

    fetch(genero_url) // busca os gêneros de filmes
        .then(response => response.json()) // converte a resposta para JSON
        .then(genero_data => {
            const genero_map = {}; // mapeia os gêneros
            if (genero_data.genres) { // se existirem gêneros
                genero_data.genres.forEach(g => { // para cada gênero
                    genero_map[g.id] = g.name; // adiciona ao mapa
                });
            }

            fetch("../paginas/recomendacoes.php", { // busca as recomendações
                method: "POST",
            })
                .then(response => response.json())
                .then(data => {
                    clearInterval(intervalo); // limpa o intervalo da barra de progresso
                    barra.style.width = "100%"; // define a largura da barra como 100%
                    barra.innerText = "100%"; // atualiza o texto da barra
                    barra.style.backgroundColor = "#28a745"; // muda a cor da barra para verde

                    setTimeout(() => { // aguarda 1 segundo
                        if (data.erro) { // se existir erro
                            resultado.innerHTML = `<strong>Erro:</strong> ${data.erro}`; // exibe o erro
                        } else if (data.recomendacoes) { // se existirem recomendações
                            const recs = data.recomendacoes.split('\n').filter(line => line.trim() !== ''); // separa as recomendações em linhas
                            let cards_html = ''; // variável para armazenar o HTML dos cards

                            function criar_detalhes(filme) {
                                const imagem = filme.poster_path ? 'https://image.tmdb.org/t/p/w500' + filme.poster_path : 'caminho/para/imagem/default.jpg';
                                const nota = filme.vote_average;

                                let generos_array = filme.genre_ids.map(id => genero_map[id] || "Desconhecido");
                                if (generos_array.length === 0 || generos_array.every(g => g === "Desconhecido")) {
                                    generos_array = ["Gêneros não disponíveis"];
                                }
                                const generos = generos_array.join(", ");

                                return ` 
                                <div class="card-container">
                                    <div class="card">
                                        <img src="${imagem}" class="card-img" alt="${filme.title}">
                                            <h4>${filme.title}</h4>
                                            <p><strong>Gênero:</strong> ${generos}</p>
                                            <p><strong>Nota do Público:</strong> ${nota}</p>
                                            <center>
                                            <button
                                                class="btn btn-custom me-4" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#detalhesModal"
                                                onclick="buscar_detalhes(${filme.id});"
                                                style="height: 50px;">
                                                Detalhes
                                            </button>
                                            </center>
                                        </div>
                                    </div>
                                `;
                            }


                            // pesquisa os filmes no TMDb
                            let fetches = recs.map(titulo => { // para cada recomendação
                                return fetch(base_url + encodeURIComponent(titulo)) // busca o filme no TMDb com base no título
                                    .then(response => response.json()) // converte a resposta para JSON
                                    .then(data => { // quando a resposta for processada
                                        if (data.results && data.results.length > 0) { // se existirem resultados
                                            const filme = data.results[0]; // pega o primeiro resultado
                                            cards_html += criar_detalhes(filme); // adiciona o card do filme ao HTML
                                        }
                                    })
                                    .catch(error => { // se houver erro
                                        console.error("Erro ao buscar no TMDb:", error); // exibe o erro
                                    });
                            });

                            // aguarda todas as requisições serem concluídas
                            Promise.all(fetches).then(() => { // quando todas as requisições forem concluídas
                                resultado.innerHTML = cards_html || "Nenhuma recomendação disponível."; // atualiza o HTML com os cards dos filmes
                            });
                        } else { // se não existirem recomendações
                            resultado.innerHTML = "Nenhuma recomendação disponível."; // exibe mensagem de erro
                        }
                    }, 500); // aguarda 500ms antes de buscar as recomendações
                })
                .catch(error => { // se houver erro
                    clearInterval(intervalo); // limpa o intervalo da barra de progresso
                    barra.style.width = "100%"; // atualiza a barra de progresso para 100%
                    barra.style.backgroundColor = "#dc3545"; // muda a cor da barra para vermelho
                    barra.innerText = "Erro"; // atualiza o texto da barra

                    resultado.innerHTML = `<p>Erro ao buscar recomendações.</p>`; // exibe mensagem de erro
                    console.error("Erro na requisição:", error); // exibe o erro
                });
        })
        .catch(error => { // se houver erro
            clearInterval(intervalo); // limpa o intervalo da barra de progresso
            barra.style.width = "100%"; // atualiza a barra de progresso para 100%
            barra.style.backgroundColor = "#dc3545"; // muda a cor da barra para vermelho
            barra.innerText = "Erro"; // atualiza o texto da barra

            resultado.innerHTML = `<p>Erro ao buscar gêneros.</p>`; // exibe mensagem de erro
            console.error("Erro ao buscar gêneros:", error); // exibe o erro
        });
}

function buscar_detalhes(filme_id) {
    const api_key = "7d76651465970372fcd6d406b5b325ee";
    const url = `https://api.themoviedb.org/3/movie/${filme_id}?api_key=${api_key}&language=pt-BR&append_to_response=videos`;  // URL para buscar detalhes do filme
    const release_dates_url = `https://api.themoviedb.org/3/movie/${filme_id}/release_dates?api_key=${api_key}`; // URL para buscar as datas de lançamento

    Promise.all([ // faz as requisições para buscar os detalhes do filme e as datas de lançamento
        fetch(url).then(response => response.json()), // busca os detalhes do filme
        fetch(release_dates_url).then(response => response.json()) // busca as datas de lançamento
    ])
        .then(([data, release_data]) => { // quando as requisições forem concluídas
            let release_date = data.release_date || 'Data não disponível'; // data de lançamento do filme
            let idade = 'Não informada'; // classificação indicativa do filme

            // classificação indicativa para o Brasil
            if (release_data && release_data.results) { // se as datas de lançamento forem encontradas
                const br_release = release_data.results.find(r => r.iso_3166_1 === 'BR'); // busca a data de lançamento para o Brasil
                if (br_release && br_release.release_dates.length > 0) { // se existirem datas de lançamento
                    const cert = br_release.release_dates.find(rd => rd.certification && rd.certification.trim() !== ''); // busca a classificação indicativa
                    if (cert) { // se existir classificação indicativa
                        idade = cert.certification; // atualiza a classificação indicativa      
                    }
                }
            }

            // formatando data
            if (release_date !== 'Data não disponível') { // se a data de lançamento estiver disponível
                const [ano, mes, dia] = release_date.split("-"); // separa a data em ano, mês e dia
                release_date = `${dia}-${mes}-${ano}`; // formata a data para o padrão brasileiro
            }

            // trailer
            const trailer_url = data.videos?.results?.[0]?.key  // busca o trailer do filme
                ? `https://www.youtube.com/watch?v=${data.videos.results[0].key}` // URL do trailer
                : null;

            // atualiza os elementos no modal
            document.getElementById('modal_titulo').innerText = data.title || "Título não disponível"; // atualiza o título do modal
            document.getElementById('modal_generos').innerText = data.genres?.map(g => g.name).join(", ") || "Gêneros não disponíveis"; // atualiza os gêneros do modal
            document.getElementById('modal_lancamento').innerText = release_date; // atualiza a data de lançamento do modal
            document.getElementById('modal_idade').innerText = idade; // atualiza a classificação indicativa do modal
            document.getElementById('modal_resumo').innerText = data.overview || "Resumo não disponível"; // atualiza o resumo do modal

            if (trailer_url) { // se o trailer estiver disponível
                document.getElementById('trailer_link').classList.remove('d-none'); // remove a classe d-none para exibir o link do trailer
                document.getElementById('trailer_url').href = trailer_url; // atualiza a URL do trailer
            } else {
                document.getElementById('trailer_link').classList.add('d-none'); // adiciona a classe d-none para ocultar o link do trailer
            }
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error); // exibe o erro no console
        });
}
