
<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    // Verifica si el producto está en el carrito
    if (isset($_SESSION['carrito'][$product_id])) {
        // Acción de disminuir la cantidad
        if ($action == 'decrease' && $_SESSION['carrito'][$product_id]['cantidad'] > 0) {
            $_SESSION['carrito'][$product_id]['cantidad'] -= 1;

           
        }
        // Acción de aumentar la cantidad
        if ($action == 'increase') {
            $_SESSION['carrito'][$product_id]['cantidad'] += 1;
        }
        } 
          
}

// Redirige de nuevo al carrito
header("Location: carrito.php");
exit;