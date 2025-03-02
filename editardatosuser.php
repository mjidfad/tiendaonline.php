<?php


session_start();


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:74%;">

        <h4 style="">Datos usuario , <?php echo $_SESSION['nombre'] ?> </h4>

        <?php

        echo "<table style='width:100%;margin:2px;'>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Localidad</th>
            <th>Provincia</th>
             <th>Teléfono</th>
            <th>Correo electrónico</th>
            <th>Contraseña</th>
            <th>modificar</th>
            <th>eliminar</th>
        </tr>"
        ?>
        <tr>
            <td><?php echo $_SESSION['dni'] ?></td>
            <td><?php echo $_SESSION['nombre']; ?></td>
            <td><?php echo $_SESSION['direccion']; ?></td>
            <td><?php echo $_SESSION['localidad']; ?></td>
            <td><?php echo $_SESSION['provincia']; ?></td>
            <td><?php echo $_SESSION['telefono']; ?></td>
            <td><?php echo $_SESSION['email']; ?></td>
            <td><?php echo $_SESSION['contrasena']; ?></td>
            <?php echo "<td><a style='color:green;' href='editarclienteuser.php?dni=" . $_SESSION['dni'] . "'>editar</a></td>"  ?>
            <?php echo "<td><a style='color:red;' href='eliminarclienteuser.php?dni=" . $_SESSION['dni'] . "' onclick=' return confirmar()'   >eliminar</a></td>"; ?>

            </table>

    </div>
    <div class="hijo3" style="width:14%">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>


</div>

<script>
    function confirmar() {
        // Confirmar la eliminación
        var confirmacion = confirm("¿Estás seguro de que deseas eliminar este usuario?+");
        if (confirmacion) {
            // Si el usuario confirma, redirigir a la página que elimina el usuario
            //window.location.href="eliminarcliente.php";
            return true;

        } else {
            return false;
            alert("usuaro no eliminado");

        }
    }
</script>
<?php include("footer.php"); ?>