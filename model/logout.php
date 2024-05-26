<?php


session_start();
session_unset();
session_destroy();
header("Location: ../index.php"); // Reindirizza l'utente nella homepage dopo il logout
exit();
?>