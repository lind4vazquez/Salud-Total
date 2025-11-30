<?php
require 'config.php';

session_unset();   // borra las variables
session_destroy(); // elimina la sesión

header('Location: login.php');
exit;
