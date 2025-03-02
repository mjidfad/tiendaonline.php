<?php
session_start();
include 'db.php';
include 'clases.php';
include 'gestionararticulos.php';
$conexion = $pdo;


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
<?php include("header.php"); ?>

<div class="padre " style="height: 80vh;">
    <div class="hijo1 " style="width: 12%;">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2   "style="width: 74%;">
        <div style="display: flex;width:100%;height:auto;justify-content: space-between;margin: 5px;">
            <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4><a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar Articulos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="ordenaraz.php">Ordenar Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenarza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarbarato.php">Ordenar Precio bajo </a></li>
                <li><a class="dropdown-item" href="ordenarcaro.php">Ordenar Precio Alto </a></li>
            </ul>
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
                    <th>editar </th>
                    <th>eleminar </th>

                </tr>
                <?php while ($articulo = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($articulo['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriapadre']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['categoriahijo']); ?></td>
                        <td><?php echo htmlspecialchars($articulo['precio']); ?></td>
                        <td> <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" width="80" height="70" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $articulo["codigo"] ?>'>Editar</a></td>
                        <td><a onclick=' return confirmar()' href='eliminararticulos.php?eliminar=<?php echo $articulo["codigo"] ?>'>Eliminar</a></td>


                    </tr>
                <?php } ?>

                </table>
                <script>
                            function confirmar() {
                                // Confirmar la eliminación
                                var confirmacion = confirm("Estás seguro de que deseas eliminar este usuario?");
                                if (confirmacion) {
                                    alert("Articulo eliminado  corectamente ");
                                    return true;

                                } else {
                                    return false;
                                    alert("Articulo  no eliminado");

                                }
                            }
                        </script>
        </div>
    </div>
    <div class="hijo3" style="width: 14%;">
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