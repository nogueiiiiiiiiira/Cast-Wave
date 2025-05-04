function buscarRecomendacoes() {
    const resultado = document.getElementById("resultado");
    resultado.innerHTML = "Carregando recomendações...";

    fetch("../paginas/recomendacoes.php", {
        method: "POST",
    })
    .then(response => {
        console.log("Resposta do servidor:", response);  // log da resposta do servidor
        return response.json();  // processa como JSON
    })
    .then(data => {
        console.log("Dados recebidos:", data);  // log dos dados recebidos
        if (data.erro) {
            resultado.innerHTML = "<strong>Erro:</strong> " + data.erro;
        } else if (data.recomendacoes) {
            resultado.innerHTML = "<strong>Recomendações:</strong><br>" + data.recomendacoes;
        } else {
            resultado.innerHTML = "Nenhuma recomendação disponível.";
        }
    })
    .catch(error => {
        resultado.innerHTML = "Erro ao buscar recomendações.";
        console.error("Erro na requisição:", error);  // log do erro
    });
}
