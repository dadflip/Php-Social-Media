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
        header('Location: @.php');
    }

    if (isset($_SESSION['UID'])) {
        $notifs = new NotificationManager($cuicui_manager);
        $notifications = $notifs->getNotificationsByUserId($_SESSION['UID']);
        $countAllNotifications = count($notifications);
        $user_info = $cuicui_sess->getUserInfoAndSettings($_SESSION["UID"]);
        $settings = $user_info->getSettingsArray();
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Home | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    </head>
    <body>
        <?php
        if (isset($_GET["userpage"])) {
            echo createTitleBar("@Me");
        } else {
            echo createTitleBar("@Home");
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
                                <h1>Notifications <a class="user" href="notify.php"><i class="fas fa-external-link-alt"></i></a></h1>
                                <?php if($settings['notifications']['push']) {
                                    if ($countAllNotifications > 0) : ?>
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
                                <?php endif; } else {
                                ?>
                                    <div class="no-notifs">
                                        <p>Veuillez réactiver les notifications dans les paramètres !</p>
                                    </div>
                                <?php
                                } ?>
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

        <div class="floating-form" id="newTopicForm">
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
                        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Categories']); ?>
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
    </body>
    
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>