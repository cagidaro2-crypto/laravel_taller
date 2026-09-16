<?php
require 'vendor/autoload.php';

$conn = new mysqli('127.0.0.1', 'root', '', 'alex_laravel');

if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
}

echo "=== ROLES ===" . PHP_EOL;
$result = $conn->query('SELECT * FROM roles');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo 'ID: ' . $row['id_rol'] . ' | Nombre: ' . $row['nombre_rol'] . PHP_EOL;
    }
} else {
    echo "Sin roles" . PHP_EOL;
}

echo PHP_EOL . "=== USUARIOS ===" . PHP_EOL;
$result = $conn->query('SELECT id_usuario, id_rol, nombre, correo FROM usuarios');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo 'ID: ' . $row['id_usuario'] . ' | Rol: ' . $row['id_rol'] . ' | Nombre: ' . $row['nombre'] . ' | Email: ' . $row['correo'] . PHP_EOL;
    }
} else {
    echo "Sin usuarios" . PHP_EOL;
}

$conn->close();
?>
