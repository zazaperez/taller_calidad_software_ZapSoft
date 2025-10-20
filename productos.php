<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include('conexion.php');

// --- OBTENER tipos de productos para el selector ---
$tipos = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre ASC");

// --- AGREGAR producto ---
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $tipo_id = $_POST['tipo_id'];

    // Manejo de imagen (opcional)
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $nombreArchivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $ruta = "uploads/" . $nombreArchivo;
        if (!is_dir("uploads")) mkdir("uploads");
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        $imagen = $ruta;
    }

    $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, tipo_id, imagen) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $tipo_id, $imagen);
    $stmt->execute();
    $stmt->close();
}

// --- ELIMINAR producto ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// --- CONSULTAR todos los productos ---
$resultado = $conexion->query("SELECT p.*, t.nombre AS tipo FROM productos p LEFT JOIN tipos_productos t ON p.tipo_id = t.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">ZapSoft 👟</a>
            <div>
                <a href="tipos_productos.php" class="btn btn-outline-light me-2">Tipos de Productos</a>
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center fw-bold text-primary">🥿 Gestión de Productos</h2>

        <!-- Formulario para agregar producto -->
        <div class="card mt-4 mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                Agregar Nuevo Producto
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre del producto" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="precio" class="form-control" placeholder="Precio" step="0.01" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                    </div>
                    <div class="col-md-2">
                        <select name="tipo_id" class="form-select" required>
                            <option value="">Tipo...</option>
                            <?php while ($tipo = $tipos->fetch_assoc()): ?>
                                <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="imagen" class="form-control">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" name="agregar" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de productos -->
        <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id'] ?></td>
                        <td class="text-center">
                            <?php if($fila['imagen']): ?>
                                <img src="<?= $fila['imagen'] ?>" width="60" class="rounded">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td>$<?= number_format($fila['precio'], 2) ?></td>
                        <td><?= $fila['stock'] ?></td>
                        <td><?= htmlspecialchars($fila['tipo'] ?? 'Sin tipo') ?></td>
                        <td class="text-center">
                            <!-- Botones de Editar y Eliminar -->
                            <a href="editar_producto.php?id=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?eliminar=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-center mt-4 mb-3 text-muted">
        <small>© 2025 ZapSoft - Módulo de Productos</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/funciones.js"></script>

</body>
</html>
