<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Meus Aluguéis</title>
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
    <h3 class="text-center mb-4"><i class="fa-solid fa-film me-2"></i>Meus Aluguéis</h3>
</div>
   
<style>

        body {
            scroll-behavior: smooth;
            font-family: 'Roboto', sans-serif;
            background-color: black;
        }


        #navbarNav ul {
            margin-left: -7.5px;
            transform: translateY(-1px);
        }

        #navbarNav ul li a {
            text-decoration: none;
            transition: color 0.3s;
            margin: 20px 0; 
        }


        #navbarNav ul li a:hover {
            color: #28a745; 
        }


</style>
</body>
</html>
