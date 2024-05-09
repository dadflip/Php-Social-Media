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

if (isset($_GET["@userid"])) {
    $usernameWithAt = $_GET["@userid"];
    $username = substr($usernameWithAt, 1);

    if ($username == $_SESSION["username"]) {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['options']);
        exit();
    }
}

$user_info = $cuicui_sess->getUserInfoAndSettings($cuicui_manager->getIdByUsername_ToString($username));
$settings = $user_info->getSettingsArray();
$follow_res = $cuicui_manager->getFollow($_SESSION["UID"], $username);
$user_info = $cuicui_manager->getUserInfoByName($username);

if (isset($user_info)) {
    $user_posts = $cuicui_manager->getUserPosts($user_info->getID());

    $user_statistics = $cuicui_manager->getUserStatistics($user_info->getID());
} else {
    header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['options'] . '?userexists=false');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Cuicui - Utilisateur</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css" ?>>
    </head>

    <body>
        <?php
        echo createTitleBar("User profile");
        ?>

        <main class="container">
            <div class="main-content">
                <div class="options-container">
                    <?php if ($settings['additional_info']['privacy_settings'] || $cuicui_manager->isUserAdmin($_SESSION['UID'])) { ?>
                        <?php if ($user_info != NULL) { ?>
                            <div class="user-info">
                                <?php if($cuicui_manager->isUserAdmin($_SESSION['UID'])) { ?>
                                    <p> @Profil privé, Accès Admin Autorisé</p>
                                <?php } ?>

                                <h3>Informations sur <?php echo $user_info->getUsername(); ?></h3>
                                <p>Nom d'utilisateur : <?php echo $user_info->getUsername(); ?></p>
                                <?php
                                $avatarPath = $appdir['PATH_IMG_DIR'] . $user_info->getAvatar();

                                if (file_exists($avatarPath)) {
                                    $avatarUrl = $avatarPath;
                                } else {
                                    $avatarUrl = $appdir['PATH_IMG_DIR'] . '/placeholder.png';
                                }
                                ?>

                                <div class="user-section">
                                    <img src="<?php echo $avatarUrl; ?>" alt="Avatar de <?php echo $user_info->getUsername(); ?>" class="profile-picture">
                                    <p>Biographie : <?php echo $user_info->getBiography(); ?></p>

                                    <button id="followButton" class="followButton" onclick="<?php echo ($follow_res ? 'unfollowUser(' . $user_info->getID() . ')' : 'followUser(' . $user_info->getID() . ')'); ?>">
                                        <?php
                                        if ($follow_res) {
                                            echo "arrêter de suivre";
                                        } else {
                                            echo "suivre";
                                        }
                                        ?>
                                    </button>
                                </div>

                                <div class="admin-section">
                                    <?php if ($_SESSION["isAdmin"] == "1") { ?>
                                        <button class="clickable cuicui-button admin" onclick="warnUser()">
                                            Avertir cet utilisateur
                                        </button>
                                        <button class="clickable cuicui-button admin" onclick="showBanForm()">
                                            Bannir cet utilisateur
                                        </button>
                                    <?php } ?>

                                    <div id="banForm" style="display: none;">
                                        <form id="banUserForm">
                                            <label for="banDuration">Durée du ban (en heures, jours, ou minutes):</label>
                                            <input type="text" id="banDuration" name="banDuration" value=" 1j 2h 0m 0s" required>

                                            <label for="banReason">Motif du ban:</label>
                                            <textarea id="banReason" name="banReason" required></textarea>

                                            <button type="submit" class="clickable cuicui-button admin">
                                                Bannir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="user-posts">
                            <h2>Publications de <?php echo $user_info->getUsername(); ?></h2>
                            <?php
                            if ($user_posts != NULL) {
                                foreach ($user_posts as $post) {
                                    echo "<div class='user-post'>";
                                    echo "<p>" . $post['text_content'] . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Cet utilisateur n'a pas encore publié de messages.</p>";
                            }
                            ?>
                        </div>

                        <div class="user-stats">
                            <h2>Statistiques de <?php echo $user_info->getUsername(); ?></h2>
                            <?php
                            echo "<fieldset>";
                            echo "<legend>Statistiques</legend>";
                            echo "<p>Nombre de followers : " . $user_statistics['follower_count'] . "</p>";
                            echo "<p>Nombre de publications : " . $user_statistics['post_count'] . "</p>";
                            echo "</fieldset>";
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="private-profile">
                            <p>
                                Cet Utilisateur a désactivé la visibilité sur son profil !
                            </p>
                        </div>
                    <?php }  ?>
                </div>

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

        <script>
            function showBanForm() {
                document.getElementById("banForm").style.display = "block";
            }

            document.getElementById("banUserForm").addEventListener("submit", function(event) {
                event.preventDefault();

                const userID = <?php echo $user_info->getID(); ?>;
                const adminID = <?php echo $_SESSION["UID"]; ?>;
                const duration = document.getElementById("banDuration").value;
                const reason = document.getElementById("banReason").value;

                $.ajax({
                    url: window.__ajx__ + "banuser.php",
                    method: "POST",
                    data: {
                        userID,
                        adminID,
                        duration,
                        reason
                    },
                    success: function(response) {
                        if (response === "ok") {
                            alert(`User with id ${userID} banned successfully`);
                        } else if (response === "unset") {
                            console.error("An error occurred while trying to ban this user");
                        } else {
                            alert(response);
                        }
                        console.log(response);
                    },
                    error: function(error) {
                        console.error(`Error while banning ${userID}`, error);
                    }
                });
            });

            function warnUser() {
                <?php
                $cuicui_manager->warnUser($user_info->getID(), $_SESSION["UID"], "You received a warn from admins");
                ?>
            }
        </script>
    </body>

    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>