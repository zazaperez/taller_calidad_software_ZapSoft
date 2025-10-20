<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['usuario'])) {
    // Redirige al login si no está autenticado
    header("Location: index.php");
    exit();
}

// Si la sesión está activa, mostrar el contenido del dashboard
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">ZapSoft 👟</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="productos.php">🥿 Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="tipos_productos.php">👞 Tipos de Productos</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2 px-3" href="logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5 text-center">
        <h1 class="fw-bold text-primary">Bienvenido a ZapSoft, <?php echo $_SESSION['usuario']; ?> 👋</h1>
        <p class="lead mt-3">
            Administra tu zapatería fácilmente desde este panel.  
            Puedes gestionar los <b>productos</b> y sus <b>tipos</b>, ver tu inventario y mantener la información actualizada.
        </p>

        <!-- 🛍️ Botón para ir al catálogo -->
        <div class="mt-4">
            <a href="catalogo.php" class="btn btn-outline-primary btn-lg shadow-sm px-4">
                🛍️ Ver catálogo público
            </a>
        </div>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        <small>© 2025 ZapSoft - Sistema de Gestión de Zapatería</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
