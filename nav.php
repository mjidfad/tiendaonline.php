<nav class="navbar navbar-expand-lg navbar-light bg-white shadow" >
  <div class="container-fluid">
   
    <img src="../imagnes2/tienda.jpeg" alt="" width="60" height="75">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
   
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav  mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="editor.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="">contactos</a>
        </li>
       
        
      </ul>
      <form class="d-flex" method="post" action="buscararticulo.php" >
        <input class="form-control me-2" type="search" placeholder="Search" name="cadena" aria-label="Search">
        <button class="btn btn-outline-success" type="submit"  >Search</button>
      </form>
    </div>
  </div>
</nav>