<?php
session_start();
include 'db2.php';
if (isset($_GET['cadena'])) {
    $cadena = $_GET['cadena'];
}
if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}
/////
$cliente=$_SESSION['id'];
 
if (isset($_POST['añadir'])) {
    include 'db.php';
 
    $cantidad = max(1, intval($_POST['cantidad'])); // Asegurar que la cantidad sea al menos 1
    $id_producto = $_POST['id_producto'];
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

include 'headeruser.php';
?>
<div class="padre  d-flex" style="height: 70vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:74%;">
        <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="ordenaruseraz.php">Ordenar Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenaruserza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenaruserbarato.php">Ordenar Precio bajo </a></li>
                <li><a class="dropdown-item" href="ordenarusercaro.php">Ordenar Precio Alto </a></li>
            </ul>
        </div>
        <?php if ($result->num_rows > 0): ?>
        <div class="mt-4 d-flex flex-row justify-content-around">
<!-- Resultados de búsqueda -->

    <?php while ($fila = $result->fetch_assoc()): ?>
                <div class="card m-1 w-25">
                     <img  src="<?php echo $fila["imagen"] ?>" class="card-img-top img2" alt=""  height="150px"  width="80px">
                
                    <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                    <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                    <div class="d-flex flex-row justify-content-between">
                 <p><?php echo $fila["precio"] ?> €</p>
                 <?php if ($fila["descuento"] > 0) { ?>
                    <p style="color: red; font-size: 18px;"><?php echo $fila["descuento"] ?> % descuento</p>
                <?php } else { ?>
                    <p style="display:none" ><?php echo $fila["descuento"] ?> % descuento</p>
                <?php } ?>
             
            </div>
            <form method='POST' class="d-flex flex-row">                     
                            <input type='hidden' name='id_producto' value='<?php echo $fila["id"] ?>'>
                            <input class="form-control" type='number' name='cantidad' value='1' min='1' style='width: 50px;' required>
                            <button class="btn btn-primary w-75" type='submit' name="añadir">Añadir</button>
                        </form>   
                </div>
     <?php endwhile; ?>
<?php else: ?>
   
           <p class="card-title">No se encontraron resultados.</p>
          
<?php endif; ?>
        </div>
       
</div>
  <?php



?>

<div class="hijo3" style="width:14%">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom elemento"><a href="usuario.php">Monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="editardatosuser.php">Editar datos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="cerrar.php">Cerrar session</a></li>
        </ul>

    </div>
</div>


<!-- Paginación -->
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
<?php

?>