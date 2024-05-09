<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    try {
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    } catch (mysqli_sql_exception $e) {
        echo "Une erreur s'est produite : " . $e->getMessage();
        echo '<button onclick="window.location.href=\'' . $appdir['PATH_CUICUI_APP'] . '/admin/database/db_config.php\'">Configurer</button>';
    }

    if (isset($_SESSION['UID'])) {
        $notifs = new NotificationManager($cuicui_manager);
        $notifications = $notifs->getNotificationsByUserId($_SESSION['UID']);
        $countAllNotifications = count($notifications);
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Cuicui App</title>
        <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>

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
        <div id="barre-recherche" class="search-bar">
            <button id="toggle-search-bar"><i class="fas fa-search"></i></button>

            <div id="recherche-input-wrapper">
                <input type="text" id="recherche-input" placeholder="Rechercher...">
            </div>

            <div id="filtres" class="filtres-dropdown">
                <button id="toggle-filtres">Filtres</button>
                <div id="filtres-select">
                    <div class="filtre active" data-value="user"><i class="fas fa-user"></i><span>Utilisateur</span></div>
                    <div class="filtre" data-value="post"><i class="fas fa-file-alt"></i><span>Post</span></div>
                    <div class="filtre" data-value="media"><i class="fas fa-image"></i><span>Média</span></div>
                    <div class="filtre" data-value="date"><i class="far fa-calendar-alt"></i><span>Date</span></div>
                    <div class="filtre" data-value="titre"><i class="fas fa-heading"></i><span>Titre</span></div>
                    <div class="filtre" data-value="populaires"><i class="fas fa-thumbs-up"></i><span>Populaires</span></div>
                    <div class="filtre" data-value="categorie"><i class="fas fa-tags"></i><span>Catégorie</span></div>
                    <div class="filtre active" data-value="contenu"><i class="fas fa-align-left"></i><span>Contenu</span></div>
                </div>
            </div>
        </div>

        <div class="app">
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
                                <?php
                                    $countUnread = $notifs->countUnreadNotifications($_SESSION['UID']);
                                ?>

                                <div class="tab notifications-badge" data-tab="tab4" onclick="showTab('tab4')">
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
                            <button onclick="toggleForm()" type="button" class="page_button1" title="FLIPBOX">
                                <i class="fas fa-plus"></i>
                            </button>

                            <button onclick="postImage()" type="button" class="social_button" title="Ajouter des médias">
                                <i class="fas fa-camera"></i>
                            </button>

                            <button onclick="addMedia()" type="button" class="social_button" title="Poster une image">
                                <i class="fas fa-image"></i>
                            </button>

                            <button onclick="recordAudio()" type="button" class="social_button" title="Enregistrer du son">
                                <i class="fas fa-microphone"></i>
                            </button>

                            <button onclick="stream()" type="button" class="social_button" title="stream">
                                <i class="fas fa-video"></i>
                            </button>
                        <?php
                        }
                        ?>
                    </div>

                    <div id="tab1" class="tab-content">
                        <div id="resultats" class="resultats"><br></div>
                    </div>

                    <div id="tab2" class="tab-content" style="display: none;">
                        <div id="userList"></div>
                    </div>

                    <div id="tab3" class="tab-content" style="display: none;">
                        <div id="friendList"></div>
                    </div>

                    <div id="tab4" class="tab-content" style="display: none;">
                        <div class="content">
                            <h1>Notifications</h1>
                            <?php if ($countAllNotifications > 0) : ?>
                                <?php foreach ($notifications as $notification) : ?>
                                    <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                                        <p><?php echo $notification['text_content']; ?></p>
                                        <?php if ($notification['notification_id']) : ?>
                                            <p><?php echo $notification['title']; ?></p>
                                            <p><?php echo $notification['c_datetime']; ?></p>
                                            <p><?php echo $notification['notification_type']; ?></p>
                                        <?php endif; ?>
                                        <span>
                                            <?php if (!$notification['is_read']) : ?>
                                                <button class="mark-as-read" data-id="<?php echo $notification['notification_id']; ?>">Marquer comme lu</button>
                                            <?php endif; ?>
                                            <button class="delete-btn" data-id="<?php echo $notification['notification_id']; ?>">Supprimer</button>
                                        </span>
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

    <div class="floating-form">
        <i class="fas fa-times close-icon" onclick="toggleForm()"></i>
        <form action="<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/post.php'; ?>" method="post" enctype="multipart/form-data">
            <fieldset class="slides">
                <legend><i class="fas fa-heading"></i> Titre :</legend>
                <input type="text" name="title" required>
            </fieldset>

            <fieldset class="slides">
                <legend><i class="fas fa-comment"></i> Contenu :</legend>
                <textarea name="content" required></textarea>
            </fieldset>

            <fieldset class="slides">
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

            <fieldset class="slides">
                <legend><i class="fas fa-key"></i> Mots-clés (virgules) :</legend>
                <input type="text" name="keywords">
                <span id="keywordsSection"></span>
            </fieldset>

            <fieldset class="slides">
                <fieldset class="media-type-selection">
                    <legend>Sélectionner le type de média :</legend>
                    <label><input type="radio" name="media-type" value="image"> Image</label>
                    <label><input type="radio" name="media-type" value="video"> Vidéo</label>
                    <label><input type="radio" name="media-type" value="audio"> Son</label>
                    <label><input type="radio" name="media-type" value="other-media"> Autre</label>
                </fieldset>

                <fieldset class="media-upload">
                    <fieldset class="image-upload image-preview">
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
                </fieldset>
            </fieldset>


            <fieldset class="slides">
                <legend> Poster </legend>
                <input type="submit" value="Poster">
            </fieldset>
        </form>

        <div class="diaporama-container">
            <div class="navigation-buttons">
                <button onclick="prevSlide()">Précédent</button>
                <button onclick="nextSlide()">Suivant</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.media-upload fieldset').forEach(function(fieldset) {
                fieldset.style.display = 'none';
            });

            document.querySelectorAll('input[name="media-type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    var selectedMediaType = this.value;

                    document.querySelectorAll('.media-upload fieldset').forEach(function(fieldset) {
                        fieldset.style.display = 'none';
                    });

                    document.querySelector('.' + selectedMediaType + '-upload').style.display = 'block';
                });
            });
        });

        var currentSlide = 0;
        var slides = document.querySelectorAll('.slides');

        function showSlide(index) {
            if (index >= 0 && index < slides.length) {
                for (var i = 0; i < slides.length; i++) {
                    if (i === index) {
                        slides[i].style.display = 'block';
                    } else {
                        slides[i].style.display = 'none';
                    }
                }
                currentSlide = index;
            }
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        showSlide(0);

        const input = document.querySelector('input[type="file"]');
        const preview = document.querySelector('.preview');

        input.addEventListener('change', () => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.innerHTML = '';
                    preview.appendChild(img);
                };

                reader.readAsDataURL(input.files[0]);
            }
        });

        $(document).ready(function() {
            $("#toggle-search-bar").click(function() {
                $("#barre-recherche").toggleClass("collapsed");
            });
        });
    </script>
    
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js" ?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js" ?>></script>


<script>
    window.__u_url__ = atob("<?php echo base64_encode($GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] . "/" . $GLOBALS["LANG"] . $GLOBALS["php_files"]["user"]); ?>");
    window.__ajx__ = atob("<?php echo base64_encode($appdir['PATH_PHP_DIR'] . '/ajax/main/'); ?>");
    window.__u__ = atob("<?php if(isset($_SESSION['UID'])){echo base64_encode($_SESSION['UID']);} ?>");
    window.__img_u__ = atob("<?php if(isset($_SESSION['pfp_url'])){ echo base64_encode($_SESSION['pfp_url']);} ?>");
</script>

<?php if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {?>
<script>
    window.__admin_but__ = true;
</script>
<?php } else { ?>
<script>
    window.__admin_but__ = false;
</script>
<?php } ?>

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

<style>
    #filtres-select {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 10px;
        margin: 0.5em;
    }

    .filtre {
        display: flex;
        align-items: center;
        cursor: pointer;
        border-radius: 10px;
        border: 1px solid #ffffff1f;
        padding: 0.2em;
        justify-content: space-around;
    }

    .filtre i {
        color: #000;
    }

    .filtre span {
        color: #000;
    }

    .filtre.active {
        background-color: #e5e5e524;
    }
</style>

<script>
$('.mark-as-read').click(function() {
    var notificationId = $(this).data('id');

    $.ajax({
        url: window.__ajx__ + 'markAsRead.php',
        method: 'POST',
        data: { id: notificationId },
        success: function(response) {
            // Actualiser la page ou faire d'autres actions nécessaires
            location.reload();
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs
            console.error(error);
        }
    });
});

$('.delete-btn').click(function() {
    var notificationId = $(this).data('id');

    $.ajax({
        url: window.__ajx__ + 'deleteNotification.php',
        method: 'POST',
        data: { id: notificationId },
        success: function(response) {
            // Actualiser la page ou faire d'autres actions nécessaires
            location.reload();
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs
            console.error(error);
        }
    });
});



    $(document).ready(function() {

        // Appeler la méthode pour construire les flipbox au chargement de la page
        construireFlipboxRecommandees();

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

        $(document).ready(function() {
            // Gérer le clic sur les filtres
            $('.filtre').click(function() {
                $(this).toggleClass('active');
                // Ajoutez votre logique pour gérer les filtres sélectionnés ici
                var filtresSelectionnes = [];
                $('.filtre.active').each(function() {
                    filtresSelectionnes.push($(this).data('value'));
                });
                console.log("Filtres sélectionnés :", filtresSelectionnes);
            });
        });

        //-------------------------------------------------------------------------------------------------

        // Variables pour le délai de saisie et le timeout de recherche
        var typingDelay = 500; // Délai de saisie en millisecondes
        var searchTimeout;

        // Fonction pour gérer la saisie dans la barre de recherche
        $('#barre-recherche input[type="text"]').on('input', function() {
            // Réinitialiser le délai de recherche à chaque saisie
            clearTimeout(searchTimeout);

            // Démarrer le délai de saisie
            searchTimeout = setTimeout(function() {
                var recherche = $('#barre-recherche input[type="text"]').val().trim();

                // Vérifier si la valeur de recherche n'est pas vide
                if (recherche !== '') {
                    // Effectuer la recherche
                    effectuerRecherche();
                } else {
                    // La valeur de recherche est vide, vous pouvez gérer cela ici si nécessaire
                    console.log('La valeur de recherche est vide');

                    // Construire les flipbox si la valeur de recherche est vide
                    construireFlipboxRecommandees();
                }
            }, typingDelay);
        });

    });

    // Attachez l'événement clic aux flipboxes générées dynamiquement
    $('#resultats').on('click', '.flip-box.clickable', function(event) {
        // Vérifiez si l'élément cliqué a la classe 'no-flip'
        if (!$(event.target).hasClass('no-flip')) {
            // Si l'élément cliqué ne possède pas la classe 'no-flip', effectuez le retournement
            $(this).find('.flip-box-inner').toggleClass('flipped');
        }
    });

    //------------------------ Recherches et Resultats ---------------------------------

    function effectuerRecherche(termeRecherche = '') {
        // Si aucun terme de recherche n'est spécifié, récupérer le terme depuis la barre de recherche
        if (termeRecherche === '') {
            termeRecherche = $('#barre-recherche input[type="text"]').val();
        }

        // Récupérer les filtres sélectionnés à partir de la grille d'icônes
        var filtresSelectionnes = {};
        $('.filtre.active').each(function() {
            var filtre = $(this).data('value');
            filtresSelectionnes[filtre] = true; // Utiliser le nom du filtre comme clé avec une valeur booléenne true
        });

        // Convertir les clés en une liste de filtres
        var filterList = Object.keys(filtresSelectionnes);

        // Afficher les filtres sélectionnés dans la console
        console.log("Filtres sélectionnés :", filterList);

        // Afficher le logo de chargement et ajuster sa position et sa taille
        var $loadingIcon = $(".loading-icon");
        var $searchBar = $(".search-bar");

        // Effectuer la requête AJAX vers le script de recherche (search.php)
        $.ajax({
            url: window.__ajx__ + "search.php",
            type: "GET",
            data: {
                q: termeRecherche,
                filters: filterList // Envoyer la liste des filtres sélectionnés
            },
            dataType: "json",
            success: function(resultats) {
                // Afficher les résultats dynamiquement avec surbrillance en utilisant le terme de recherche
                console.log(resultats);
                console.log(termeRecherche);
                afficherResultatsAvecTitre(resultats, termeRecherche);
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la requête AJAX. XHR : ", xhr);
                console.error('Erreur Ajax :', error);
            }
        });
    }

    // Ajouter un gestionnaire d'événements sur chaque lien cliquable pour effectuer une recherche au clic
    $(document).on('click', '.keyword-link', function(e) {
        e.preventDefault(); // Empêcher le comportement par défaut du lien
        var keyword = $(this).text(); // Récupérer le mot-clé cliqué
        // Effectuer la recherche avec le mot-clé comme terme de recherche
        effectuerRecherche(keyword);
    });


    // Fonction pour afficher les résultats avec un titre et un bouton retour
    function afficherResultatsAvecTitre(resultats, termeRecherche) {
        // Créer le titre
        var titre = $('<h2>').text("Résultats pour '" + termeRecherche + "'");

        // Créer le bouton retour
        var boutonRetour = $('<button>')
            .text('Retour')
            .addClass('btn btn-primary btn-retour') // Ajouter des classes Bootstrap pour le style
            .css({
                'margin-top': '10px', // Ajouter une marge supérieure pour l'espacement
                'font-size': '16px' // Changer la taille de la police
            })
            .on('click', function() {
                construireFlipboxRecommandees(); // Appeler la fonction construireFlipboxes pour reconstruire les éléments
            });

        // Créer un conteneur div pour le titre et le bouton retour
        var divTitreRetour = $('<div>')
            .append(titre, boutonRetour)
            .addClass('titre-retour-container'); // Ajouter une classe pour le style CSS

        // Créer un conteneur div pour les résultats
        var divResultats = $('<div>').attr('id', 'resultats');

        // Ajouter le titre, le bouton retour et les résultats au conteneur principal
        var container = $('<div>').addClass('resultats-container').append(divTitreRetour, divResultats);

        // Vider le conteneur actuel et ajouter le nouveau conteneur au document
        $('#resultats').empty().append(container);

        // Afficher les résultats dynamiquement avec surbrillance en utilisant le terme de recherche
        afficherResultatsAvecSurbrillance(resultats, termeRecherche);
    }



    // Fonction pour afficher les résultats de recherche
    function afficherResultatsAvecSurbrillance(resultats, termeRecherche) {
        // Effacer les résultats précédents
        // $("#resultats").empty();

        var recommandMaxSize = 15;
        var i = 0;

        // Afficher les nouveaux résultats dans des flipboxes
        while (i < resultats.length && i < recommandMaxSize) {
            var resultat = resultats[i];
            console.log(resultat.textId);
            console.log(resultat.media_url);

            // Récupérer les valeurs de keywords et category
            var keywords = resultat && resultat.tags ? resultat.tags : 'all';
            var category = resultat && resultat.category ? resultat.category : '';

            likesData[resultat.textId] = resultat.likes;
            dislikesData[resultat.textId] = resultat.dislikes;
            
            // Définir la classe CSS en fonction de la sensibilité du contenu
            var postClass = resultat.sensitive_content === 1 ? 'post sensitive' : 'post';
            console.log('-----------------' + resultat.sensitive_content);

            // Ajouter le badge avec l'icône d'alerte pour les posts sensibles
            var badge = resultat.sensitive_content === 1 ? '<span class="badge"><i class="fas fa-exclamation-triangle"></i></span>' : '';

            var flipbox = '<hr><div class="' + postClass + '">';

            // Construction de la flipbox
            flipbox += '<div class="flip-box clickable" data-keywords="' + keywords + '" data-category="' + category + '" data-title="' + resultat.title + '" data-text-id="' + resultat.textId + '">';


            flipbox += '<div class="flip-box-inner">';
            flipbox += '<div class="flip-box-front">';

            flipbox += '<h5 class="user-info">';

            // Ajouter une section pour l'image de profil
            flipbox += badge + '<div class="profile-image">';
            flipbox += '<img src="' + resultat.profile_pic_url + '" alt="Profile Image">';
            flipbox += '</div>';
            
            // Ajouter une section pour le nom d'utilisateur
            flipbox += '<a class="no-flip" href="' + window.__u_url__ + '?@userid=@' + resultat.username + '" class="user-name">';
            flipbox += '<i style="margin: 8px;" class="fas fa-user"></i>' + resultat.username;
            flipbox += '</a>';
            
            // Ajouter une section pour le bouton Suivre
            flipbox += '<span class="follow-section no-flip">';
            flipbox += '<span class="user-icon"></span>';

            flipbox += '<span id="follow-button-zone' + removeAtSign(resultat.textId) + '">';
            checkFollowAndGenerateButton(window.__u__, resultat.users_uid, removeAtSign(resultat.textId));
            flipbox += '</span>';

            // Ajouter un bouton pour signaler l'utilisateur
            flipbox += '<div class="report-button" onclick="reportUser(' + resultat.users_uid + ')">Signaler</div>';

            console.log('--->' + window.__admin_but__);
            if(window.__admin_but__ === true) {
                flipbox += `<div class="admin-button admin clickable cuicui-button" title="Effacer le post" onclick='removePost("${resultat.textId}", "${window.__u__}")'><i class="fas fa-solid fa-trash"></i></div>`;
                flipbox += `<div class="admin-button admin clickable cuicui-button" title="Marquer comme sensible" onclick='markSensitive("${resultat.textId}", "${window.__u__}")'><i class="fas fa-exclamation-triangle"></i></div>`
            }

            flipbox += '</span>';
            
            flipbox += '</h5>';               

            if (resultat.media_type === 'image') {
                flipbox += '<div class="sup-cadre">';
                flipbox += '<div class="cadre">';
                flipbox += '<img class="media-center no-flip" src="' + resultat.media_url + '" alt="Image">';
                flipbox += '</div>';

                // Ajouter une zone avec des sous-zones
                flipbox += '<div class="action-zone no-flip">';
                // Ajouter des icônes cliquables et d'autres contenus
                flipbox += '<div class="download-icon" onclick="downloadContent()"><i class="fas fa-download"></i></div>';
                flipbox += '<div class="other-content">Autre contenu ici</div>';
                flipbox += '</div>'; // Fermer la div action-zone
                flipbox += '</div>';

            } else if (resultat.media_type === 'video') {
                flipbox += '<div class="sup-cadre">';
                flipbox += '<div class="cadre">';
                flipbox += '<video class="media-center no-flip" controls>';
                flipbox += '<source src="' + resultat.media_url + '" type="video/mp4">';
                flipbox += 'Votre navigateur ne supporte pas la lecture de la vidéo.';
                flipbox += '</video>';
                flipbox += '</div>';

                // Ajouter une zone avec des sous-zones
                flipbox += '<div class="action-zone no-flip">';
                // Ajouter des icônes cliquables et d'autres contenus
                flipbox += '<div class="download-icon" onclick="downloadContent()"><i class="fas fa-download"></i></div>';
                flipbox += '<div class="other-content">Autre contenu ici</div>';
                flipbox += '</div>'; // Fermer la div action-zone
                flipbox += '</div>';

            } else if (resultat.media_type === 'audio') {
                flipbox += '<div class="sup-cadre">';
                flipbox += '<audio class="media-center no-flip" controls>';
                flipbox += '<source src="' + resultat.media_url + '" type="audio/mpeg">';
                flipbox += 'Votre navigateur ne supporte pas la lecture de l\'audio.';
                flipbox += '</audio>';
                flipbox += '</div>';
                
                // Ajouter une zone avec des sous-zones
                flipbox += '<div class="action-zone no-flip">';
                // Ajouter des icônes cliquables et d'autres contenus
                flipbox += '<div class="download-icon" onclick="downloadContent()"><i class="fas fa-download"></i></div>';
                flipbox += '<div class="other-content">Autre contenu ici</div>';
                flipbox += '</div>';
            } else {

                flipbox += '<div class="sup-cadre">';
                // Détecter le type de fichier à partir de l'URL
                var fileType = getFileTypeFromUrl(resultat.media_url);
                
                // Afficher le composant en fonction du type de fichier
                if (fileType === 'pdf') {
                    // Afficher un PDF téléchargeable
                    flipbox += '<div class="pdf-container">';
                    flipbox += '<embed src="' + resultat.media_url + '" type="application/pdf" width="100%" height="600px" />';
                    flipbox += '</div>';
                } else if (fileType === 'excel') {
                    // Afficher un classeur Excel
                    flipbox += '<div class="excel-container">';
                    flipbox += '<iframe src="' + resultat.media_url + '" style="width:100%; height:600px;" frameborder="0"></iframe>';
                    flipbox += '</div>';
                } else {
                    // Afficher un composant par défaut pour les autres types de fichiers
                    flipbox += '<div class="other-file-container">';
                    flipbox += '<a href="' + resultat.media_url + '" target="_blank">Télécharger le fichier</a>';
                    flipbox += '</div>';
                }
                flipbox += '</div>';
            }        

            flipbox += '<h6>' + resultat.title + '</h6>';
            flipbox += '<div class="keywords">';

                // Vérifier si resultat.keywords est défini et est une chaîne
                if (typeof resultat.tags === 'string') {
                    // Séparer la chaîne en un tableau de mots-clés en utilisant la virgule comme délimiteur
                    var keywordsArray = resultat.tags.split(',');
                    // Boucle pour chaque mot-clé
                    keywordsArray.forEach(function(keyword) {
                        // Supprimer les espaces inutiles autour du mot-clé
                        keyword = keyword.trim();
                        // Diviser le mot-clé en mots individuels
                        var words = keyword.split(' ');
                        // Boucle pour chaque mot individuel
                        words.forEach(function(word) {
                            // Ajouter un lien cliquable pour chaque mot individuel
                            flipbox += '<a href="#" class="keyword-link">' + word + '</a>';
                        });
                        // Ajouter un espace entre les mots-clés
                        flipbox += ' ';
                    });
                } else {
                    // Si resultat.keywords n'est pas une chaîne, l'afficher directement
                    flipbox += '<span class="keyword"></span>';
                }

            flipbox += '</div>';

            flipbox += '<div class="flipbox-button no-flip">';
            flipbox += '<a href="view.php?post=' + resultat.textId + '">';
            flipbox += '<button class="flipbox-button-icon"><i class="fas fa-external-link-alt"></i></button>';
            flipbox += '</a>';
            flipbox += '</div>';


            flipbox += '</div>';

            flipbox += '<div class="flip-box-back">';
            flipbox += '<div class="scrollable-content">';
            flipbox += '<p>' + resultat.text_content + '</p>';
            flipbox += '</div>';
            if(window.__u__){
                flipbox += '<div class="actions no-flip">';
                flipbox += '<label class="action-icon no-flip"><input type="radio" name="likeDislike_' + resultat.textId + '" value="like" onclick="likeDislike(\'' + resultat.textId + '\', this.value)"> <i style="color: green;" class="fas fa-thumbs-up"></i> <span class="likes-container" data-likes="' + (likesData[resultat.textId] || 0) + '">' + (likesData[resultat.textId] || 0) + '</span></label>';
                flipbox += '<label class="action-icon no-flip"><input type="radio" name="likeDislike_' + resultat.textId + '" value="dislike" onclick="likeDislike(\'' + resultat.textId + '\', this.value)"> <i style="color: red;" class="fas fa-thumbs-down"></i> <span class="dislikes-container" data-dislikes="' + (dislikesData[resultat.textId] || 0) + '">' + (dislikesData[resultat.textId] || 0) + '</span></label>';
                flipbox += '<span class="action-icon no-flip" onclick="mettreEnFavori(\'' + resultat.textId + '\')"><i style="color: white;" class="fas fa-heart"></i></span>';
                flipbox += '<span class="action-icon no-flip" onclick="faireUnDon(\'' + resultat.textId + '\')"><i style="color: yellow;" class="fas fa-donate"></i></span>';
                flipbox += '</div>';
            }
            flipbox += '</div>';
            flipbox += '</div>';

            flipbox += '</div>';

            if(window.__u__){
                flipbox += '<div class="comments-zone">';
                flipbox += '<div class="commentaires no-flip">';
                flipbox += '<div class="comments-display">';
            
            
                // Construction des commentaires associés au post
                if (resultat.comments && resultat.comments.length > 0) {
                    flipbox += buildCommentsHTML(resultat.comments);
                }
                flipbox += '</div>'; // Fin de la div comments-display
                flipbox += '<textarea class="response-zone-textarea no-flip" id="zoneCommentaire' + resultat.textId + '" placeholder="Ajouter un commentaire"></textarea>';
                flipbox += '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(\'' + resultat.textId + '\')">Envoyer</button>';
                flipbox += '</div>';
                flipbox += '</div>';
            }

            // Ajouter la flipbox à la liste des résultats
            $("#resultats").append(flipbox);
            i++;
        }
    }

    // Fonction pour mettre en surbrillance le texte de recherche dans les résultats
    function mettreEnSurbrillance(texteRecherche, contenu) {
        // Vérifier si le contenu est défini
        if (typeof contenu !== "undefined") {
            // Créer une expression régulière pour rechercher le texte de recherche de manière insensible à la casse
            var regex = new RegExp("(" + escapeRegex(texteRecherche) + ")", "ig");

            // Remplacer le texte correspondant par le même texte enveloppé de balises <span> pour la surbrillance
            var resultatSurligne = contenu.replace(
                regex,
                '<span class="surligne">$1</span>'
            );

            return resultatSurligne;
        } else {
            // Retourner une chaîne vide si le contenu est indéfini
            return "";
        }
    }
</script>

</html>