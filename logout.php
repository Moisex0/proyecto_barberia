<?php

session_start();   // Inicio la sesión :)
session_destroy(); // Cierro todo :)
header("Location: login.php"); // Me regreso al login :)
exit();

?>
