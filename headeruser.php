<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos.css">
    <title>ElcheModa</title>
  </head>
<body>
   
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow" >
  <div class="container-fluid">
   
    <img src="../imagnes2/tienda.jpeg" alt="" width="60" height="75">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav  mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="usuario.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">contactos</a>
        </li>
        <li class="nav-item dropdown d-flex f-row ">
        <a  href="carrito.php" style="text-decoration: none; font-size: 23px;"> 🛒
      
        </a><h5 id="carrit"><?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?></h5>
         
        </li>
       
       
    
        <li class="nav-item dropdown">
          <a class="nav-link " href="compras.php" id="navbarDropdown" >
            Compras
          </a>
             
        </li>
        
      </ul>
      <form class="d-flex" method="post" action="buscararticulosuser.php" >
        <input class="form-control me-2" type="search" placeholder="Search" name="cadena" aria-label="Search">
        <button class="btn btn-outline-success" type="submit"  >Search</button>
      </form>
    </div>
  </div>
</nav>
