<?php

session_start();   // Inicio la sesión :)
session_destroy(); // Cierro todo :)
header("Location: index.php"); // Me regreso al login :)
exit();

?>
