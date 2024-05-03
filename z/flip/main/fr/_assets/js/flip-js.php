<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>

<body>
<script>
//Envoyer des données au serveur
$(document).ready(function() {

    // Attachez l'événement clic aux flipboxes générées dynamiquement
    $('#resultats').on('click', '.flip-box.clickable', function() {
        // Récupérer l'ID de l'utilisateur depuis la variable de session PHP
        var userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        
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
                url: '.php/traitment.php', // Remplacez cela par le chemin correct vers votre script PHP
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


/*document.getElementById("capturePhoto").addEventListener("click", function () {
    captureMedia("image");
});

document.getElementById("captureVideo").addEventListener("click", function () {
    captureMedia("video");
});*/

// Fonction pour échapper les caractères spéciaux dans une expression régulière
function escapeRegex(str) {
    return str.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
}

// Fonction pour gérer la touche "Enter"
function handleKeyPress(event) {
    if (event.key === "Enter") {
        // Appeler la fonction effectuerRecherche lorsque la touche "Enter" est pressée
        effectuerRecherche();
    }
}

// Fonction pour revenir à la page précédente
function goBack() {
    window.history.back();
}


// -------------- Toogle -------------------------------------------------------

// Fonction pour afficher ou masquer le formulaire
function toggleForm() {
    var newTopicForm = document.getElementById("newTopicForm");
    var loadingIcon = document.querySelector(".loading-icon");

    // Inversez l'état d'affichage du formulaire
    if (newTopicForm.style.display === "none") {
        newTopicForm.style.display = "block";
        loadingIcon.style.display = "none"; // Masquer l'icône de chargement
    } else {
        newTopicForm.style.display = "none";
        loadingIcon.style.display = "none"; // Afficher l'icône de chargement
    }
}

function toggleMenu() {
    var menu = document.getElementById("menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}


// ----------------- User actions ---------------------------------------------

function captureMedia(mediaType) {
    navigator.mediaDevices
        .getUserMedia({ video: true, audio: true })
        .then(function (stream) {
            if (mediaType === "image") {
                // Capturer une photo
                var video = document.createElement("video");
                video.srcObject = stream;

                video.onloadedmetadata = function () {
                    var canvas = document.createElement("canvas");
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext("2d").drawImage(video, 0, 0);

                    // Convertir le canvas en base64 (vous pouvez utiliser une autre méthode selon vos besoins)
                    var imageDataURL = canvas.toDataURL("image/png");
                    console.log(imageDataURL);

                    // Fermer le flux vidéo
                    stream.getTracks().forEach((track) => track.stop());
                };
            } else if (mediaType === "video") {
                // Enregistrer une vidéo
                var videoElement = document.createElement("video");
                videoElement.srcObject = stream;

                // Créer une balise vidéo pour l'enregistrement
                var mediaRecorder = new MediaRecorder(stream);
                var chunks = [];

                mediaRecorder.ondataavailable = function (e) {
                    if (e.data.size > 0) {
                        chunks.push(e.data);
                    }
                };

                mediaRecorder.onstop = function () {
                    var blob = new Blob(chunks, { type: "video/webm" });

                    // Créer un objet URL à partir du blob et l'assigner à une balise vidéo
                    var videoURL = URL.createObjectURL(blob);
                    videoElement.src = videoURL;

                    // Fermer le flux vidéo
                    stream.getTracks().forEach((track) => track.stop());
                };

                mediaRecorder.start();

                // Arrêtez l'enregistrement après quelques secondes (à ajuster selon vos besoins)
                setTimeout(function () {
                    mediaRecorder.stop();
                }, 5000);
            }
        })
        .catch(function (error) {
            console.error("Erreur lors de l'accès à la caméra/microphone:", error);
        });
}


function envoyerCommentaire(textId) {
    var commentaire = document.getElementById("zoneCommentaire").value;
    console.log(commentaire);

    // Envoyer le commentaire via AJAX
    effectuerAction("envoyer_commentaire", {
        textId: textId,
        commentaire: commentaire,
    });

    // Vider le contenu du textarea
    document.getElementById("zoneCommentaire").value = "";
}


//-------------------- Like - dislike --------------------------------------------

// Variables globales temporaires pour les likes et dislikes
var likesData = {};
var dislikesData = {};

// Fonction pour liker ou disliker un texte
function likeDislike(textId, action) {
    // Afficher les valeurs des variables pour débogger
    console.log("Text ID:", textId);
    console.log("Action:", action);

    def_like = likesData[textId];
    def_dislike = dislikesData[textId];

    // Mettre à jour les likes ou les dislikes en fonction de l'action
    if (action === 'like') {
        // Vérifier si le texte n'a pas déjà été liké
        if (!likesData[textId]) {
            likesData[textId] = 1;
        } else {
            // Si le texte a déjà été liké, incrémenter la valeur existante
            likesData[textId]++;
        }

        // Si le texte a déjà été disliké, décrémenter la valeur existante
        if (dislikesData[textId]) {
            dislikesData[textId]--;
            // Vérifier si le nombre de dislikes devient négatif et le fixer au minimum à 0
            if (dislikesData[textId] < 0) {
                dislikesData[textId] = 0;
            }
        }

        // Appeler la fonction pour effectuer l'action
        effectuerAction('like', { textId: textId });

    } else if (action === 'dislike') {
        // Vérifier si le texte n'a pas déjà été disliké
        if (!dislikesData[textId]) {
            dislikesData[textId] = 1;
        } else {
            // Si le texte a déjà été disliké, incrémenter la valeur existante
            dislikesData[textId]++;
        }

        // Si le texte a déjà été liké, décrémenter la valeur existante
        if (likesData[textId]) {
            likesData[textId]--;
            // Vérifier si le nombre de likes devient négatif et le fixer au minimum à 0
            if (likesData[textId] < 0) {
                likesData[textId] = 0;
            }
        }

        // Appeler la fonction pour effectuer l'action
        effectuerAction('dislike', { textId: textId });
    }

    // Mettre à jour l'interface utilisateur
    updateLikesDislikesUI(textId);
}


// Fonction pour mettre à jour l'interface utilisateur des likes et dislikes
function updateLikesDislikesUI(textId) {
    var likesContainer = $('.flip-box[data-text-id="' + textId + '"] .likes-container');
    likesContainer.attr("data-likes", likesData[textId] || 0);
    likesContainer.text(likesData[textId] || 0);

    var dislikesContainer = $('.flip-box[data-text-id="' + textId + '"] .dislikes-container');
    dislikesContainer.attr("data-dislikes", dislikesData[textId] || 0);
    dislikesContainer.text(dislikesData[textId] || 0);
}

// Gestionnaire d'événement pour les boutons radio de like et de dislike
$('.flip-box').on('change', 'input[type="radio"]', function() {
    var textId = $(this).closest('.flip-box').data('text-id');
    var action = $(this).val(); // Récupérer la valeur (like ou dislike) du bouton radio sélectionné
    likeDislike(textId, action); // Appeler la fonction pour liker ou disliker le texte
});



// Dans la fonction faireUnDon
function faireUnDon(textId) {
    effectuerAction("faire_un_don", { textId: textId });
}

function effectuerAction(action, data) {
    console.log("action =", action);
    console.log("data =", data);
    $.ajax({
        url: ".php/actions.php",
        type: "POST",
        data: { action: action, data: data },
        success: function (response) {
            console.log("action effectue !");
            console.log(response); // Afficher la réponse dans la console
        },
        error: function () {
            console.error("Erreur lors de l'action " + action);
        },
    });
}

// ----------------- Getting Infos ---------------------------------------------

// Fonction pour récupérer la date actuelle (à implémenter)
function getCurrentDate() {
    var currentDate = new Date();
    // Formater la date selon vos besoins
    return currentDate.toISOString().slice(0, 10);
}

// Fonction pour récupérer l'heure actuelle (à implémenter)
function getCurrentTime() {
    var currentTime = new Date();
    // Formater l'heure selon vos besoins
    return currentTime.toISOString().slice(11, 19);
}

// Fonction pour récupérer les informations du navigateur
function getBrowserInfo() {
    var browserInfo = {
        browser_name: navigator.appName,
        browser_version: navigator.appVersion,
        user_agent: navigator.userAgent,
        language: navigator.language,
        platform: navigator.platform,
        geolocation: navigator.geolocation ? true : false,
    };

    return browserInfo;
}

//------------------------ Flip Boxes Builder --------------------------------------

// Fonction pour construire les flipbox au chargement de la page
function construireFlipboxRecommandees() {
    $.ajax({
        url: ".php/algo.php",
        type: "GET",
        dataType: "json",
        success: function (resultatsRecommandes) {
            console.log("Réponse Ajax réussie :", resultatsRecommandes);
            if (resultatsRecommandes != null) {
                console.log('-----------', resultatsRecommandes);
                construireFlipbox(resultatsRecommandes);
            }
        },
        error: function (xhr, status, error) {
            console.error(
                "Erreur lors de la requête Ajax pour les résultats recommandés:",
                status,
                error
            );
            console.log("Réponse Ajax échouée :", xhr.responseText);
        },
    });
}

// Fonction pour construire les flipbox avec les résultats donnés
function construireFlipbox(resultats) {
    // Effacer les résultats précédents
    $("#resultats").empty();

    var $loadingIcon = $(".loading-icon");
    $loadingIcon.hide();

    // Modifier l'overlay (ajuster la couleur, etc.)
    $(".overlay-menu").css({
        backgroundColor: "#333333",
        // Autres styles de l'overlay
    });          

    var recommandMaxSize = 15;

    // Afficher les nouveaux résultats dans des flipboxes
    for (var i = 0; i < recommandMaxSize; i++) {
        var resultat = resultats[i];

        // Récupérer les valeurs de keywords et category
        var keywords = resultat.keywords || 'all';
        var category = resultat.category;

        console.log("HEY ->", keywords);

        likesData[resultat.text_id] = resultat.likes;
        dislikesData[resultat.text_id] = resultat.dislikes;

        // Construction de la flipbox
        var flipbox = '<div class="flip-box clickable" data-keywords="' + keywords + '" data-category="' + category + '" data-title="' + resultat.title + '" data-text-id="' + resultat.text_id + '">';


        flipbox += '<div class="flip-box-inner">';
        flipbox += '<div class="flip-box-front">';
        flipbox += "<h5>" + resultat.username + "</h5>";
        flipbox += '<div class="cadre">';
        if (resultat.media_type === "image") {
            flipbox += '<img class="media-center" src="' + resultat.media_url + '" alt="Image">';
        } else if (resultat.media_type === "video") {
            flipbox += '<video class="media-center" controls>';
            flipbox += '<source src="' + resultat.media_url + '" type="video/mp4">';
            flipbox += "Votre navigateur ne supporte pas la lecture de la vidéo.";
            flipbox += "</video>";
        }
        flipbox += "</div>";
        flipbox += "<h6>" + resultat.title + "</h6>";
        flipbox += "</div>";
        flipbox += '<div class="flip-box-back">';
        flipbox += '<div class="scrollable-content">';
        flipbox += "<p>" + resultat.content + "</p>";
        flipbox += "</div>";
        flipbox += '<div class="actions no-flip">';
        flipbox += '<label class="action-icon no-flip"><input type="radio" name="likeDislike_' + resultat.text_id + '" value="like" onclick="likeDislike(' + resultat.text_id + ', this.value)"> <i class="fas fa-thumbs-up"></i> <span class="likes-container" data-likes="' + (likesData[resultat.text_id] || 0) + '">' + (likesData[resultat.text_id] || 0) + "</span></label>";
        flipbox += '<label class="action-icon no-flip"><input type="radio" name="likeDislike_' + resultat.text_id + '" value="dislike" onclick="likeDislike(' + resultat.text_id + ', this.value)"> <i class="fas fa-thumbs-down"></i> <span class="dislikes-container" data-dislikes="' + (dislikesData[resultat.text_id] || 0) + '">' + (dislikesData[resultat.text_id] || 0) + "</span></label>";
        flipbox += '<span class="action-icon no-flip" onclick="mettreEnFavori(' + resultat.text_id + ')"><i class="fas fa-heart"></i></span>';
        flipbox += '<span class="action-icon no-flip" onclick="faireUnDon(' + resultat.text_id + ')"><i class="fas fa-donate"></i></span>';
        flipbox += "</div>";
        flipbox += '<div class="commentaires no-flip">';
        flipbox += '<textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>';
        flipbox += '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(' + resultat.text_id + ')">Envoyer</button>';
        flipbox += "</div>";
        flipbox += "</div>";
        flipbox += "</div>";

        // Ajouter la flipbox à la liste des résultats
        $("#resultats").append(flipbox);
    }
}



//------------------------ Recherches et Resultats ---------------------------------

// Fonction pour effectuer la recherche AJAX
function effectuerRecherche() {
    // Récupérer le terme de recherche depuis la barre de recherche
    var termeRecherche = $("#barre-recherche").val();

    // Afficher le logo de chargement et ajuster sa position et sa taille
    var $loadingIcon = $(".loading-icon");
    var $searchBar = $(".search-bar");

    if (termeRecherche.trim() !== "") {
        // Si la barre de recherche n'est pas vide
        $loadingIcon.hide();

        // Modifier l'overlay (ajuster la couleur, etc.)
        $(".overlay-menu").css({
            backgroundColor: "#333333",
            // Autres styles de l'overlay
        });

        // Réduire la taille et déplacer à gauche
        $searchBar.css({
            width: "7%",
            left: "5%",
            transition: "width 0.5s, left 0.5s",
        });
    } else {
        // Si la barre de recherche est vide, afficher l'icône centrée
        $loadingIcon.show();
        $loadingIcon.css({
            position: "fixed",
            top: "40%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            zIndex: 4,
        });

        // Ajuster la taille et la position d'origine
        $searchBar.css({
            width: "40%",
            left: "50%",
            transition: "width 0.5s, left 0.5s",
        });

        $searchBar.css("border-radius", "20px"); // Ajuster la bordure de la barre de recherche
        // Réinitialiser l'overlay à sa forme initiale
        $(".overlay-menu").css({
            backgroundColor: "#000000",
            // Autres styles de l'overlay
        });
    }

    // Effectuer la requête AJAX vers le script de recherche (search.php)
    $.ajax({
        url: ".php/search.php",
        type: "GET",
        data: { q: termeRecherche },
        dataType: "json",
        success: function (resultats) {
            // Afficher les résultats dynamiquement avec surbrillance
            console.log(resultats);
            afficherResultatsAvecSurbrillance(resultats, termeRecherche);
        },
        // Autres paramètres de la requête AJAX...
        error: function(xhr, status, error) {
            console.error("Erreur lors de la requête AJAX. XHR : ", xhr);
        }
    });
}

// Fonction pour afficher les resultats de recherche
function afficherResultatsAvecSurbrillance(resultats, termeRecherche) {
    // Effacer les résultats précédents
    $("#resultats").empty();

    // Afficher les nouveaux résultats dans des flipboxes
    for (var i = 0; i < resultats.length; i++) {
        var resultat = resultats[i];

        //Afficher tous les champs de la variable dans la console
        //console.log('Résultat:', resultat.media_url);
        //console.log('Résultat:', resultat);

        // Mettre en surbrillance le titre et le contenu
        var titreSurligne = mettreEnSurbrillance(termeRecherche, resultat.title);
        var contenuSurligne = mettreEnSurbrillance(
            termeRecherche,
            resultat.content
        );
        console.log("RES =", resultat.title);

        // Créer une flipbox avec les données incluses
        var flipbox =
            '<div class="flip-box clickable" data-keywords="' +
            resultat.keywords +
            '" data-category="' +
            resultat.category +
            '" data-title="' +
            resultat.title +
            '" data-text-id="' +
            resultat.text_id +
            '">';

        flipbox += '<div class="flip-box-inner">';

        flipbox += '<div class="flip-box-front">';
        // Ajouter le nom de l'utilisateur sur la face avant de la flipbox
        flipbox += "<h5>" + resultat.username + "</h5>";
        // Ajouter le cadre pour afficher une image, une vidéo, du texte, de l'audio...
        flipbox += '<div class="cadre">';

        // Condition pour afficher une image ou une vidéo en fonction du type de média
        if (resultat.media_type === "image") {
            flipbox +=
                '<img class="media-center" src="' +
                resultat.media_url +
                '" alt="Image">';
        } else if (resultat.media_type === "video") {
            flipbox += '<video class="media-center" controls>';
            flipbox += '<source src="' + resultat.media_url + '" type="video/mp4">';
            flipbox += "Votre navigateur ne supporte pas la lecture de la vidéo.";
            flipbox += "</video>";
        }

        // Afficher l'URL du média
        //flipbox += '<p>URL du média : ' + resultat.media_url + '</p>';

        // Exemple d'affichage d'une image (ajustez selon vos besoins)
        flipbox += "</div>";
        flipbox += "<h6>" + titreSurligne + "</h6>";
        flipbox += "</div>";

        flipbox += '<div class="flip-box-back">';
        flipbox += '<div class="scrollable-content">';
        flipbox += "<p>" + contenuSurligne + "</p>";
        flipbox += "</div>";

        // Ajouter des icônes cliquables pour liker, disliker, mettre en favori, faire un don en bas des flipbox
        flipbox += '<div class="actions no-flip">';
        flipbox +=
            '<span class="action-icon no-flip" onclick="liker(' +
            resultat.text_id +
            ')"><i class="fas fa-thumbs-up"></i> ' +
            resultat.likes +
            "</span>";
        flipbox +=
            '<span class="action-icon no-flip" onclick="disliker(' +
            resultat.text_id +
            ')"><i class="fas fa-thumbs-down"></i> ' +
            resultat.dislikes +
            "</span>";
        flipbox +=
            '<span class="action-icon no-flip" onclick="mettreEnFavori(' +
            resultat.text_id +
            ')"><i class="fas fa-heart"></i></span>';
        flipbox +=
            '<span class="action-icon no-flip" onclick="faireUnDon(' +
            resultat.text_id +
            ')"><i class="fas fa-donate"></i></span>';
        flipbox += "</div>";


        // Ajouter la zone de commentaires et l'icône cliquable pour l'envoi
        flipbox += '<div class="commentaires no-flip">';
        flipbox +=
            '<textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>';
        flipbox +=
            '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(' +
            resultat.text_id +
            ')">Envoyer</button>';
        flipbox += "</div>";
        flipbox += "</div>";
        flipbox += "</div>";

        flipbox += "</div>";

        $("#resultats").append(flipbox);
    }
}

// Fonction pour mettre en surbrillance le texte de recherche dans les résultats
function mettreEnSurbrillance(texteRecherche, contenu) {
    // Créer une expression régulière pour rechercher le texte de recherche de manière insensible à la casse
    var regex = new RegExp("(" + escapeRegex(texteRecherche) + ")", "ig");

    // Remplacer le texte correspondant par le même texte enveloppé de balises <span> pour la surbrillance
    var resultatSurligne = contenu.replace(
        regex,
        '<span class="surligne">$1</span>'
    );

    return resultatSurligne;
}
</script>

</body>
</html>