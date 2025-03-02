<?php
include 'db.php';
include 'clases.php';
include 'gestionarsocios.php';
$conexion = $pdo;
$gestor = new GestorSocios($conexion);
$socios = $gestor->mostrar();



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Socios</title>
</head>
<body>
    <table border="1" >
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Localidad</th>
            <th>Provincia</th>
             <th>Teléfono</th>
            <th>Correo electrónico</th>
            <th>Contraseña</th>
        </tr>
        <?php foreach ($socios as $socio) {?>
            <tr>
            <td><?php echo htmlspecialchars($socio->getDni()); ?></td>
                <td><?php echo htmlspecialchars($socio->getNombre()); ?></td>
                 <td><?php echo htmlspecialchars($socio->getDireccion()); ?></td>
                 <td><?php echo htmlspecialchars($socio->getLocalidad()); ?></td>
                 <td><?php echo htmlspecialchars($socio->getProvincia()); ?></td>
                 <td><?php echo htmlspecialchars($socio->getTelefono()); ?></td>
                <td><?php echo htmlspecialchars($socio->getEmail()); ?></td>
                <td><?php echo htmlspecialchars($socio->getContrasena()); ?></td>
                
               
            </tr>
        <?php } ?>
    </table>
    <a href="index.php">Volver al Menú principal</a>
</body>
</html>

