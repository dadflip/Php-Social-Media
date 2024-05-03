<?php
// Fonction pour imprimer le composant HTML de la page d'accueil
function printHomePageComponent() {
    // Composant HTML
    $component = '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                background-color: #000000ba;
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
        </style>
    </head>
    <body>
        <div class="header animate__animated animate__fadeInDown">
            <div class="container">
                <h1>CuiCui App</h1>
                <p>Chat, Explore and Discover</p>
                <a href="./mainpage.php" class="cta-button">Open</a>
            </div>
        </div>

        <div class="container services animate__animated animate__fadeInUp">
            <div class="service">
                <i class="icon fas fa-laptop"></i>
                <h2>Service 1</h2>
                <p>Description du service 1.</p>
            </div>
            <div class="service">
                <i class="icon fas fa-cog"></i>
                <h2>Service 2</h2>
                <p>Description du service 2.</p>
            </div>
            <div class="service">
                <i class="icon fas fa-chart-bar"></i>
                <h2>Service 3</h2>
                <p>Description du service 3.</p>
            </div>
        </div>

        <!-- WOW.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
        <script>
            new WOW().init();
        </script>
    </body>
    </html>';

    // Imprimer le composant HTML
    echo $component;
}