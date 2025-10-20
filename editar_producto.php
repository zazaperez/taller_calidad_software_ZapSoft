<?php
session_start();
include('conexion.php');

// Verificar si el ID del producto está presente en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del producto
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Verificar si el producto existe
    if (!$producto) {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "ID de producto no especificado.";
    exit();
}

// Si el formulario es enviado, actualizar los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $tipo_id = $_POST['tipo_id'];

    // Actualizar el producto en la base de datos
    $stmt = $conexion->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, tipo_id = ? WHERE id = ?");
    $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $tipo_id, $id);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la lista de productos después de la edición
    header("Location: productos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center">Editar Producto</h2>

        <!-- Formulario de edición -->
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($producto['descripcion']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" value="<?= $producto['precio'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_id" class="form-label">Tipo de Producto</label>
                <select name="tipo_id" class="form-select" required>
                    <?php
                    // Obtener tipos de productos para el dropdown
                    $tipos = $conexion->query("SELECT * FROM tipos_productos ORDER BY nombre ASC");
                    while ($tipo = $tipos->fetch_assoc()):
                    ?>
                        <option value="<?= $tipo['id'] ?>" <?= $producto['tipo_id'] == $tipo['id'] ? 'selected' : '' ?>><?= htmlspecialchars($tipo['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/funciones.js"></script>
</body>
</html>
