<?php
session_start();
include 'control/conn.php';

if (!isset($_POST['email'])) {
    echo 'Accesso non autorizzato!';
    exit();
}

$email = $_POST['email'];

$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_errno) {
    printf("<h1>Connessione al server Mysql fallita: %s</h1>", $conn->connect_error);
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    session_destroy();
    header("Location: ../index.php");
} else {
    echo "Errore durante l'eliminazione dell'account";
}
?>