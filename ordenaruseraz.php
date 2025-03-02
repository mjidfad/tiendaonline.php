<?php
session_start();
include("db.php");
include 'clases.php';
include 'gestionarsocios.php';
$conexion = $pdo;
$gestor = new GestorSocios($conexion);

if (isset($_POST['añadir'])) {
    
    $id_producto = $_POST['id_producto'];
    $cantidad = max(1, intval($_POST['cantidad'])); // Asegurar que la cantidad sea al menos 1
    $stmt = $pdo->prepare("SELECT * FROM articulos WHERE id = :id");
    $stmt->execute([':id' => $id_producto]); 
    $producto = $stmt->fetch();
    if ($producto) {
        // Crear carrito y agregarlo si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] === $producto['id']) {
                $item['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }
  
        // Si no está en el carrito, agregarlo
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad
            ];
        }
    }
  
}

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
$stmt = $conexion->prepare("SELECT * FROM articulos ORDER BY precio ASC  LIMIT " . $inicio ." ,". $REGS);
$stmt->execute();


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height:80vh;width:100%;">
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
                <li><a class="dropdown-item" href="usuario.php">ordenar por Default</a></li>
                <li><a class="dropdown-item" href="ordenaruserza.php">ordenar por Descendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserbarato.php">ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarusercaro.php">ordenar por precio alto</a></li>
            </ul>
        </div>

        <div class="mt-4 d-flex flex-row justify-content-around">
        <?php
   while($fila = $stmt->fetch()) {
    ?>  
            <div class="card m-1" style="width: 30%;">
                <img style="object-fit: cover;" src="<?php echo $fila["imagen"] ?>" class="card-img-top" alt=""  height="150px"  width="80px">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                    <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                    <p><?php echo $fila["precio"] ?></p>
                    <form method='POST' class="d-flex flex-row">
                <input type='hidden' name='id_producto' value='<?php echo $fila["id"] ?>'>
                <input class="form-control" type='number' name='cantidad' value='0' min='1' style='width: 50px;' required>
                <button  class="btn btn-primary" type='submit' name="añadir" >Añadir</button>
            </form>
                   
                </div>
            </div>
            <?php } ?>  
        </div>
    </div>
    <div class="hijo3" style="width:14%" >
        <ul class="list-group pt-4">
        <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="editardatosuser.php">editar datos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
            



<?php

echo '<div class="d-flex justify-content-center">';
if ($pagina > 1) {
        //echo '<a href="?pagina=1">Primera</a>';
        echo '<a class="mx-1" href="?pagina=' . ($pagina - 1) . '"><img class="mb-1" id="im" src="back.png" alt="" height="12px"></a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina) {
            echo "<span style='color:blue;'>$i</span>";
        } else {
            echo "<a class='mx-2' href=\"?pagina=$i\">$i</a>";
        }
    }
    if ($pagina < $total_paginas) {
        echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px"></a>';
        //echo '<a href="?pagina=' . $total_paginas . '">Última</a>';
    }
     echo '</div>';

    ?>
<?php include("footer.php"); ?>