
<?php
session_start();

include 'clases.php';
include 'gestionararticulos.php';
include 'db.php';

if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}




$conexion = $pdo;
$REGS = 4;
$pagina = 1;
$inicio = 0;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $REGS;
}
$name1=$_GET['name1'];

$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriapadre = '$name1'");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriapadre = '$name1' LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();

?>
<?php include("header.php"); ?>

<div class="padre " style="height: 80vh;">
    <div class="hijo1 ">
        <?php include("side.php") ;?>
    </div>
    <div class="hijo2   ">
        <div style="display: flex;width:100%;height:auto;justify-content: space-between;margin: 5px;">
           
        </div>
        <div class="container mt-4">
            <table class="table table-borderless">
                <tr>
                    <th>codigo</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>categoriapadre</th>
                    <th>categoriahijo</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Imagen</th>
                    <th>Editar</th>
                    <th>Eleminar</th>

                </tr>
                <?php while ($articulo = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($articulo['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriapadre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriahijo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['precio']); ?>  €</td>
                        <td>
                            <?php if ($articulo["descuento"] > 0) { ?>
                                <p style="color: red; font-size: 18px;"><?php echo $articulo["descuento"] ?> %</p>
                            <?php } else { ?>
                                <p><?php echo $articulo["descuento"] ?> % </p>
                            <?php } ?>
                        </td>
                        <td> <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" width="80" height="80" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $articulo["codigo"] ?>'><img name='borrar' src='../imagnes2/check2.png' height='25' ></a></td>
                        <td><a href='eliminararticulos.php?eliminar=<?php echo $articulo["codigo"] ?>'><img height='25' src='../imagnes2/close2.png'></a></td>


                    </tr>
                <?php } ?>

                </table>
        </div>
    </div>
    <div class="hijo3">
        <ul class="list-group pt-4">
        <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="categorias.php">gestion gategorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
            



<?php

echo '<div class="d-flex justify-content-center m-3">';
if ($pagina > 1) {
    // Mantener los parámetros name1 y name2 en la URL
    echo '<a class="mx-1" href="?pagina=' . ($pagina - 1)  . '&name1=' . urlencode($name1) .  '"><img class="mb-1" id="im" src="back.png" alt="" height="12px">Anterior</a>';
}

for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == $pagina) {
        echo "<span style='color:blue;'>$i</span>";
    } else {
        // Mantener los parámetros name1 y name2 en la URL
        echo "<a class='mx-2' href=\"?pagina=$i&name1=" . urlencode($name1) .  "\">$i</a>";
    }
}

if ($pagina < $total_paginas) {
    // Mantener los parámetros name1 y name2 en la URL
    echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '&name1=' . urlencode($name1) .'"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px">Seguiente</a>';
}
echo '</div>';


    ?>
<?php include("footer.php"); ?>