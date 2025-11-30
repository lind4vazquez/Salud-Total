<?php
require 'config.php';
check_auth();

$errors = [];

// Obtener proveedores para el select
$provStmt = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre");
$proveedores = $provStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
    $proveedor_id = !empty($_POST['proveedor_id']) ? (int) $_POST['proveedor_id'] : null;

    if ($nombre === '')       $errors[] = 'El nombre es obligatorio.';
    if ($categoria === '')    $errors[] = 'La categoría es obligatoria.';
    if ($cantidad === false)  $errors[] = 'La cantidad debe ser un número entero.';
    if ($precio === false)    $errors[] = 'El precio debe ser un número válido.';

    // Si no hay errores, insertar
    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO medicamentos (nombre, categoria, cantidad, precio, proveedor_id)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nombre, $categoria, $cantidad, $precio, $proveedor_id]);

        $_SESSION['flash'] = 'Medicamento registrado correctamente.';
        header('Location: panel.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Medicamento</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">

        <h1>Registrar Medicamento</h1>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    • <?= htmlspecialchars($e) ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Categoría:</label>
            <input type="text" name="categoria" required>

            <label>Cantidad:</label>
            <input type="number" name="cantidad" min="0" required>

            <label>Precio:</label>
            <input type="number" name="precio" min="0" step="0.01" required>

            <label>Proveedor:</label>
            <select name="proveedor_id">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Registrar</button>
        </form>

        <p><a href="panel.php">Volver</a></p>

        <script src="js/validation.js"></script>

    </div>
</body>

</html>
