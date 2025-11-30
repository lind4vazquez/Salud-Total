<?php
require 'config.php';
check_auth();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['flash'] = 'ID inválido.';
    header('Location: panel.php');
    exit;
}

// obtener medicamento existente
$stmt = $pdo->prepare("SELECT * FROM medicamentos WHERE id = ?");
$stmt->execute([$id]);
$med = $stmt->fetch();

if (!$med) {
    $_SESSION['flash'] = 'Medicamento no encontrado.';
    header('Location: panel.php');
    exit;
}

// obtener proveedores
$provStmt = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre");
$proveedores = $provStmt->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
    $proveedor_id = !empty($_POST['proveedor_id']) ? (int)$_POST['proveedor_id'] : null;

    if ($nombre === '')       $errors[] = 'El nombre es obligatorio.';
    if ($categoria === '')    $errors[] = 'La categoría es obligatoria.';
    if ($cantidad === false)  $errors[] = 'La cantidad debe ser un número entero.';
    if ($precio === false)    $errors[] = 'El precio debe ser un número válido.';

    if (empty($errors)) {
        $update = $pdo->prepare("
            UPDATE medicamentos
            SET nombre = ?, categoria = ?, cantidad = ?, precio = ?, proveedor_id = ?
            WHERE id = ?
        ");
        $update->execute([$nombre, $categoria, $cantidad, $precio, $proveedor_id, $id]);

        $_SESSION['flash'] = 'Medicamento actualizado correctamente.';
        header('Location: panel.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamento</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">

        <h1>Editar Medicamento</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    • <?= htmlspecialchars($e) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($med['nombre']) ?>" required>

            <label>Categoría:</label>
            <input type="text" name="categoria" value="<?= htmlspecialchars($med['categoria']) ?>" required>

            <label>Cantidad:</label>
            <input type="number" name="cantidad" min="0" value="<?= $med['cantidad'] ?>" required>

            <label>Precio:</label>
            <input type="number" name="precio" min="0" step="0.01" value="<?= $med['precio'] ?>" required>

            <label>Proveedor:</label>
            <select name="proveedor_id">
                <option value="">-- Sin proveedor --</option>
                <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id'] ?>" <?=$p['id']==$med['proveedor_id']?"selected":""?>>
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Guardar Cambios</button>

        </form>q

        <p><a href="panel.php">Volver</a></p>

        <script src="js/validation.js"></script>

    </div>
</body>

</html>
