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

if (isset($_SESSION['UID'])) {
    // Marquer les notifications comme lues lors de l'accès à la page de notifications
    $updateQuery = "UPDATE notifications SET is_read = 1 WHERE users_uid = ? AND is_read = 0";
    $updateStmt = $cuicui_manager->prepare($updateQuery);
    $updateStmt->execute([$_SESSION['UID']]);
}

// Récupérer les notifications de l'utilisateur depuis les deux dernières semaines
$twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-2 weeks'));
$selectQuery = "SELECT * FROM notifications WHERE /*users_uid = ? AND*/ c_datetime > ? ORDER BY c_datetime DESC";
$selectStmt = $cuicui_manager->prepare($selectQuery);
//$selectStmt->bind_param("ss", $_SESSION['UID'], $twoWeeksAgo);
$selectStmt->bind_param("s", $twoWeeksAgo);
$selectStmt->execute();
$result = $selectStmt->get_result();

// Vérifier si la requête a réussi
if ($result) {
    // Récupérer les résultats sous forme de tableau associatif
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Gérer l'erreur si la requête échoue
    echo "Erreur lors de la récupération des notifications";
}
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

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    if (isset($_GET["userpage"])) {
        echo createTitleBar("Ma page");
    } else {
        echo createTitleBar("Accueil");
    }
    ?>

    <main class="container">
        <div class="center-column">
            <div class="center-main-panel">
                <div class="center-panel">
                    <div class="tabs-container">
                        <div class="tab active" data-tab="tab1" onclick="showTab('tab1')">
                            <i class="fas fa-dove"></i> Cui Cui Box
                        </div>
                        <?php
                        if (isset($_SESSION["UID"])) {
                        ?>
                            <div class="tab" data-tab="tab2" onclick="showTab('tab2')">
                                <i class="fas fa-comments"></i> Chat
                            </div>
                            <div class="tab" data-tab="tab3" onclick="showTab('tab3')">
                                <i class="fas fa-users"></i> Amis
                            </div>
                            <div class="tab notifications-badge" data-tab="tab4" onclick="showTab('tab4')">
                                <?php
                                //echo count($notifications) ;
                                $countUnread = 0;
                                ?>

                                <?php
                                if (count($notifications) > 0) {
                                    foreach ($notifications as $notification) {
                                        if (!$notification['is_read']) {
                                            $countUnread++;
                                        }
                                    }
                                }
                                ?>

                                <span class="badge badge-red"><?php echo '(' . $countUnread . ')'; ?></span>
                                <i class="fas fa-bell"></i>
                                Notifications
                            </div>
                        <?php
                        }
                        ?>
                        <br>
                    </div>

                    <?php
                    if (isset($_SESSION["UID"])) {
                    ?>
                        <!-- Bouton pour afficher le formulaire (Nouveau sujet)-->
                        <button onclick="toggleForm()" type="button" class="page_button1" title="FLIPBOX">
                            <i class="fas fa-plus"></i> <!-- Nouvelle icône pour le bouton flip -->
                        </button>

                        <!-- Bouton pour ajouter des médias avec une icône d'appareil photo -->
                        <button onclick="postImage()" type="button" class="social_button" title="Ajouter des médias">
                            <i class="fas fa-camera"></i>
                        </button>

                        <!-- Bouton pour poster des images avec une icône d'appareil photo -->
                        <button onclick="addMedia()" type="button" class="social_button" title="Poster une image">
                            <i class="fas fa-image"></i>
                        </button>

                        <!-- Bouton pour enregistrer du son avec une icône de microphone -->
                        <button onclick="recordAudio()" type="button" class="social_button" title="Enregistrer du son">
                            <i class="fas fa-microphone"></i>
                        </button>

                        <!-- Bouton pour enregistrer le flux vidéo avec une icône de caméscope -->
                        <button onclick="stream()" type="button" class="social_button" title="stream">
                            <i class="fas fa-video"></i>
                        </button>


                        <!-- Ajoutez d'autres boutons avec d'autres fonctionnalités ici -->
                    <?php
                    }
                    ?>
                </div>

                <div id="tab1" class="tab-content wow animate__fadeIn" data-wow-duration="1s" data-wow-delay="1.5s">
                    <div id="resultats" class="resultats"><br></div>
                </div>

                <div id="tab2" class="tab-content" style="display: none;">
                    <div id="userList"></div>
                </div>

                <div id="tab3" class="tab-content" style="display: none;">
                    Contenu des Amis
                </div>

                <div id="tab4" class="tab-content" style="display: none;">
                    <div class="content">
                        <h1>Notifications</h1>
                        <?php if (count($notifications) > 0) : ?>
                            <?php foreach ($notifications as $notification) : ?>
                                <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                                    <p><?php echo $notification['text_content']; ?></p>
                                    <?php if ($notification['notification_id']) : ?>
                                        <p><?php echo $notification['title']; ?></p>
                                        <p><?php echo $notification['c_datetime']; ?></p>
                                        <p><?php echo $notification['notification_type']; ?></p>
                                    <?php endif; ?>
                                    <span><button class="delete-btn" data-id=<?php echo $notification['notification_id']; ?>>Supprimer</button></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-notifs">
                                <p>Aucune notification</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-panel wow animate__fadeInRight" data-wow-duration="1s" data-wow-delay="2s">
            <section id="right-links">
                <ul>
                    <li><a href="#">Découvrir</a></li>
                    <li><a href="#">Langue</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>">API</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Assistance</a></li>
                    <li><a href="#">Termes et conditions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>

                <p> &copy; Cuicui App 2024</p>
            </section>
        </div>
    </main>


    <div class="floating-form wow animate__zoomIn" id="newTopicForm" data-wow-duration="1s" data-wow-delay="2.5s">
        <i class="fas fa-times close-icon" onclick="toggleForm()"></i>
        <form action="<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/post.php'; ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend><i class="fas fa-heading"></i> Titre :</legend>
                <input type="text" name="title" required>
            </fieldset>

            <fieldset>
                <legend><i class="fas fa-comment"></i> Contenu :</legend>
                <textarea name="content" required></textarea>
            </fieldset>

            <fieldset>
                <legend><i class="fas fa-tags"></i> Catégorie :</legend>
                <select name="category" required>
                    <option value="Amis"> General</option>
                    <option value="Amis"><i class="fas fa-users"></i> Amis</option>
                    <option value="Famille"><i class="fas fa-home"></i> Famille</option>
                    <option value="Travail"><i class="fas fa-briefcase"></i> Travail</option>
                    <option value="Événements"><i class="fas fa-calendar-alt"></i> Événements</option>
                    <option value="Loisirs"><i class="fas fa-gamepad"></i> Loisirs</option>
                    <option value="Animaux"><i class="fas fa-paw"></i> Animaux</option>
                    <option value="Voyages"><i class="fas fa-plane"></i> Voyages</option>
                    <option value="Éducation"><i class="fas fa-graduation-cap"></i> Éducation</option>
                    <option value="Musique"><i class="fas fa-music"></i> Musique</option>
                    <option value="Cuisine"><i class="fas fa-utensils"></i> Cuisine</option>
                    <option value="Sports"><i class="fas fa-futbol"></i> Sports</option>
                    <option value="Technologie"><i class="fas fa-laptop"></i> Technologie</option>
                    <option value="Art"><i class="fas fa-paint-brush"></i> Art</option>
                    <option value="Santé"><i class="fas fa-medkit"></i> Santé</option>
                    <option value="Nature"><i class="fas fa-tree"></i> Nature</option>
                    <option value="Mode"><i class="fas fa-tshirt"></i> Mode</option>
                    <option value="Photographie"><i class="fas fa-camera"></i> Photographie</option>
                    <option value="Lecture"><i class="fas fa-book"></i> Lecture</option>
                    <option value="Cinéma"><i class="fas fa-film"></i> Cinéma</option>
                    <option value="Jeux vidéo"><i class="fas fa-gamepad"></i> Jeux vidéo</option>
                    <option value="Humour"><i class="fas fa-laugh"></i> Humour</option>
                    <option value="Finance"><i class="fas fa-money-bill-wave"></i> Finance</option>
                    <option value="Automobile"><i class="fas fa-car"></i> Automobile</option>
                    <option value="Décoration"><i class="fas fa-home"></i> Décoration</option>
                    <option value="Science"><i class="fas fa-flask"></i> Science</option>
                    <option value="Politique"><i class="fas fa-balance-scale"></i> Politique</option>
                    <option value="Religion"><i class="fas fa-church"></i> Religion</option>
                    <option value="Aventure"><i class="fas fa-hiking"></i> Aventure</option>
                    <option value="Fitness"><i class="fas fa-dumbbell"></i> Fitness</option>
                    <option value="Danse"><i class="fas fa-drum"></i> Danse</option>
                    <option value="Shopping"><i class="fas fa-shopping-bag"></i> Shopping</option>
                    <option value="Mariage"><i class="fas fa-ring"></i> Mariage</option>
                    <option value="Gastronomie"><i class="fas fa-utensils"></i> Gastronomie</option>
                    <option value="Humour"><i class="fas fa-laugh"></i> Humour</option>
                    <option value="Vie nocturne"><i class="fas fa-cocktail"></i> Vie nocturne</option>
                    <option value="Écologie"><i class="fas fa-recycle"></i> Écologie</option>
                    <option value="Festivals"><i class="fas fa-music"></i> Festivals</option>
                    <option value="Bricolage"><i class="fas fa-tools"></i> Bricolage</option>
                    <option value="Énigmes"><i class="fas fa-puzzle-piece"></i> Énigmes</option>
                    <option value="Jardinage"><i class="fas fa-seedling"></i> Jardinage</option>
                    <option value="Bibliothèque"><i class="fas fa-book-open"></i> Bibliothèque</option>
                    <option value="Art culinaire"><i class="fas fa-utensils"></i> Art culinaire</option>
                    <option value="Vie quotidienne"><i class="fas fa-coffee"></i> Vie quotidienne</option>
                    <option value="Bénévolat"><i class="fas fa-hands-helping"></i> Bénévolat</option>
                    <option value="DIY"><i class="fas fa-tools"></i> DIY</option>
                    <option value="Beauté"><i class="fas fa-heart"></i> Beauté</option>
                    <option value="Anecdotes"><i class="fas fa-quote-left"></i> Anecdotes</option>
                    <option value="Culture"><i class="fas fa-globe"></i> Culture</option>
                </select>

            </fieldset>

            <fieldset>
                <legend><i class="fas fa-key"></i> Mots-clés (virgules) :</legend>
                <input type="text" name="keywords">
                <span id="keywordsSection"></span>
            </fieldset>

            <fieldset class="image-preview">
                <legend><i class="fas fa-image"></i> Uploader une image :</legend>
                <input type="file" name="image" id="image" accept="image/*">
                <div class="preview"></div>
            </fieldset>

            <fieldset class="video-upload">
                <legend><i class="fas fa-video"></i> Uploader une vidéo :</legend>
                <input type="file" name="video" id="video" accept="video/*">
            </fieldset>

            <fieldset class="audio-upload">
                <legend><i class="fas fa-volume-up"></i> Uploader du son :</legend>
                <input type="file" name="audio" id="audio" accept="audio/*">
            </fieldset>

            <fieldset class="other-media-upload">
                <legend><i class="fas fa-file"></i> Uploader d'autres médias :</legend>
                <input type="file" name="other-media" id="other-media" accept=".mp3, .mp4, .avi, .pdf">
            </fieldset>

            <input type="submit" value="Poster">
        </form>
    </div>
    </div>



    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents() ?>
    </nav>


    <script>
        // Sélection de l'élément input pour l'image
        const input = document.querySelector('input[type="file"]');
        // Sélection de l'élément div pour la prévisualisation de l'image
        const preview = document.querySelector('.preview');

        // Écouteur d'événement pour détecter les changements dans l'input
        input.addEventListener('change', () => {
            // Vérifier si des fichiers ont été sélectionnés
            if (input.files && input.files[0]) {
                // Créer un objet FileReader
                const reader = new FileReader();

                // Lorsque le fichier est chargé
                reader.onload = (e) => {
                    // Créer un élément image
                    const img = document.createElement('img');
                    // Définir la source de l'image sur les données du fichier chargé
                    img.src = e.target.result;
                    // Ajouter l'image à la prévisualisation
                    preview.innerHTML = '';
                    preview.appendChild(img);
                };

                // Lire le fichier en tant que URL de données
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js" ?>></script>


<script>
    window.__u_url__ = '<?php echo $GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] . "/" . $GLOBALS["LANG"] . $GLOBALS["php_files"]["user"]; ?>';
</script>
<script>
    window.__ajx__ = "<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/'; ?>";
</script>
<script>
    window.__u__ = <?php echo $_SESSION['UID']; ?>
</script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>


<script>
    function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();

        if (message !== '') {
            appendMessage('You', message); // Ajoutez le message à l'interface
            messageInput.value = ''; // Effacez le champ de saisie
        }
    }

    function appendMessage(sender, message) {
        const chatMessages = document.getElementById('chatMessages');
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
        chatMessages.appendChild(messageElement);

        // Faites défiler automatiquement vers le bas pour afficher le dernier message
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
</script>


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
                var postId = $(this).attr('data-text-id');
                var title = $(this).attr('data-title');
                var tags = $(this).attr('data-keywords');
                var category = $(this).attr('data-category');
                var currentDate = getCurrentDate();
                var currentTime = getCurrentTime();
                var browserInfo = getBrowserInfo();

                console.log(browserInfo);
                console.log(title);
                console.log(tags);
                var flipboxData = {
                    tags: tags,
                    category: category,
                    title: title
                };
                console.log(flipboxData);

                // Créer un objet contenant les données à envoyer
                var dataToSend = {
                    userId: userId,
                    postId: postId,
                    title: title,
                    tags: tags,
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
        $('#barre-recherche').on('input', function() {
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
        $("#barre-recherche").on("input", function() {
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

    // Script JavaScript pour gérer la suppression de notifications
    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var notificationId = this.getAttribute('data-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                    // Envoyer une requête Ajax pour supprimer la notification
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_notification.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // Actualiser la page après la suppression
                                location.reload();
                            } else {
                                console.error('Erreur lors de la suppression de la notification');
                            }
                        }
                    };
                    xhr.send('notification_id=' + notificationId);
                }
            });
        });
    });
</script>

</html>