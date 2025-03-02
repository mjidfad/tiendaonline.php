<?php
session_start();
include 'db.php';
include 'clases.php';
include 'gestionararticulos.php';



?>


<?php include("header.php"); ?>

<div class="padre " style="height: 80vh;">
    <div class="hijo1 ">
        <?php include("side.php") ?>
    </div>
    <div class="hijo2   ">
       
<?php
    $conexion = $pdo;
    $gestor = new Gestorarticulos($conexion);

   // Procesar el formulario si se ha enviado
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cadena = $_POST['cadena'];
    $socios = $gestor->buscarA($cadena);
   if (count($socios) > 0) {

?>

        <div class="container mt-4">
            <table class="table table-borderless">
                <tr>
                    <th>codigo</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>categoriapadre</th>
                    <th>categoriahijo</th>
                    <th>Precio</th>
                    <th>imagen</th>
                    <th>editar articulo</th>
                    <th>eleminar articulo</th>

                </tr>
                <?php    foreach ($socios as $articulo) {?>
                    <tr>
                        <td><?php echo htmlspecialchars($articulo->getCodigo()); ?></td>
                        <td><?php echo htmlspecialchars($articulo->getNombre()); ?></td>
                        <td><?php echo htmlspecialchars($articulo->getDescripcion()); ?></td>
                        <td><?php echo htmlspecialchars($articulo->getCategoriapadre()); ?></td>
                        <td><?php echo htmlspecialchars($articulo->getCategoriahijo()); ?></td>
                        <td><?php echo htmlspecialchars($articulo->getPrecio()); ?></td>
                        <td> <img src="<?php echo htmlspecialchars($articulo->getImagen()); ?>" width="80" height="80" srcset=""> </td>
                        <td><a href='editararticulos.php?codigo=<?php echo $articulo->getCodigo() ?>'>Editar</a></td>
                        <td><a href='eliminararticulos.php?eliminar={$articulo->getNombre()}'>Eliminar</a></td>


                    </tr>
                    <?php } ?>

                </table>
        </div>
        <?php }}

?>
    </div>
    <div class="hijo3">
        <ul class="list-group pt-4">
        <li class="list-group-item border-0 border-bottom"><a href="editor.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="añadirarticulos.php">añadir articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="categorias.php">gestion gategorias</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
            



<?php include("footer.php"); ?>