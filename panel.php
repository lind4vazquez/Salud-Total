<?php
require 'config.php';
check_auth();

// Capturar mensaje flash si existe
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

// Consulta general
$sql = "
SELECT medicamentos.*, proveedores.nombre AS proveedor
FROM medicamentos
LEFT JOIN proveedores ON proveedores.id = medicamentos.proveedor_id
ORDER BY medicamentos.id DESC
";
$stmt = $pdo->query($sql);
$medicamentos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Inventario</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">

        <h1>Panel de Inventario</h1>

        <?php if ($flash): ?>
            <div class="flash"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <p>
            <a class="btn" href="registro.php">Registrar Medicamento</a>
            <a class="btn" href="logout.php">Cerrar sesión</a>
        </p>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicamentos as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['nombre']) ?></td>
                    <td><?= htmlspecialchars($m['categoria']) ?></td>
                    <td><?= htmlspecialchars($m['cantidad']) ?></td>
                    <td>$<?= htmlspecialchars($m['precio']) ?></td>
                    <td><?= htmlspecialchars($m['proveedor']) ?></td>
                    <td>
                        <a class="btn" href="editar.php?id=<?= $m['id'] ?>">Editar</a>
                        <a class="btn btn-danger" href="eliminar.php?id=<?= $m['id'] ?>">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
