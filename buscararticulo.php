<?php
session_start();
include 'db2.php';
if (isset($_POST['buscar'])) {
    global $cadena;
    $cadena= $_POST['cadena'];;
    # code...
}
if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}

// Paginación
$limit = 4; // Número de resultados por página
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit;
global $cadena;
// Consulta SQL para obtener los artículos que coinciden con la cadena de búsqueda
$sql = "SELECT * FROM articulos WHERE nombre LIKE ? LIMIT ?, ?";
$stmt = $pdo->prepare($sql);
$searchTerm = "%" . $cadena . "%"; // Buscar cualquier artículo que contenga la cadena
$stmt->bind_param('sii', $searchTerm, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Obtener el total de resultados para paginación
$sqlTotal = "SELECT COUNT(*) FROM articulos WHERE nombre LIKE ?";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->bind_param('s', $searchTerm);
$stmtTotal->execute();
$stmtTotal->bind_result($totalResults);
$stmtTotal->fetch();
$totalPages = ceil($totalResults / $limit);

?>


<?php include("header.php"); ?>

<div class="padre " style="height: 80vh;">
    <div class="hijo1 ">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2   ">

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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($fila = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila["codigo"]); ?></td>
                        <td><?php echo htmlspecialchars( $fila["nombre"]) ?></td>
                        <td><?php echo htmlspecialchars($fila["descripcion"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["categoriapadre"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["categoriahijo"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["precio"]); ?> €</td>
                        <td><?php if ($fila["descuento"] > 0) { ?>
                    <p style="color: red; font-size: 18px;"><?php echo $fila["descuento"] ?> %</p>
                <?php } else { ?>
                    <p  ><?php echo $fila["descuento"] ?> % </p>
                <?php } ?></td>
                        <td> <img src="<?php echo htmlspecialchars($fila["imagen"]); ?>" width="80" height="80" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $fila["codigo"] ?>'><img name='borrar' src='../imagnes2/check2.png' height='25' ></a></td>
                        <td><a href='eliminararticulos.php?eliminar=<?php echo $fila["codigo"] ?>'><img height='25' src='../imagnes2/close2.png'></a></td>


                    </tr>
                     <?php endwhile; ?>
                     <?php else: ?>
   
<?php endif; ?>
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
<div class="d-flex justify-content-center">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li><a href="?cadena=<?php echo urlencode($cadena); ?>&page=<?php echo $page - 1; ?>">Anterior</a></li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <a href="?cadena=<?php echo urlencode($cadena); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
            <li><a href="?cadena=<?php echo urlencode($cadena); ?>&page=<?php echo $page + 1; ?>">Siguiente</a></li>
        <?php endif; ?>
    </ul>
</div>

<style>
    .pagination li.active a {
        color: blue; 
        
    }
</style>          



<?php include("footer.php"); ?>