<?php
session_start();
include("headerindex.php");
?>


<div class=" d-flex flex-row" style="width:100%;height:auto;">
    <div class="hijo1 "style="width:14%;">
        <?php include("sideindex.php") ?>
    </div>
    <div class="  d-flex flex-column " style="width:1200px;font-size: 16px; ">

       <div style="width:50%;margin:auto;padding:10px;">
       <form method="POST" action="" class="">
                <label class="m-0 p-0">Dni:</label>
                <input class="form-control " id="dni" name="dni" maxlength="9" required>
                <label>Nombre:</label>
                <input class="form-control " id="nombre" name="nombre" maxlength="20" required>
                <label>Direccion:</label>
                <input class="form-control " id="direccion" name="direccion" maxlength="20" required>
                <label>localidad:</label>
                <input class="form-control " id="localidad" name="localidad" maxlength="20" required>
                <label>Provincia:</label>
                <input class="form-control" id="provincia" name="provincia" maxlength="20" required>
                <label>Teléfono:</label>
                <input class="form-control" id="telefono" name="telefono" maxlength="9" required>
                <label>Correo electrónico:</label>
                <input class="form-control " id="email" name="email" type="email" maxlength="20" required>
                <label>Contraseña:</label>
                <input class="form-control " id="contrasena" name="contrasena" maxlength="8" required><br>
                <input type="" name="id" hidden>
                <input type="" name="activo" hidden>
                <input class="btn btn-primary" type="submit" value="Registrar" name="envier">
            </form>

    </div>
    </div>
    <div class="hijo3" style="width:14%" >
        <ul class="list-group pt-4">
        <li class="list-group-item border-0 border-bottom"><a href="index.php">iniciar session</a></li>
           
        </ul>

    </div>
</div>


    <?php
    // Incluir el archivo de conexión y las clases necesarias
  
    include 'db.php';
    include 'clases.php';
    include 'gestionarsocios.php';
    // Establecer conexión
    $conexion = $pdo;
    $gestor = new GestorSocios($conexion);

    // Procesar el formulario si se ha enviado
    if (isset($_POST['envier'])) {
        $dni=strtoupper($_POST['dni']);
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $localidad=$_POST['localidad'];
        $provincia=$_POST['provincia'];
        $telefono = $_POST['telefono'];
        $email= $_POST['email'];
        $contraseña=$_POST['contrasena'];
        $contrasena= password_hash($contraseña, PASSWORD_DEFAULT);
      
        if (!validarDNI($dni)) {
           
             echo "<div class='alerta'>
            <h5>!El DNI no tiene un formato válido¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                                   }else if(comprobarDNI($dni)){
             
                echo "<div class='alerta'>
            <h5>!el dni " .$dni. " esta registrado, inicia session para entrar¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
               // echo "<a href='login.php'>inicia session</a>";
                                  }else if(validarEmail($email)){
        
            echo "<div class='alerta'>
            <h5>!el email ". $email ." esta registrado, inicia session para entrar¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
           // echo "<a href='login.php'>inicia session</a>";
                                 } else {
        $socio = new Socio($dni,$nombre, $direccion, $localidad, $provincia,$telefono,$email,$contrasena);
        $resultado = $gestor->insertar($socio);
        echo "<p>$resultado</p>";
       
            echo '<a href="login.php">Volver al pagina de iniciar session</a>';
        
        
    } }
    ?>
<?php
//////validar el formato dni
function validarDNI($dni) {
    $pattern = '/^\d{8}[A-Z]$/';
if (preg_match($pattern, $dni)) {
        return true;
    } else {
        return false;
    }
}
////comprobar dni si existe
function comprobarDNI($dni) {
        include 'db.php';
        $sql = "SELECT * FROM usuarios WHERE dni = :dni";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
        $stmt->execute();
         // Verifica si el DNI existe
        if ($stmt->rowCount() > 0) {
            return true;  // El DNI existe en la base de datos
        } else {
            return false;  // El DNI no existe
        }
    }
    /////compbrobar si existe el email
     function validarEmail($email){
        include 'db.php';
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        // Comprobar si se encuentra el correo electrónico
        if ($stmt->rowCount() > 0) {
            return true;//email existe en base de datos
        } else {
           return false;
        }


     }
?><?php include("footer.php")  ?>