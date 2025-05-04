document.addEventListener("DOMContentLoaded", function () {
    const botaoLogout = document.getElementById("logout");
    if (botaoLogout) {
        botaoLogout.addEventListener("click", function(e) {
            e.preventDefault();

            if (confirm("Tem certeza que deseja sair?")) {
                window.location.href = '../paginas/excluir_conta.php'; // redireciona para a página de exclusão
            }

            else{
                return false; // impede a ação de exclusão
            }
        });
    }
});
