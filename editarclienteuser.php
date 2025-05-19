<?php
session_start();
include("db.php");
include 'clases.php';
include 'gestionarsocios.php';
$conexion = $pdo;
$gestor = new GestorSocios($conexion);


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: auto;width:100%;">
    <div class="hijo1 " style="width:12%">
    <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:74%;">
        <div class="dropdown  me-3 w-100 "></div>

        <h4>Editar Cliente : <?php echo   $_SESSION['nombre']; ?></h4>
        <form method="POST" style='width:90%;margin:auto;'>
            <label for="dni">DNI:</label><br>
            <input class="form-control " type="text" id="dni" readonly  name="dni" value="<?php echo htmlspecialchars($_SESSION['dni']); ?>" required>

            <label for="nombre">Nombre:</label>
            <input class="form-control " type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_SESSION['nombre']) ?>" required>
            <label for="dni">Direccion:</label>
            <input class="form-control " type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($_SESSION['direccion']); ?>" required>
            <label for="localidad">Localidad:</label><br>
            <input class="form-control " type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($_SESSION['localidad']); ?>" required>
            <label for="provincia">Direccion:</label><br>
            <input class="form-control " type="text" id="provincia" name="provincia" value="<?php echo htmlspecialchars($_SESSION['provincia']); ?>" required>
            <label for="telefono">Telefono:</label><br>
            <input class="form-control" type="text" id="telefono" maxlength="9" name="telefono" value="<?php echo htmlspecialchars($_SESSION['telefono']); ?>" required>
            <label for="dni">Email:</label><br>
            <input class="form-control " type="text" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
            <label for="dni">Contrasena:</label><br>
            <input class="form-control " type="text" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($_SESSION['contrasena']); ?>" required><br>


            <input class="btn btn-primary" type="submit" value="Actualizar" name="actualizar">
        </form>
    </div>
    <div class="hijo3" style="width:14%">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>

</div>

<?php
if (isset($_SESSION['dni'])) {


    // Si el formulario es enviado
    if (isset($_POST['actualizar'])) {
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $localidad = $_POST['localidad'];
        $provincia = $_POST['provincia'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];
        $contrasena2 = password_hash($contraseña, PASSWORD_BCRYPT);
        // Actualizar los datos del cliente

        $sql = "UPDATE usuarios SET  nombre='$nombre' , direccion='$direccion',localidad='$localidad',provincia='$provincia',telefono='$telefono', email='$email'
        ,contrasena='$contrasena2' WHERE dni='$dni'";

        // Ejecutar la consulta

        if ($pdo->query($sql)) {

           
            $_SESSION['nombre'] = $nombre;
            $_SESSION['direccion'] = $direccion;
            $_SESSION['localidad'] = $localidad;
            $_SESSION['provincia'] = $provincia;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['email'] = $email;
            $_SESSION['contrasena'] = $contrasena;
          
            echo "<div class='alerta'>
            <h5>!Los datos han sido actualizados correctamente¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
          
            echo "<script type='text/javascript'>window.location.href = 'editardatosuser.php';</script>";
        } else {
           
            echo "<div class='alerta'>
            <h5>!Error al actualizar los datos¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
    }
}


?>
<?php include("footer.php"); ?>