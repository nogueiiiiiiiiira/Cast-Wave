function confirmar_exclusao() {
    if (confirm("Tem certeza que deseja excluir sua conta? Essa ação não poderá ser desfeita.")) {
      window.location.href = '../paginas/excluir_conta.php'; // redireciona para a página de exclusão
    }

    else{
        return false; // impede a ação de exclusão
    }
  }