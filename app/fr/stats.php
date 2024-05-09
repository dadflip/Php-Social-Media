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


$follow_res = $cuicui_manager->getFollow($_SESSION["UID"], $_SESSION["username"]);

// Récupérer les informations sur l'utilisateur
$user_info = $cuicui_manager->getUserInfo($_SESSION["UID"]);

if(isset($user_info)) {
    $user_posts = $cuicui_manager->getUserPosts($user_info->getID());

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
    <title>@CuicuiBox - Statistics</title>
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href=<?php echo "../../css/" . $_SESSION["theme"] . ".css" ?>>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/options.css">
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
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

        .user-stats i {
            margin-right: 5px; /* Espacement entre l'icône et le texte */
            transition: color 0.3s; /* Transition de couleur sur hover */
        }

        .user-stats i:hover {
            color: #ff6600; /* Couleur d'icône sur hover */
        }

        .post-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            justify-items: stretch;
            column-gap: 2em;
        }

        .post-actions button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .post-actions button:hover {
            background-color: #0056b3;
        }

        .post-actions button:focus {
            outline: none;
            box-shadow: 0 0 0 2px #007bff;
        }

        .post-actions button:disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }

    </style>
</head>

<body>
    <?php
    echo createTitleBar("@Statistiques");
    ?>

    <main class="container">
        <div class="main-content">
            <div class="options-container">
                <!-- Section pour afficher les publications de l'utilisateur -->
                <div class="user-posts">
                    <h2>Publications de <?php echo $user_info->getUsername(); ?></h2>
                    <?php
                    // Vérifier si l'utilisateur a des publications
                    if ($user_posts != NULL) {
                        // Afficher les publications de l'utilisateur
                        foreach ($user_posts as $post) {
                            echo "<p><strong>Date :</strong> " . $post['post_date'] . "</p>"; // Afficher la date de la publication
                            echo "<p><strong>Contenu :</strong> " . $post['text_content'] . "</p>"; // Afficher le contenu de la publication

                            // Ajouter des boutons pour supprimer ou modifier le post
                            echo "<div class='post-actions'>";
                            // Utilisation de md5() pour sécuriser l'ID de post
                            $hashed_post_id = $post['textId'];
                            echo '<button onclick="editPost(\'' . $hashed_post_id . '\')">Modifier</button>';
                            echo '<button onclick="deletePost(\'' . $hashed_post_id . '\')">Supprimer</button>';
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
                    echo "<p><i class='fas fa-users'></i> Nombre de followers : " . $user_statistics['follower_count'] . "</p>";
                    echo "<p><i class='fas fa-pen'></i> Nombre de publications : " . $user_statistics['post_count'] . "</p>";
                    echo "<p><i class='fas fa-thumbs-up'></i> Nombre de Likes : " . $user_statistics['like_count'] . "</p>";
                    echo "<p><i class='fas fa-thumbs-down'></i> Nombre de Dislikes : " . $user_statistics['dislike_count'] . "</p>";
                    echo "<p><i class='fas fa-box'></i> Nombre de Clics (posts découverts) : " . $user_statistics['flipbox_count'] . "</p>";
                    echo "<p><i class='fas fa-comments'></i> Nombre de Commentaires : " . $user_statistics['comment_count'] . "</p>";
                    // Ajoutez d'autres statistiques si nécessaire
                    echo "</fieldset>";
                    ?>
                </div>
            </div>

            <div class="right-panel wow animate__fadeInRight" data-wow-duration="1s" data-wow-delay="2s">
                <section id="right-links">
                    <ul>
                        <?php echo getNavbarContents() ?>
                        <hr class="links-separator">
                        <li><a href="#"><i class="fas fa-search"></i> Découvrir</a></li>
                        <li><a href="#"><i class="fas fa-lock"></i> Politique de confidentialité</a></li>
                        <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>"><i class="fas fa-cogs"></i> API</a></li>
                        <li><a href="#"><i class="fas fa-question-circle"></i> Assistance</a></li>
                        <li><a href="#"><i class="fas fa-file-alt"></i> Termes et conditions</a></li>
                    </ul>
                    <p>&copy; Cuicui App 2024</p>
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
    function deletePost(postID) {
        console.log(postID);
        $.ajax({
            url: window.__ajx__ + 'deletePost.php',
            type: 'POST',
            data: {
                postID: postID
            },
            success: function(response) {
                // Gérer la suppression réussie
                console.log('Post supprimé avec succès !');

                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error); // Gérer les erreurs éventuelles
            }
        });
    }

    function editPost(postId) {
        window.location.href = 'edit.php?postId=' + postId;
    }
</script>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js" ?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>

</html>