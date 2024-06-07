<?php
session_start();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    include 'Connection.php';

    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Realizar la consulta para obtener el usuario
    $connObj = new Connection();
    $conn = $connObj->getConnection();

    // Evitar inyección SQL utilizando consultas preparadas
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontró un usuario
    if ($result->num_rows == 1) {
        // Iniciar sesión y redirigir al usuario a la página principal
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $email;
        header("location: index.php");
    } else {
        // Usuario o contraseña incorrectos, redirigir al formulario de inicio de sesión con un mensaje de error
        $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
        header("location: login.php");
    }

    // Cerrar conexión
    $stmt->close();
    $connObj->closeConnection();
} else {
    // Si alguien intenta acceder a este script sin enviar el formulario, redirigir al formulario de inicio de sesión
    header("location: login.php");
}
?>
