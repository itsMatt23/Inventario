<?php
$servidor = "localhost";
$bd = "gestioninv";
$usuario = "root";
$clave = "mateo2324";

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>