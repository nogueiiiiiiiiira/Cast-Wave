<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Minha Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">


<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">        
        <li class="nav-item"><a class="nav-link" href="./pagina_principal.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="./minhaConta.php">Minha Conta</a></li>
        <li class="nav-item"><a class="nav-link" href="./meusAlugueis.php">Meus Aluguéis</a></li>
        <li class="nav-item"><a class="nav-link" href="./logout.php">Sair</a></li>
      </ul>
    </div>
  </div>
</nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow rounded-4 p-4">
          <h3 class="text-center mb-4"><i class="fa-solid fa-user-circle me-2"></i>Minha Conta</h3>
          
          <form>
            <div class="mb-3">
              <label for="nome" class="form-label">Nome completo</label>
              <input type="text" class="form-control" id="nome">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" class="form-control" id="email">
            </div>

            <div class="mb-3">
              <label class="form-label">Data de cadastro</label>
              <input type="text" class="form-control" disabled>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">Salvar alterações</button>
            </div>
          </form>

          <hr class="my-4">

          <h5><i class="fa-solid fa-key me-2"></i>Alterar Senha</h5>
          <form>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Senha atual">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Nova senha">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Confirmar nova senha">
            </div>

            <div class="d-grid gap-2">
              <button class="btn btn-warning">Alterar senha</button>
            </div>
          </form>

          <hr class="my-4">

          <div class="text-center">
            <button class="btn btn-outline-danger" onclick="confirmarExclusao()">
              <i class="fa-solid fa-trash me-2"></i>Excluir minha conta
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>

      body {
            scroll-behavior: smooth;
            font-family: 'Roboto', sans-serif;
        }

        #navbarNav ul li a {
          text-decoration: none;
          transition: color 0.3s;
        }

        #navbarNav ul li a:hover {
            color: #28a745; 
        }


  </style>

  <script>
    function confirmarExclusao() {
      if (confirm("Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.")) {

        // back para ser feito

        alert("Conta excluída com sucesso.");
        window.location.href = "/projetoLocadora/login.html"; 
      }
    }
  </script>

</body>
</html>
