<?php
session_start();

if (isset($_POST['eliminar'])) {
    $index = $_POST['index'];
   
     $id_producto = $_POST['id'];
    include 'db2.php';
    // Consulta para eliminar el producto por su ID
    $sql = "DELETE FROM carrito WHERE id = ?";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $id_producto);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Producto eliminado correctamente.";
           // header("Location: carrito.php"); // Redirigir de nuevo al carrito
        } else {
            echo "Error al eliminar el producto: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $pdo->error;
    }
    if (isset($_SESSION['carrito'][$index])) {
        unset($_SESSION['carrito'][$index]);

        
       

       // $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

header('Location: carrito.php');
exit;
?>