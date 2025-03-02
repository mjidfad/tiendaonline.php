<?php
session_start();

$host = 'sql312.infinityfree.com';  // Database host
$dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
$username = 'if0_38397091';  // Database username
$password = 'aeouSECyCHNsSn';s

// Crear conexión
$conn = new mysqli($host, $username, $password, $database);
// Verificar si se ha recibido el DNI a través del GET
if (isset($_GET['dni'])) {
    // Recoger el DNI de la URL
   $dni =$_GET['dni'];
   $sql = "SELECT estado FROM usuarios WHERE dni = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dni);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Si el usuario está activo (estado = 1), se actualiza a inactivo (estado = 0)
    if ($row['estado'] == "activo") {
        $sql_update = "UPDATE usuarios SET estado = 'inactivo' WHERE dni = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $dni);
        
        if ($stmt_update->execute()) {
         
            echo "<div class='alerta'>
            <h5>!Usuario con DNI $dni ha sido actualizado a inactivo¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
            echo "<script type='text/javascript'>window.location.href = 'index.php';</script>";
        } else {
            
            echo "<div class='alerta'>
            <h5>!Error al actualizar el estado.¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
        }
    } else {
       
        echo "<div class='alerta'>
            <h5>!El usuario ya está inactivo.¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
    }
} else {
    
    echo "<div class='alerta'>
            <h5>!No se encontró un usuario con ese DNI¡</h5>
            <button class='cerrar-btn' onclick='cerrarAlerta()'>Cerrar</button> </div>
            <script> function cerrarAlerta() { document.querySelector('.alerta').style.display='none';}</script>";
}
//} 
}