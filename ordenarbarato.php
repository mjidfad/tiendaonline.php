<?php include("header.php");

session_start();

include 'clases.php';
include 'gestionararticulos.php';
include 'db2.php';
if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}
$usuarios_por_pagina = 4;

// Obtener el número de página actual, si no se pasa ningún parámetro, se asume la página 1
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcular el desplazamiento (offset) para la consulta SQL
$inicio = ($pagina - 1) * $usuarios_por_pagina;

// Consulta SQL para obtener usuarios, ordenados por nombre y con paginación
$sql = "SELECT * FROM articulos ORDER BY precio ASC LIMIT $inicio, $usuarios_por_pagina";
$resultado = $pdo->query($sql);

$sql_total = "SELECT COUNT(*) as total FROM articulos";
$resultado_total = $pdo->query($sql_total);
$total_usuarios = $resultado_total->fetch_assoc()['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_usuarios / $usuarios_por_pagina);

?>


<div class="padre " style="height: 80vh;">
    <div class="hijo1 ">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2   ">
        <div style="display: flex;width:100%;height:auto;justify-content: space-between;margin: 5px;">
            <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4><a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar Articulos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="editor.php">Ordenar default</a></li>
                <li><a class="dropdown-item" href="ordenaraz.php">Ordenar Ascendente </a></li>
                <li><a class="dropdown-item" href="ordenarza.php">Ordenar Descendente </a></li>
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
                    <th>Descuento</th>
                    <th>imagen</th>
                    <th>editar</th>
                    <th>eleminar</th>

                </tr>
                <?php if ($resultado->num_rows > 0) {
    while ($articulo = $resultado->fetch_assoc()) {
                    
                    ?>
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
                        <td> <img src="<?php echo htmlspecialchars($articulo['imagen']); ?>" width="80" height="70" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $articulo["codigo"] ?>'><img name='borrar' src='../imagnes2/check2.png' height='25' ></a></td>
                        <td><a onclick=' return confirmar()' href='eliminararticulos.php?eliminar=<?php echo $articulo["codigo"] ?>'><img height='25' src='../imagnes2/close2.png'></a></td>
                    </tr>
                <?php }} ?>
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

// Obtener el número total de usuarios para calcular el número total de páginas

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

    ?>
        <?php include("footer.php") ?>