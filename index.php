<?php
session_start();
include('conexion.php');

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Usamos prepared statements (más seguro)
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ?");
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Usuario o contraseña incorrectos";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - ZapSoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #007bff, #6610f2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ======== ICONOS DELANTE ======== */
        .iconos-delante {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
            font-size: 3rem;
            color: rgba(0, 0, 0, 0.2);
        }

        .iconos-delante span {
            position: absolute;
            animation: flotarIconos 15s infinite ease-in-out;
        }

        @keyframes flotarIconos {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            z-index: 10;
            position: relative;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
            width: 100%;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- ICONOS DE ZAPATO DELANTE -->
    <div class="iconos-delante">
        <span style="top:10%; left:8%;">👟</span>
        <span style="top:25%; left:60%;">👟</span>
        <span style="top:70%; left:80%;">👟</span>
        <span style="top:55%; left:50%;">👟</span>
        <span style="top:45%; left:15%;">👟</span>
        <span style="top:70%; left:80%;">👟</span>
        <span style="top:55%; left:50%;">👟</span>
        <span style="top:80%; left:25%;">👟</span>
    </div>


    <div class="login-container">
        <h2 class="mb-4 fw-bold text-primary">ZapSoft 👟</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <?php if(isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
