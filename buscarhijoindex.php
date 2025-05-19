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
$name2=$_GET['name2'];
$name1=$_GET['name1'];
$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriahijo = '$name2'");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $conexion->prepare("SELECT * FROM articulos WHERE categoriahijo = '$name2' AND categoriapadre='$name1' LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();


?>
<?php include("headerindex.php"); ?>
<div class=" d-flex flex-row" style="width:100%;height:70vh;">
    <div class="hijo1 " style="width: 12%;">
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
<?php 
// Assuming $stmt is your prepared statement and executed
if ($stmt->rowCount() > 0) { 
    while ($fila = $stmt->fetch()) { ?>
        <div class="card m-1" style="width: 30%;">
            <img src="<?php echo $fila["imagen"] ?>" class="card-img-top img2" alt="" height="150px" width="80px">
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
            <a href="#" class="btn btn-primary" onclick="alerta()">Comprar</a>
        </div>
    <?php } 
} else {
    echo "<p>No hay artículos disponibles.</p>";
}
?>

      
        <script>
        function alerta() {
            alert("¡inicia session para comprar articulos!");
        }
    </script>
</div>
</div> 
    <div class="hijo3 " style="width:24%">
        <?php include("loginform.php")  ?>

        

    </div>
</div>
<?php
echo '<div class="d-flex justify-content-center m-3">';
if ($pagina > 1) {
    // Mantener los parámetros name1 y name2 en la URL
    echo '<a class="mx-1" href="?pagina=' . ($pagina - 1) . '&name1=' . urlencode($name1) . '&name2=' . urlencode($name2) . '"><img class="mb-1" id="im" src="back.png" alt="" height="12px"></a>';
}

for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == $pagina) {
        echo "<span style='color:blue;'>$i</span>";
    } else {
        // Mantener los parámetros name1 y name2 en la URL
        echo "<a class='mx-2' href=\"?pagina=$i&name1=" . urlencode($name1) . "&name2=" . urlencode($name2) . "\">$i</a>";
    }
}

if ($pagina < $total_paginas) {
    // Mantener los parámetros name1 y name2 en la URL
    echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '&name1=' . urlencode($name1) . '&name2=' . urlencode($name2) . '"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px"></a>';
}
echo '</div>';

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
