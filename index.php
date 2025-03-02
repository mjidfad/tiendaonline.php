<?php
session_start();
include("db.php");
include 'clases.php';
include 'gestionarsocios.php';

$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';
global$pdo;
// Crear la conexión
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



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
<div class="padre  d-flex" style="height: 80vh;">
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
            <?php
            while ($fila = $stmt->fetch()) {
            ?>
                <div class="card m-1" style="width: 30%;height:auto;">
                    <img style="object-fit: cover;" src="<?php echo $fila["imagen"] ?>" class="card-img-top" alt="" height="150px" width="80px">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $fila["nombre"] ?></h5>
                        <p class="card-text"><?php echo $fila["descripcion"] ?></p>
                        <p><?php echo $fila["precio"] ?></p>
                        <a href="#" class="btn btn-primary" onclick="alerta()">Comprar</a>
                    </div>
                    <script>
                        function alerta() {
                            alert("¡inicia session para comprar articulos!");
                        }
                    </script>
                </div>
            <?php }


            ?>
        </div>
    </div>
    <div class="hijo3 " style="width:24%;">
        <?php include("loginform.php")  ?>



    </div>
</div>
<?php
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

<?php




if (isset($_POST['login'])) {
    $host = 'sql312.infinityfree.com';  // Database host
    $dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
    $username = 'if0_38397091';  // Database username
    $password = 'aeouSECyCHNsSn';
  

$pdo=new mysqli($host, $username, $password, $dbname);

$usuario = $_POST['dni'];
$contrasena = $_POST['contrasena'];
$hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

$query = "SELECT * FROM usuarios WHERE dni = ?";
$stmt = $pdo->prepare($query);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();
 $data = $result->fetch_assoc();
 
// $pass=mysqli_fetch_array($result,MYSQLI_ASSOC);
// $hash=$pass['contrasena'];
if ($result->num_rows == 1) {
    if (password_verify($contrasena,  $hashed_password)) {
       
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
            echo "<h3> dni o contraseña no correcto  </h3>";
        }
    }else{
        echo "la cuenta esta desactivada,activa tu cuenta para iniciar session";
    }
    }
} else {
    echo "<h3> dni o contraseña no correcto</h3>";
}
}        
?>
<?php include("footer.php");
