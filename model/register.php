<?php

include '../control/conn.php';

function password_criptata($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function query_insert($conn, $name, $surname, $gender, $email, $password, $role) {
    $sql = "INSERT INTO users (name, surname, gender, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Errore nella preparazione della query: " . $conn->error;
        return false;
    }

    $stmt->bind_param("ssssss", $name, $surname, $gender, $email, $password, $role);
    if (!$stmt->execute()) {
        echo "Errore nell'esecuzione della query: " . $stmt->error;
        return false;
    }

    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        echo "Error: unable to insert data";
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST); // Verifica se il form è stato inviato correttamente

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = password_criptata($_POST['password']); // Hash della password
    $role = $_POST['role'];

    if (query_insert($conn, $name, $surname, $gender, $email, $password, $role)) {
        echo "Registration successful";
        header("Location: ../index.php");
        exit();
    }
}

$conn->close();
?>
