<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../.configs.php');
include('../../../lib/include/php/Database/DatabaseManager.php');

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Affecter le premier sous-tableau de $database_configs à $db_flipapp
$db_flipapp = $database_configs[0];

// Initialiser la connexion à la base de données flipapp
$databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

// Sélectionner la base de données
$databaseManager->selectDatabase($db_flipapp['name']);


// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs 'email' et 'password' existent dans $_POST
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Préparation de la requête SQL pour vérifier l'utilisateur et son mot de passe
    $stmtCheckUser = $databaseManager->getAdminConnection()->prepare("SELECT user_id, username, password FROM users WHERE email = ?");
    $stmtCheckUser->bind_param("s", $email);
    $stmtCheckUser->execute();
    $resultCheckUser = $stmtCheckUser->get_result();
    $resultCheckUserData = $resultCheckUser->fetch_assoc();



    // Si l'utilisateur existe et le mot de passe correspond
    if ($resultCheckUser->num_rows > 0 && password_verify($password, $resultCheckUserData['password'])) {
        // Commencer une session
        session_start();
        // Stocker l'ID de l'utilisateur dans la session
        $_SESSION['user_id'] = $resultCheckUserData['user_id'];
        $_SESSION['username'] = $resultCheckUserData['username'];
        $_SESSION['email'] = $email;


        // Rediriger vers la page d'accueil ou une autre page sécurisée
        header('Location: ../user/');
        exit();
    } else {
        // Rediriger vers le formulaire de connexion avec un message d'erreur
        header('Location: login.php?error=1');
        exit();
    }
}

// Fermer la connexion à la base de données
$databaseManager->closeConnection();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Flip App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../_assets/css/styles.css">
</head>

<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <br>
    <?php
    // Vérifier si le paramètre 'error' existe dans l'URL
    if (isset($_GET['error'])) {
        // Afficher un message d'erreur
        echo '<section id="error-section">';
        echo '<p class="error-message">Email ou mot de passe incorrect.</p>';
        echo '<p>Vous n\'avez pas de compte ? <a href="inscription.php">Inscrivez-vous ici</a>.</p>';
        echo '</section>';
    }
    ?>
    <br>
    <nav>
        <ul>
            <li><a href="#section1"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
            <li><a href="#section3"><i class="fas fa-link"></i> Liens</a></li>
            <li><a href="inscription.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
        </ul>
    </nav>
    <br>
    <section id="section2">
        <h2>Bienvenue sur sur Flip App</h2>
        <!--div class="gallery">
            <img src="../_assets/img/flipapp/10.png" alt="@FLIP.APP">
        </div-->
    </section>
    <hr>
    <section id="section1">
        <h2>Connexion</h2>
        <form action="#" method="POST">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Entrez votre nom d'utilisateur" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

            <button type="submit">Se Connecter</button>

            <!-- Bouton de redirection vers inscription.php -->
            <a href="inscription.php" class="redirect-button">Non membre ? Inscris-toi vite par ici !</a>
        </form>
    </section>
    <hr>
    <section id="section3">
        <div class="image-container">
            <img src="../_assets/img/flipapp/10.png" alt="Image 1">
        </div>
        <hr>
        <ul>
            <li><a href="#">Découvrir</a></li>
            <li><a href="#">Applications</a></li>
            <li><a href="#">Langue</a></li>
            <li><a href="#">Politique de confidentialité</a></li>
            <li><a href="#">API</a></li>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">Assistance</a></li>
            <li><a href="#">Termes et conditions</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </section>
    <br>
    <footer>
        <p>&copy; 2024 Flip App by Dadflip Solutions. Tous droits réservés.</p>
    </footer>

</body>

</html>
