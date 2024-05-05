<?php
include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Accueil</title>
    <style>
        /* Styles personnalisés */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .header {
            padding: 100px 0;
            background-color: var(--swiper-theme-color);
            color: #ffffff;
            border-radius: 10px;
            border: 1px solid;
            min-width: 40%;
            margin: 20px;
        }

        .header h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .header p {
            font-size: 1.5em;
            margin-bottom: 40px;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff5722;
            color: #ffffff;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #ff7043;
        }

        .icon {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .services {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .service {
            text-align: center;
            margin-bottom: 40px;
            max-width: 300px;
        }

        .service .icon {
            color: #ff5722;
        }

        .service h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .service p {
            font-size: 1.2em;
        }

        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .links-grid li {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .links-grid li:hover {
            background-color: #f0f0f0;
        }

        .links-grid li img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .links-grid li:hover img {
            transform: scale(1.1);
        }

        .links-grid li .description {
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
        }

        .links-grid li:hover .description {
            opacity: 1;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .links-grid li .icon {
            font-size: 2em;
            animation: rotate 2s linear infinite;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            list-style: none;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
        }

        li a {
            text-decoration: none;
            color: #333;
        }

        li a:hover {
            background-color: #f0f0f0;
        }

        p {
            margin-top: 20px;
        }

        /* Styles pour les cartes */
        .card-link {
            text-decoration: none;
            color: inherit; /* Utilise la couleur par défaut du texte */
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            max-width: 300px;
            /* Ajuster la largeur maximale selon les besoins */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 40%;
            height: auto;
            object-fit: cover;
            /* Assurer que l'image remplit complètement le cadre */
            border-radius: 10px 10px 0 0;
            /* Coins arrondis uniquement en haut */
        }

        .card .content {
            padding: 20px;
            text-align: center;
        }

        .card h3 {
            margin-top: 0;
        }

        .card p {
            margin-bottom: 0;
        }

        .card .icon {
            font-size: 2em;
        }
    </style>
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


    <div class="right-panel wow animate__fadeInRight" data-wow-duration="1s" data-wow-delay="2s">
        <section id="bottom-links" class="links-grid">
            <a href="#" class="card-link">
                <div class="card">
                    <img src=<?php echo $appdir['PATH_IMG_DIR'] . "/index/image1.png" ?> alt="Image 1">
                    <div class="content">
                        <h3>Découvrir</h3>
                        <p>Cliquez ici pour découvrir plus de contenu.</p>
                        <i class="icon fas fa-search"></i>
                    </div>
                </div>
            </a>
            <a href="#" class="card-link">
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
            <a href="#" class="card-link">
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

    <!-- WOW.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</body>

</html>
