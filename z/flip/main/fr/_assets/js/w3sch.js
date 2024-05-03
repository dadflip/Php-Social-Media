// Envoyer des données au serveur
$(document).ready(function() {

    // Attachez l'événement clic aux post-frames générées dynamiquement
    $('#resultats').on('click', '.post-frame.clickable', function() {
        // Récupérer l'ID de l'utilisateur depuis la variable de session PHP
        var userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        
        // Vérifier si l'ID de l'utilisateur est disponible
        if (userId !== null) {

            // Récupérer les autres informations nécessaires depuis les attributs des post-frames
            var title = $(this).find('img').attr('alt');
            var keywords = $(this).attr('data-keywords');
            var category = $(this).attr('data-category');
            var currentDate = getCurrentDate();
            var currentTime = getCurrentTime();
            var browserInfo = getBrowserInfo();

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
        // Fermer le menu lorsque du texte est saisi dans la barre de recherche
        $('#menu').hide();
    });

    // Attacher la fonction de recherche à l'événement de saisie dans la barre de recherche
    $("#barre-recherche").on("input", function () {
        // Déclencher la recherche après un léger délai (par exemple, 300 ms) pour éviter une recherche à chaque frappe
        clearTimeout(window.rechercheTimeout);
        window.rechercheTimeout = setTimeout(effectuerRecherche, 300);
    });

    // Appeler la méthode pour construire les post-frames au chargement de la page
    construirePostFrames();

});



document.getElementById("capturePhoto").addEventListener("click", function () {
    captureMedia("image");
});

document.getElementById("captureVideo").addEventListener("click", function () {
    captureMedia("video");
});

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

// Fonction pour capturer des médias (image ou vidéo)
function captureMedia(mediaType) {
    navigator.mediaDevices
        .getUserMedia({ video: true, audio: true })
        .then(function (stream) {
            // Vous pouvez remplacer la logique de capture de média ici en fonction du type de média
            console.log("Capture de média:", mediaType);
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

    $(document).ready(function () {
        construirePostFrames(); // Appeler la méthode pour construire les post-frames après l'envoi du commentaire
    });
}

// Dans la fonction liker
function liker(textId) {
    effectuerAction("liker", { textId: textId });
    mettreAJourResultats(textId); // Passer textId lors de l'appel
}

// Dans la fonction disliker
function disliker(textId) {
    effectuerAction("disliker", { textId: textId });
    mettreAJourResultats(textId); // Passer textId lors de l'appel
}

// Dans la fonction mettreEnFavori
function mettreEnFavori(textId) {
    effectuerAction("mettre_en_favori", { textId: textId });
    mettreAJourResultats(textId); // Passer textId lors de l'appel
}

// Dans la fonction faireUnDon
function faireUnDon(textId) {
    effectuerAction("faire_un_don", { textId: textId });
    mettreAJourResultats(textId); // Passer textId lors de l'appel
}

// Dans la fonction mettreAJourResultats
function mettreAJourResultats(textId, action) {
    if (!textId) {
        console.error("textId non défini. Impossible de mettre à jour les résultats.");
        return;
    }

    $.ajax({
        url: ".php/search.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
            // La mise à jour a réussi
            var newTextLikes = response.likes;
            var newTextDislikes = response.dislikes;

            console.log("textId:", textId);

            // Trouver le conteneur de likes et dislikes dans le post-frame spécifique
            var likesContainer = $('[data-text-id="' + textId + '"] .likes-container');

            // Récupérer les valeurs actuelles de likes et dislikes
            var likes = parseInt(likesContainer.attr("data-likes"));
            var dislikes = parseInt(likesContainer.attr("data-dislikes"));

            // Mettre à jour les valeurs en fonction de l'action
            if (action === "like") {
                likes++;
            } else if (action === "dislike") {
                dislikes++;
            }

            // Mettre à jour les données dans le conteneur
            likesContainer.attr("data-likes", likes);
            likesContainer.attr("data-dislikes", dislikes);

            // Mettre à jour l'affichage des likes et dislikes dans l'interface utilisateur
            likesContainer.find(".likes").text("Likes: " + likes);
            likesContainer.find(".dislikes").text("Dislikes: " + dislikes);

            // Mettre à jour les résultats recommandés après la mise à jour
            $(document).ready(function () {
                construirePostFrames();
            });
        },
        error: function () {
            console.error("Erreur lors de la mise à jour des résultats");
        },
    });
}


// Dans la fonction effectuerAction
function effectuerAction(action, data) {
    $.ajax({
        url: ".php/actions.php",
        type: "POST",
        data: { action: action, data: data },
        success: function (response) {
            // Traiter la réponse si nécessaire
        },
        error: function () {
            console.error("Erreur lors de l'action " + action);
        },
    });
}

// Fonction pour construire les post-frames au chargement de la page
function construirePostFramesRecommandees() {
    $.ajax({
        url: ".php/algo.php",
        type: "GET",
        dataType: "json",
        success: function (resultatsRecommandes) {
            console.log("Réponse Ajax réussie :", resultatsRecommandes);
            if (resultatsRecommandes != null) {
                construirePostFrames(resultatsRecommandes);
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

// Fonction pour construire les post-frames avec les résultats donnés
function construirePostFrames(resultats) {
    // Effacer les résultats précédents
    $("#resultats").empty();

    var $loadingIcon = $(".loading-icon");

    $loadingIcon.hide();

    // Modifier l'overlay (ajuster la couleur, etc.)
    $(".overlay-menu").css({
        backgroundColor: "#333333",
        // Autres styles de l'overlay
    });

    var recommandMaxSize = 8;

    // Afficher les nouveaux résultats dans des post-frames
    for (var i = 0; i < recommandMaxSize; i++) {
        var resultat = resultats[i];

        var postFrame = '<div class="post-frame" data-text-id="' + resultat.text_id + '">';

        postFrame += '<div class="post-frame-front">';
        postFrame += "<h5>" + resultat.username + "</h5>";

        postFrame += '<div class="cadre">';

        if (resultat.media_type === "image") {
            postFrame += '<img class="media-center" src="' + resultat.media_url + '" alt="Image">';
        } else if (resultat.media_type === "video") {
            postFrame += '<video class="media-center" controls>';
            postFrame += '<source src="' + resultat.media_url + '" type="video/mp4">';
            postFrame += "Votre navigateur ne supporte pas la lecture de la vidéo.";
            postFrame += "</video>";
        }

        postFrame += "</div>";
        postFrame += "<h6>" + resultat.title + "</h6>";
        postFrame += "</div>";

        postFrame += '<div class="post-frame-back">';
        postFrame += '<div class="scrollable-content">';
        postFrame += "<p>" + resultat.content + "</p>";
        postFrame += "</div>";

        postFrame += '<div class="actions no-flip">';
        postFrame += '<span class="action-icon no-flip" onclick="liker(' + resultat.text_id + ')"><i class="fas fa-thumbs-up"></i> ' + resultat.likes + "</span>";
        postFrame += '<span class="action-icon no-flip" onclick="disliker(' + resultat.text_id + ')"><i class="fas fa-thumbs-down"></i> ' + resultat.dislikes + "</span>";
        postFrame += '<span class="action-icon no-flip" onclick="mettreEnFavori(' + resultat.text_id + ')"><i class="fas fa-heart"></i></span>";
        postFrame += '<span class="action-icon no-flip" onclick="faireUnDon(' + resultat.text_id + ')"><i class="fas fa-donate"></i></span>';
        postFrame += "</div>";

        postFrame += '<div class="commentaires no-flip">';
        postFrame += '<textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>';
        postFrame += '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(' + resultat.text_id + ')">Envoyer</button>';
        postFrame += "</div>";

        postFrame += "</div>";
        postFrame += "</div>";

        // Ajouter le post-frame à la liste des résultats
        $("#resultats").append(postFrame);
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
            // Afficher les résultats dans des post-frames
            afficherResultats(resultats);
        },
        error: function () {
            console.error("Erreur lors de la requête AJAX");
        },
    });
}

// Fonction pour afficher les résultats dans des post-frames
function afficherResultats(resultats) {
    // Sélectionner la zone d'affichage des résultats
    var $resultatsZone = $("#resultats");

    // Vider la zone des résultats précédents
    $resultatsZone.empty();

    // Parcourir les résultats et créer les post-frames
    resultats.forEach(function (resultat) {
        // Créer un post-frame avec les informations du résultat
        var postFrame = `
            <div class="post-frame">
                <img src="${resultat.image}" alt="${resultat.alt}">
                <p>${resultat.description}</p>
            </div>
        `;

        // Ajouter le post-frame à la zone des résultats
        $resultatsZone.append(postFrame);
    });
}

// Envoyer des données au serveur
$(document).ready(function () {
    // Attachez la fonction de recherche à l'événement de saisie dans la barre de recherche
    $("#barre-recherche").on("input", function () {
        // Déclencher la recherche après un léger délai (par exemple, 300 ms) pour éviter une recherche à chaque frappe
        clearTimeout(window.rechercheTimeout);
        window.rechercheTimeout = setTimeout(effectuerRecherche, 300);
    });

    // Appeler la méthode pour construire les post-frames au chargement de la page
    construirePostFrames();
});

// Fonction pour construire les post-frames au chargement de la page
function construirePostFrames() {
    // Exemple de post-frames à afficher initialement
    var postsInitiaux = [
        { image: "post1.jpg", alt: "Post 1", description: "Description du post 1" },
        { image: "post2.jpg", alt: "Post 2", description: "Description du post 2" },
        // Ajoutez d'autres posts ici
    ];

    // Afficher les post-frames initiaux
    afficherResultats(postsInitiaux);
}

// Fonction pour afficher les résultats de recherche avec surbrillance
function afficherResultatsAvecSurbrillance(resultats, termeRecherche) {
    // Effacer les résultats précédents
    $("#resultats").empty();

    // Afficher les nouveaux résultats dans des post-frames
    for (var i = 0; i < resultats.length; i++) {
        var resultat = resultats[i];

        // Mettre en surbrillance le titre et le contenu
        var titreSurligne = mettreEnSurbrillance(termeRecherche, resultat.title);
        var contenuSurligne = mettreEnSurbrillance(termeRecherche, resultat.content);

        // Créer un post-frame avec les données incluses
        var postFrame = `
            <div class="post-frame clickable" data-keywords="${resultat.keywords}" data-category="${resultat.category}" data-title="${resultat.title}" data-text-id="${resultat.text_id}">
                <div class="post-frame-inner">
                    <div class="post-frame-front">
                        <h5>${resultat.username}</h5>
                        <div class="cadre">
                            ${resultat.media_type === "image" ? `<img class="media-center" src="${resultat.media_url}" alt="Image">` : 
                            (resultat.media_type === "video" ? `<video class="media-center" controls><source src="${resultat.media_url}" type="video/mp4">Votre navigateur ne supporte pas la lecture de la vidéo.</video>` : '')}
                        </div>
                        <h6>${titreSurligne}</h6>
                    </div>
                    <div class="post-frame-back">
                        <div class="scrollable-content">
                            <p>${contenuSurligne}</p>
                        </div>
                        <div class="actions no-flip">
                            <span class="action-icon no-flip" onclick="liker(${resultat.text_id})"><i class="fas fa-thumbs-up"></i> ${resultat.likes}</span>
                            <span class="action-icon no-flip" onclick="disliker(${resultat.text_id})"><i class="fas fa-thumbs-down"></i> ${resultat.dislikes}</span>
                            <span class="action-icon no-flip" onclick="mettreEnFavori(${resultat.text_id})"><i class="fas fa-heart"></i></span>
                            <span class="action-icon no-flip" onclick="faireUnDon(${resultat.text_id})"><i class="fas fa-donate"></i></span>
                        </div>
                        <div class="commentaires no-flip">
                            <textarea class="no-flip" id="zoneCommentaire" placeholder="Ajouter un commentaire"></textarea>
                            <button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(${resultat.text_id})">Envoyer</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Ajouter le post-frame à la zone des résultats
        $("#resultats").append(postFrame);
    }
}

// Fonction pour mettre en surbrillance le texte de recherche dans les résultats
function mettreEnSurbrillance(texteRecherche, contenu) {
    // Créer une expression régulière pour rechercher le texte de recherche de manière insensible à la casse
    var regex = new RegExp("(" + escapeRegex(texteRecherche) + ")", "ig");

    // Remplacer le texte correspondant par le même texte enveloppé de balises <span> pour la surbrillance
    var resultatSurligne = contenu.replace(regex, '<span class="surligne">$1</span>');

    return resultatSurligne;
}
