<?php
session_start();
  include 'control/conn.php';  
// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) { // Verifica l'email anziché l'username
    echo 'Accesso non autorizzato!';
    exit();
}

$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_errno) {
    printf("<h1>Connessione al server Mysql fallita: %s</h1>", $conn->connect_error);
    exit();
}

$email = $_SESSION['email'];

// Utilizzare una query preparata
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Nessun utente trovato";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Project X wiki</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Il tuo header va qui -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Homepage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">PKB table</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">GDPR info</a></li>
                    <li class="nav-item"><a class="nav-link" href="#!">Area Feedback</a></li>
                </ul>
                <div>
                    <?php if (isset($_SESSION['username'])) : ?>
                        <!-- Se l'utente è loggato mostra il nome utente con profilo e azione di logout -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php">Home</a></li>
                                <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <!-- Se l'utente non è loggato allora mostra i bottoni di registrazione e login -->
                        <button class="btn btn-outline-dark mx-2" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                    <?php endif; ?>
                </div>

                <!-- Login Modal -->
                <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Login form -->
                                <form action="model/login.php" method="POST" id="loginForm">
                                    <div class="mb-3">
                                        <label for="loginEmail" class="form-label">Email</label>
                                        <input type="email" name="LoginEmail" class="form-control" id="loginEmail" placeholder="Enter your email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="loginPassword" class="form-label">Password</label>
                                        <input type="password" name="LoginPassword" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                                    </div>
                                    <div class="alert alert-danger d-none" id="loginError" role="alert"></div>
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Register Modal -->
            <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="registerModalLabel">Register</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Register form -->
                            <form action="model/register.php" method="POST">
                                <div class="mb-3">
                                    <label for="registerName" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="registerName" placeholder="Enter your name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerSurname" class="form-label">Surname</label>
                                    <input type="text" name="surname" class="form-control" id="registerSurname" placeholder="Enter your surname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" name="birthdate" class="form-control" id="registerBirthdate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerGender" class="form-label">Gender</label>
                                    <select class="form-select" name="gender" id="registerGender" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="registerEmail" placeholder="Enter your email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="registerPassword" placeholder="Enter your password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerRole" class="form-label">Role</label>
                                    <select class="form-select" name="role" id="registerRole" required>
                                        <option value="user">User</option>
                                        <option value="developer">Developer</option>
                                        <option value="engineer">Engineer</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Qui mostri i dati dell'utente -->
    <div class="container">
        <h1>Profilo di <?php echo htmlspecialchars($user['name']); ?></h1>
        <p>Nome: <?php echo htmlspecialchars($user['name']); ?></p>
        <p>Cognome: <?php echo htmlspecialchars($user['surname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <!-- Aggiungi altri campi come necessario -->
    </div>



     <!-- Footer-->
     <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white"></p>
        </div>
    </footer>
    <!-- Il resto del tuo HTML va qui -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
