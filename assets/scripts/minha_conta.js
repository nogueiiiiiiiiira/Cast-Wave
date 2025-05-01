function confirmarExclusao() {
    if (confirm("Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.")) {
        window.location.href = "./excluirConta.php";
    }
}