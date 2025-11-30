<?php
require 'config.php';
check_auth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['flash'] = 'ID inválido.';
    header('Location: panel.php');
    exit;
}

// Si ya se envió la confirmación:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['confirm']) && $_POST['confirm'] === 'si') {
        // Eliminar medicamento
        $del = $pdo->prepare("DELETE FROM medicamentos WHERE id = ?");
        $del->execute([$id]);

        $_SESSION['flash'] = 'Medicamento eliminado correctamente.';
        header('Location: panel.php');
        exit;

    } else {
        // Canceló
        $_SESSION['flash'] = 'Eliminación cancelada.';
        header('Location: panel.php');
        exit;
    }
}

// Obtener nombre para mostrar en confirmación
$stmt = $pdo->prepare("SELECT nombre FROM medicamentos WHERE id = ?");
$stmt->execute([$id]);
$med = $stmt->fetch();

if (!$med) {
    $_SESSION['flash'] = 'Medicamento no encontrado.';
    header('Location: panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Medicamento</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">

        <h1>Eliminar Medicamento</h1>

        <p>¿Seguro que deseas eliminar <strong><?= htmlspecialchars($med['nombre']) ?></strong>?</p>

        <form method="post">
            <button class="btn-danger" name="confirm" value="si">Sí, eliminar</button>
            <button class="btn" name="confirm" value="no">Cancelar</button>
        </form>

        <p><a href="panel.php">Volver</a></p>

    </div>
</body>

</html>
