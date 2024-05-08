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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Inclure les feuilles de style CSS -->
    <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>">
    <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>">
    <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    // Affichage de la barre de titre en fonction du contexte
    if (isset($_GET["userpage"])) {
        echo createTitleBar("Ma page");
    } else {
        echo createTitleBar("Accueil");
    }
    ?>
    <div class="container">
        <div class="card-post">
            <h1>Post Details</h1>
            <?php
            // Vérification de la présence du paramètre 'post' dans l'URL
            if (isset($_GET['post'])) {
                $postId = $_GET['post'];

                // Requête SQL pour récupérer les informations du post à partir de son ID
                $query = "SELECT posts.*, users.username, media.url AS media_url, users.profile_pic_url
                          FROM posts 
                          INNER JOIN users ON posts.users_uid = users.UID
                          LEFT JOIN media ON posts.media_id = media.media_id
                          WHERE posts.textId = ?";
                $res = $cuicui_manager->createRequest($query, "s", $postId);

                // Vérification si la requête s'est exécutée avec succès
                if ($res === false) {
                    echo "<p>Une erreur s'est produite lors de la récupération des informations du post.</p>";
                } else {
                    // Récupération des informations du post
                    $post = $res->fetch_assoc();

                    // Affichage des informations du post
                    echo "<h2>Title: {$post['title']}</h2>";
                    echo "<p>Content: {$post['text_content']}</p>";
                    echo "<p>Author: {$post['username']}</p>";
                    // Afficher d'autres informations sur le post, par exemple : date, tags, etc.
                    echo "<p>Media URL: {$post['media_url']}</p>";
                    echo "<img src='{$post['profile_pic_url']}' alt='Profile Picture'>";

                    // Fermer le résultat de la requête
                    $res->close();

                    // Requête SQL pour récupérer les commentaires associés au post
                    $commentsQuery = "SELECT * FROM comments WHERE parent_id = ?";
                    $commentsRes = $cuicui_manager->createRequest($commentsQuery, "s", $postId);

                    // Vérification si la requête s'est exécutée avec succès
                    if ($commentsRes === false) {
                        echo "<p>Une erreur s'est produite lors de la récupération des commentaires.</p>";
                    } else {
                        // Afficher les commentaires
                        echo "<h3>Comments:</h3>";
                        while ($comment = $commentsRes->fetch_assoc()) {
                            echo "<p>{$comment['content']}</p>";
                            // Afficher d'autres informations sur les commentaires si nécessaire
                        }
                        // Fermer le résultat de la requête
                        $commentsRes->close();
                    }
                }
            } else {
                // Si le paramètre 'post' est manquant, afficher un message d'erreur
                echo "<p>Post not found.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>
