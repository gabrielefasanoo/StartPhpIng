<?php
session_start();
include 'control/conn.php';
// Verifica se l'utente è loggato
if (!isset($_SESSION['email'])) { // Verifica l'email anziché l'username
    echo 'Accesso non autorizzato!';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Project X wiki</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
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
                            <li><a class="dropdown-item" href="profilo.php">Profilo</a></li>
                            <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <!-- Se l'utente non è loggato allora mostra i bottoni di registrazione e login -->
                    <button class="btn btn-outline-dark mx-2" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                    <button class="btn btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<body>
    <?php
    include 'control/conn.php';
    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Query per selezionare tutte le righe dalla tabella PKB
    $sql = "SELECT * FROM pkb";
    $result = $conn->query($sql);
    echo '<div class="container">';
    if ($result->num_rows > 0) {
        echo '<div class="row">';
        // Output dei dati in forma di card per ciascuna riga
        while ($row = $result->fetch_assoc()) {
            // Genera un ID univoco per il bottone e il modal
            $unique_id = 'modal' . $row['id'];
    ?>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nome']; ?></h5>
                        <p class="card-text"><?php echo substr($row['descrizione'],0,100); ?>...</p>
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#<?php echo $unique_id; ?>">More Info</button>
                        <!-- Aggiungi qui il codice per il modal -->
                        <div class="modal fade" id="<?php echo $unique_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $unique_id; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel<?php echo $unique_id; ?>">Modal Title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Contenuto del modal -->
                                        <h5 class="card-title"><?php echo $row['nome']; ?></h5>
                                        <p class="card-text"><?php echo $row['descrizione']; ?></p>
                                        <hr>
                                        <h5 class="card-title">Context Pattern</h5>
                                        <p class="card-text"><?php echo $row['context_pattern']; ?></p>
                                        <hr>
                                        <h5 class="card-title">Collocazione MVC</h5>
                                        <p class="card-text"><?php echo $row['collocazione_mvc']; ?></p>
                                        <hr>
                                        <h5 class="card-title">ISO 9241-210 Phase</h5>
                                        <p class="card-text"><?php echo $row['iso_9241_210_phase']; ?></p>

                                        <hr>
                                        <h5 class="card-title           ">GDPR</h5>
                                        <p class="card-text"><?php echo $row['article_gdpr']; ?></p>
                                        <hr>
                                        <h5 class="card-title        ">Privacy by Design</h5>  
                                        <p class="card-text"><?php echo $row['privacy_by_design']; ?></p>
                                        <hr>
                                        <h5 class="card-title     ">OWASP Top Ten</h5>
                                        <p class="card-text"><?php echo $row['owasp_top_ten']; ?></p>
                                        <hr>
                                        <h5 class="card-title   ">CWE Top 25</h5>
                                        <p class="card-text"><?php echo $row['cwe_top_25']; ?></p>
                                        <hr>
                                        <h5 class="card-title       ">Esempi</h5>
                                        <p class="card-text"><?php echo $row['esempi']; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Aggiungi qui gli altri campi della tabella come necessario -->
                    </div>
                </div>
            </div>
    <?php
        }
        echo '</div>'; // Chiudi il div della riga
    } else {
        echo "Nessun risultato trovato nella tabella PKB";
    }

    $conn->close();
    echo '</div>'; // Chiudi il div del container
    ?>
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white"></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<!-- nome	
descrizione	
context_pattern	
collocazione_mvc	
iso_9241_210_phase	
article_gdpr	
privacy_by_design	
owasp_top_ten	
cwe_top_25	
esempi --> -->