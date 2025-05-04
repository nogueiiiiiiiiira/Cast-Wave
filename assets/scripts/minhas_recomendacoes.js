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

    // Buscar recomendações do backend
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
                // data.recomendacoes is a string with recommendations
                // Split by new lines and create cards for each recommendation
                const recs = data.recomendacoes.split('\n').filter(line => line.trim() !== '');
                let cardsHtml = '';
                recs.forEach(rec => {
                    cardsHtml += `
                        <div class="card" style="width: 18rem; margin: 10px; display: inline-block; vertical-align: top;">
                            <div class="card-body">
                                <p class="card-text">${rec.trim()}</p>
                            </div>
                        </div>
                    `;
                });
                resultado.innerHTML = cardsHtml || "Nenhuma recomendação disponível.";
            } else {
                resultado.innerHTML = "Nenhuma recomendação disponível.";
            }
        }, 500); // Delay para o 100% ser visível
    })
    .catch(error => {
        clearInterval(intervalo);
        barra.style.width = "100%";
        barra.style.backgroundColor = "#dc3545"; // Vermelho de erro
        barra.innerText = "Erro";

        resultado.innerHTML = `<p>Erro ao buscar recomendações.</p>`;
        console.error("Erro na requisição:", error);
    });
}
