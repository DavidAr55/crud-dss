<?php
session_start();

// Verificar si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir la conexión a la base de datos
    include 'Connection.php';
    $connObj = new Connection();
    $conn = $connObj->getConnection();

    // Recibir el ID del procesador a eliminar
    $procesadorID = $_POST['id'];

    // Eliminar el procesador de la base de datos
    $stmt = $conn->prepare("DELETE FROM InventarioProcesadores WHERE ProcesadorID=?");
    $stmt->bind_param("i", $procesadorID);
    
    if ($stmt->execute()) {
        // Redireccionar a la página de dashboard
        $_SESSION['executeSuccess'] = "Registro eliminado exitosamente de la base de datos";
        echo "<script> location.href = 'dashboard.php' </script>";
    } else {
        // Manejar el error
        $_SESSION['executeError'] = "Error al eliminar el procesador: " . $conn->error;
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
