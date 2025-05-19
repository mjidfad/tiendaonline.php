<?php include("header.php");
session_start();
include 'clases.php';
include 'gestionararticulos.php';
$gestor = new Gestorarticulos($pdo);

?>
<div class=" d-flex flex-row" style="width:100%;height:auto;">
    <div class="hijo1 " style="width:14%;">
        <?php include("side.php") ?>
    </div>

    <div class=" d-flex flex-column " style="width:70%;font-size: 16px; ">
        <div style="width:50%;margin:auto;padding:10px;">

            <?php
            // Solicitud POST
            // Solicitud POST $servername = "localhost";
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "abdelmjidfaddoul6";
            $pdo = new mysqli($servername, $username, $password, $dbname);
            if (isset($_GET['codigo'])) {
                $codigo = $_GET['codigo'];
                $sql = "SELECT * FROM articulos WHERE codigo = '$codigo'";
                $resultado = mysqli_query($pdo, $sql);
                $cliente = mysqli_fetch_assoc($resultado);


                echo
                "<div class='m-3' style='width:100%'> <form action='' method='POST' enctype='multipart/form-data'>
    <label for='codigo'>Código:</label>
    <input  class='form-control' type='text' id='codigo' name='codigo' maxlength='6' required value=" . htmlspecialchars($cliente['codigo']) . " disabled >

    <label   for='nombre'>Nombre:</label>
    <input class='form-control' type='text' id='nombre' name='nombre' value=" . htmlspecialchars($cliente['nombre'])
                    . " required>

    <label  for='descripcion'>Descripción:</label><br>
    <textarea class='form-control' id='descripcion' name='descripcion' value='' required>" . htmlspecialchars($cliente['descripcion']) . "</textarea>";


                // Dirección del servidor de la base de datos
                include 'db.php';


                $stmt = $pdo->query("SELECT * FROM categoria_padre");
                $categories = $stmt->fetchAll(); ?>
                <label for='categoria'>Categoría padre : <?php echo htmlspecialchars($cliente['categoriapadre']) ?></label>;
                <select id='categoria' required class='form-control' name="categoriapadre">
                    <?php foreach ($categories as $category) {  ?>
                        <option name='' value='<?php echo htmlspecialchars($category['name'])  ?>'><?php echo htmlspecialchars($category['name'])  ?></option>
                    <?php }    ?>
                </select>
                <label for='categoria'>Categoría hijo : <?php echo htmlspecialchars($cliente['categoriahijo']) ?></label>;
                <?php
                $stmt = $pdo->query("SELECT * FROM categoria_hijos");
                $categories = $stmt->fetchAll(); ?>
                <select id='categoria' required class='form-control' name="categoriahijo">
                    <?php foreach ($categories as $category) {  ?>
                        <option name='' value='<?php echo htmlspecialchars($category['name'])  ?>'><?php echo htmlspecialchars($category['name'])  ?></option>
                <?php }
                } ?>
                </select>
                <?php echo
                " <label  for='precio'>Precio:</label>
    <input class='form-control' type='number' step='0.01' id='precio' name='precio'value=" . htmlspecialchars($cliente['precio']) . " required>
<label  for='precio'>Descuento:</label>
    <input class='form-control' type='number' step='0.01' id='precio' name='descuento'value=" . htmlspecialchars($cliente['descuento']) . " >
    <label  for='imagen'>Imagen:</label>
    <input class='form-control' type='file' id='imagen' name='imagen' value=" . htmlspecialchars($cliente['imagen']) . " required>
     <img src=" . htmlspecialchars($cliente['imagen']) . " height='120px' width='120px'><br><br>
    <button class='btn btn-primary' name='subir' value='Subir Producto'    >actualizar</button>
</form></div> ";


                ?>

        </div>
    </div>
    <div class="hijo3" style="width: 16%;">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="categorias.php">gestion gategorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
<?php
if (isset($_POST['subir'])) {
    //$codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $categoriapadre = $_POST['categoriapadre'];
    $categoriahijo = $_POST['categoriahijo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $descuento = $_POST['descuento'];

    $nombre_archivo = $_FILES['imagen']['name']; //Nombre del archivo que nos ha pasado el formulario
    $tamano = $_FILES['imagen']['size']; //Tamaño del archivo que nos ha pasado el formulario
    $type = $_FILES['imagen']['type'];
    $ruta = "../imagnesarticulos/$nombre_archivo"; //Ruta del nombre del archivo, previamente hemos creado la carpeta
    $temp = $_FILES['imagen']['tmp_name'];
    $tamanio_maximo = 10 * 1024 * 1024;




    if ($type === "image/jpeg" || $type === "image/png") {
        if ($tamano <= $tamanio_maximo) {

            if (move_uploaded_file($temp, $ruta)) {

                $articulo = new Articulos($codigo, $nombre, $descripcion, $categoriapadre, $categoriahijo,  $precio, $descuento, $ruta);
                $resultado = $gestor->modificarA($articulo);
                echo "<p>$resultado</p>";
            }
        } else {


            echo "<div class='alerta'>
            <h5>!El archivo es demasiado grande. El tamaño máximo permitido es 3 MB¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            echo '<a href="editor.php">Volver al pagina de editor</a>';
        }
    } else {

        echo "<div class='alerta'>
            <h5>!Solo se permiten archivos PNG y  JPEG¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        echo '<a href="editor.php">Volver al pagina de editor</a>';
    }
}




?>




</body>

</html>