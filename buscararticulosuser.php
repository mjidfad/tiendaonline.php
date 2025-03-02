<?php
session_start();
include("db.php");
include 'clases.php';
include 'gestionararticulos.php';
$conexion = $pdo;
        $gestor = new Gestorarticulos($conexion);


//////////
$REGS = 4;
$pagina = 1;
$inicio = 0;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $REGS;
}
$stmt = $conexion->prepare("SELECT * FROM articulos");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $conexion->prepare("SELECT * FROM articulos LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:75%;">
        <div class="dropdown  me-3 w-100 d-flex justify-content-between">
            <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4>
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="ordenaruseraz.php">ordenar por Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserza.php">ordenar por Descendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserbarato.php">ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarusercaro.php">ordenar por precio alto</a></li>
            </ul>
        </div>
        <?php
        

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
                                <a href="#" class="btn btn-primary">Comprar</a>
                            </div>

                        </div>
                    <?php } ?>


                </div>

        <?php }
        }

        ?>
    </div>
    <div class="hijo3" style="width:14%">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="editardatosuser.php">editar datos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>




<?php


?>
<?php include("footer.php"); ?>