<?php
session_start();

$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_errno) {
    printf("<h1>Connessione al server Mysql fallita: %s</h1>", $conn->connect_error);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $log_email = $_POST['LoginEmail'];
    $log_psw = $_POST['LoginPassword'];

    // Preparazione della query
    $stmt = $conn->prepare("SELECT email, password, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $log_email);

    // Esecuzione della query
    $stmt->execute();

    // Ottieni i risultati
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Verifica delle credenziali
        if (password_verify($log_psw, $user['password'])) {
            // Credenziali corrette, memorizza le informazioni di sessione
            $_SESSION['username'] = $user['name'];
            header('Location: index.php');
            exit();
        } else {
            echo 'Nome utente o password errati!';
        }
    } else {
        echo 'Nome utente o password errati!';
    }
}
?>