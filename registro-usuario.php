<?php
require 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $rol = isset($_POST['rol']) && in_array($_POST['rol'], ['admin','empleado']) ? $_POST['rol'] : 'empleado';

    if ($nombre === '') $errors[] = 'El nombre es obligatorio.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Correo inválido.';
    if (strlen($clave) < 4) $errors[] = 'La contraseña debe tener al menos 4 caracteres.';

    // verificar email duplicado
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[] = 'El correo ya está registrado.';

    if (empty($errors)) {
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO usuarios (nombre, email, clave, rol) VALUES (?, ?, ?, ?)");
        $ins->execute([$nombre, $email, $hash, $rol]);
        $success = 'Registro completado. Puedes iniciar sesión.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Registro de Usuario</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
  <div class="container">
    <h1>Registro de usuario</h1>

    <?php if (!empty($errors)): ?>
      <div class="error-box"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>";?></ul></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="flash"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
      <label>Nombre
        <input name="nombre" type="text" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
      </label>

      <label>Correo
        <input name="email" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </label>

      <label>Contraseña
        <input name="clave" type="password" required>
      </label>

      <label>Rol
        <select name="rol">
          <option value="empleado">Empleado</option>
          <option value="admin">Administrador</option>
        </select>
      </label>

      <button type="submit">Registrar</button>
    </form>

    <p><a href="login.php">Volver al login</a></p>
  </div>
</body>
</html>
