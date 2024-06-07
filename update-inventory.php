<?php
session_start();

// Verificar si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir la conexión a la base de datos
    include 'Connection.php';
    $connObj = new Connection();
    $conn = $connObj->getConnection();

    // Recibir los datos del formulario
    $procesadorID = $_POST['procesadorID'];
    $marca = $_POST['inputMarca'];
    $modelo = $_POST['inputModelo'];
    $velocidad = $_POST['velocidad'];
    $nucleos = $_POST['nucleos'];
    $hilos = $_POST['hilos'];
    $estado = $_POST['estado'];

    // Actualizar los datos en la base de datos
    $stmt = $conn->prepare("UPDATE InventarioProcesadores SET Marca=?, Modelo=?, VelocidadGHz=?, Nucleos=?, Hilos=?, Estado=? WHERE ProcesadorID=?");
    $stmt->bind_param("ssdiisi", $marca, $modelo, $velocidad, $nucleos, $hilos, $estado, $procesadorID);
    
    if ($stmt->execute()) {
        // Redireccionar a la página de dashboard
        $_SESSION['executeSuccess'] = "Registro actualizado con exito";
        echo "<script> location.href = 'dashboard.php' </script>";
    } else {
        // Manejar el error
        $_SESSION['executeError'] = "Error al actualizar el procesador: " . $conn->error;
        echo "<script> location.href = 'dashboard.php' </script>";
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $connObj->closeConnection();
} else {
    // Si se intenta acceder directamente a este archivo sin enviar datos por POST, redireccionar al dashboard
    $_SESSION['executeError'] = "Error 500";
    echo "<script> location.href = 'dashboard.php' </script>";
}
?>
