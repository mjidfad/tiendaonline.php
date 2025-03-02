<?php

include 'db.php';
include 'clases.php';
include 'gestionararticulos.php';
?>
<?php include("headerindex.php"); ?>

<div class="padre  d-flex" style="height: 80vh;">
    <div class="hijo1 " style="width:12%;">
        <?php include("sideindex.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:66%;height:auto;">
    <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="ordenarindexaz.php">Ordenar Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenarindexza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexbarato.php">ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarindexcaro.php">ordenar por precio alto</a></li>
            </ul>
        </div>
        <?php
        $conexion = $pdo;
        $gestor = new Gestorarticulos($conexion);

        // Procesar el formulario si se ha enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cadena = $_POST['cadena'];
            $socios = $gestor->buscarA($cadena);
            if (count($socios) > 0) {

        ?>

                <div class="mt-4 d-flex flex-row justify-content-around">

               <?php foreach ($socios as $articulo) { ?>
                <div class="card m-1" style="width: 30%;height:auto;">
                    <img style="object-fit: cover;" src="<?php echo $articulo->getImagen() ?>" class="card-img-top" alt="" height="150px" width="80px">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $articulo->getNombre() ?></h5>
                        <p class="card-text"><?php $articulo->getDescripcion() ?></p>
                        <p><?php echo $articulo->getPrecio() ?></p>
                        <a href="#" class="btn btn-primary" onclick="alerta()" >Comprar</a>
                </div>
                <script>
        function alerta() {
            alert("¡inicia session para comprar articulos!");
        }
    </script>
            </div>
            <?php }} }?> 
        </div>
    </div>
    <div class="hijo3 " style="width:24%;">
        <?php include("loginform.php")  ?>
    </div>
</div>




<?php include("footer.php"); ?>