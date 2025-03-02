<?php

include("headerindex.php");
?>

<div style="position:relative;">
<div class="padre d-flex flex-row" style="width:100%;height:80vh;">
    <div class="hijo1 " style="width:12%;">
        <!--?php include("side.php") ?-->
    </div>
    <div class="hijo2  d-flex flex-column " style="width:80%;font-size: 16px; ">

        <div style="width:50%;margin:auto;padding:10px;">

            <form id="form-verificar" method="post" action="">
                <label for="dni">DNI:</label>
                <input class="form-control " type="text" id="dni" name="dni" required>
                <br>
                <label for="email">Correo Electrónico:</label>
                <input class="form-control " type="email" id="email" name="email" required>
                <input class="form-check-input" type="text" name="cambio" value="inactivo" hidden >
                <br>
                <button class="btn btn-primary" name="buscar" type="submit">Verificar Usuario</button><br>

            </form>
        </div>
    </div>
    <div class="hijo3" style="width:14%;">
        <ul class="list-group pt-4">
            <a class="list-group-item border-0 border-bottom" href="index.php">Iniciar session</a>

        </ul>

    </div>
</div>
    <?php
      $host = 'sql312.infinityfree.com';  // Database host
      $dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
      $username = 'if0_38397091';  // Database username
      $password = 'aeouSECyCHNsSn';
    $conexion = new mysqli($host, $usuario, $clave, $basedatos);

    if (isset($_POST['buscar'])) {
        $dni = $_POST['dni'];
        $email = $_POST['email'];
        $estado=$_POST['cambio'];
        $query = "SELECT * FROM usuarios WHERE dni = '$dni' AND email = '$email' AND estado ='$estado'";
        $resultado = $conexion->query($query);

        if ($resultado->num_rows > 0) {
    ?>
   <div style="width:50%;margin:auto;padding:10px; position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);">
            <h3>Usuario con DNI <?php echo $dni ?> verificado. Ahora puedes activar tu cuenta</h3>
            <form id='cambiar-contraseña' method='post' action=''>
                <input  type="hidden" name="dni" value="<?php echo $dni ?>">
                <input type="hidden" name="email" value="<?php echo $email ?>">
                <label for=''>activar cuenta:</label>
                <input class="form-check-input" type="radio" name="radio" value="activo" >
                <button class="btn btn-primary" id="butt" name='cambiar' type='submit'>activar cuenta</button>
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
        
        $estado=$_POST['radio'];
       
        
            $query2 = "UPDATE usuarios SET estado = '$estado' WHERE dni = '$dni'";
            $result2 = $conexion->query($query2);
            if ($result2) {
                echo "<div class='alerta'>
                <h5>¡cuenta activada, inicia session para entrar ala tienda!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                
            } else {
                echo "<div class='alerta'>
                <h5>¡cuenta no activada!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            }
        } 
    
    ?>

<?php include("footer.php")  ?>