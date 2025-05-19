<?php

include("headerindex.php");
?>

<div style="position:relative;">
<div class="padre d-flex flex-row" style="width:100%;height:80vh;">
    <div class="hijo1 " style="width:14%;">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2  d-flex flex-column " style="width:1200px;font-size: 16px; ">

        <div style="width:50%;margin:auto;padding:10px;">

            <form id="form-verificar" method="post" action="">
                <label for="dni">DNI:</label>
                <input class="form-control " type="text" id="dni" maxlength="9" name="dni" required>
                <br>
                <label for="email">Correo Electrónico:</label>
                <input class="form-control " type="email" id="email" name="email" required>
                <br>
                <button class="btn btn-primary" name="buscar" type="submit">Verificar Usuario</button><br>

            </form>
        </div>
    </div>
    <div class="hijo3" style="width:14%;">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="index.php">Iniciar session</a></li>

        </ul>

    </div>
</div>
    <?php
include 'db2.php';

    if (isset($_POST['buscar'])) {
        $dni = $_POST['dni'];
        $email = $_POST['email'];
        $query = "SELECT * FROM usuarios WHERE dni = '$dni' AND email = '$email'";
        $resultado = $pdo->query($query);

        if ($resultado->num_rows > 0) {
    ?>
   <div style="width:50%;margin:auto;padding:10px; position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);">
            <h3>Usuario con DNI <?php echo $dni ?> verificado. Ahora puedes cambiar tu contraseña</h3>
            <form id='cambiar-contraseña' method='post' action=''>
                <input  type="hidden" name="dni" maxlength="9" value="<?php echo $dni ?>">
                <input type="hidden" name="email" value="<?php echo $email ?>">
                <label for='nueva-contraseña'>Nueva Contraseña:</label>
                <input class="form-control " type='password' id='nueva-contraseña' name='nueva-contraseña' required maxlength="6">
                <br>
                <label for='confirmar-contraseña'>Confirmar Contraseña:</label>
                <input class="form-control " type='password' id='confirmar-contraseña' name='confirmar-contraseña' required maxlength="6">
                <br>
                <button class="btn btn-primary" id="butt" name='cambiar' type='submit'>Cambiar Contraseña</button>
            </form>
</div>

</div>



            <script>
                const verificar = document.getElementById("form-verificar");
                const butt = document.getElementById("butt");
                butt.addEventListener("click", ok());

                function ok() {
                    verificar.style = "display:none";
                }
            </script>
    <?php
        } else {
            echo "<div class='alerta'>
            <h5>¡Usuario no encontrado. Verifique sus datos!</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
    }

    if (isset($_POST['cambiar'])) {
        $dni = $_POST['dni'];
        $nueva_contraseña = $_POST['nueva-contraseña'];
        $confirmar_contraseña = $_POST['confirmar-contraseña'];
        $nueva_contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        if ($nueva_contraseña == $confirmar_contraseña) {
            $query2 = "UPDATE usuarios SET contrasena = '$nueva_contraseña_hash' WHERE dni = '$dni'";
            $result2 = $pdo->query($query2);
            if ($result2) {
                echo "<div class='alerta'>
                <h5>¡contraseña cambiada, inicia session para entrar ala tienda!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                
            } else {
                echo "<div class='alerta'>
                <h5>¡contraseña no cambiada!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            }
        } else {
            echo "<div class='alerta'>
                <h5>¡Las contraseñas no coinciden.!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
    }
    ?>

<?php include("footer.php")  ?>