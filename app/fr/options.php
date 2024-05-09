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
if (isset($_POST['submit'])) {
    $theme = $_POST['theme'];
    $lang = $_POST['lang'];
    $username = $_POST['username-input'];
    $email = $_POST['email-input'];
    $newPassword = $_POST['change-password'];

    $user_info->setUsername($username);
    $user_info->setEmail($email);
    $user_info->setTheme($theme);
    $user_info->setLang($lang);
    $user_info->setPassword($newPassword);

    $success = $user_info->updateUserInfoAndSettings($cuicui_manager->getConn());

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile-picture-input"]) && $_FILES["profile-picture-input"]["error"] == UPLOAD_ERR_OK) {
        $profilePic = $_FILES['profile-picture-input'];
        $cuicui_manager->changeProfileImage($user_info->getUsername(), $user_info->getID(), $profilePic);
    }
}
if (isset($_POST['submit-more'])) {
    $settingsArray = [
        'notifications' => [
            'push' => isset($_POST['notifications']) ? $_POST['notifications'] : 0
        ],
        'privacy' => [
            'post_visibility' => 'friends'
        ],
        'other_preferences' => [
            'autoplay_videos' => false,
            'show_online_status' => true
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
            'privacy_settings' => isset($_POST['privacy-settings']) ? $_POST['privacy-settings'] : 0
        ]
    ];

    $user_info->setSettingsArray($settingsArray);
    $success = $user_info->updateUserInfoAndSettings($cuicui_manager->getConn());
}
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <title>Paramètres | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    </head>

    <body>
        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="fermerPopup()">&times;</span>
                <p>L'utilisateur n'existe pas</p>
            </div>
        </div>

        <script>
            function ouvrirPopup() {
                document.getElementById('popup').style.display = 'block';
            }

            function fermerPopup() {
                document.getElementById('popup').style.display = 'none';
            }
        </script>

        <?php
        echo createTitleBar("@Profil");

        if (isset($_GET["userexists"])) {
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
                            $avatarUrl = $avatarPath;
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
                            <form method="post" action="#" class="user-info-change" id="info-change" enctype="multipart/form-data">
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
                                    <input type='file' name='profile-picture-input' id="profile-picture-input" accept="image/*">
                                    <label for="change-password">Changer le mot de passe</label>
                                    <input type="password" name="change-password" id="change-password" value=<?php echo $user_info->getPassword(); ?>>
                                </fieldset>
                                <input type="submit" name="submit" value="Continuer" id="submit-button">
                            </form>

                            <a href="<?php echo $GLOBALS['normalized_paths']['PATH_MODULES'] . $GLOBALS['php_files']['deleteAccount']; ?>">Supprimer mon compte</a>
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

                <button id="toggle-right-panel">☰</button>
                <div class="right-panel">
                    <section id="right-links">
                        <ul>
                            <?php echo getNavbarContents() ?>
                            <hr class="links-separator">
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/discover/starter.php' ?>"><i class="fas fa-search"></i> Découvrir</a></li>
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/cuicui/ourpolicy.php' ?>"><i class="fas fa-lock"></i> Politique de confidentialité</a></li>
                            <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>"><i class="fas fa-cogs"></i> API</a></li>
                        </ul>
                        <p>&copy; Cuicui App 2024</p>
                    </section>
                </div>
            </div>
        </main>
    </body>


    <?php echo generateProfileOptionsPanel($_SESSION['username'], $user_info->getSettingsArray()); ?>
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>

</html>