
<?php
session_start();

include 'clases.php';
include 'gestionararticulos.php';
include 'db.php';

$conexion = $pdo;
$REGS = 4;
$pagina = 1;
$inicio = 0;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $REGS;
}
$name=$_GET['name'];

$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriapadre = '$name'");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriapadre = '$name' LIMIT " . $inicio . " ," . $REGS);
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
                    <th>imagen</th>
                    <th>editar articulo</th>
                    <th>eleminar articulo</th>

                </tr>
                <?php while ($articulo = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($articulo['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriapadre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriahijo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['precio']); ?></td>
                        <td> <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" width="80" height="80" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $articulo["codigo"] ?>'>Editar</a></td>
                        <td><a href='eliminararticulos.php?eliminar={$articulo->getNombre()}'>Eliminar</a></td>


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