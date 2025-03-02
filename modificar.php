<!DOCTYPE html>
<html lang="es"></html><head>
    <meta charset="UTF-8">
    <title>Modificar Socio</title>
</head>
<body></body>
    <?php
    // Incluir el archivo de conexión y las clases necesarias
    include 'db.php';
    include 'clases.php';
    include 'gestionarsocios.php';
    $conexion = $pdo;
    $gestor = new GestorSocios($conexion);
    // Solicitud POST
       // Solicitud POST
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            // Modificar datos del socio
            $dni=$_POST['dni'];
            $nombre = $_POST['nombre'];
            $direccion = $_POST['direccion'];
            $localidad=$_POST['localidad'];
            $provincia=$_POST['provincia'];
             $telefono = $_POST['telefono'];
            $email= $_POST['email'];
            $contrasena=$_POST['contrasena'];
          
            
            $socio = new Socio($dni,$nombre, $direccion, $localidad, $provincia,$telefono,$email,$contrasena);
            $resultado = $gestor->modificar($socio);
          if($resultado){
           
            echo "<div class='alerta'>
            <h5>!datos actualizados¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            echo '<a href="index.php">Volver al menú principal</a>';
          }else{
            
            echo "<div class='alerta'>
            <h5>!error en actualizar¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
          }
            
        } else {
            // Buscar el socio y mostrar formulario de modificación
            $nombre = $_POST['nombre'];
            $socios = $gestor->buscar($nombre);
            if (count($socios) == 1) {
                $socio = $socios[0];
                ?>

     <h2>Modificar datos del socio</h2>
                <form method="POST" action="">
                <label for="dni">Dni:</label>
                <input id="dni" name="dni" maxlength="30" value="<?php echo htmlspecialchars($socio->getDni()); ?>" disabled><br><br>
                    <label for="nombre">Nombre:</label>
                    <input id="nombre" name="nombre" maxlength="30" value="<?php echo htmlspecialchars($socio->getNombre()); ?>" required><br><br>
                     <label for="direccion">Dirección:</label>
                    <input id="direccion" name="direccion" maxlength="100" value="<?php echo htmlspecialchars($socio->getDireccion()); ?>" required><br><br>
                    <label for="localidad">Localidad:</label>
                    <input id="localidad" name="localidad" maxlength="30" value="<?php echo htmlspecialchars($socio->getLocalidad()); ?>" required><br><br>
                    <label for="provincia">Provincia:</label>
                    <input id="provincia" name="provincia" maxlength="30" value="<?php echo htmlspecialchars($socio->getProvincia()); ?>" required><br><br>
                     <label for="telefono">Teléfono:</label>
                    <input id="telefono" name="telefono" maxlength="15" value="<?php echo htmlspecialchars($socio->getTelefono()); ?>" required><br><br>
                    <label for="email">Correo electrónico:</label>
                    <input id="email" name="email" type="email" maxlength="50" value="<?php echo htmlspecialchars($socio->getEmail()); ?>" required><br><br>
                    <label for="contrasena">Contrasena:</label>
                    <input id="contrasena" name="contrasena" maxlength="30" value="<?php echo htmlspecialchars($socio->getContrasena()); ?>" required><br><br>
                   
                   
                    <input name="update" type="submit" value="Modificar">
                </form>

               
                <?php
            } else {
               
                echo "<div class='alerta'>
            <h5>!No se encontró el socio con el nombre proporcionado.¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                echo '<a href="modificar.php">Volver a intentar</a>';
            }
        }
    } else {
        // Mostrar el formulario para pedir el nombre del socio a modificar
        ?>

        <h2>Buscar socio para modificar</h2>
        <form method="POST" action="">
            <label>Nombre del socio a modificar:</label>
            <input id="nombre" name="nombre" maxlength="30" required><br><br>
            <input type="submit" value="Buscar" name="buscar" ><br><br>
        </form>
        <a href="index.php">Volver al menú principal</a>
        <?php
    }
    ?>

</body>
</html>