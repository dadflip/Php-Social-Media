<?php
    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    if (isset($_GET['postId'])) {
        $postId = $_GET['postId'];

        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        $postDetails = $cuicui_manager->getPostDetails($postId);

        if (isset($_POST['submit'])) {
            $newContent = $_POST['new_content'];

            $cuicui_manager->updatePostContent($postId, $newContent);

            header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['stats']);
            exit();
        }
    } else {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['mainpage']);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post | Cuicui App</title>

    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/edit.css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php
        echo createTitleBar("@Edit Post");
    ?>
    <div class="container">
        <h1>Éditer le Post</h1>
        <form action="" method="post" class="edit-form">
            <label for="new_content">Nouveau Contenu :</label><br>
            <textarea id="new_content" name="new_content" rows="4" cols="50"><?php echo $postDetails['text_content']; ?></textarea><br><br>
            <input type="submit" name="submit" value="Enregistrer les modifications">
        </form>
    </div>
</body>

</html>