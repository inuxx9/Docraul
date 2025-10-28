<?php
// config/db.php

$host = 'localhost'; // O la IP de tu servidor de BD
$dbname = 'clinica'; // ¡CAMBIA ESTO!
$user = 'root'; // ¡CAMBIA ESTO!
$password = ''; // ¡CAMBIA ESTO!

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa"; // Solo para pruebas
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>