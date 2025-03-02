<?php include("headerindex.php"); 

include 'clases.php';
include 'gestionararticulos.php';
$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';
$pdo = new mysqli($host, $username, $password, $dbname);


// Definir cuántos usuarios por página
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

<div class="padre  d-flex" style="height: 80vh;">
    <div class="hijo1 border" style="width:12%">
    <?php include("sideindex.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:66%;">
        <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="index.php">Ordenar default</a></li>
                <li><a class="dropdown-item" href="ordenarindexaz.php">Ordenar Ascendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexcaro.php">Ordenar Precio Alto </a></li>
            </ul>
        </div>

        <div class="mt-4 d-flex flex-row justify-content-around">
        <?php if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
                    
                    ?>
            <div class="card m-1" style="width: 30%;height:auto;">
                <img style="object-fit: cover;" src="<?php echo $fila["imagen"] ?>" class="card-img-top" alt=""  height="150px"  width="80px">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                    <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                    <p><?php echo $fila["precio"] ?></p>
                    <a href="#" class="btn btn-primary" onclick="alerta()" >Comprar</a>
                </div>
                <script>
        function alerta() {
            alert("¡inicia session para comprar articulos!");
        }
    </script>
            </div>
            <?php }} ?> 
        </div>
    </div>
    <div class="hijo3 " style="width:24%;">
        <?php include("loginform.php")  ?>

        

    </div>
</div>
<?php

// Obtener el número total de usuarios para calcular el número total de páginas

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
        <?php include("footer.php") ?>