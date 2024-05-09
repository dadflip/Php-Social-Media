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
<html lang="fr">
    <head>
        <title>View Post | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>">
        <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>">
        <link rel="stylesheet" href="<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>">
    </head>

    <body>
        <?php
            echo createTitleBar("@View");
        ?>
        <div class="container">
            <div class="card-post">
                <h1>Post Details</h1>
                <?php
                    if (isset($_GET['post'])) {
                        $postId = $_GET['post'];

                        $query = "SELECT posts.*, users.username, media.url AS media_url, users.profile_pic_url
                                FROM posts 
                                INNER JOIN users ON posts.users_uid = users.UID
                                LEFT JOIN media ON posts.media_id = media.media_id
                                WHERE posts.textId = ?";
                        $res = $cuicui_manager->createRequest($query, "s", $postId);

                        if ($res === false) {
                            echo "<p>Une erreur s'est produite lors de la récupération des informations du post.</p>";
                        } else {
                            $post = $res->fetch_assoc();

                            echo "<h2>Title: {$post['title']}</h2>";
                            echo "<p>Content: {$post['text_content']}</p>";
                            echo "<p>Author: {$post['username']}</p>";
                            echo "<p>Media URL: {$post['media_url']}</p>";

                            $res->close();

                            $commentsQuery = "SELECT * FROM comments WHERE parent_id = ?";
                            $commentsRes = $cuicui_manager->createRequest($commentsQuery, "s", $postId);

                            if ($commentsRes === false) {
                                echo "<p>Une erreur s'est produite lors de la récupération des commentaires.</p>";
                            } else {
                                echo "<h3>Comments:</h3>";
                                while ($comment = $commentsRes->fetch_assoc()) {
                                    echo "<p>{$comment['content']}</p>";
                                }
                                $commentsRes->close();
                            }
                        }
                    } else {
                        echo "<p>Post not found.</p>";
                    }
                ?>
            </div>
        </div>
    </body>

    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>
