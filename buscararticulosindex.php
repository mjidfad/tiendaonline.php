<?php
include 'db2.php';
if (isset($_POST['buscar'])) {
    global $cadena;
    $cadena= $_POST['cadena'];;
    # code...
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

include 'headerindex.php';
?>
<div class="padre  d-flex" style="height: 70vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideindex.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:66%;">
        <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="ordenarindexaz.php">Ordenar Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenarindexza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexbarato.php">Ordenar Precio bajo </a></li>
                <li><a class="dropdown-item" href="ordenarindexcaro.php">Ordenar Precio Alto </a></li>
            </ul>
        </div>
        <div class="mt-4 d-flex flex-row justify-content-around">
<!-- Resultados de búsqueda -->
<?php if ($result->num_rows > 0): ?>
    <?php while ($fila = $result->fetch_assoc()): ?>
                <div class="card m-1" style="width: 30%;">
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
                    <a href="#" class="btn btn-primary" onclick="alerta()" >Comprar</a>
                </div>
     <?php endwhile; ?>
<?php else: ?>
   
           <p class="card-title">No se encontraron resultados.</p>
          
<?php endif; ?>
        </div>
        <script>
        function alerta() {
            alert("¡inicia session para comprar articulos!");
        }
    </script>
</div>
    <div class="hijo3" style="width:24%">
        <?php include 'loginform.php'?>
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

<?php
// Cerrar la conexión
$pdo->close();
?>
 <?php
if (isset($_POST['login'])) {
    include 'db2.php';

$usuario = $_POST['dni'];
$contrasena = $_POST['contrasena'];

$query = "SELECT * FROM usuarios WHERE dni = ?";
$stmt = $pdo->prepare($query);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
       $data = $result->fetch_assoc();
    if (password_verify($contrasena,  $data['contrasena'])) {
     
        $_SESSION['id'] = $data['id'];
        $_SESSION['dni'] = $data['dni'];
        $_SESSION['rol'] = $data['rol'];
        $_SESSION['nombre'] = $data['nombre'];
        $_SESSION['direccion'] = $data['direccion'];
        $_SESSION['localidad'] = $data['localidad'];
        $_SESSION['provincia'] = $data['provincia'];
        $_SESSION['telefono'] = $data['telefono'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['contrasena'] = $data['contrasena'];

        $_SESSION['estado'] = $data['estado'];

        if ($_SESSION['estado'] == 'activo') {
            if ($_SESSION['rol'] == 'administrador') {
                // header('Location: editor.php');
                echo "<script type='text/javascript'>window.location.href = 'admin/admin.php';</script>";
            } elseif ($_SESSION['rol'] == 'editor') {
                echo "<script type='text/javascript'>window.location.href = 'editor.php';</script>";
                echo "editor page";
            } elseif ($_SESSION['rol'] == 'usuario') {
                echo "<script type='text/javascript'>window.location.href = 'usuario.php';</script>";
            } else {
                echo "<div class='alerta'>
        <h5>dni o contraseña no correcto!</h5>
        <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
        <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';
    }</script>";
            }
        } else {
            echo "<div class='alerta'>
    <h5>¡la cuenta esta desactivada,activa tu cuenta para iniciar session!</h5>
    <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
    <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';
}</script>";
        }
    }else {
    echo "<div class='alerta'>
<h5>¡dni o contraseña incorrecto!</h5>
<button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
<script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';
}</script>";
}
} else {
    echo "<div class='alerta'>
        <h5>¡El DNI ingresado no está registrado!</h5>
        <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button>
    </div>
    <script>
        function cerrarAlerta() {
            document.querySelector('.alerta').style.display='none';
        }
    </script>";
}
}
?>
<?php include("footer.php");
