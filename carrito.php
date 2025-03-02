<?php
session_start();
$cliente=$_SESSION['id'];

include("db.php");
include 'clases.php';
include 'gestionarsocios.php';
/////
$conexion = $pdo;
$gestor = new GestorSocios($conexion);
$REGS = 4;
$pagina = 1;
$inicio = 0;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
    $inicio = ($pagina - 1) * $REGS;
}
$stmt = $conexion->prepare("SELECT * FROM articulos");
$stmt->execute();
//contar los registros y las páginas con la división entera
$num_total_registros = $stmt->rowCount();
$total_paginas = ceil($num_total_registros / $REGS);
$stmt = $conexion->prepare("SELECT * FROM articulos LIMIT " . $inicio . " ," . $REGS);
$stmt->execute();
//////////
?>
<?php include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
        <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:75%;">
        <h4 style=""> hola <?php echo $_SESSION['nombre'] ?> </h4>

        <?php
        if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            echo "<table>
            <tr>
                <td>Codigo</td>
                <td>Nombre</td>
                <td>Precio</td>
                <td>Cantidad</td>
                <td>Total</td>
                <td>Vaciar</td>
            </tr>";
            $total_carrito = 0;
            foreach ($_SESSION['carrito'] as $index => $item) {
                $productId = $item['cantidad'];
                $subtotal = $item['precio'] * $item['cantidad'];
                $total_carrito = $subtotal + $total_carrito;
                echo "<tr>";
                echo "<td>{$item["codigo"]}</td>";
                echo "<td>{$item["nombre"]}</td>";
                echo "<td id='precio'>{$item["precio"]}</td>";
                //echo "<td>{$item["cantidad"]}</td>";
                if ($item['cantidad'] < 10) {
                    $item['cantidad'] = '0' . $item['cantidad'];
                }
                echo "<td>
                <form method='POST' action='update_carrito.php'>
                    <button class='btn btn-danger' type='submit' name='action' value='decrease'>-</button>
                    
                    <span>" . $item['cantidad'] . "</span>
                    <button class='btn btn-success' type='submit' name='action' value='increase'>+</button>
                    <input type='hidden' name='product_id' value='" . $index . "'>
                </form>

              </td>";
 
                echo "<td id='totel'>{$subtotal}</td>";
                echo "<td>
                <form method='POST' action='eliminar-carrito.php' style='display: inline;'>
                    <input type='hidden' name='index' value='{$index}'>
                    <button class='btn btn-danger' type='submit'>Eliminar</button>
                </form>
              </td>";
                echo "</tr>";
                 }
                echo "</table>";
                echo "<h4>Total: {$total_carrito}</h4>";
                 ?>
                <form method='POST' action='pagos.php' >
                <input type='hidden' name='total' value='<?php echo $total_carrito ?>'>
                <button name='pagar' class='btn btn-primary' type='submit'>pagar </button>
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
            <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="editardatosuser.php">editar datos</a></li>
            <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
        </ul>

    </div>
</div>
<?php


$json=file_get_contents('php://input');
$datos =json_decode($json,true);
print_r($datos);
if (is_array($datos)){
    global $fecha_nueva;
    global $status;
    $id_transaccion=$datos['detalles']['id'];
    $total=$datos['detalles']['purchase_units'][0]['amount']['value'];
    $status=$datos['detalles']['status'];
    $fecha=$datos['detalles']['update_time'];
    $fecha_nueva=date('Y-m-d H:i:s',strtotime($fecha));
    $email=$datos['detalles']['payer']['email_address'];
    $id_cliente=$datos['detalles']['payer']['payer_id'];

    $sql=$pdo->prepare("INSERT INTO compra (id_transaccion,fecha,status,email,id_cliente,total) VALUE(?,?,?,?,?,?)");
    $sql->execute([$id_transaccion,$fecha_nueva,$status,$email,$id_cliente,$total]);
     $id=$pdo->lastInsertId();

     // Si el carrito tiene productos, guardarlos
    // $id = $_SESSION['id'];
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $producto) {
        $producto_id = $producto['id'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        // Guardar en la base de datos
        $sql = "INSERT INTO carritos (user_id, producto_id, cantidad, precio, fecha_pago, estado_pago)
                VALUES ('$cliente', '$id', '$cantidad', '$precio', '$fecha_nueva', '$status')";

        if ($conn->query($sql) === TRUE) {
         
            echo "<div class='alerta'>
            <h5>!Producto guardado correctamente en la base de datos¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Vaciar el carrito después de guardar los datos
    unset($_SESSION['carrito']);
   
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