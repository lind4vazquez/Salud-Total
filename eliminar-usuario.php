<?php
require 'config.php';
check_auth();

// usuario que está logueado
$yo = $_SESSION['user_id'];

// verificar si es admin
$stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->execute([$yo]);
$miRol = $stmt->fetchColumn();

if ($miRol !== 'admin') {
    $_SESSION['flash'] = "No tienes permisos para eliminar usuarios.";
    header("Location: usuarios.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// evitar que el admin se auto-elimine ☠️
if ($id === $yo) {
    $_SESSION['flash'] = "No puedes eliminar tu propia cuenta.";
    header("Location: usuarios.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['flash'] = "Usuario eliminado.";
header("Location: usuarios.php");
exit;
