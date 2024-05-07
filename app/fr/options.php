<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['RightPanel']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

$success = $cuicui_manager->changeUserTheme();

if (!$success && $cuicui_manager->getError() == ErrorTypes::SessionError) {
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}

$user_info = $cuicui_sess->getUserInfoAndSettings($_SESSION["UID"]);

if ($user_info == NULL && $cuicui_manager->getError() == ErrorTypes::NoConnection) {
    die("No SQL connection could be established");
}

// Inclure les fichiers nécessaires et initialiser les objets nécessaires

if(isset($_POST['submit'])){
    // Récupérer toutes les variables POST du formulaire
    $theme = $_POST['theme'];
    $lang = $_POST['lang'];
    echo json_encode($_POST['lang']);
    $username = $_POST['username-input'];
    $email = $_POST['email-input'];
    $newPassword = $_POST['change-password'];

    // Utiliser les fonctions setters de $user_info pour définir les valeurs
    $user_info->setUsername($username);
    $user_info->setEmail($email);
    $user_info->setTheme($theme);
    $user_info->setLang($lang);
    $user_info->setPassword($newPassword);

    // Insérer les informations de l'utilisateur dans la base de données
    $success = $user_info->updateUserInfoAndSettings($cuicui_manager->getConn());

}

if(isset($_POST['submit-more'])){
    // Reconstruire le tableau settingsArray avec les valeurs modifiées du deuxième formulaire
    $settingsArray = [
        'notifications' => [
            'email' => true, // Notifications par e-mail activées ou désactivées
            'push' => true // Notifications push activées ou désactivées
        ],
        'privacy' => [
            'post_visibility' => 'friends' // Visibilité des publications (public, privé, amis seulement, etc.)
        ],
        'other_preferences' => [
            'autoplay_videos' => false, // Lecture automatique des vidéos activée ou désactivée
            'show_online_status' => true // Afficher le statut en ligne activé ou désactivé
        ],
        'additional_info' => [
            'fullname' => $_POST['fullname-input'],
            'bio_extended' => $_POST['bio-extended-input'],
            'location' => $_POST['location-input'],
            'social_links' => $_POST['social-links-input'],
            'occupation' => $_POST['occupation-input'],
            'interests' => $_POST['interests-input'],
            'languages_spoken' => $_POST['languages-input'],
            'relationship_status' => $_POST['relationship-status-input'],
            'birthday' => $_POST['birthday-input'],
            'privacy_settings' => isset($_POST['privacy-settings']) ? $_POST['privacy-settings'] : 0 // Si la case à cocher n'est pas cochée, la valeur par défaut est 0
        ]
    ];

    $user_info->setSettingsArray($settingsArray);

    // Insérer les informations de l'utilisateur dans la base de données
    $success = $user_info->updateUserInfoAndSettings($cuicui_manager->getConn());

}

?>


<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Options</title>
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Structure de la fenêtre flottante -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="fermerPopup()">&times;</span>
            <p>L'utilisateur n'existe pas</p>
        </div>
    </div>

    <style>
        /* Style de la fenêtre flottante */
        .popup {
            display: none; /* Caché par défaut */
            position: fixed;
            z-index: 9999;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #ff3030;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .popup p {
            color: black;
        }

        /* Style du contenu de la fenêtre flottante */
        .popup-content {
            max-width: 400px;
        }

        /* Style du bouton de fermeture */
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: black;
        }

        .close:hover {
            color: red;
        }

        /* Style pour les éléments checked dans le formulaire de sélection de thème */
        input[type="radio"]:checked + label {
            font-weight: bold; /* Par exemple, changer le style du texte en gras */
            background-color: #ff3030;
        }

        /* Style pour les éléments checked dans le formulaire de sélection de langue */
        input[type="radio"]:checked + label {
            font-weight: bold; /* Par exemple, changer le style du texte en gras */
            background-color: #ff3030;
        }

    </style>

    <script>
        // Fonction pour ouvrir la fenêtre flottante
        function ouvrirPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        // Fonction pour fermer la fenêtre flottante
        function fermerPopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>

    <?php
    echo createTitleBar("Mon profil");

    if (isset($_GET["userexists"])) {
        // Afficher la fenêtre flottante
        echo '<script>ouvrirPopup();</script>';
    }
    
    ?>

    <main class="container">
        <div class="main-content">
            <div class="options-container">
                <div class="user-info">
                    <div class="uid-name">
                        <?php
                            $avatarPath = $appdir['PATH_IMG_DIR'] . $user_info->getAvatar();

                            // Vérifier si le fichier existe
                            if (file_exists($avatarPath)) {
                                // Utiliser l'avatar de l'utilisateur
                                $avatarUrl = $avatarPath;
                            } else {
                                // Utiliser l'image de placeholder par défaut
                                $avatarUrl = $appdir['PATH_IMG_DIR'] . '/placeholder.png';
                            }
                        ?>
                        <img src="<?php echo $avatarUrl; ?>" class="user-pfp clickable" id="user-pfp">
                        <div class="usernames">
                            <h1 class="username"><?php echo $user_info->getUsername(); ?></h1>
                            <h2 class="uid"><?php echo $user_info->getHandle(); ?></h2>
                        </div>
                    </div>
                    <div class="bio-zone">
                        <label for="biography">Biographie:</label>
                        <textarea id="bio-field" class="biography" rows="5" name="biography"><?php echo $user_info->bio ?></textarea>
                    </div>
                </div>
                <div class="forms">
                    <div class="form-row">
                        <form method="post" action="#" class="user-info-change" id="info-change">
                            <fieldset>
                                <legend>Changer de thème</legend>
                                <input type="radio" value="dark" id="dark" name="theme" class="radio-button theme-button" <?php echo ($user_info && $user_info->getTheme() == "dark") ? "checked" : ""; ?>>
                                <label for="dark">Sombre</label>
                                <input type="radio" value="blue" id="blue" name="theme" class="radio-button theme-button" <?php echo ($user_info && $user_info->getTheme() == "blue") ? "checked" : ""; ?>>
                                <label for="blue">Bleu</label>
                                <input type="radio" value="light" id="light" name="theme" class="radio-button theme-button" <?php echo ($user_info && $user_info->getTheme() == "light") ? "checked" : ""; ?>>
                                <label for="light">Clair</label>
                            </fieldset>

                            <fieldset>
                                <legend>Changer la langue de l'interface</legend>
                                <input type="radio" value="fr" id="fr" name="lang" class="radio-button" <?php echo ($user_info && $user_info->getLang() == "fr") ? "checked" : ""; ?>>
                                <label for="fr">Français</label>
                                <input type="radio" value="en" id="en" name="lang" class="radio-button" <?php echo ($user_info && $user_info->getLang() == "en") ? "checked" : ""; ?>>
                                <label for="en">Anglais</label>
                            </fieldset>

                            <fieldset>
                                <legend>Changer vos informations</legend>
                                <label for="username-input">Pseudonyme</label>
                                <input name="username-input" type="text" value=<?php echo $user_info->getUsername(); ?> required minlength="4" maxlength="30" autocomplete="off">
                                <label for="email-input">Adresse E-mail</label>
                                <input name="email-input" type="email" value=<?php echo $user_info->getEmail(); ?> required maxlength="50">
                                <label for="profile-picture-input" class="custom-file-upload">Changer de photo de profil</label>
                                <input name="profile-picture-input" type="file" accept="image/png, image/jpeg" id="change-pfp">
                                <label for="change-password">Changer le mot de passe</label>
                                <input type="password" name="change-password" id="change-password" value=<?php echo $user_info->getPassword(); ?> >
                            </fieldset>
                            <input type="submit" name="submit" value="Continuer" id="submit-button"> 
                        </form>
                    </div>

                   
                </div>
                <?php
                if (!$success) {
                    echo "<p class='error-msg'>";
                    if ($cuicui_manager->getError() == ErrorTypes::UndefinedTheme) {
                        echo "Le thème indiqué n'existe pas";
                    }
                    echo "</p>";
                }
                ?>
            </div>
            <?php
            if (!$success) {
                echo "<p class='error-msg'>";
                if ($cuicui_manager->getError() == ErrorTypes::UndefinedTheme) {
                    echo "Le thème indiqué n'existe pas";
                }
                echo "</p>";
            }
            ?>

            <div class="right-panel wow animate__fadeInRight" data-wow-duration="1s" data-wow-delay="2s">
                <section id="right-links">

                    <ul>
                        <?php echo getNavbarContents() ?>
                        <li><a href="#">Découvrir</a></li>
                        <li><a href="#">Langue</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>">API</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Assistance</a></li>
                        <li><a href="#">Termes et conditions</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>

                    <p> &copy; Cuicui App 2024</p>
                </section>
            </div>
        </div>
    </main>
</body>


<?php echo generateProfileOptionsPanel($_SESSION['username'], $user_info->getSettingsArray()); ?>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/options.js" ?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>

</html>