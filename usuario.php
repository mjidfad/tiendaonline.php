<?php
session_start();

include("db.php");
include 'clases.php';
include 'gestionarsocios.php';
$conexion = $pdo;
$gestor = new GestorSocios($conexion);
if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}
$cliente=$_SESSION['id'];
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
            if ($item['codigo'] === $producto['codigo']) {
                $item['cantidad'] += $cantidad;
           
                $encontrado = true;
                break;
            }
        }
        // Si no está en el carrito, agregarlo
        if (!$encontrado) {
            $_SESSION['carrito'][] = [
               
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'nombre' => $producto['nombre'],
                'imagen' =>$producto['imagen'],
                'precio' => $producto['precio'],
                'descuento' => $producto['descuento'],
                'cantidad' => $cantidad,
            ];
            include 'db2.php';
            $total= $producto['precio']-( $producto['precio']*($producto['descuento']/100));
            $stmt = $pdo->prepare("INSERT INTO carrito (id_producto,codigo, nombre,imagen, precio, descuento, cantidad, total, id_cliente) VALUES ( ?,?,?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssdiiid",$id_producto, $producto['codigo'], $producto['nombre'], $producto['imagen'], $producto['precio'],
             $producto['descuento'], $cantidad, $total, $cliente);
            // Ejecutar el query
           $stmt->execute();
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
$stmt = $conexion->prepare("SELECT * FROM articulos LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();
//////////









//////


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:74%;margin-top:10px;">
        <div class="dropdown  me-3 w-100 d-flex justify-content-between">
            <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4>
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="ordenaruseraz.php">Ordenar por Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserza.php">Ordenar por Descendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserbarato.php">Ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarusercaro.php">Ordenar por precio alto</a></li>
            </ul>
        </div>
        <div class="mt-4 d-flex flex-row justify-content-around">
            <?php while ($fila = $stmt->fetch()) { ?>
                <div class="card m-1 w-25">
                    <img src="<?php echo $fila["imagen"] ?>" class="card-img-top img2" alt="" height="150px" width="80px">
                    <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                    <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                    <div class="d-flex flex-row justify-content-between">
                        <p><?php echo $fila["precio"] ?> €</p>
                        <?php if ($fila["descuento"] > 0) { ?>
                            <p style="color: red; font-size: 18px;"><?php echo $fila["descuento"] ?> % descuento</p>
                        <?php } else { ?>
                            <p style="display:none"><?php echo $fila["descuento"] ?> % descuento</p>
                        <?php
                       // $descuento= $fila["precio"]-($fila["precio"]*($fila["descuento"]/100));
                       }                    
                        ?>                      
                    </div> 
                    <form method='POST' class="d-flex flex-row">                     
                            <input type='hidden' name='id_producto' value='<?php echo $fila["id"] ?>'>
                            <input class="form-control" type='number' name='cantidad' value='1' min='1' style='width: 50px;' required>
                            <button class="btn btn-primary w-100" type='submit' name="añadir">Añadir</button>
                        </form>
                </div>
            <?php ?>
            <?php
} 
?>
       </div>
    </div>
<div class="hijo3" style="width:14%">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom elemento"><a href="usuario.php">Monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="editardatosuser.php">Editar datos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="cerrar.php">Cerrar session</a></li>
        </ul>

    </div>
</div>





<?php

echo '<div class="d-flex justify-content-center">';
if ($pagina > 1) {
    //echo '<a href="?pagina=1">Primera</a>';
    echo '<a class="mx-1" href="?pagina=' . ($pagina - 1) . '"><img class="mb-1" id="im" src="back.png" alt="" height="12px"> Anterior</a>';
}
for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == $pagina) {
        echo "<span style='color:blue;'>$i</span>";
    } else {
        echo "<a class='mx-2' href=\"?pagina=$i\">$i</a>";
    }
}
if ($pagina < $total_paginas) {
    echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px"> Seguiente</a>';
    //echo '<a href="?pagina=' . $total_paginas . '">Última</a>';
}
echo '</div>';
////////////




?>
<?php include("footer.php"); ?>