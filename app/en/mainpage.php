<!DOCTYPE html>
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Afficher le chemin actuel du fichier pour déboguer
    //var_dump(__DIR__);

    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);
?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css"?> >
    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
</head>
<body>
    <?php
    if(!isset($_SESSION["UID"])) {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['login']);
    }

    if(isset($_GET["userpage"])) {
        echo createTitleBar("Ma page");
    }else {
        echo createTitleBar("Accueil");
    }
    ?>

    <main class="container">
        <div class="center-column">
            <div class="center-main-panel">
                <div class="center-panel">
                    <div class="tabs-container">
                        <div class="tab active" data-tab="tab1" onclick="showTab('tab1')">
                            <i class="fas fa-box-open"></i> FlipBoxes
                        </div>
                        <div class="tab" data-tab="tab2" onclick="showTab('tab2')">
                            <i class="fas fa-comments"></i> Commentaires
                        </div>
                        <div class="tab" data-tab="tab3" onclick="showTab('tab3')">
                            <i class="fas fa-users"></i> Amis
                        </div>
                        <br>
                    </div>

                    <!-- Bouton pour afficher le formulaire (Nouveau sujet)-->
                    <button onclick="toggleForm()" type="button" class="page_button1" title="FLIPBOX">
                        <i class="fas fa-plus"></i> <!-- Nouvelle icône pour le bouton flip -->
                    </button>

                    <!-- Bouton pour ajouter des médias avec une icône d'appareil photo -->
                    <button onclick="addMedia()" type="button" class="social_button" title="Ajouter des médias">
                        <i class="fas fa-camera"></i>
                    </button>
                    
                    <!-- Bouton pour poster des images avec une icône d'appareil photo -->
                    <button onclick="postImage()" type="button" class="social_button" title="Poster une image">
                        <i class="fas fa-image"></i>
                    </button>
                    
                    <!-- Bouton pour enregistrer du son avec une icône de microphone -->
                    <button onclick="recordAudio()" type="button" class="social_button" title="Enregistrer du son">
                        <i class="fas fa-microphone"></i>
                    </button>
                    
                    <!-- Ajoutez d'autres boutons avec d'autres fonctionnalités ici -->
                </div>

                <div id="tab1" class="tab-content">
                    <div id="resultats" class="resultats"><br></div>
                </div>

                <div id="tab2" class="tab-content" style="display: none;">
                    Contenu des Commentaires
                </div>

                <div id="tab3" class="tab-content" style="display: none;">
                    Contenu des Amis
                </div>
            
            </div>
        </div>

        <div class="right-panel">
            <section id="section3">
                <ul>
                    <li><a href="#">Découvrir</a></li>
                    <li><a href="#">Applications</a></li>
                    <li><a href="#">Langue</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">API</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Assistance</a></li>
                    <li><a href="#">Termes et conditions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>

                <p> &copy; Dadflip Solutions 2024</p>
            </section>
        </div>
    </main>


    <!-- Formulaire dans une bulle flottante -->
    <div class="floating-form" id="newTopicForm">
        <i class="fas fa-times close-icon" onclick="toggleForm()"></i>
        <form action=<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/post.php'; ?> method="post" enctype="multipart/form-data">
            <label for="title">Titre :</label>
            <input type="text" name="title" required><br>

            <label for="content">Contenu :</label>
            <textarea name="content" required></textarea><br>

            <label for="category">Catégorie :</label>
            <select name="category" required>
                <option value="Particulier">Particulier</option>
                <option value="Entreprise">Entreprise</option>
                <!-- Ajoutez d'autres options de catégories ici -->
            </select><br>

            <label for="keywords">Mots-clés (séparés par des virgules) :</label>
            <input type="text" name="keywords"><br>

            <!-- Ajout de la possibilité d'uploader des images -->
            <label for="image">Uploader une image :</label>
            <input type="file" name="image" accept="image/*"><br>

            <!-- Ajout de la possibilité d'uploader des vidéos -->
            <label for="video">Uploader une vidéo :</label>
            <input type="file" name="video" accept="video/*"><br>

            <input type="submit" value="Poster">
        </form>
    </div>

    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js"?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js"?>></script>


<script> window.__ajx__ = "<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/'; ?>";</script>
<script> window.__u__ = <?php echo $_SESSION['UID']; ?></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>





<script>
//Envoyer des données au serveur
$(document).ready(function() {

// Attachez l'événement clic aux flipboxes générées dynamiquement
$('#resultats').on('click', '.flip-box.clickable', function() {
    // Récupérer l'ID de l'utilisateur depuis la variable de session PHP
    var userId = <?php echo isset($_SESSION['UID']) ? $_SESSION['UID'] : 'null'; ?>;
    
    // Vérifier si l'ID de l'utilisateur est disponible
    if (userId !== null) {

        // Récupérer les autres informations nécessaires
        var title = $(this).attr('data-title');
        var keywords = $(this).attr('data-keywords');
        var category = $(this).attr('data-category');
        var currentDate = getCurrentDate();
        var currentTime = getCurrentTime();
        var browserInfo = getBrowserInfo();

        console.log(browserInfo);
        console.log(title);
        console.log(keywords);
        var flipboxData = {
            keywords: keywords,
            category: category,
            title: title
        };
        console.log(flipboxData);

        // Créer un objet contenant les données à envoyer
        var dataToSend = {
            userId: userId,
            title: title,
            keywords: keywords,
            currentDate: currentDate,
            currentTime: currentTime,
            browserInfo: browserInfo,
            category: category
        };

        // Envoyer les données au script PHP via une requête Ajax
        $.ajax({
            type: 'POST',
            url: window.__ajx__ + 'traitment.php', // Remplacez cela par le chemin correct vers votre script PHP
            data: dataToSend,
            success: function(response) {
                // Le traitement PHP a réussi, vous pouvez traiter la réponse si nécessaire
                console.log('Succès :', response);
            },
            error: function(error) {
                // Une erreur s'est produite lors de la requête Ajax
                console.error('Erreur Ajax :', error);
            }
        });
    } else {
        // L'ID de l'utilisateur n'est pas disponible, gérer en conséquence
        console.error('ID de l\'utilisateur non disponible');
    }
});

//-------------------------------------------------------------------------------------------------

// Gérer la saisie dans la barre de recherche
$('#barre-recherche').on('input', function () {
    var recherche = $(this).val().trim(); // Récupérer la valeur de la barre de recherche et supprimer les espaces vides

    // Fermer le menu lorsque du texte est saisi dans la barre de recherche
    $('#menu').hide();

    // Reconstruire les flipboxes si la barre de recherche est vide
    if (recherche == '') {
        console.log('check')
        construireFlipboxRecommandees();
    }
});

// Attacher la fonction de recherche à l'événement de saisie dans la barre de recherche
$("#barre-recherche").on("input", function () {
    // Déclencher la recherche après un léger délai (par exemple, 300 ms) pour éviter une recherche à chaque frappe
    clearTimeout(window.rechercheTimeout);
    window.rechercheTimeout = setTimeout(effectuerRecherche, 300);
});         

// Appeler la méthode pour construire les flipbox au chargement de la page
construireFlipboxRecommandees();
});   

// Attachez l'événement clic aux flipboxes générées dynamiquement
$('#resultats').on('click', '.flip-box.clickable', function(event) {
// Vérifiez si l'élément cliqué a la classe 'no-flip'
if (!$(event.target).hasClass('no-flip')) {
    // Si l'élément cliqué ne possède pas la classe 'no-flip', effectuez le retournement
    $(this).find('.flip-box-inner').toggleClass('flipped');
}
});
</script>
</html>