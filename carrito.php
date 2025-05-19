<?php
session_start();



include("db.php");
include 'clases.php';
include 'gestionarsocios.php';
/////


?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:74%;margin-top:10px;">
        <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4>

        <?php
        
$stmt = $pdo->prepare("SELECT * FROM articulos");
$stmt->execute();

$cliente = $_SESSION['id'];
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
include 'db2.php';
$sql = "SELECT * FROM carrito";
$result = $pdo->query($sql);
$row = $result->fetch_assoc();
    echo "<table>
        <tr>
       
            <td>id</td>
           <td>codigo</td>
            <td>Nombre</td>
            <td>Imagen</td>
            <td>Precio</td>
            <td>Descuento</td>
            <td>Cantidad</td>
            <td>Total</td>
            <td>Vaciar</td>
        </tr>";
    
    $total_carrito = 0;
    foreach ($_SESSION['carrito'] as $index => $item) {
        $preciototal=$item['precio'] - ($item['precio'] *($item['descuento'] /100));
        $subtotal =  $preciototal * $item['cantidad'];
        $total_carrito += $subtotal;

       
        echo "<tr>";
        echo "<td>" . $item['id'] . "</td>";
        echo "<td>" . htmlspecialchars($item["codigo"]) . "</td>";
        echo "<td>" . htmlspecialchars($item["nombre"]) . "</td>";
        echo "<td><img src='" . $item["imagen"] . "' width='60px' height='60px'></td>";
        echo "<td id='precio'>" . htmlspecialchars($item["precio"]) . " €</td>";
        echo "<td id='descuento'>" . htmlspecialchars($item["descuento"]) . " %</td>";
        $carrito_id=$item['id'] ;
        // Format quantity to 2 digits
        $item['cantidad'] = str_pad($item['cantidad'], 2, '0', STR_PAD_LEFT);

       
        echo "<td>
                <form method='POST' action='update_carrito.php'>
                    <button class='btn btn-danger' type='submit' name='action' value='decrease'>-</button>
                    <span>" . htmlspecialchars($item['cantidad']) . "</span>
                    <button class='btn btn-success' type='submit' name='action' value='increase'>+</button>
                    <input type='hidden' name='product_id' value='" . $index . "'>
                </form>
              </td>";

        echo "<td id='total'>" . htmlspecialchars($subtotal) . " €</td>";
        echo "<td>
                <form method='POST' action='eliminar-carrito.php' style='display: inline;'>
                    <input type='hidden' name='index' value='" . $index . "'>
                      <input type='hidden' name='id' value='" .  $carrito_id . "'>
                    <button class='btn btn-danger' type='submit' name='eliminar'>Eliminar</button>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<h4>Total: " . htmlspecialchars($total_carrito) . " €</h4>";


    ?>

    <form method='POST' action='pagos.php'>
        <input type='hidden' name='total' value='<?php echo htmlspecialchars($total_carrito); ?>'>
        <button name='pagar' class='btn btn-primary' type='submit'>Pagar</button>
    </form>

<?php
 
        } else {

            echo "<div class='alerta'>
            <h5>El carrito está vacío¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
        ?>

    </div>
    <?php


    ?>
    <div class="hijo3" style="width:14%">
    <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom elemento"><a href="usuario.php">Monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="editardatosuser.php">Editar datos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="cerrar.php">Cerrar session</a></li>
        </ul>

    </div>
</div>
<?php


$json = file_get_contents('php://input');
$datos = json_decode($json, true);
print_r($datos);
if (is_array($datos)) {
    include 'db.php';
    global $fecha_nueva;
    global $status;
    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id'];

    $sql = $pdo->prepare("INSERT INTO compra (id_transaccion,fecha,status,email,id_cliente,total) VALUE(?,?,?,?,?,?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
    $id = $pdo->lastInsertId();

 
    if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
        // Variables de la transacción (tomadas de la API de pago o sistema de pago)
        $id_cliente = $_SESSION['id']; // Asumimos que el id de usuario está en la sesión
       
        $estado_compra = 'completado'; // Estado de la compra después del pago
    
        // Insertamos los productos en la tabla pedidos
        foreach ($_SESSION['carrito'] as $producto) {
            $codigo_producto = $producto['codigo']; // El código del producto
            $cantidad = $producto['cantidad']; // La cantidad comprada
            $hora_pago = date('Y-m-d H:i:s'); // Fecha y hora del pago (actual)
            $precio=$producto['precio'];
            $imagen=$producto['imagen'];
            // Preparar la consulta SQL para insertar los datos en la tabla 'pedidos'
            $sql = "INSERT INTO pedidos (user_id, codigo,imagen,precio, cantidad,total, fecha_pago, estado_pago) 
                    VALUES (:id_usuario, :codigo_producto,:imagen,:precio, :cantidad,:total, :hora_pago, :estado_compra)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_cliente);
            $stmt->bindParam(':codigo_producto', $codigo_producto);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':total', $total_carrito);
            $stmt->bindParam(':hora_pago', $hora_pago);
            $stmt->bindParam(':estado_compra', $estado_compra);
            
            // Ejecutar la consulta para insertar el pedido
            $stmt->execute();
        }  
        // Vaciar el carrito después de guardar los datos
        unset($_SESSION['carrito']);////eliminar carrito de la base de datos
         include 'db2.php';
        $sql = "DELETE FROM carrito WHERE id = ?";
    
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bind_param("i", $carrito_id);
            if ($stmt->execute()) {
                echo "Producto eliminado correctamente.";
            } else {
                echo "Error al eliminar el producto: " . $stmt->error;
            }
            $stmt->close();
        }

        echo "<div class='alerta'>
            <h5>!Carrito vaciado después del pago¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
    } else {

        echo "<div class='alerta'>
            <h5>!No hay productos en el carrito¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
    }
}

?>



<?php include("footer.php"); ?>