<?php include("header.php");

include 'db.php';
include 'clases.php';
include 'gestionararticulos.php';
// Establecer conexión
$conexion = $pdo;
$gestor = new Gestorarticulos($conexion);
?>

<div class="padre " style="height: 80vh;">
    <div class="hijo1 " style="width: 12%;">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2   " style="width:75%;">
        <div style="display: flex;width:100%;height:auto;justify-content: space-between;margin: 5px;">
            <?php
            if (isset($_GET['eliminar'])) {
                $nombreEliminar = $_GET['eliminar'];
                $resultado = $gestor->eliminarA($nombreEliminar);
                echo '<script type="text/javascript">
        setTimeout(function() {
            window.location.href = "editor.php"; // URL to redirect to
        }, 1000); // 2000 milliseconds = 2 seconds
      </script>';

     
        
               
                } else {
               
                    echo "<div class='alerta'>
            <h5>!No se encontraron registros¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
                }
                echo $resultado;
            
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
<a href="editor.php">Volver al menú principal</a>
<?php include("footer.php");
