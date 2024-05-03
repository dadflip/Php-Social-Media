<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

$success = $cuicui_manager->changeUserTheme();

if(!$success && $cuicui_manager->getError() == ErrorTypes::SessionError) {
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}

$user_info = $cuicui_sess->getUserInfo($_SESSION["UID"]);

if($user_info == NULL && $cuicui_manager->getError() == ErrorTypes::NoConnection) {
    die("No SQL connection could be established");
}

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Options</title>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css"?> >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
    echo createTitleBar("Mon profil");
    ?>
    <div class="main-content">
        <div class="options-container">
            <div class="user-info">
                <div class="uid-name">
                    <img src=<?php echo $user_info->getProfilePicture();?>  class="user-pfp clickable" id="user-pfp">
                    <div class="usernames">
                        <h1 class="username"><?php echo $user_info->getUsername();?></h1>
                        <h2 class="uid"><?php echo $user_info->getHandle();?></h2>
                    </div>
                </div>
                <label for="biography">Biographie:</label>
                <textarea class="biography" rows="5" name="biography"><?php echo $user_info->bio?></textarea>
            </div>
            <div class="forms">
                <form method="get" class="theme-select" id="theme-select">
                    <fieldset>
                        <legend>Changer de thème</legend>
                        <input type="radio" value="dark" id="dark" name="theme" class="radio-button theme-button">
                        <label for="dark">Sombre</label>
                        <input type="radio" value="blue" id="blue" name="theme" class="radio-button theme-button">
                        <label for="blue">Bleu</label>
                        <input type="radio" value="light" id="light" name="theme" class="radio-button theme-button">
                        <label for="light">Clair</label>
                    </fieldset>
                </form>
                <form method="get" action="#" class="lang-select">
                    <fieldset>
                        <legend>Changer la langue de l'interface</legend>
                        <input type="radio" value="fr" id="fr" name="lang" class="radio-button">
                        <label for="fr">Français</label>
                        <input type="radio" value="en" id="en" name="lang" class="radio-button">
                        <label for="en">Anglais</label>
                    </fieldset>
                </form>
                <form method="post" class="user-info-change" id="info-change"> 
                    <fieldset>
                        <legend>Changer vos informations</legend>
                        <label for="username-input">Pseudonyme</label>
                        <input name="username-input" type="text" value=<?php echo $user_info->getUsername();?> required minlength="4" maxlength="30" autocomplete="off">
                        <label for="email-input">Adresse E-mail</label>
                        <input name="email-input" type="email" value=<?php echo $user_info->getUsername();?> required maxlength="50">
                        <label for="profile-picture-input" class="custom-file-upload" >Changer de photo de profil</label>
                        <input name="profile-picture-input" type="file" accept="image/png, image/jpeg" id="change-pfp">
                        <label for="change-birthday">Changer la date de naissance</label>
                        <input type="date" name="change-birthday" id="change-birthday" value="2003-09-12">
                    </fieldset>
                </form>
            </div>
            <?php
            if(!$success) {
                echo "<p class='error-msg'>";
                if($cuicui_manager->getError() == ErrorTypes::UndefinedTheme) {
                    echo "Le thème indiqué n'existe pas";
                }
                echo "</p>";
            }
            ?>
            <button type="submit">Changer le profil</button>
        </div>
    </div>
    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/options.js"?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>

</html>
