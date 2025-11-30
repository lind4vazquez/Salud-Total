<?php
require 'config.php';

if (!is_admin()) {
    $_SESSION['flash'] = "Acceso restringido.";
    header("Location: panel.php");
    exit;
}

check_auth();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    if ($nombre === '') $errors[] = "El nombre es obligatorio.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO proveedores (nombre, telefono, direccion) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $telefono, $direccion]);
        $_SESSION['flash'] = "Proveedor agregado correctamente.";
        header("Location: proveedores.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Agregar Proveedor</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">

<h1>Agregar Proveedor</h1>

<?php if (!empty($errors)): ?>
<div class="error-box">
    <?php foreach ($errors as $e): ?>
        <p><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form method="post">

<label>Nombre</label>
<input type="text" name="nombre" required>

<label>Teléfono</label>
<input type="text" name="telefono">

<label>Dirección</label>
<textarea name="direccion"></textarea>
<br>
<button type="submit">Guardar</button>

</form>

<a href="proveedores.php">Volver</a>

</div>
</body>
</html>
