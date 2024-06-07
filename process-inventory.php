<?php
session_start();
include 'Connection.php';
include 'config.php';

// Crear una nueva instancia de la clase Connection
$connObj = new Connection();
$conn = $connObj->getConnection();

// Obtener datos del formulario
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$velocidad = $_POST['velocidad'];
$nucleos = $_POST['nucleos'];
$hilos = $_POST['hilos'];
$fechaAdquisicion = $_POST['fechaAdquisicion'];
$estado = $_POST['estado'];

// Manejo de la imagen
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["imagen"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validar el archivo de imagen
$check = getimagesize($_FILES["imagen"]["tmp_name"]);
if ($check === false) {
    die("El archivo no es una imagen.");
}

// Verificar si el archivo ya existe
if (file_exists($target_file)) {
    die("Lo sentimos, el archivo ya existe.");
}

// Verificar el tamaño del archivo (limite de 5MB)
if ($_FILES["imagen"]["size"] > 5000000) {
    die("Lo sentimos, tu archivo es demasiado grande.");
}

// Permitir solo ciertos formatos de archivo
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    die("Lo sentimos, solo se permiten archivos JPG, JPEG, PNG y GIF.");
}

// Intentar subir el archivo
if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
    die("Lo sentimos, hubo un error al subir tu archivo.");
}

// Preparar y ejecutar la consulta SQL
$sql = $conn->prepare("INSERT INTO InventarioProcesadores (Marca, Modelo, VelocidadGHz, Nucleos, Hilos, FechaAdquisicion, Estado, Imagen) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("ssdiisss", $marca, $modelo, $velocidad, $nucleos, $hilos, $fechaAdquisicion, $estado, $target_file);

if ($sql->execute()) {
    $_SESSION['executeSuccess'] = "Nuevo registro creado exitosamente";
    echo "<script> location.href = 'dashboard.php' </script>";
} else {
    $_SESSION['executeError'] = "Error: " . $sql->error;
    echo "<script> location.href = 'dashboard.php' </script>";
}

// Cerrar la conexión
$connObj->closeConnection();
?>
