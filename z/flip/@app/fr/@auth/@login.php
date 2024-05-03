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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../public/css/style.css">
  <link rel="stylesheet" href="../../public/css/bck-vid-style.css">
</head>

<body>
  <div class="preloader">
      <img src="../../public/img/app/ico/flip.png" alt="preloader">
  </div>

  <video class="background-video" muted autoplay loop src="../../public/video/background-video.mp4"></video>

  <?php
  // Vérifier si le paramètre 'error' existe dans l'URL
  if (isset($_GET['error'])) {
      // Afficher un message d'erreur
      echo '<section id="error-section">';
      echo '<p class="error-message">Email ou mot de passe incorrect.</p>';
      echo '<p>Vous n\'avez pas de compte ? <a href="@sign-in.php">Inscrivez-vous ici</a>.</p>';
      echo '</section>';
  }
  ?>
  <br>
  <section id="section2">
    <h2 id="text" data-text="Welcome on @flipxe app">Starting...</h2>
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
          <a href="@sign-in.php" class="redirect-button">Non membre ? Inscris-toi vite par ici !</a>
      </form>
  </section>
  <hr>
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

  <script type="text/javascript">
    const textEl = document.getElementById('text');
    const text = textEl.getAttribute('data-text');
    let idx = 1;
    const speed = 250; // Fixer la vitesse à 250 millisecondes

    writeText();

    function writeText() {
        textEl.innerText = text.slice(0, idx);

        idx++;

        if(idx > text.length) {
            idx = 1;
        }

        setTimeout(writeText, speed);
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
