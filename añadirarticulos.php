<?php include("header.php"); 

$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);







?>
<div class=" d-flex flex-row" style="width:100%;height:auto;">
    <div class="hijo1" style="width: 14%;">
        <?php include("side.php") ?>
    </div>
    <div class="  d-flex flex-column " style="width:1000px;font-size: 16px; ">
       <div style="width:50%;margin:auto;padding:10px;">
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="codigo">Código:</label>
                <input class="form-control " type="text" id="codigo" name="codigo" maxlength="6" required>
                <label for="nombre">Nombre:</label>
                <input class="form-control " type="text" id="nombre" name="nombre" required>
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control " id="descripcion" name="descripcion" required></textarea>
                <label for="categoria">Categoría padre:</label>
               
                <label for="">Categorias padre :</label>
                <select class="form-control " name="categoriapadre" required>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categoria_padre");
                    $subcategories = $stmt->fetchAll();

                    if ($subcategories) {
                        foreach ($subcategories as $row) {
                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <label for="">Categorias hijo :</label>
                <select class="form-control " name="categoriahijo" required >
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categoria_hijos");
                    $subcategories = $stmt->fetchAll();

                    if ($subcategories) {
                        foreach ($subcategories as $row) {
                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select>


                 <label for="precio">Precio:</label>
                <input class="form-control " type="number" step="0.01" id="precio" name="precio"  required>

                <label for="precio">Descuento:</label>
                <input class="form-control " type="number" step="0.01" id="descuento" name="descuento"  required>

                <label for="imagen">Imagen:</label>
                <input class="form-control " type="file" id="imagen" name="imagen" required><br>
                <input class="btn btn-primary" type="submit" name="subir" value="Subir Producto">
            </form>
        </div>
    </div>
    <div class="hijo3" style="width:16%;">
        <ul class="list-group pt-4 ">
            <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="categorias.php">gestion gategorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>

        </ul>

    </div>
</div>
<?php


include 'clases.php';



if (isset($_POST['subir'])) {




    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $categoriapadre = $_POST['categoriapadre'];
    $categoriahijo = $_POST['categoriahijo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $descuento=$_POST['descuento'];
 
    $nombre_archivo = $_FILES['imagen']['name']; //Nombre del archivo que nos ha pasado el formulario
    $tamano = $_FILES['imagen']['size']; //Tamaño del archivo que nos ha pasado el formulario
    $type = $_FILES['imagen']['type'];
    $ruta = "imagnesarticulos/$nombre_archivo"; //Ruta del nombre del archivo, previamente hemos creado la carpeta
    $temp = $_FILES['imagen']['tmp_name'];
    $tamanio_maximo = 10 * 1024 * 1024;

     $stmt = $pdo->prepare("SELECT * FROM articulos WHERE  codigo = ?");
    $stmt->execute([$codigo]);
    if ($stmt->rowCount() > 0) {
        
        echo "<div class='alerta'>
                <h5>¡el articulo con codigo ". $codigo ." existe en la tabla de articulos!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        return false;
    } 
    if ($type === "image/jpeg" || $type === "image/png") {
        if ($tamano <= $tamanio_maximo) {
            if (move_uploaded_file($temp, $ruta)) {
                $sql = "INSERT INTO articulos (codigo, nombre, descripcion, categoriapadre, categoriahijo, precio, descuento, imagen) 
                VALUES (:codigo, :nombre, :descripcion, :categoriapadre, :categoriahijo, :precio, :descuento, :imagen)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':codigo' => $codigo,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':categoriapadre' => $categoriapadre,
            ':categoriahijo' => $categoriahijo,
            ':precio' => $precio,
            ':descuento' => $descuento,
            ':imagen' => $ruta
        ]);

      
        echo "<div class='alerta'>
                <h5>¡El articulo añadido perfectamente.!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
    }
               
               
            }
        } else {
            
            echo "<div class='alerta'>
                <h5>¡El archivo es demasiado grande. El tamaño máximo permitido es 3 MB.!</h5>
                <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
                <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            
        }
    } 


?>
<?php include("footer.php")  ?>