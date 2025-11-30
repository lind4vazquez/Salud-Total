<?php
require 'config.php';

if (!is_admin()) {
    $_SESSION['flash'] = "Acceso restringido.";
    header("Location: panel.php");
    exit;
}

check_auth();

$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY creado_en DESC");
$usuarios = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Usuarios - Salud Total</title>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">

<h1>Gestión de Usuarios</h1>
<?= flash_message() ?>

<a class="btn" href="registro-usuario.php">Agregar usuario</a>
<a class="btn" href="panel.php">Volver al panel</a>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Creado en</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>

<?php foreach ($usuarios as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['nombre']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['rol']) ?></td>
    <td><?= $u['creado_en'] ?></td>
    <td>
        <a class="btn" href="editar-usuario.php?id=<?= $u['id'] ?>">Editar</a>
        <a class="btn btn-danger" href="eliminar-usuario.php?id=<?= $u['id'] ?>">Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</div>
</body>
</html>
