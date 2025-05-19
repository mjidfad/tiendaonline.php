<?php
session_start();
$id = $_SESSION['id'];

if (!isset($_SESSION['rol'])) {
    // Si no hay sesión, redirigir al login
    header("Location: index.php");
    exit();
}
include 'db2.php';


include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
    <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:75%;"><?php 
// Consulta SQL para obtener los datos de la tabla 'carritos'
$sql = "SELECT * FROM pedidos WHERE user_id ='$id' ";
$result = $pdo->query($sql);


if ($result->num_rows > 0) {
    // Mostrar la tabla
    echo "<table >
            <tr>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Fecha de Pago</th>
                <th>Estado de Pago</th>
            </tr>";

    // Recorrer los resultados y mostrar cada fila
    while($row = $result->fetch_assoc()) {
        echo "<tr>
               <td>" . $row["user_id"] . "</td>
                <td>" . $row["codigo"] . "</td>
                <td><img src='" . $row["imagen"] . "' width='60px' height='60px'></td>
                 <td>" . $row["precio"] . "</td>
                <td>" . $row["cantidad"] . "</td>
                <td>" . $row["total"] . "</td>
                <td>" . $row["fecha_pago"] . "</td>
                <td>" . $row["estado_pago"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    
    echo "<div class='alerta'>
            <h5>!No se encontraron resultados.¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
}
?>
 </div>
   

   <div class="hijo3" style="width:14%" >
   <ul class="list-group pt-4">
            <li class="list-group-item border-0 border-bottom elemento"><a href="usuario.php">Monstrar articulos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="editardatosuser.php">Editar datos</a></li>
            <li class="list-group-item border-0 border-bottom elemento"><a href="cerrar.php">Cerrar session</a></li>
        </ul>

   </div>
</div>
<?php include 'footer.php'; ?>
