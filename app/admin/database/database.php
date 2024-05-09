<?php
    include '../../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    session_start();

    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    }else{
        header('Location:' . $appdir['PATH_CUICUI_APP']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Database | Cuicui Admin</title>
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.db.css"?> >
</head>
<body>
    <div class="container">
        <h2>Redirection vers les Gestionnaires de Bases de Données</h2>
        
        <!-- Formulaire pour saisir l'URL de la base de données -->
        <form method="get" action="">
            <label for="db_url">URL de la base de données :</label>
            <input type="text" id="db_url" name="db_url" required>
            <input type="submit" value="Rediriger">
        </form>

        <?php
        // Tableau associatif des gestionnaires de bases de données préenregistrés avec leur URL
        $db_managers = [
            "phpMyAdmin" => "https://www.phpmyadmin.net/",
            "pgAdmin" => "https://www.pgadmin.org/"
            // Ajoutez d'autres gestionnaires de bases de données au besoin
        ];

        // Affichage des cadres cliquables pour les gestionnaires de bases de données préenregistrés
        echo "<h3>Gestionnaires de Bases de Données Préenregistrés</h3>";
        echo "<ul>";
        foreach ($db_managers as $manager => $url) {
            echo "<li><a href='$url' target='_blank'>$manager</a></li>";
        }
        echo "</ul>";

        // Vérification et ajout de "http://" à l'URL si nécessaire
        if (isset($_GET['db_url'])) {
            $db_url = filter_input(INPUT_GET, 'db_url', FILTER_SANITIZE_URL);
            if ($db_url) {
                if (!preg_match("~^(?:f|ht)tps?://~i", $db_url)) {
                    $db_url = "http://" . $db_url;
                }
                echo "<script>window.location.href = '$db_url';</script>";
            }
        }
        ?>
    </div>
</body>
</html>
