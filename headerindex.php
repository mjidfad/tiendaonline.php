<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="link.css">
    <title>ElcheModa</title>
  </head>
  <body></body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow" >
  <div class="container-fluid">
   
    <img src="../imagnes2/tienda.jpeg" alt="" width="60" height="75">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav  mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">contactos</a>
        </li>
        
        
        
      </ul>
      <form class="d-flex" method="post" action="buscararticulosindex.php" >
       <input class="form-control me-2" type="search" placeholder="Search" name="cadena" aria-label="Buscar por nombre" placeholder="" >
        <button class="btn btn-outline-success" type="submit" name="buscar" >Search</button>
      </form>
    </div>
  </div>
</nav>