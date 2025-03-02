<?php
    $host = 'sql312.infinityfree.com';  // Database host
    $dbname = 'if0_38397091_abdelmjidfaddoul6';  // Database name
    $username = 'if0_38397091';  // Database username
    $password = 'aeouSECyCHNsSn';

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Connected successfully!";
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>