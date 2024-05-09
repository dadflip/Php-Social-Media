var offset = 0;

// Récupérez toutes les images à l'intérieur des flipboxes
var images = document.querySelectorAll('.media-center');

// Récupérez l'élément de la fenêtre modale
var modal = document.getElementById("myModal");

// Récupérez l'élément de l'image à l'intérieur de la fenêtre modale
var modalImg = document.getElementById("modalImg");

// Récupérez l'élément qui permet de fermer la fenêtre modale
var span = document.getElementsByClassName("close")[0];


//------------------------ Window Events -------------------------------------------

$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        chargerPlusDePosts();
    }
});


//------------------------ Flip Boxes Builder --------------------------------------


function chargerPlusDePosts() {
    // Effectuer une requête AJAX pour récupérer les posts suivants depuis la base de données
    // Vous pouvez utiliser $.ajax() ou une autre méthode pour cela
    $.ajax({
        url: window.__ajx__ + "load_algo.php",
        method: 'GET',
        data: { offset: offset, limit: 5 }, // Offset et limit pour récupérer les posts suivants
        success: function (response) {
            // Ajouter les nouveaux posts à la liste existante
            construireFlipbox(response);
            offset += 5; // Mettre à jour l'offset pour la prochaine requête
        },
        error: function (xhr, status, error) {
            console.error("Erreur lors du chargement des posts:", status, error);
        }
    });
}

// Définir un gestionnaire d'erreurs Ajax global
$(document).ajaxError(function(event, xhr, settings, thrownError) {
    // Vérifier si l'erreur est liée à la requête Ajax pour les résultats recommandés
    if (settings.url === window.__ajx__ + "algo.php") {
        console.error("Erreur lors de la requête Ajax pour les résultats recommandés:", thrownError);
        
        // Recharger la page après un court délai
        setTimeout(function() {
            location.reload();
        }, 2000); // Rechargement après 2 secondes (2000 millisecondes)
    }
});

// Fonction pour construire les flipbox au chargement de la page
function construireFlipboxRecommandees() {
    $.ajax({
        url: window.__ajx__ + "algo.php",
        type: "GET",
        dataType: "json",
        success: function (resultatsRecommandes) {
            console.log("Réponse Ajax réussie :", resultatsRecommandes);
            if (resultatsRecommandes && resultatsRecommandes.length > 0) {
                console.log(resultatsRecommandes);
                construireFlipbox(resultatsRecommandes);
            } else {
                console.log('Aucun résultat recommandé n\'a été renvoyé.');
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

    var recommandMaxSize = 25;
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
        if(resultat.sensitive_content == 1) {
            var postClass = 'post sensitive';
        } else {
            var postClass = 'post';
        }

        console.log('-----------------' + resultat.sensitive_content);

        // Ajouter le badge avec l'icône d'alerte pour les posts sensibles
        var badge = resultat.sensitive_content == 1 ? '<span class="badge"><i class="fas fa-exclamation-triangle"></i></span>' : '';

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
        if (window.__admin_but__ === true) {
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
            keywordsArray.forEach(function (keyword) {
                // Supprimer les espaces inutiles autour du mot-clé
                keyword = keyword.trim();
                // Diviser le mot-clé en mots individuels
                var words = keyword.split(' ');
                // Boucle pour chaque mot individuel
                words.forEach(function (word) {
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
        if (window.__u__) {
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

        if (window.__u__) {
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



    // Parcourir toutes les images
    images.forEach(function (image) {
        // Ajoutez un écouteur d'événements au clic sur chaque image
        image.addEventListener('click', function () {
            // Affichez la fenêtre modale
            modal.style.display = "block";
            // Affichez l'image sélectionnée à l'intérieur de la fenêtre modale
            modalImg.src = this.src;
        });
    });



    // Lorsque l'utilisateur clique sur le bouton de fermeture, fermez la fenêtre modale
    /*span.onclick = function() {
        modal.style.display = "none";
    }*/

    // Lorsque l'utilisateur clique en dehors de la fenêtre modale, fermez-la également
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }



    // Sélectionner tous les champs de texte par leur classe
    let champsTexte = document.querySelectorAll('.response-zone-textarea');

    // Ajouter les écouteurs d'événements à chaque champ de texte
    champsTexte.forEach(champTexte => {
        // Ajouter un écouteur d'événement pour détecter les mentions lors de la saisie
        champTexte.addEventListener('input', function () {
            detecterMentions(champTexte);
            gererClicMention();
        });

        // Ajouter un écouteur d'événement pour gérer les appuis sur les touches
        champTexte.addEventListener('keyup', gererAppuiTouche);
    });


}

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

$('#resultats').on('click', '.flip-box.clickable', function(event) {
    // Vérifiez si l'élément cliqué a la classe 'no-flip'
    if (!$(event.target).hasClass('no-flip')) {
        // Si l'élément cliqué ne possède pas la classe 'no-flip', effectuez le retournement
        $(this).find('.flip-box-inner').toggleClass('flipped');
    }
});

$(document).ready(function () {
    // Appeler la méthode pour construire les flipbox au chargement de la page
    construireFlipboxRecommandees();

    // Attachez l'événement clic aux flipboxes générées dynamiquement
    $('#resultats').on('click', '.flip-box.clickable', function () {
        // Récupérer l'ID de l'utilisateur depuis la variable de session PHP
        var userId = window.__u__;

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
                success: function (response) {
                    // Le traitement PHP a réussi, vous pouvez traiter la réponse si nécessaire
                    console.log('Succès :', response);
                },
                error: function (error) {
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

    // Variables pour le délai de saisie et le timeout de recherche
    var typingDelay = 500; // Délai de saisie en millisecondes
    var searchTimeout;

    // Fonction pour gérer la saisie dans la barre de recherche
    $('#barre-recherche input[type="text"]').on('input', function () {
        // Réinitialiser le délai de recherche à chaque saisie
        clearTimeout(searchTimeout);

        // Démarrer le délai de saisie
        searchTimeout = setTimeout(function () {
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

$(document).ready(function () {
    // Gérer le clic sur les filtres
    $('.filtre').click(function () {
        $(this).toggleClass('active');
        // Ajoutez votre logique pour gérer les filtres sélectionnés ici
        var filtresSelectionnes = [];
        $('.filtre.active').each(function () {
            filtresSelectionnes.push($(this).data('value'));
        });
        console.log("Filtres sélectionnés :", filtresSelectionnes);
    });
});

//------------------------ Recherches et Resultats ---------------------------------

function effectuerRecherche(termeRecherche = '') {
    // Si aucun terme de recherche n'est spécifié, récupérer le terme depuis la barre de recherche
    if (termeRecherche === '') {
        termeRecherche = $('#barre-recherche input[type="text"]').val();
    }

    // Récupérer les filtres sélectionnés à partir de la grille d'icônes
    var filtresSelectionnes = {};
    $('.filtre.active').each(function () {
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
        success: function (resultats) {
            // Afficher les résultats dynamiquement avec surbrillance en utilisant le terme de recherche
            console.log(resultats);
            console.log(termeRecherche);
            afficherResultatsAvecTitre(resultats, termeRecherche);
        },
        error: function (xhr, status, error) {
            console.error("Erreur lors de la requête AJAX. XHR : ", xhr);
            console.error('Erreur Ajax :', error);
        }
    });
}

// Ajouter un gestionnaire d'événements sur chaque lien cliquable pour effectuer une recherche au clic
$(document).on('click', '.keyword-link', function (e) {
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
        .on('click', function () {
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
        var postClass = resultat.sensitive_content == 1 ? 'post sensitive' : 'post';
        console.log('-----------------' + resultat.sensitive_content);

        // Ajouter le badge avec l'icône d'alerte pour les posts sensibles
        var badge = resultat.sensitive_content == 1 ? '<span class="badge"><i class="fas fa-exclamation-triangle"></i></span>' : '';

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
        if (window.__admin_but__ === true) {
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
            keywordsArray.forEach(function (keyword) {
                // Supprimer les espaces inutiles autour du mot-clé
                keyword = keyword.trim();
                // Diviser le mot-clé en mots individuels
                var words = keyword.split(' ');
                // Boucle pour chaque mot individuel
                words.forEach(function (word) {
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
        if (window.__u__) {
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

        if (window.__u__) {
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

//-------------------- Follow - Unfollow --------------------------------------------
// Fonction pour vérifier si l'utilisateur suit un autre utilisateur et générer le bouton en conséquence
function checkFollowAndGenerateButton(follower_id, target_id, text_id) {
    $.ajax({
        url: window.__ajx__ + 'follow.php',
        type: 'POST',
        data: {
            action: 'check',
            followerId: follower_id,
            targetUserId: target_id
        },
        success: function (response) {
            // Convertir la réponse en booléen
            var isFollowing = (response === 'true');
            generateButton(target_id, isFollowing, text_id);
        },
        error: function (xhr, status, error) {
            console.error(error); // Gérer les erreurs éventuelles
            // En cas d'erreur, générer le bouton avec la valeur par défaut (Suivre)
            generateButton(target_id, false, text_id);
        }
    });
}

// Fonction pour générer le bouton de suivi
function generateButton(target_id, isFollowing, text_id) {
    var flipbox = '';
    console.log('-----' + isFollowing)
    console.log(text_id);
    if (isFollowing == true && target_id != window.__u__) {
        flipbox += '<div id="follow-button-' + target_id + '" class="follow-button" onclick="unfollowUser(' + target_id + ')">Retirer</div>';
    } else if (isFollowing == false && target_id != window.__u__) {
        flipbox += '<div id="follow-button-' + target_id + '" class="follow-button" onclick="followUser(' + target_id + ')">Suivre</div>';
    } else {
        flipbox += '<div class="no-follow">@me</div>';
    }
    // Ajouter flipbox à l'élément de votre choix dans le DOM
    $('#follow-button-zone' + text_id).html(flipbox);
}

// Fonction pour enlever le caractère '@' d'un ID
function removeAtSign(textId) {
    return textId.replace('@', ''); // Utilisez la méthode replace pour remplacer le caractère '@' par une chaîne vide
}

// Fonction pour remettre le caractère '@' dans un ID
function addAtSign(textId) {
    return '@' + textId; // Ajoutez le caractère '@' devant l'ID
}


// Fonction pour suivre un utilisateur
function followUser(targetUserId) {
    // Récupérer l'ID de l'utilisateur connecté
    var followerId = window.__u__;

    $.ajax({
        url: window.__ajx__ + 'follow.php',
        type: "POST",
        data: {
            action: 'follow',
            followerId: followerId,
            targetUserId: targetUserId
        },
        success: function (response) {
            console.log("Utilisateur suivi avec succès !");
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Erreur Ajax: " + error);
        }
    });
}

// Fonction pour ne plus suivre un utilisateur
function unfollowUser(targetUserId) {
    // Récupérer l'ID de l'utilisateur connecté
    var followerId = window.__u__;

    $.ajax({
        url: window.__ajx__ + 'follow.php',
        type: "POST",
        data: {
            action: 'unfollow',
            followerId: followerId,
            targetUserId: targetUserId
        },
        success: function (response) {
            console.log("Utilisateur non suivi avec succès !");
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Erreur Ajax: " + error);
        }
    });
}


// Fonction pour suivre un utilisateur
function reportUser(targetUserId) {
    // Récupérer l'ID de l'utilisateur connecté
    var userId = window.__u__;

    $.ajax({
        url: window.__ajx__ + 'reportUser.php',
        type: "POST",
        data: {
            reporterId: userId,
            targetId: targetUserId
        },
        success: function (response) {
            console.log("Utilisateur signalé avec succès !");
            window.location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Erreur Ajax: " + error);
        }
    });
}


function removePost(postID, adminID) {
    $.ajax({
        url: window.__ajx__ + "deletePost.php",
        type: "POST",
        data: {
            postID,
            adminID
        },
        success: function (result) {
            if (result === "ok") {
                $(`[data-text-id="${postID}"]`).remove();
            } else {
                //Trouver un moyen de mettre l'utilisateur au courant
                alert(result);
                location.reload();
            }
        },
        error: function (xhr, status, error) {
            alert()
            console.error(
                "Erreur lors de la requête Ajax pour l'effacement du post:" + postID,
                status,
                error
            );
            console.log("Réponse Ajax échouée :", xhr.responseText);
        },

    })
}

function markSensitive(postID, adminID) {
    $.ajax({
        url: window.__ajx__ + "markAsSensitive.php",
        type: "POST",
        // dataType: "json",
        data: {
            postID,
            adminID
        },
        success: function (result) {
            console.log("Réponse Ajax réussie :", result);
            if (result === "ok") {
                console.log("marked as sensitive");
                location.reload();
            } else {
                alert(result);
            }
        },
        error: function (xhr, status, error) {
            console.error(
                "Erreur lors de la requête Ajax pour l'effacement du post:" + postID,
                status,
                error
            );
            console.log("Réponse Ajax échouée :", xhr.responseText);
        },

    })
}

function getFileTypeFromUrl(url) {
    // Extraire l'extension du fichier de l'URL
    var extension = url.split('.').pop().toLowerCase();

    // Déterminer le type de fichier en fonction de l'extension
    switch (extension) {
        case 'pdf':
            return 'pdf'; // PDF
        case 'xls':
        case 'xlsx':
            return 'excel'; // Classeur Excel
        // Ajoutez d'autres cas pour les autres types de fichiers si nécessaire
        default:
            return 'default'; // Autres types de fichiers par défaut
    }
}


// Fonction pour détecter les mentions dans un champ de texte et les remplacer par des liens cliquables
function detecterMentions(champTexte) {
    // Récupérer le contenu du champ de texte
    var contenu = champTexte.value;

    // Regex pour détecter les mentions avec le format @texte et #texte
    var regexMentions = /(@|#)(\w+)\s/g;

    // Remplacer les mentions par des liens cliquables
    var contenuModifie = contenu.replace(regexMentions, function (match, typeMention, texteMentionne) {
        // Déterminer le type de mention
        var lienDebut = (typeMention === '@') ? '@[' : '#[';
        var lienFin = ']';

        // Retourner le lien avec le texte mentionné comme contenu
        return lienDebut + texteMentionne + lienFin;
    });

    // Mettre à jour le contenu du champ de texte avec les mentions transformées en liens
    champTexte.value = contenuModifie;
}


// Fonction pour détecter les mentions dans un texte et les remplacer par des liens cliquables
function createLinksMentions(texte) {
    // Regex pour détecter les mentions avec le format @[text]
    var regexMentions1 = /@\[([^\]]+)\]/g;
    // Regex pour détecter les mentions avec le format #[text]
    var regexMentions2 = /#\[([^\]]+)\]/g;

    var contenuModifie = texte.replace(regexMentions1, function (match, texteMentionne) {
        // Retourner le lien avec le texte mentionné comme contenu
        return '<i class="far fa-user"></i><a onclick="gererClicMention()" class="mention-link"> @' + texteMentionne + '</a>';
    });

    // Remplacer les mentions par des liens cliquables
    contenuModifie = contenuModifie.replace(regexMentions2, function (match, texteMentionne) {
        // Retourner le lien avec le texte mentionné comme contenu
        return '<i class="fas fa-hashtag"></i><a class="tag-link">' + texteMentionne + '</a>';
    });

    // Retourner le texte modifié avec les mentions transformées en liens
    return contenuModifie;
}



// Fonction pour gérer l'appui sur les touches "Espace" ou "Entrée" dans le champ de texte
function gererAppuiTouche(event) {
    // Vérifier si la touche appuyée est "Espace" ou "Entrée"
    if (event.keyCode === 32 || event.keyCode === 13) {
        // Détecter les mentions dans le champ de texte
        detecterMentions(event.target);
    }
}

// Variable pour stocker la référence à la fenêtre flottante actuellement ouverte
var fenetreFlottanteActuelle = null;

// Fonction pour gérer le clic sur les liens de mention
function gererClicMention() {
    var liensMention = document.querySelectorAll('.mention-link');
    liensMention.forEach(function (lien) {
        lien.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche le lien de naviguer
            var texteMentionne = lien.textContent.substr(1); // Récupère le texte mentionné sans le caractère @
            if (!fenetreFlottanteActuelle) {
                afficherFenetreFlottante(texteMentionne);
            }
        });
    });
}

// Fonction pour afficher une fenêtre flottante avec le texte mentionné
function afficherFenetreFlottante(texteMentionne) {
    // Créer les éléments de la fenêtre flottante
    var fenetreFlottante = document.createElement('div');
    fenetreFlottante.className = 'fenetre-flottante';

    // Créer un objet contenant les options avec leurs textes et leurs actions
    var options = [
        { text: 'Voir le profil', action: 'voirProfil' },
        { text: 'Envoyer un message', action: 'envoyerMessage' },
    ];

    // Parcourir chaque option et créer un lien pour chaque option
    options.forEach(option => {
        var lien = document.createElement('a');
        lien.href = '#';
        lien.textContent = option.text;
        lien.addEventListener('click', function () {
            // Exécuter l'action associée à l'option
            executerAction(option.action, texteMentionne);
            // Fermer la fenêtre flottante
            fenetreFlottante.remove();
            // Réinitialiser la référence à la fenêtre flottante actuellement ouverte
            fenetreFlottanteActuelle = null;
        });
        // Ajouter le lien à la fenêtre flottante
        fenetreFlottante.appendChild(lien);
    });

    // Créer le bouton de fermeture
    var boutonFermer = document.createElement('button');
    boutonFermer.className = 'bouton-fermer';
    boutonFermer.textContent = 'Fermer';
    boutonFermer.addEventListener('click', function () {
        // Fermer la fenêtre flottante
        fenetreFlottante.remove();
        // Réinitialiser la référence à la fenêtre flottante actuellement ouverte
        fenetreFlottanteActuelle = null;
    });

    // Ajouter le bouton de fermeture à la fenêtre flottante
    fenetreFlottante.appendChild(boutonFermer);

    // Ajouter la fenêtre flottante au corps du document
    document.body.appendChild(fenetreFlottante);

    // Mettre à jour la référence à la fenêtre flottante actuellement ouverte
    fenetreFlottanteActuelle = fenetreFlottante;
}

// Fonction pour exécuter une action associée à une option
function executerAction(action, texteMentionne) {
    // Ici, vous pouvez implémenter le code pour chaque action
    switch (action) {
        case 'voirProfil':
            window.location.href = window.__u_url__ + '?@userid=' + texteMentionne;
            break;
        case 'envoyerMessage':
            window.location.href = window.__app_url__ + '/chat.php?user=' + removeAtSign(texteMentionne);
            break;
    }
}


// Fonction récursive pour construire les éléments HTML des commentaires et de leurs réponses
function buildCommentsHTML(comments) {
    let html = '';
    comments.forEach(comment => {
        // Transformer les mentions en liens cliquables
        var contenuAvecMentions = createLinksMentions(comment.content);

        html += '<div class="comment">';
        html += '<p>' + contenuAvecMentions + '</p>'; // Contenu du commentaire avec mentions transformées
        html += '<p>userID : ' + comment.users_uid + '</p>';
        html += '<p>' + comment.datetime + '</p>'; // Date et heure du commentaire
        html += '<div class="response-zone no-flip">';
        html += '<textarea class="response-zone-textarea no-flip" id="zoneCommentaire' + comment.comment_id + '" placeholder="Répondre"></textarea>';
        html += '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(\'' + comment.comment_id + '\')">Répondre</button>';
        html += '</div>';

        // Vérifier s'il y a des réponses à ce commentaire
        if (comment.replies && comment.replies.length > 0) {
            // Ajouter un bouton pour replier/déplier les réponses
            html += '<button class="toggle-replies-btn" onclick="toggleReplies(this)">Afficher les réponses</button>';
            // Ajouter les réponses dans un conteneur caché par défaut
            html += '<div class="replies" style="display: none;">';
            html += buildCommentsHTML(comment.replies); // Appel récursif
            html += '</div>'; // Fin de la div replies
        }

        html += '</div>'; // Fin de la div commentaire
    });
    return html;
}


// Fonction pour replier/déplier les réponses
function toggleReplies(btn) {
    let repliesContainer = btn.nextElementSibling;
    if (repliesContainer.style.display === 'none') {
        repliesContainer.style.display = 'block';
        btn.textContent = 'Masquer les réponses';
    } else {
        repliesContainer.style.display = 'none';
        btn.textContent = 'Afficher les réponses';
    }
}

//-------------------- Like - Dislike --------------------------------------------

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

// Fonction pour envoyer un commentaire via AJAX et construire le composant de commentaire
function envoyerCommentaire(parentId) {
    // Récupérer le contenu du commentaire à partir du textarea
    var commentaireContent = document.getElementById('zoneCommentaire' + parentId).value;

    // Vider le contenu du textarea
    document.getElementById('zoneCommentaire' + parentId).value = "";

    // Envoyer le commentaire via AJAX
    effectuerAction("envoyer_commentaire", {
        textId: parentId,
        commentaire: commentaireContent,
    }, function (resultat) {
        // Une fois que la requête AJAX est réussie, ajouter le nouveau commentaire à la liste des commentaires
        if (resultat.success) {

            // Construire le composant de commentaire
            var nouveauCommentaire = document.createElement('div');
            nouveauCommentaire.classList.add('comment');
            nouveauCommentaire.innerHTML = '<p>' + createLinksMentions(commentaireContent) + '</p>';

            var responseZone = document.createElement('div');
            responseZone.classList.add('response-zone', 'no-flip');
            responseZone.innerHTML = '<textarea class="response-zone-textarea no-flip" id="zoneCommentaire' + resultat.textId + '" placeholder="Répondre"></textarea>' +
                '<button class="envoyer-commentaire no-flip" onclick="envoyerCommentaire(\'' + resultat.textId + '\')">Répondre</button>';
            // Ajouter la zone de réponse à nouveauCommentaire
            nouveauCommentaire.appendChild(responseZone);

            // Trouver le conteneur parent des commentaires
            var parentContainer;

            if (parentId) {
                // Il s'agit d'une réponse à un commentaire existant
                parentContainer = document.getElementById('zoneCommentaire' + parentId).parentNode.parentNode;
            } else {
                // Il s'agit d'un commentaire principal
                parentContainer = document.querySelector('.comments-display');
            }

            // Ajouter le nouveau commentaire à la bonne zone
            parentContainer.appendChild(nouveauCommentaire);


        } else {
            // Gérer les erreurs si nécessaire
            console.error("Erreur lors de l'envoi du commentaire :", resultat.error);
        }
    });
}



// Fonction pour envoyer une action via AJAX
function effectuerAction(action, data, callback) {
    console.log("action =", action);
    console.log("data =", data);
    $.ajax({
        url: window.__ajx__ + "actions.php",
        type: "POST",
        data: { action: action, data: data },
        success: function (response) {
            console.log("action effectuée !");
            console.log(response); // Afficher la réponse dans la console
            // Appeler la fonction de rappel avec la réponse
            if (callback) {
                callback(JSON.parse(response));
            }
        },
        error: function () {
            console.error("Erreur lors de l'action " + action);
        },
    });
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
$('.flip-box').on('change', 'input[type="radio"]', function () {
    var textId = $(this).closest('.flip-box').data('text-id');
    var action = $(this).val(); // Récupérer la valeur (like ou dislike) du bouton radio sélectionné
    likeDislike(textId, action); // Appeler la fonction pour liker ou disliker le texte
});



// Dans la fonction faireUnDon
function faireUnDon(textId) {
    effectuerAction("faire_un_don", { textId: textId });
}


// Fonction pour ouvrir une discussion avec un utilisateur dans un nouvel onglet
function openChat(username) {
    window.open("chat.php?user=" + encodeURIComponent(username), "_blank");
}

// Fonction pour charger la liste des utilisateurs suivis depuis l'API en utilisant Fetch
function loadUsersWithFetch() {
    fetch("cuicui-api-users.php")
        .then(response => response.json())
        .then(users => {
            const userList = document.getElementById("userList");
            // Ajouter chaque utilisateur à la liste avec un lien cliquable pour ouvrir la discussion
            users.forEach(user => {
                const listItem = document.createElement("li");
                const link = document.createElement("a");
                link.textContent = user.username;
                link.href = "#"; // Lien vide pour éviter le rechargement de la page
                link.onclick = () => openChat(user.username); // Ouvrir la discussion au clic sur le lien
                listItem.appendChild(link);
                userList.appendChild(listItem);
            });
        })
        .catch(error => console.error("Erreur lors du chargement des utilisateurs:", error));
}

// Fonction pour charger la liste des utilisateurs suivis depuis l'API en utilisant jQuery
function loadUsersWithJQuery() {
    $.ajax({
        url: window.__ajx__ + 'getUsersForChat.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Créer les composants HTML pour afficher les utilisateurs suivis
            var userListHTML = '<div class="user-list"><h2>Discussions</h2>';
            $.each(data, function (index, user) {
                userListHTML += '<div class="user-card" onclick="openChat(\'' + user.username + '\')">';
                userListHTML += '<p>' + user.username + '</p>';
                userListHTML += '</div>';
                // Vous pouvez également stocker les clés publiques et privées des utilisateurs dans des attributs data pour une utilisation ultérieure
            });
            userListHTML += '</div>';

            // Afficher la liste des utilisateurs suivis dans le conteneur approprié
            $('#userList').html(userListHTML);
        },
        error: function (xhr, status, error) {
            console.error('Erreur lors de la récupération des utilisateurs suivis:', error);
        }
    });
}


// Fonction pour charger les publications d'un utilisateur
function loadUserPosts(userId) {
    $.ajax({
        url: window.__ajx__ + 'getFriendsPosts.php',
        type: 'POST',
        dataType: 'json',
        data: { friendId: userId }, // Passer l'ID de l'utilisateur ami
        success: function (response) {
            if (response.success) {
                // Si la requête a réussi, traiter les données de publication
                var posts = response.posts;
                console.log(posts);
                if (posts.length > 0) {
                    // Si l'utilisateur a des publications, créer le composant HTML pour chaque publication
                    var postsHTML = '';
                    posts.forEach(function (post) {
                        postsHTML += '<div class="friends-post">';
                        postsHTML += '<h3>' + post.title + '</h3>';
                        postsHTML += '<p>' + post.text_content + '</p>';
                        postsHTML += '<p>' + post.category + '</p>';
                        postsHTML += '<p>' + post.post_date + '</p>';
                        postsHTML += '<p>' + post.tags + '</p>';
                        postsHTML += '<a href="view.php?post=' + post.textId + '">';
                        postsHTML += '<button class="flipbox-button-icon"><i class="fas fa-external-link-alt"></i></button>';
                        postsHTML += '</a>';
                        // Ajoutez d'autres éléments HTML pour afficher les détails de la publication si nécessaire
                        postsHTML += '</div>';
                    });
                    // Ajouter les composants HTML des publications à l'élément avec l'ID 'userPosts'
                    $('#userPosts').html(postsHTML);
                } else {
                    // Si l'utilisateur n'a pas de publications, afficher un message indiquant qu'aucune publication n'a été trouvée
                    $('#userPosts').html('<p>Cet utilisateur n\'a pas encore publié de messages.</p>');
                }
            } else {
                // Si la requête a échoué, afficher un message d'erreur
                console.error('Erreur lors de la récupération des publications de l\'utilisateur:', response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Erreur lors du chargement des publications de l\'utilisateur:', error);
        }
    });
}


// Fonction pour charger les amis avec jQuery
function loadFriendsWithJQuery() {
    $.ajax({
        url: window.__ajx__ + 'getUsersForChat.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Créer les composants HTML pour afficher les utilisateurs suivis
            var userListHTML = '<div class="user-list"><h2>Amis</h2>';
            $.each(data, function (index, user) {
                userListHTML += '<div class="user-card" data-user-id="' + user.UID + '">';
                userListHTML += '<p>' + user.username + '</p>';
                
                userListHTML += '<button onclick="unfollowUser(\'' + user.UID + '\')">Unfollow</button>';
                userListHTML += '<div id="userPosts">click to load user\'s post</div>';
                
                userListHTML += '</div>';
                // Vous pouvez également stocker les clés publiques et privées des utilisateurs dans des attributs data pour une utilisation ultérieure
            });
            userListHTML += '</div>';

            // Afficher la liste des utilisateurs suivis dans le conteneur approprié
            $('#friendList').html(userListHTML);

            // Charger les publications de l'utilisateur lorsque vous cliquez sur leur carte utilisateur
            $('.user-card').click(function () {
                var userId = $(this).attr('data-user-id');
                console.log('click' + userId);
                loadUserPosts(userId);
            });
        },
        error: function (xhr, status, error) {
            console.error('Erreur lors de la récupération des utilisateurs suivis:', error);
        }
    });
}

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

$('.mark-as-read').click(function () {
    var notificationId = $(this).data('id');

    $.ajax({
        url: window.__ajx__ + 'markAsRead.php',
        method: 'POST',
        data: { id: notificationId },
        success: function (response) {
            // Actualiser la page ou faire d'autres actions nécessaires
            location.reload();
        },
        error: function (xhr, status, error) {
            // Gérer les erreurs
            console.error(error);
        }
    });
});

$('.delete-btn').click(function () {
    var notificationId = $(this).data('id');

    $.ajax({
        url: window.__ajx__ + 'deleteNotification.php',
        method: 'POST',
        data: { id: notificationId },
        success: function (response) {
            // Actualiser la page ou faire d'autres actions nécessaires
            location.reload();
        },
        error: function (xhr, status, error) {
            // Gérer les erreurs
            console.error(error);
        }
    });
});

// Charger la liste des utilisateurs au chargement de la page en utilisant Fetch
// window.onload = loadUsersWithFetch;

// Ou bien, charger la liste des utilisateurs au chargement de la page en utilisant jQuery
$(document).ready(loadUsersWithJQuery);
$(document).ready(loadFriendsWithJQuery);


// Fonction pour créer des bulles de mots-clés
function createKeywordBubbles() {
    // Récupérer le champ de saisie des mots-clés
    var keywordsInput = document.querySelector('input[name="keywords"]');
    // Récupérer la section des mots-clés
    var keywordsSection = document.getElementById('keywordsSection');

    // Effacer les anciennes bulles de mots-clés
    keywordsSection.innerHTML = '';

    // Vérifier si le champ de saisie des mots-clés contient une virgule
    if (keywordsInput.value.includes(',')) {
        // Diviser la chaîne de mots-clés en un tableau
        var keywordsArray = keywordsInput.value.split(',');
        // Parcourir le tableau des mots-clés
        keywordsArray.forEach(function (keyword) {
            // Créer un élément de bulle pour chaque mot-clé
            var keywordBubble = document.createElement('span');
            keywordBubble.classList.add('keyword-bubble');
            keywordBubble.textContent = keyword.trim(); // Supprimer les espaces autour du mot-clé
            // Ajouter la bulle de mot-clé à la section des mots-clés
            keywordsSection.appendChild(keywordBubble);
        });
    }
}

// Ajouter un écouteur d'événement pour déclencher la création des bulles de mots-clés à chaque modification du champ
document.querySelector('input[name="keywords"]').addEventListener('input', createKeywordBubbles);

document.addEventListener("DOMContentLoaded", function () {
    var toggleButton = document.getElementById("toggle-filtres");
    var filtresDropdown = document.getElementById("filtres");

    toggleButton.addEventListener("click", function () {
        filtresDropdown.classList.toggle("active");
    });
});

// JavaScript pour basculer l'affichage du panneau
document.addEventListener("DOMContentLoaded", function () {
    var toggleButton = document.getElementById("toggle-right-panel");
    var rightPanel = document.querySelector(".right-panel");

    toggleButton.addEventListener("click", function () {
        rightPanel.classList.toggle("active");
    });
});

function deletePost(postID) {
    console.log(postID);
    $.ajax({
        url: window.__ajx__ + 'deletePost.php',
        type: 'POST',
        data: {
            postID: postID
        },
        success: function(response) {
            // Gérer la suppression réussie
            console.log('Post supprimé avec succès !');

            location.reload();
        },
        error: function(xhr, status, error) {
            console.error(error); // Gérer les erreurs éventuelles
        }
    });
}

function editPost(postId) {
    window.location.href = 'edit.php?postId=' + postId;
}


function unbanUser(userId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", window.__ajx__ + "unbanUser.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            location.reload();
        }
    };
    xhr.send("userId=" + userId);
}

function markAsNonSensitive(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", window.__ajx__ + "markAsNonSensitive.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            location.reload();
        }
    };
    xhr.send("postId=" + postId);
}