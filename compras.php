<?php
session_start();
$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';      // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}
include("headeruser.php")  ?>
<div class="padre  d-flex" style="height: 80vh;width:100%;">
    <div class="hijo1 " style="width:12%">
    <?php include("sideuser.php") ?>
    </div>
    <div class="hijo2   p-1 d-flex flex-column" style="width:75%;"><?php 
// Consulta SQL para obtener los datos de la tabla 'carritos'
$sql = "SELECT user_id, producto_id, cantidad, precio, fecha_pago, estado_pago FROM carritos";
$result = $conn->query($sql);

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    // Mostrar la tabla
    echo "<table >
            <tr>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha de Pago</th>
                <th>Estado de Pago</th>
            </tr>";

    // Recorrer los resultados y mostrar cada fila
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["user_id"] . "</td>
                <td>" . $row["producto_id"] . "</td>
                <td>" . $row["cantidad"] . "</td>
                <td>" . $row["precio"] . "</td>
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
       <li class="list-group-item border-0 border-bottom"><a href="usuario.php">monstrar articulos</a></li>
           <li class="list-group-item border-0 border-bottom"><a href="editardatosuser.php">editar datos</a></li>
           <li class="list-group-item border-0 border-bottom"><a href="cerrar.php">cerrar session</a></li>
       </ul>

   </div>
</div>

