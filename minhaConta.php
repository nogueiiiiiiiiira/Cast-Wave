<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Minha Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>

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

<br>
<br>

<div class="container mt-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header text-center">
      <i class="fa-solid fa-user-circle me-2"></i>Minha Conta
    </div>
    <div class="card-body">
      <form>
        <div class="mb-3">
          <label for="nome" class="form-label">Nome completo</label>
          <div class="d-flex">
            <input disabled type="text" class="form-control" id="nome">
          </div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <div class="d-flex">
            <input disabled type="email" class="form-control" id="email">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Data de cadastro</label>
          <input type="text" class="form-control" disabled>
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <div class="d-flex">
            <input disabled type="password" class="form-control" id="senha">
          </div>
        </div>

        <br>
        <br>

        <div class="text-center mt-3">
          <button type="submit" class="btn btn-custom">
            <i class="fa-solid fa-save me-2"></i>Editar minha conta
          </button>
        </div>
      </form>

      <div class="text-center mt-3">
        <button class="btn btn-custom" onclick="confirmarExclusao()">
          <i class="fa-solid fa-trash me-2"></i>Excluir minha conta
        </button>
      </div>
    </div>
  </div>
</div>

<style>

  body {
    font-family: 'Roboto', sans-serif;
    background-color: black;
  }

  #navbarNav ul {
    transform: translateY(-0.5px);
    margin-left: -7.5px;
  }

  #navbarNav ul li a {
    text-decoration: none;
    transition: color 0.3s;
    margin: 19px 0; 
  }

  #navbarNav ul li a:hover {
    color: #28a745; 
  }

  .btn {
    color: white !important;
    border: none;
    transition: transform 0.3s ease;
    cursor: pointer;
  }

  .btn:hover {
    transform: scale(1.05);
  }
        
  .card {
    border-radius: 10px;
    background-color: #212529;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
  }

  .card .btn-custom {
    background-color: #343a40; 
    color: #28a745; 
    border: 1px solid #28a745; 
    padding: 8px 16px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
  }

  .card .btn-custom:hover {
    background-color: #28a745;
    color: white;
  }

  .card-header {
    background-color: #28a745;
    color: white;
    font-size: 1.2rem;
    padding: 15px;
  }

  .form-label {
    font-weight: 600;
  }

  .form-control {
    border-radius: 8px;
    padding: 12px;
  }

  .text-center {
    margin-top: 20px;
  }

  .card-body {
    padding: 25px;
  }

  .navbar-nav a {
    transition: all 0.3s ease;
  }

  .d-flex {
    display: flex;
    align-items: center;
  }

  .d-flex input {
    flex-grow: 1;
  }

  .d-flex button {
    flex-shrink: 0;
  }

  input{
    background-color: #e2e8f0;
    color: white;
    border: 1px solid #28a745;
  }

</style>

<script>
  function confirmarExclusao() {
    if (confirm("Tem certeza que deseja excluir sua conta? Esta ação não poderá ser desfeita.")) {
      alert("Conta excluída com sucesso.");
      window.location.href = "/projetoLocadora/login.html"; 
    }
  }
</script>

</body>
</html>
