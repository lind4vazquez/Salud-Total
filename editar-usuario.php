<?php
require 'config.php';
check_auth();

$id = (int)($_GET['id'] ?? 0);

// Usuario actual (el que está logueado)
$yo = $_SESSION['user_id'];

// Buscar el usuario a editar
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = "Usuario no encontrado.";
    header("Location: usuarios.php");
    exit;
}

// Revisar si el que edita es un admin
$stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->execute([$yo]);
$miRol = $stmt->fetchColumn();

$soyAdmin = ($miRol === 'admin');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);

    if ($nombre === '') $errors[] = "El nombre no puede estar vacío.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Correo inválido.";

    // Solo admin puede cambiar rol
    $rol = $user['rol'];
    if ($soyAdmin) {
        $rol = $_POST['rol'] === 'admin' ? 'admin' : 'empleado';
    }

    // Solo admin puede cambiar contraseña
    $nuevoPassword = null;
    if ($soyAdmin && !empty($_POST['nueva_clave'])) {
        if (strlen($_POST['nueva_clave']) < 4) {
            $errors[] = "La contraseña debe tener al menos 4 caracteres.";
        } else {
            $nuevoPassword = password_hash($_POST['nueva_clave'], PASSWORD_DEFAULT);
        }
    }

    if (empty($errors)) {

        if ($nuevoPassword) {
            // admin cambia contraseña
            $sql = "UPDATE usuarios SET nombre=?, email=?, rol=?, clave=? WHERE id=?";
            $pdo->prepare($sql)->execute([$nombre, $email, $rol, $nuevoPassword, $id]);
        } else {
            // usuario normal o admin sin cambiar clave
            $sql = "UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?";
            $pdo->prepare($sql)->execute([$nombre, $email, $rol, $id]);
        }

        $_SESSION['flash'] = "Usuario actualizado correctamente.";
        header("Location: usuarios.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Editar Usuario</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">

<h1>Editar usuario</h1>

<?php if (!empty($errors)): ?>
<div class="error-box">
    <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
</div>
<?php endif; ?>

<form method="post">

<label>Nombre</label>
<input type="text" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? $user['nombre']) ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>

<?php if ($soyAdmin): ?>
    <label>Rol</label>
    <select name="rol">
        <option value="empleado" <?= $user['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
        <option value="admin" <?= $user['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <label>Nueva contraseña (opcional)</label>
    <input type="password" name="nueva_clave" placeholder="Dejar en blanco si no se cambia">
<?php endif; ?>

<button type="submit">Guardar cambios</button>

</form>

<p><a href="usuarios.php">Volver</a></p>

</div>
</body>
</html>
