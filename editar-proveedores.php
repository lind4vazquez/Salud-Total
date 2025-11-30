<?php
require 'config.php';

if (!is_admin()) {
    $_SESSION['flash'] = "Acceso restringido.";
    header("Location: panel.php");
    exit;
}


check_auth();

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id = ?");
$stmt->execute([$id]);
$prov = $stmt->fetch();

if (!$prov) {
    $_SESSION['flash'] = "Proveedor no encontrado.";
    header("Location: proveedores.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    if ($nombre === '') $errors[] = "El nombre es obligatorio.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE proveedores SET nombre=?, telefono=?, direccion=? WHERE id=?");
        $stmt->execute([$nombre, $telefono, $direccion, $id]);

        $_SESSION['flash'] = "Proveedor actualizado correctamente.";
        header("Location: proveedores.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Editar Proveedor</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
<h1>Editar Proveedor</h1>

<?php if (!empty($errors)): ?>
<div class="error-box"><?php foreach ($errors as $e) echo "<p>$e</p>" ?></div>
<?php endif; ?>

<form method="post">

<label>Nombre</label>
<input type="text" name="nombre" value="<?= htmlspecialchars($prov['nombre']) ?>" required>

<label>Teléfono</label>
<input type="text" name="telefono" value="<?= htmlspecialchars($prov['telefono']) ?>">

<label>Dirección</label>
<textarea name="direccion"><?= htmlspecialchars($prov['direccion']) ?></textarea>

<button type="submit">Actualizar</button>

</form>

<a href="proveedores.php">Volver</a>

</div>
</body>
</html>
