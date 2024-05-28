<?php
session_start();
include '../control/conn.php';

function query_select($conn, $email) {
    $stmt = $conn->prepare("SELECT email, password, name FROM users WHERE email = ?");
    if (!$stmt) {
        echo "Errore nella preparazione della query: " . $conn->error;
        return false;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function confronto_credenziali($input_password, $stored_password) {
    return password_verify($input_password, $stored_password);
}

function inizio_sessione($email, $name) {
    $_SESSION['email'] = $email; 
    $_SESSION['username'] = $name;
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $log_email = $_POST['LoginEmail'];
    $log_psw = $_POST['LoginPassword'];

    $user = query_select($conn, $log_email);

    if ($user) {
        if (confronto_credenziali($log_psw, $user['password'])) {
            inizio_sessione($log_email, $user['name']);
        } else {
            echo 'Email o password errati!';
        }
    } else {
        echo 'Email o password errati!';
    }
}
?>
