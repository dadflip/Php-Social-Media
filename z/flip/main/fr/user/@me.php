<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --------------------------- include --------------------------------

include('../authentication/functions.php');

// ---------------------- database manager ----------------------------

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Affecter le premier sous-tableau de $database_configs à $db_flipapp
$db_flipapp = $database_configs[0];

// Initialiser la connexion à la base de données flipapp
$databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

// Sélectionner la base de données
$databaseManager->selectDatabase($db_flipapp['name']);

// ---------------------------------------------------------------------

if(!isset($_SESSION['user_id']) &&  !isset($_SESSION['email'])){
    header('Location:' . path_FLIP_APP);
    exit();
}else{
    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">

    <title>User Profile | Flip App</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!--link rel="stylesheet" href="../_assets/css/app.css"-->
    <!--link rel="stylesheet" href="../_assets/css/flipapp.css"-->
    <link rel="stylesheet" href="../_assets/css/flip.style.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <header>
        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="modalImg">
        </div>
    </header>

    <div class="header">
        <div class="user-info-bar">
            <!-- Display user info -->
            <span class="user-name"><?php echo isset($userName) ? strtoupper($userName) : "Guest"; ?></span>
        </div>

        <div class="topnav">
            <a href="/dadflip/">APPLICATIONS</a>
            <!-- Link to user profile -->
            <a href="@me.php?usr=<?php echo isset($userId) ? $userId : ""; ?>">PROFIL</a>
            <a href="#">PARAMETRES</a>
            <a class="split" href="exit.php">DECONNEXION</a>

            <div class="search-bar">
                <input id="barre-recherche" type="text" placeholder="SEARCH BOX : What is your request?" onkeydown="handleKeyPress(event)">
                <div class="search-icon" onclick="effectuerRecherche()">&#10147;</div>
            </div>
        </div>
    </div>

    <div class="notifications">
        <h2>Notifications</h2>
        <ul>
            <li><a href="#">Notification 1</a></li>
            <li><a href="#">Notification 2</a></li>
            <li><a href="#">Notification 3</a></li>
            <!-- Additional notifications here -->
        </ul>
    </div>

    <main>
        <div class="container">
            <?php include 'nav.php'; ?>
            <div class="center-column">
                <div class="center-main-panel">
                    <div class="center-panel">
                        <!-- Tabs for different sections -->
                        <div class="tabs-container">
                            <div class="tab active" data-tab="tab1" onclick="showTab('tab1')">
                                <i class="fas fa-box-open"></i> FlipBoxes
                            </div>
                            <div class="tab" data-tab="tab2" onclick="showTab('tab2')">
                                <i class="fas fa-comments"></i> Commentaires
                            </div>
                            <div class="tab" data-tab="tab3" onclick="showTab('tab3')">
                                <i class="fas fa-users"></i> Amis
                            </div>
                            <br>
                        </div>

                        <!-- Buttons for different functionalities -->
                        <button onclick="toggleForm()" type="button" class="page_button1" title="FLIPBOX">
                            <i class="fas fa-plus"></i>
                        </button>

                        <button onclick="addMedia()" type="button" class="social_button" title="Ajouter des médias">
                            <i class="fas fa-camera"></i>
                        </button>

                        <button onclick="postImage()" type="button" class="social_button" title="Poster une image">
                            <i class="fas fa-image"></i>
                        </button>

                        <button onclick="recordAudio()" type="button" class="social_button" title="Enregistrer du son">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <!-- Additional buttons here -->
                    </div>

                    <!-- Tab contents -->
                    <div id="tab1" class="tab-content">
                        <div id="resultats" class="resultats"><br></div>
                    </div>

                    <div id="tab2" class="tab-content" style="display: none;">
                        Contenu des Commentaires
                    </div>

                    <div id="tab3" class="tab-content" style="display: none;">
                        Contenu des Amis
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating form -->
    <div class="floating-form" id="newTopicForm">
        <i class="fas fa-times close-icon" onclick="toggleForm()"></i>
        <form action=".php/add_flipbox.php" method="post" enctype="multipart/form-data">
            <label for="title">Titre :</label>
            <input type="text" name="title" required><br>

            <label for="content">Contenu :</label>
            <textarea name="content" required></textarea><br>

            <label for="category">Catégorie :</label>
            <select name="category" required>
                <option value="Particulier">Particulier</option>
                <option value="Entreprise">Entreprise</option>
                <!-- Additional categories here -->
            </select><br>

            <label for="keywords">Mots-clés (séparés par des virgules) :</label>
            <input type="text" name="keywords"><br>

            <!-- Upload image -->
            <label for="image">Uploader une image :</label>
            <input type="file" name="image" accept="image/*"><br>

            <!-- Upload video -->
            <label for="video">Uploader une vidéo :</label>
            <input type="file" name="video" accept="video/*"><br>

            <input type="submit" value="FLIP ➯">
        </form>
    </div>

    <div class="right-panel">
        <!-- Additional content for right panel if any -->
    </div>

    <div class="footer">
        <center>
            <div class="main-logo-container">
                <img src="../_assets/img/flipapp/10.png" alt="Image 1">
            </div>
        </center>
    </div>

    <footer>
        <section id="section3">
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

            <p> &copy; Dadflip Solutions 2024</p>
        </section>
    </footer>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</html>
