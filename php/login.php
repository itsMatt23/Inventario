<?php
session_start();
require_once("conexion.php");
//$cedulaAdmin = "0000000000";
//$contrasenaAdmin = "12345";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST["cedula"];
    $contrasena = $_POST["contrasena"];
    $_SESSION["cedula"] = $cedula;

    $consulta = "SELECT * FROM usuarios WHERE cedula = '$cedula' AND contrasena = '$contrasena'";
    $resultado = $conexion->query($consulta);

    //if($cedula == $cedulaAdmin && $contrasena == $contrasenaAdmin){
      //  header("Location: ../administrador.php");
        //$_SESSION["admin"] = $cedulaAdmin;
    //}

    if ($resultado->num_rows == 1) {
        $_SESSION["cedula"] = $cedula;
            header("Location: ../menu.php"); 
        exit();
    } else {
        echo 
        '<script>
        alert("Credendiales incorrectas!!");
        location.href = "../index.php"
        </script>
        ';
    }
}
?>
