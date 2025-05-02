function confirmarExclusao() {
    if (confirm("Tem certeza que deseja excluir sua conta? Essa ação não poderá ser desfeita.")) {
      window.location.href = '../paginas/excluir_conta.php';
    }

    else{
        return false;
    }
  }