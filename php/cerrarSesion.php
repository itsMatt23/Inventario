<?php
session_start();
if (isset($_SESSION['cedula'])) {
    session_destroy();

    header("Location: ../index.php");
    exit();
}
?>