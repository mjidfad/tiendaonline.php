<?php include("header.php");
    $host = 'sql312.infinityfree.com';  // Database host
    $dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
    $username = 'if0_38397091';  // Database username
    $password = 'aeouSECyCHNsSn';
    
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<?php
////function agregar padre
function addCategory($name)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categoria_padre WHERE  name = ?");
    $stmt->execute([$name]);
    if ($stmt->rowCount() > 0) {
        
        echo "<div class='alerta'>
            <h5>!La categoria_padre ya existe para esta categoría¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        return false;
    } else {
        $stmt = $pdo->prepare("INSERT INTO categoria_padre (name) VALUES (?) ");
        $stmt->execute([$name]);
    }
}
//function agregar hijos
function addSubcategory($categoryId, $name)
{
    global $pdo;
    // Verificar si el hijo ya existe para esa categoría
    $stmt = $pdo->prepare("SELECT * FROM categoria_hijos WHERE category_id = ? AND name = ?");
    $stmt->execute([$categoryId, $name]);

    if ($stmt->rowCount() > 0) {
        
        echo "<div class='alerta'>
            <h5>!La categoria_hijo ya existe para esta categoría¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        return false;
    } else {

        // Insertar la subcategoría
        $stmt = $pdo->prepare("INSERT INTO categoria_hijos (category_id, name) VALUES (?, ?) ");
        $stmt->execute([$categoryId, $name]);
    }
}
?>
<div class="padre " style="width:100%;height:80vh;">
    <div class="hijo1 " style="width:14%;" >
        <?php include("side.php") ?>
    </div>
    <div class="hijo2  "style="font-size: 16px;width:1000px;">
        <div style="width:50%;margin:auto;padding:10px;">
            <form method="post" action="">
                <label for="">Agregar categoria padre :</label><br>
                <input class="form-control " type="text" name="categoryName" placeholder="Nombre de la categoría padre" required><br>
                <input class="btn btn-primary" type="submit" name="addCategory" value="Agregar"><br>
            </form><br>
            <form method="post" action="">
                <label for="">Categorias padre :</label>
                <select class="form-control " name="categoryId">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categoria_padre");
                    $subcategories = $stmt->fetchAll();

                    if ($subcategories) {
                        foreach ($subcategories as $row) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                    ?>
                </select><br>
                <label for="">Agregar categoria hijo :</label>
                <input class="form-control " type="text" name="subcategoryName" placeholder="Nombre de la subcategoría" required><br>
                <input class="btn btn-primary" type="submit" name="addSubcategory" value="Agregar">
            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['addCategory'])) {
        addCategory($_POST['categoryName']);
        echo "<script type='text/javascript'>window.location.href = 'categorias.php';</script>";
    }

    if (isset($_POST['addSubcategory'])) {
        addSubcategory($_POST['categoryId'], $_POST['subcategoryName']);
        echo "<script type='text/javascript'>window.location.href = 'categorias.php';</script>";
    }
    ?>
    <div class="hijo3" style="width:16%;">
        <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="editarcategorias.php">gestion categorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
<?php include("footer.php"); ?>