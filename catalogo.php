<?php
include('conexion.php');

// Filtrar por tipo si el cliente selecciona uno
$filtro = "";
if (isset($_GET['tipo']) && $_GET['tipo'] != "") {
    $tipo_id = intval($_GET['tipo']);
    $filtro = "WHERE p.tipo_id = $tipo_id";
}

// Consultar los tipos de producto (para el filtro)
$tipos = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre ASC");

// Consultar los productos (filtrados o todos)
$query = "
    SELECT p.*, t.nombre AS tipo
    FROM productos p
    LEFT JOIN tipos_productos t ON p.tipo_id = t.id
    $filtro
    ORDER BY p.id DESC
";
$productos = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Zapatos - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="catalogo.php">ZapSoft 👟</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light">Área Administrativa</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container mt-5">
        <h1 class="text-center fw-bold text-primary mb-4">🛍️ Catálogo de Zapatos</h1>

        <!-- Filtro de tipos -->
        <form method="GET" class="text-center mb-4">
            <label class="fw-semibold me-2">Filtrar por tipo:</label>
            <select name="tipo" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php while($tipo = $tipos->fetch_assoc()): ?>
                    <option value="<?= $tipo['id'] ?>" <?= isset($tipo_id) && $tipo_id == $tipo['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <!-- Mostrar productos -->
        <div class="row justify-content-center">
            <?php if ($productos->num_rows > 0): ?>
                <?php while($p = $productos->fetch_assoc()): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <!-- Imagen del producto -->
                            <?php if($p['imagen']): ?>
                                <img src="<?= $p['imagen'] ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>" style="height:220px;object-fit:cover;">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/250x220?text=Sin+Imagen" class="card-img-top" alt="Sin imagen">
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <!-- Nombre del producto -->
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($p['nombre']) ?></h5>
                                <p class="text-muted small mb-1"><?= htmlspecialchars($p['tipo'] ?? 'Sin tipo') ?></p>
                                <p class="card-text"><?= htmlspecialchars($p['descripcion']) ?></p>
                                <p class="fw-bold text-success mb-1">$<?= number_format($p['precio'], 2) ?></p>
                                <p class="text-muted small">Disponibles: <?= $p['stock'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center text-muted">
                    <p>No hay productos disponibles en este tipo.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botón para redirigir al dashboard -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Ir al Dashboard</a>
        </div>

    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        <small>© 2025 ZapSoft - Catálogo de Zapatería</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/funciones.js"></script>
</body>
</html>
