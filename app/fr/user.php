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

// Vérifier si le paramètre "@userid" est défini dans l'URL
if (isset($_GET["@userid"])) {
    // Récupérer la valeur du paramètre "@userid"
    $usernameWithAt = $_GET["@userid"];
    $username = substr($usernameWithAt, 1); // Supprimer le premier caractère "@" du nom d'utilisateur

    if ($username == $_SESSION["username"]) {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['options']);
        exit();
    }

    // Utiliser la valeur récupérée comme vous le souhaitez, par exemple l'afficher
    // echo "Nom d'utilisateur : " . $username;
} else {
    // Afficher un message d'erreur si le paramètre "@userid" n'est pas défini dans l'URL
    // echo "L'identifiant d'utilisateur n'est pas défini dans l'URL";
}

$follow_res = $cuicui_manager->getFollow($_SESSION["UID"], $username);

// Récupérer les informations sur l'utilisateur
$user_info = $cuicui_manager->getUserInfoByName($username);

if(isset($user_info)) {
    // Récupérer les publications de l'utilisateur depuis la base de données
    $user_posts = $cuicui_manager->getUserPosts($user_info->getID());

    // Récupérer les statistiques de l'utilisateur depuis la base de données
    $user_statistics = $cuicui_manager->getUserStatistics($user_info->getID());
} else {
    header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['options'] . '?userexists=false');
    exit();
}
?>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Utilisateur</title>
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href=<?php echo "../../css/" . $_SESSION["theme"] . ".css" ?>>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/options.css">
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Styles pour les nouvelles sections */
        .user-posts,
        .user-stats {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .user-posts h2,
        .user-stats h2 {
            margin-top: 0;
        }

        .user-post {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid;
            margin: 1em;
        }

        .profile-picture {
            width: 150px;
            /* Définir la largeur */
            height: 150px;
            /* Définir la hauteur */
            border-radius: 50%;
            /* Rendre l'image ronde */
            object-fit: cover;
            /* Ajuster la taille de l'image */
            border: 2px solid #ffffff;
            /* Ajouter une bordure */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Ajouter une ombre */
            margin-bottom: 20px;
            /* Ajouter une marge en bas */
        }

        .admin-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            column-gap: 1em;
            align-items: stretch;
            justify-items: center;
        }

        button.clickable.cuicui-button.admin {
            background-color: #5100c1;
            border: none;
            border-radius: 1em;
        }

        form#banUserForm {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            align-items: stretch;
            align-content: center;
        }

        textarea#banReason {
            resize: none;
        }
    </style>
</head>

<body>
    <?php
    echo createTitleBar("User profile");
    ?>

    <main class="container">
        <div class="main-content">
            <div class="options-container">
                <!-- Afficher les informations sur l'utilisateur -->
                <?php if ($user_info != NULL) { ?>
                    <div class="user-info">
                        <h3>Informations sur <?php echo $user_info->getUsername(); ?></h3>
                        <p>Nom d'utilisateur : <?php echo $user_info->getUsername(); ?></p>
                        <!-- Ajoutez d'autres informations sur l'utilisateur si nécessaire -->
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

                        <img src="<?php echo $avatarUrl; ?>" alt="Avatar de <?php echo $user_info->getUsername(); ?>" class="profile-picture">

                        <p>Biographie : <?php echo $user_info->getBiography(); ?></p>

                        <button id="followButton" class="followButton" onclick="<?php echo ($follow_res ? 'unfollowUser(' . $user_info->getID() . ')' : 'followUser(' . $user_info->getID() . ')'); ?>">
                            <?php 
                            if($follow_res) {
                                echo "arrêter de suivre";
                            } else {
                                echo "suivre";
                            }
                            ?>
                        </button>

                        <div class="admin-section">
                            <?php if($_SESSION["isAdmin"] == "1") { ?>
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
                                    <input type="text" id="banDuration" name="banDuration" required>

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


                <!-- Section pour afficher les publications de l'utilisateur -->
                <div class="user-posts">
                    <h2>Publications de <?php echo $user_info->getUsername(); ?></h2>
                    <?php
                    // Vérifier si l'utilisateur a des publications
                    if ($user_posts != NULL) {
                        // Afficher les publications de l'utilisateur
                        foreach ($user_posts as $post) {
                            // Afficher les détails de chaque publication
                            echo "<div class='user-post'>";
                            echo "<p>" . $post['text_content'] . "</p>"; // Afficher le contenu de la publication
                            // Ajoutez d'autres détails de publication si nécessaire
                            echo "</div>";
                        }
                    } else {
                        // Afficher un message si l'utilisateur n'a pas de publications
                        echo "<p>Cet utilisateur n'a pas encore publié de messages.</p>";
                    }
                    ?>
                </div>


                <!-- Section pour afficher les statistiques de l'utilisateur -->
                <div class="user-stats">
                    <h2>Statistiques de <?php echo $user_info->getUsername(); ?></h2>
                    <?php
                    // Afficher les statistiques de l'utilisateur
                    echo "<fieldset>";
                    echo "<legend>Statistiques</legend>";
                    echo "<p>Nombre de followers : " . $user_statistics['follower_count'] . "</p>";
                    echo "<p>Nombre de publications : " . $user_statistics['post_count'] . "</p>";
                    // Ajoutez d'autres statistiques si nécessaire
                    echo "</fieldset>";
                    ?>
                </div>
            </div>


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

<script>
    window.__ajx__ = "<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/'; ?>";
</script>
<script>
    window.__u__ = <?php echo $_SESSION['UID']; ?>
</script>

<script>
    function showBanForm() {
        document.getElementById("banForm").style.display = "block";
    }

    document.getElementById("banUserForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const userID = <?php echo $user_info->getID();?>;
        const adminID = <?php echo $_SESSION["UID"];?>;
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
                if(response === "ok") {
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

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js" ?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>

</html>