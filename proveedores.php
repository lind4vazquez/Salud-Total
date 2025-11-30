<?php
require 'config.php';

if (!is_admin()) {
    $_SESSION['flash'] = "Acceso restringido.";
    header("Location: panel.php");
    exit;
}


check_auth();

$stmt = $pdo->query("SELECT * FROM proveedores ORDER BY creado_en DESC");
$proveedores = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Proveedores - Salud Total</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">

    <h1>Gestión de Proveedores</h1>
    <?= flash_message() ?>

    <a class="btn" href="agregar-proveedores.php">Agregar proveedor</a>
    <a class="btn" href="panel.php">Volver al panel</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Creado en</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (empty($proveedores)): ?>
            <tr><td colspan="6">No hay proveedores registrados.</td></tr>
        <?php else: ?>
            <?php foreach ($proveedores as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['telefono']) ?></td>
                    <td><?= htmlspecialchars($p['direccion']) ?></td>
                    <td><?= $p['creado_en'] ?></td>
                    <td>
                        <a class="btn" href="editar-proveedores.php?id=<?= $p['id'] ?>">Editar</a>
                        <a class="btn btn-danger" href="eliminar_proveedores.php?id=<?= $p['id'] ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        </tbody>
    </table>
</div>
</body>
</html>
