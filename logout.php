<?php
// logout.php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si hay una cookie de sesión, se elimina
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente destruir la sesión
session_destroy();

// Redirigir al login
header("Location: index.php");
exit();
?>
