<?php


$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_errno) {
    printf("<h1>Connessione al server Mysql fallita: %s</h1>", $conn->connect_error);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST); // Verifica se il form è stato inviato correttamente

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash della password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, surname, birthdate, gender, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Errore nella preparazione della query: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sssssss", $name, $surname, $birthdate, $gender, $email, $password, $role);
    if (!$stmt->execute()) {
        echo "Errore nell'esecuzione della query: " . $stmt->error;
        exit();
    }

    if ($stmt->affected_rows > 0) {
        /*$_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $name;*/
        echo "Registration successful";
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error: unable to insert data";
    }
}

$conn->close();
    ?>