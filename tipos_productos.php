<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include('conexion.php');

// --- AGREGAR nuevo tipo de producto ---
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conexion->prepare("INSERT INTO tipos_productos (nombre, descripcion) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $descripcion);
    $stmt->execute();
    $stmt->close();
}

// --- EDITAR tipo de producto ---
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conexion->prepare("UPDATE tipos_productos SET nombre=?, descripcion=? WHERE id=?");
    $stmt->bind_param("ssi", $nombre, $descripcion, $id);
    $stmt->execute();
    $stmt->close();
    
    // Redirigir para evitar que el formulario se envíe nuevamente al actualizar
    header("Location: tipos_productos.php");
    exit();
}

// --- ELIMINAR tipo de producto ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM tipos_productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- CONSULTAR todos los tipos ---
$resultado = $conexion->query("SELECT * FROM tipos_productos ORDER BY id DESC");

// --- CARGAR datos para editar tipo de producto ---
$tipo_a_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $stmt = $conexion->prepare("SELECT * FROM tipos_productos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $tipo_a_editar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Productos - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">ZapSoft 👟</a>
            <div>
                <a href="productos.php" class="btn btn-outline-light me-2">Productos</a>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center fw-bold text-primary">👞 Tipos de Productos</h2>

        <!-- Formulario de registro o edición -->
        <div class="card mt-4 mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <?php echo isset($tipo_a_editar) ? 'Editar' : 'Agregar'; ?> Tipo de Producto
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <?php if (isset($tipo_a_editar)): ?>
                        <input type="hidden" name="id" value="<?= $tipo_a_editar['id'] ?>">
                    <?php endif; ?>
                    <div class="col-md-5">
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del tipo" value="<?= isset($tipo_a_editar) ? htmlspecialchars($tipo_a_editar['nombre']) : '' ?>" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción" value="<?= isset($tipo_a_editar) ? htmlspecialchars($tipo_a_editar['descripcion']) : '' ?>">
                    </div>
                    <div class="col-md-2 d-grid">
                        <?php if (isset($tipo_a_editar)): ?>
                            <button type="submit" name="editar" class="btn btn-success">Actualizar</button>
                        <?php else: ?>
                            <button type="submit" name="agregar" class="btn btn-success">Guardar</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de tipos -->
        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td class="text-center">
                            <!-- Botón de editar y eliminar -->
                            <a href="?editar=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?eliminar=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este tipo?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-center mt-4 mb-3 text-muted">
        <small>© 2025 ZapSoft - Módulo de Tipos de Productos</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/funciones.js"></script>

</body>
</html>

