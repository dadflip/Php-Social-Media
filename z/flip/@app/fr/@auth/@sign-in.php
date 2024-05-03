<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../.configs.php');
include('../../../lib/include/php/Database/DatabaseManager.php');

// Constantes pour les messages d'erreur
define('ERROR_PASSWORD', "Le mot de passe doit contenir au moins 8 caractères, incluant des chiffres, des lettres et des caractères spéciaux.");
define('ERROR_PASSWORD_MATCH', "Les mots de passe ne correspondent pas.");
define('ERROR_EMAIL_EXIST', "Cette adresse e-mail est déjà utilisée.");
define('ERROR_INVALID_EMAIL', "L'adresse e-mail n'est pas valide.");
define('ERROR_USERNAME_EXIST', "Ce nom d'utilisateur est déjà pris.");
define('ERROR_SEND_EMAIL', "Erreur lors de l'envoi de l'e-mail de vérification.");
define('SUCCESS_SEND_EMAIL', "E-mail de vérification envoyé avec succès.");

// Fonction pour générer un ID utilisateur à 10 chiffres
function generateUserId() {
    return mt_rand(10000000, 99999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Vérifier si les variables POST sont définies
        if (isset($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['confirm-password'])) {

            // Récupération des données du formulaire
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            // Validation du mot de passe
            if (!isValidPassword($password)) {
                echo ERROR_PASSWORD;
                exit();
            }

            // Vérifier si les mots de passe correspondent
            if ($password !== $confirmPassword) {
                echo ERROR_PASSWORD_MATCH;
                exit();
            }

            // Instancier un objet de la classe DatabaseManager avec les informations de connexion
            $databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

            // Affecter le premier sous-tableau de $database_configs à $db_flipapp
            $db_flipapp = $database_configs[0];

            // Initialiser la connexion à la base de données flipapp
            $databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

            // Sélectionner la base de données
            $databaseManager->selectDatabase($db_flipapp['name']);

            // Vérifier si l'adresse e-mail existe déjà
            $checkEmailQuery = "SELECT COUNT(*) as count FROM users WHERE email = ?";
            $stmtCheckEmail = $databaseManager->getAdminConnection()->prepare($checkEmailQuery);
            $stmtCheckEmail->bind_param("s", $email);
            $stmtCheckEmail->execute();
            $checkEmailResult = $stmtCheckEmail->get_result();
            $row = $checkEmailResult->fetch_assoc();

            if ($row['count'] > 0) {
                echo ERROR_EMAIL_EXIST;
                exit();
            }

            // Validation de l'adresse e-mail
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo ERROR_INVALID_EMAIL;
                exit();
            }

            // Vérifier si le nom d'utilisateur existe déjà
            $checkUsernameQuery = "SELECT COUNT(*) as count FROM users WHERE username = ?";
            $stmtCheckUsername = $databaseManager->getAdminConnection()->prepare($checkUsernameQuery);
            $stmtCheckUsername->bind_param("s", $username);
            $stmtCheckUsername->execute();
            $checkUsernameResult = $stmtCheckUsername->get_result();
            $row = $checkUsernameResult->fetch_assoc();

            if ($row['count'] > 0) {
                echo ERROR_USERNAME_EXIST;
                exit();
            }


            // Générer un nouvel ID utilisateur
            $userId = generateUserId();

            // Hasher le mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Récupérer l'état de la case à cocher (conditions acceptées)
            $acceptedConditions = isset($_POST['accept_conditions']) ? 1 : 0;

            // Préparer la requête pour insérer le nouvel utilisateur dans la base de données
            $stmtInsertUser = $databaseManager->getAdminConnection()->prepare("INSERT INTO users (user_id, username, email, password, accepted_conditions) VALUES (?, ?, ?, ?, ?)");
            $stmtInsertUser->bind_param("isssi", $userId, $username, $email, $hashedPassword, $acceptedConditions);
            $stmtInsertUser->execute();

            // Démarrer la session et définir les variables de session
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['userLoggedIn'] = true;

            // Rediriger vers la page principale après l'inscription
            header('Location: ../user/');
            exit();
        } else {
            // Gérer le cas où certaines variables ne sont pas définies
            echo "Toutes les variables POST ne sont pas définies.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

// Fonction de validation du mot de passe
function isValidPassword($password) {
    return strlen($password) >= 8 && preg_match("~[0-9]+~", $password) && preg_match("~[a-zA-Z]+~", $password) && preg_match("~[!@#$%^&*(),.?\":{}|<>]+~", $password);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/bck-vid-style.css">
</head>

<body>
  <div class="preloader">
    <img src="../../public/img/app/ico/flip.png" alt="preloader">
  </div>

  <video class="background-video" muted autoplay loop src="../../public/video/background-video.mp4"></video>

    <section id="inscription">
        <h2>Inscription</h2>
        <form action="#" method="post">
          <div class="column">
            <label for="firstname">Prénom</label>
            <input type="text" placeholder="Entrez votre prénom" name="firstname" required>

            <label for="lastname">Nom</label>
            <input type="text" placeholder="Entrez votre nom" name="lastname" required>

            <label for="email">Email</label>
            <input type="text" placeholder="Entrez votre email" name="email" required>
          </div>
          
          <div class="column">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" placeholder="Entrez votre nom d'utilisateur" name="username" required>

            <label for="password">Mot de passe</label>
            <input type="password" placeholder="Entrez votre mot de passe" name="password" required>

            <label for="confirm-password">Confirmer le mot de passe</label>
            <input type="password" placeholder="Confirmez votre mot de passe" name="confirm-password" required>

          </div>

          
        <label>
          <input type="checkbox" name="accept_conditions" required> J'accepte les 
          <span style="cursor: pointer; color: #ff0000; font-weight: 200;" onclick="toggleConditions()">conditions d'utilisation</span>
        </label>
          
        <button type="submit">S'inscrire</button>

        <!-- Bouton de redirection vers login.php -->
        <a href="@login.php" class="redirect-button">Déjà inscrit ? Connectez-vous ici</a>
        </form>
    </section>
    <br><br>
    <!-- Section des conditions d'utilisation -->
    <section id="conditionsContainer">
        <span class="close-button" onclick="toggleConditions()">&times;</span>
        <container>
            <h2>Conditions d'utilisation</h2>
            <button class="toggle-button" onclick="toggleSection('sectionA')">Collecte d'Informations</button>
            <div id="sectionA" class="hidden">
                <p>Voici les conditions d'utilisation de notre service :</p>

                <h1>Conditions d'utilisation et Politique de confidentialité</h1>

                <p>
                    Bienvenue sur Flip App (ci-après dénommé "nous", "notre", "nos", "Flip"). Veuillez lire attentivement
                    ces conditions d'utilisation et notre politique de confidentialité avant d'utiliser nos services.
                </p>

                <h2>1. Collecte d'informations</h2>

                <p>Nous recueillons certaines informations personnelles lorsque vous vous inscrivez sur notre site ou que vous utilisez
                    nos services. Ces informations peuvent inclure, sans s'y limiter :</p>

                <ul>
                    <li>Votre nom</li>
                    <li>Votre prénom</li>
                    <li>Votre adresse e-mail</li>
                    <li>Votre localisation</li>
                    <li>Votre activité sur le site</li>
                    <!-- Ajoutez d'autres catégories d'informations collectées -->
                </ul>
            </div>

            <button class="toggle-button" onclick="toggleSection('sectionB')">Utilisation des Informations</button>
            <div id="sectionB" class="hidden">
                <h2>2. Utilisation des informations</h2>

                <p>Nous utilisons les informations que nous collectons pour :</p>

                <ul>
                    <li>Fournir, exploiter et maintenir nos services</li>
                    <li>Améliorer, personnaliser et élargir nos services</li>
                    <li>Comprendre et analyser comment vous utilisez nos services</li>
                    <li>Communiquer avec vous, soit directement, soit par l'intermédiaire de l'un de nos partenaires</li>
                </ul>

                <!-- Ajoutez d'autres sections en fonction de l'utilisation de vos données -->
            </div>

            <button class="toggle-button" onclick="toggleSection('sectionC')">Protection des Informations</button>
            <div id="sectionC" class="hidden">
                <h2>3. Protection des informations</h2>

                <p>Nous mettons en œuvre des mesures de sécurité raisonnables pour protéger la sécurité de vos informations personnelles. Cependant, aucune méthode de transmission sur Internet ou méthode de stockage électronique n'est totalement sûre et fiable, et nous ne pouvons garantir sa sécurité absolue.</p>

                <!-- Ajoutez d'autres sections en fonction de l'utilisation de vos données -->
            </div>

            <button class="toggle-button" onclick="toggleSection('sectionD')">Vos droits</button>
            <div id="sectionD" class="hidden">
                <h2>4. Vos droits</h2>

                <p>Vous avez le droit :</p>

                <ul>
                    <li>D'accéder à vos informations personnelles</li>
                    <li>De corriger vos informations personnelles</li>
                    <li>De supprimer vos informations personnelles</li>
                    <li>De vous opposer au traitement de vos informations personnelles</li>
                </ul>

                <!-- Ajoutez d'autres droits en fonction de la législation applicable -->
            </div>

            <button class="toggle-button" onclick="toggleSection('sectionE')">Cookies</button>
            <div id="sectionE" class="hidden">
                <h2>5. Cookies</h2>

                <p>Notre site utilise des cookies. En continuant à utiliser notre site, vous consentez à notre utilisation de
                    cookies conformément à notre politique de confidentialité.</p>

                <!-- Ajoutez des détails sur l'utilisation des cookies -->

                <p>En cliquant sur "J'accepte les conditions", vous acceptez ces conditions d'utilisation.</p>
            </div>

            <button class="toggle-button" onclick="toggleConditions()">Compris !</button>

            <!-- Ajoutez d'autres boutons et div pour chaque section -->
        </container>
    </section>
    <br><br>
    <section id="section3">
        <ul>
          <li><a href="../@discover">Découvrir</a></li>
          <li><a href="#">Applications</a></li>
          <li><a href="#">Langue</a></li>
          <li><a href="#">Politique de confidentialité</a></li>
          <li><a href="#">API</a></li>
          <li><a href="../@discover/faq/">FAQ</a></li>
          <li><a href="#">Assistance</a></li>
          <li><a href="#">Termes et conditions</a></li>
          <li><a href="../../../@site">Site</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
    </section>
    <br>
      <footer>
        <p>&copy; 2024 Flip App by Dadflip Solutions. Tous droits réservés.</p>
        <button class="btn btn-secondary">
        <i class="fa fa-pause" aria-hidden="true"></i>
        </button>
      </footer>

    <script>
        // Fonction pour afficher ou masquer les conditions d'utilisation
        function toggleConditions() {
            var conditionsContainer = document.getElementById('conditionsContainer');
            conditionsContainer.style.display = conditionsContainer.style.display === 'none' ? 'block' : 'none';
        }

        function toggleSection(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === 'none') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        const btn = document.querySelector(".btn");
        const video = document.querySelector(".background-video");
        const fa = document.querySelector(".fa");
        const preloader = document.querySelector(".preloader");

        btn.addEventListener("click", () => {
          if (btn.classList.contains("pause")) {
            btn.classList.remove("pause");
            video.play();
            fa.classList.add("fa-pause");
            fa.classList.remove("fa-play");
          } else {
            btn.classList.add("pause");
            video.pause();
            fa.classList.remove("fa-pause");
            fa.classList.add("fa-play");
          }
        });

        window.addEventListener("load", () => {
          preloader.style.zIndex = "-999";
        });
    </script>
</body>

</html>