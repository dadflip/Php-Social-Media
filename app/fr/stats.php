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
$user_info = $cuicui_manager->getUserInfo($_SESSION["UID"]);

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
        <title>Statistics | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css" ?>>
    </head>

    <body>
        <?php
            echo createTitleBar("@Statistiques");
        ?>

        <main class="container">
            <div class="main-content">
                <div class="options-container">
                    <div class="user-posts">
                        <h2>Publications de <?php echo $user_info->getUsername(); ?></h2>
                        <?php
                        if ($user_posts != NULL) {
                            foreach ($user_posts as $post) {
                                echo "<p><strong>Date :</strong> " . $post['post_date'] . "</p>";
                                echo "<p><strong>Contenu :</strong> " . $post['text_content'] . "</p>";

                                echo "<div class='post-actions'>";
                                // Utilisation de md5() pour sécuriser l'ID de post
                                $hashed_post_id = $post['textId'];
                                echo '<button onclick="editPost(\'' . $hashed_post_id . '\')">Modifier</button>';
                                echo '<button onclick="deletePost(\'' . $hashed_post_id . '\')">Supprimer</button>';
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
                        echo "<p><i class='fas fa-users'></i> Nombre de followers : " . $user_statistics['follower_count'] . "</p>";
                        echo "<p><i class='fas fa-pen'></i> Nombre de publications : " . $user_statistics['post_count'] . "</p>";
                        echo "<p><i class='fas fa-thumbs-up'></i> Nombre de Likes : " . $user_statistics['like_count'] . "</p>";
                        echo "<p><i class='fas fa-thumbs-down'></i> Nombre de Dislikes : " . $user_statistics['dislike_count'] . "</p>";
                        echo "<p><i class='fas fa-box'></i> Nombre de Clics (posts découverts) : " . $user_statistics['flipbox_count'] . "</p>";
                        echo "<p><i class='fas fa-comments'></i> Nombre de Commentaires : " . $user_statistics['comment_count'] . "</p>";
                        echo "</fieldset>";
                        ?>
                    </div>
                </div>

                <button id="toggle-right-panel">☰</button>
                <div class="right-panel">
                    <section id="right-links">
                        <ul>
                            <?php echo getNavbarContents() ?>
                            <hr class="links-separator">
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/discover/starter.php'?>"><i class="fas fa-search"></i> Découvrir</a></li>
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/cuicui/ourpolicy.php'?>"><i class="fas fa-lock"></i> Politique de confidentialité</a></li>
                            <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>"><i class="fas fa-cogs"></i> API</a></li>
                        </ul>
                        <p>&copy; Cuicui App 2024</p>
                    </section>
                </div>
            </div>
        </main>
    </body>

    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>