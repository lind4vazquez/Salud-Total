<?php
require 'config.php';

if (!is_admin()) {
    $_SESSION['flash'] = "Acceso restringido.";
    header("Location: panel.php");
    exit;
}

check_auth();

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM proveedores WHERE id = ?");
$stmt->execute([$id]);

header("Location: proveedores.php");
exit;
