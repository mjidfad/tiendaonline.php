<?php include("headerindex.php");
session_start();
include 'clases.php';
include 'gestionararticulos.php';
include 'db2.php';


// Definir cuántos usuarios por página
$usuarios_por_pagina = 4;

// Obtener el número de página actual, si no se pasa ningún parámetro, se asume la página 1
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcular el desplazamiento (offset) para la consulta SQL
$inicio = ($pagina - 1) * $usuarios_por_pagina;

// Consulta SQL para obtener usuarios, ordenados por nombre y con paginación
$sql = "SELECT * FROM articulos ORDER BY nombre ASC LIMIT $inicio, $usuarios_por_pagina";
$resultado = $pdo->query($sql);

$sql_total = "SELECT COUNT(*) as total FROM articulos";
$resultado_total = $pdo->query($sql_total);
$total_usuarios = $resultado_total->fetch_assoc()['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_usuarios / $usuarios_por_pagina);

?>

<div class="padre  d-flex" style="height: 70vh;width:100%;">
    <div class="hijo1" style="width:12%">
        <?php include("sideindex.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:66%;">
        <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="index.php">Ordenar default</a></li>
                <li><a class="dropdown-item" href="ordenarindexza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexbarato.php">ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarindexcaro.php">ordenar por precio alto</a></li>
            </ul>
        </div>

        <div class="mt-4 d-flex flex-row justify-content-around">
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <div class="card m-1" style="width: 30%;">
                        <img src="<?php echo $fila["imagen"] ?>" class="card-img-top img2" alt="" height="150px" width="80px">

                        <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                        <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                        <div class="d-flex flex-row justify-content-between">
                            <p><?php echo $fila["precio"] ?> €</p>
                            <?php if ($fila["descuento"] > 0) { ?>
                                <p style="color: red; font-size: 18px;"><?php echo $fila["descuento"] ?> % descuento</p>
                            <?php } else { ?>
                                <p style="display:none"><?php echo $fila["descuento"] ?> % descuento</p>
                            <?php } ?>

                        </div>
                        <a href="#" class="btn btn-primary" onclick="alerta()">Comprar</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card m-1" style="width: 30%;height:150px;">
                    <p class="card-title">No se encontraron resultados.</p>
                </div>
            <?php endif; ?>
        </div>
        <script>
            function alerta() {
                alert("¡inicia session para comprar articulos!");
            }
        </script>
    </div>
    <div class="hijo3 " style="width:24%;">
        <?php include("loginform.php")  ?>



    </div>
</div>
<?php

// Obtener el número total de usuarios para calcular el número total de páginas

echo '<div class="d-flex justify-content-center p-3">';
if ($pagina > 1) {
    //echo '<a href="?pagina=1">Primera</a>';
    echo '<a class="mx-1" href="?pagina=' . ($pagina - 1) . '"><img class="mb-1" id="im" src="back.png" alt="" height="12px">Anterior</a>';
}
for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == $pagina) {
        echo "<span style='color:blue;'>$i</span>";
    } else {
        echo "<a class='mx-2' href=\"?pagina=$i\">$i</a>";
    }
}
if ($pagina < $total_paginas) {
    echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px">Seguiente</a>';
    //echo '<a href="?pagina=' . $total_paginas . '">Última</a>';
}
echo '</div>';



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
        } else {
            echo "<div class='alerta'>
    <h5>¡dni o contraseña incorrecto!</h5>
    <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
    <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';
}</script>";
        }
    }else {
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
<?php include("footer.php") ?>