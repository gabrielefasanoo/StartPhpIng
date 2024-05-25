<?php
session_start();

$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_errno) {
    printf("<h1>Connessione al server Mysql fallita: %s</h1>", $conn->connect_error);
    exit();
}

// Credenziali presenti nel DB
$stored_email = "SELECT email FROM users";
$stored_psw_hash = "SELECT password FROM users";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $log_email = $_POST['LoginEmail'];
    $log_psw = $_POST['LoginPassword'];

    // Verifica delle credenziali
    if ($log_email === $stored_email && password_verify($log_psw, $stored_psw_hash)) {
        // Credenziali corrette, memorizza le informazioni di sessione
        $username = "SELECT name FROM users WHERE $stored_email = $log_email";
        $_SESSION['username'] = $username;
        header('Location: index.html');
        exit();
    } else {
        echo 'Nome utente o password errati!';
    }
}
?>
