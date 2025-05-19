<?php
session_start();
include("db.php");
include 'clases.php';
include 'gestionarsocios.php';




//////////
$REGS = 4;
$pagina = 1;
$inicio = 0;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $REGS;
}
$stmt = $pdo->prepare("SELECT * FROM articulos");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $pdo->prepare("SELECT * FROM articulos LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();


?>
<?php include("headerindex.php")  ?>
<div class="padre  d-flex" style="height: 70vh;width:100%;">
    <div class="hijo1" style="width:12%">
        <?php include("sideindex.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:66%;height:auto;">
        <div class="dropdown  me-3 w-100 ">
            <a class="nav-link dropdown-toggle float-end text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Ordenar articulos
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="ordenarindexaz.php">Ordenar Ascendente</a></li>
                <li><a class="dropdown-item" href="ordenarindexza.php">Ordenar Descendente </a></li>
                <li><a class="dropdown-item" href="ordenarindexbarato.php">ordenar por precio bajo</a></li>
                <li><a class="dropdown-item" href="ordenarindexcaro.php">ordenar por precio alto</a></li>
            </ul>
        </div>

        <div class="mt-4 d-flex flex-row justify-content-around">
            <?php while ($fila = $stmt->fetch()) { ?>
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
            <?php } ?>

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
    echo '<a class="mx-1" href="?pagina=' . ($pagina + 1) . '"><img class="mb-1" id="im" src="icon1.png" alt="" height="12px">Siguiente</a>';
    //echo '<a href="?pagina=' . $total_paginas . '">Última</a>';
}
echo '</div>';

?>
<?php
if (isset($_POST['login'])) {
    include 'db2.php'; // Asegúrate de que 'db2.php' contiene la conexión a la base de datos

    $usuario = $_POST['dni'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT * FROM usuarios WHERE dni = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        
        if (password_verify($contrasena, $data['contrasena'])) {
            // Guardamos la información del usuario en la sesión
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

            // Verificar si el usuario tiene un carrito activo
            $id_cliente = $data['id'];
            $carrito_query = "SELECT * FROM carrito WHERE id_cliente = ? AND estado_pago = 'pendiente'";
            $carrito_stmt = $pdo->prepare($carrito_query);
            $carrito_stmt->bind_param('i', $id_cliente);
            $carrito_stmt->execute();
            $carrito_result = $carrito_stmt->get_result();

            // Si el carrito está presente y es "pendiente"
            if ($carrito_result->num_rows > 0) {
                $carrito_data = [];

                while ($carrito = $carrito_result->fetch_assoc()) {
                    // Obtener los artículos del carrito
                    $articulos_query = "SELECT * FROM carrito WHERE id = ?";
                    $articulos_stmt = $pdo->prepare($articulos_query);
                    $articulos_stmt->bind_param('i', $carrito['id']);
                    $articulos_stmt->execute();
                    $articulos_result = $articulos_stmt->get_result();

                    while ($articulo = $articulos_result->fetch_assoc()) {
                        $carrito_data[] = [
                            'id' => $articulo['id'],
                            'codigo' => $articulo['codigo'],
                            'nombre' => $articulo['nombre'],
                            'precio' => $articulo['precio'],
                            'imagen' => $articulo['imagen'],
                            'descuento' => $articulo['descuento'],
                            'cantidad' => $articulo['cantidad'],
                            'total' => $articulo['total'],
                            'id_cliente' => $articulo['id_cliente'],
                            'estado_pago' => $carrito['estado_pago']
                        ];
                    }
                }

                // Guardar los artículos del carrito en la sesión
                $_SESSION['carrito'] = $carrito_data;
                $_SESSION['carrito_id']=$articulo['id'];
            }

            // Redirección según el rol del usuario
            if ($_SESSION['estado'] == 'activo') {
                if ($_SESSION['rol'] == 'administrador') {
                    echo "<script type='text/javascript'>window.location.href = 'admin/admin.php';</script>";
                } elseif ($_SESSION['rol'] == 'editor') {
                    echo "<script type='text/javascript'>window.location.href = 'editor.php';</script>";
                } elseif ($_SESSION['rol'] == 'usuario') {
                    echo "<script type='text/javascript'>window.location.href = 'usuario.php';</script>";
                } else {
                    echo "<div class='alerta'>
                        <h5>DNI o contraseña incorrecto!</h5>
                        <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button>
                    </div>
                    <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none'; }</script>";
                }
            } else {
                echo "<div class='alerta'>
                    <h5>¡La cuenta está desactivada! Activa tu cuenta para iniciar sesión.</h5>
                    <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button>
                </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none'; }</script>";
            }
        } else {
            echo "<div class='alerta'>
                <h5>¡DNI o contraseña incorrectos!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button>
            </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none'; }</script>";
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
<?php include("footer.php");
