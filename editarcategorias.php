<?php 
session_start();
if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}
include("header.php");
// Obtener categorías padre
include 'db.php';

?>
<div class=" d-flex flex-row" style="width:100%;height:auto;">
    <div class="hijo1 ">
        <?php include_once("side.php"); ?>
    </div>
    <div class="  d-flex flex-column " style="font-size: 16px;width:75%;;padding-top:10px;">
        <div style="width:50%;margin:auto;padding:10px">
            <h5 id="h5" class="">Categorias :</h5>
            <ul>
                <?php
                                function displayCategories()
                {
                    include 'db.php';
                    // Obtener categorías
                    $stmt = $pdo->query("SELECT * FROM categoria_padre");
                    $categories = $stmt->fetchAll();

                    echo "<ul id='ul1'>";

                    foreach ($categories as $category) {
                        echo "<li style='text-align:left;display:flex;flex-direccion:row;'>";
                        echo  htmlspecialchars($category['name']);
                        echo " <a href='?editCategory=" . $category['id'] . "'><img <img onclick='edit()' name='borrar' src='../imagnes2/check2.png' height='20' ></a>  ";
                        echo " <a href='?deleteCategory=" . $category['id'] . "'onclick=' return confirmar()'    ><img height='20' src='../imagnes2/close2.png'></a>";

                        // Obtener subcategorías de esta categoría
                        $stmt = $pdo->prepare("SELECT * FROM categoria_hijos WHERE category_id = ?");
                        $stmt->execute([$category['id']]);
                        $subcategories = $stmt->fetchAll();

                        if ($subcategories) {
                            echo "<ul style='margin-top: 10px; padding-left: 20px;text-align:center;'>";
                            foreach ($subcategories as $subcategory) {
                                echo "<li style='border: 1px solid #ccc; margin: 5px; padding: 10px;display:flex;justify-content: space-between;'>";
                                echo  htmlspecialchars($subcategory['name']);
                                echo " <a href='?editSubcategory=" . $subcategory['id'] . "'><img onclick='edit()' name='borrar' src='../imagnes2/check2.png' height='20' ></a>  ";
                                echo " <a href='?deleteSubcategory=" . $subcategory['id'] . " 'onclick=' return confirmar()'    ><img height='20' src='../imagnes2/close2.png'></a>";
                                echo "</li>";
                            }
                            echo "</ul>";
                        }
                ?>
                        <script>
                            function confirmar() {
                                // Confirmar la eliminación
                                var confirmacion = confirm("Estás seguro de que deseas eliminar la categoria?");
                                if (confirmacion) {
                                    alert("categoria eliminada corectamente ");
                                   
                                  
                                    window.location.reload();

                                } else {
                                    return false;
                                    alert("usuaro no eliminado");

                                }
                            }
                            confirmar;
                        </script>
                    <?php

                        echo "</li>";
                    }

                    echo "</ul>";
                    ///////
                    ?>
            </ul>
            <?php

                    if (isset($_GET['deleteCategory'])) {
                        deleteCategory($_GET['deleteCategory']);
                    }

                    if (isset($_GET['deleteSubcategory'])) {
                        deleteSubcategory($_GET['deleteSubcategory']);
                    }

                    if (isset($_GET['editCategory'])) {
                        include 'db2.php';
                        $categoryId = $_GET['editCategory'];
                        $query = "SELECT * FROM categoria_padre WHERE id = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->bind_param("i", $categoryId);
                        $stmt->execute();
                        $categories = $stmt->get_result()->fetch_assoc();
            ?>
                <form action="" method="post">

                    <input type="hidden" name="category_id" value="<?php echo   $categories['id'] ?>">
                    <label for="">editar categoria padre</label>
                    <input type="text" name="category_name" value="<?php echo  $categories['name'] ?>">
                    <button name="guardar1" type="submit">Guardar Cambios</button>
                </form>
            <?php
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if (isset($_GET['editSubcategory'])) {
                        include 'db2.php';
                        $subcategoryId = $_GET['editSubcategory'];
                        $sql = "SELECT * FROM categoria_padre ";
                        $result = $pdo->query($sql);
                        $row = $result->fetch_assoc();

                        //////
                        $result = $pdo->query("SELECT * FROM categoria_hijos WHERE id = $subcategoryId ");
                        $row = $result->fetch_assoc();


            ?>
                <form action="" method="post">
                    <input type="hidden" name="category_id1" value="<?php echo   $row['id'] ?>">
                    <input type="hidden" name="category_id2" value="<?php echo   $row['category_id'] ?>">
                    <label for="">editar categoria hijo</label>
                    <input type="text" name="category_name2" value="<?php echo  $row['name'] ?>">


                    <button name="guardar2" type="submit">Guardar Cambios</button>
                </form>


        <?php

                    }
                }
                // Mostrar las categorías y subcategorías
                displayCategories();
                if (isset($_POST['guardar1'])) {

                    $id1 = $_POST['category_id'];
                    $nombre1 = $_POST['category_name'];
                    $query1 = "UPDATE categoria_padre SET name = '$nombre1' WHERE id = $id1";

                    $stmt = $pdo->prepare($query1);
                    $stmt->execute();
                }

                if (isset($_POST['guardar2'])) {
                    $id1 = $_POST['category_id1'];
                    $id2 = $_POST['category_id2'];
                    $nombre2 = $_POST['category_name2'];
                    $query2 = "UPDATE categoria_hijos SET name = '$nombre2' WHERE category_id ='$id2' AND id ='$id1'";

                    $stmt = $pdo->prepare($query2);
                    if ($stmt->execute()) {

                        echo "<script type='text/javascript'>window.location.href = 'editarcategorias.php';</script>";
                    }
                }

                //////
                function editCategory($categoryId, $newName)
                {
                    global $pdo;
                    $stmt = $pdo->prepare("UPDATE categoria_padre SET name = ? WHERE id = ?");
                    $stmt->execute([$newName, $categoryId]);
                }

                function editSubcategory($subcategoryId, $newName)
                {
                    global $pdo;
                    $stmt = $pdo->prepare("UPDATE categoria_hijos SET name = ? WHERE id = ?");
                    $stmt->execute([$newName, $subcategoryId]);
                }
                ///////
                function deleteCategory($categoryId)
                {
                    global $pdo;
                    // Eliminar primero las subcategorías
                    $stmt = $pdo->prepare("DELETE FROM categoria_hijos WHERE category_id = ?");
                    $stmt->execute([$categoryId]);

                    // Luego eliminar la categoría
                    $stmt = $pdo->prepare("DELETE FROM categoria_padre WHERE id = ?");
                    $stmt->execute([$categoryId]);
                }

                function deleteSubcategory($subcategoryId)
                {
                    global $pdo;
                    $stmt = $pdo->prepare("DELETE FROM categoria_hijos WHERE id = ?");
                    $stmt->execute([$subcategoryId]);
                }

        ?>



        </div>
    </div>
    <div class="hijo3"style="width: 16%;" >
        <ul class="list-group pt-4 ">
            <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="categorias.php">añadir gategorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>

        </ul>

    </div>
</div>

<style>
    #h5,
    #bmujeres,
    #bhombres {
        cursor: pointer;
    }

    .down {

        transform: rotate(90deg);
        /*transition: 0.5s ease-in-out;*/
    }

    li {
        list-style: none;
        margin: 3px;
    }

    a {
        text-decoration: none;
        color: black;

    }

    a:hover {
        color: black;


    }

    #ul,
    #ulmujeres,
    #ulhombres,
    #h6 {
        display: none;
    }
</style>
    <?php include("footer.php")  ?>