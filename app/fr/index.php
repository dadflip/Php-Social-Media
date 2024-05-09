<?php
include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Index | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/indexpage.css" ?>>
    </head>

    <body>
        <?php
        echo printHomePageComponent();
        ?>
        <div class="links-grid">
            <?php
            echo getNavbarContents();
            ?>
        </div>

        <div class="right-panel">
            <section id="bottom-links" class="links-grid">
                <a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/discover/starter.php'?>" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image1.png" ?> alt="Image 1">
                        <div class="content">
                            <h3>Découvrir</h3>
                            <p>Cliquez ici pour découvrir plus de contenu.</p>
                            <i class="icon fas fa-search"></i>
                        </div>
                    </div>
                </a>
                <a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/lang/lang.php'?>" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image2.png" ?> alt="Image 2">
                        <div class="content">
                            <h3>Langue</h3>
                            <p>Cliquez ici pour choisir votre langue préférée.</p>
                            <i class="icon fas fa-language"></i>
                        </div>
                    </div>
                </a>
                <a href="#" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image3.png" ?> alt="Image 3">
                        <div class="content">
                            <h3>Politique de confidentialité</h3>
                            <p>Cliquez ici pour consulter notre politique de confidentialité.</p>
                            <i class="icon fas fa-lock"></i>
                        </div>
                    </div>
                </a>
                <a href="<?php echo $appdir['PATH_API_DIR'] ?>" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image4.png" ?> alt="Image 4">
                        <div class="content">
                            <h3>API</h3>
                            <p>Cliquez ici pour accéder à notre API.</p>
                            <i class="icon fas fa-cogs"></i>
                        </div>
                    </div>
                </a>
                <a href="#" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image5.png" ?> alt="Image 5">
                        <div class="content">
                            <h3>FAQ</h3>
                            <p>Cliquez ici pour accéder à notre FAQ.</p>
                            <i class="icon fas fa-question-circle"></i>
                        </div>
                    </div>
                </a>
                <a href="#" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image6.png" ?> alt="Image 6">
                        <div class="content">
                            <h3>Assistance</h3>
                            <p>Besoin d'aide ? Cliquez ici pour accéder à notre assistance.</p>
                            <i class="icon fas fa-life-ring"></i>
                        </div>
                    </div>
                </a>
                <a href="#" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image7.png" ?> alt="Image 7">
                        <div class="content">
                            <h3>Termes et conditions</h3>
                            <p>Cliquez ici pour consulter nos termes et conditions.</p>
                            <i class="icon fas fa-file-contract"></i>
                        </div>
                    </div>
                </a>
                <a href="#" class="card-link">
                    <div class="card">
                        <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image8.png" ?> alt="Image 8">
                        <div class="content">
                            <h3>Contact</h3>
                            <p>Besoin de nous contacter ? Cliquez ici pour accéder à nos coordonnées.</p>
                            <i class="icon fas fa-envelope"></i>
                        </div>
                    </div>
                </a>
            </section>


            <p>&copy; Cuicui App 2024</p>
            </section>
        </div>
    </body>

    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>
