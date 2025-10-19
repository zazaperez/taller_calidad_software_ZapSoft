<?php
// conexion.php
// Conexión a la base de datos MySQL para ZapSoft

// CONFIGURACIÓN: cambia estos valores si tienes otro usuario/clave
$db_host = "localhost";
$db_user = "root";
$db_pass = "";         // En XAMPP por defecto es cadena vacía
$db_name = "zapsoft_db";

// Crear la conexión
$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Revisar la conexión
if ($conexion->connect_error) {
    // En producción no muestres detalles; aquí para desarrollo/depuración
    die("Error de conexión (" . $conexion->connect_errno . "): " . $conexion->connect_error);
}

// Establecer charset para evitar problemas con tildes/acentos
$conexion->set_charset("utf8mb4");

// Función auxiliar para cerrar la conexión (opcional)
function cerrarConexion($conexion) {
    if ($conexion) $conexion->close();
}
?>
