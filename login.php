<?php
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    // Busca por la columna REAL de tu tabla: nombre
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch();

    // Verifica el password usando la columna REAL: clave
    if ($admin && password_verify($password, $admin['clave'])) {

        // ----------- ESTO VA AQUÍ ------------------
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_rol'] = $admin['rol'];
        // -------------------------------------------

        header('Location: panel.php');
        exit;

    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<div class="container">

    <h1>Iniciar Sesión</h1>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">

        <label>Usuario:</label>
        <input type="text" name="usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Entrar</button>
        <p>¿No tienes cuenta? <a href="registro-usuario.php">Registrarse</a></p>

    </form>

</div>

</body>
</html>
