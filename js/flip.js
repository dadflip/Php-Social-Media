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

$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() == $(document).height()) {
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
        success: function(response) {
            // Ajouter les nouveaux posts à la liste existante
            construireFlipbox(response);
            offset += 5; // Mettre à jour l'offset pour la prochaine requête
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors du chargement des posts:", status, error);
        }
    });
}

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

    var recommandMaxSize = 15;
    var i = 0;

    // Afficher les nouveaux résultats dans des flipboxes
    while (i < resultats.length && i < recommandMaxSize) {
        var resultat = resultats[i];
        console.log(resultat.textId);

        // Récupérer les valeurs de keywords et category
        var keywords = resultat && resultat.tags ? resultat.tags : 'all';
        var category = resultat && resultat.category ? resultat.category : '';

        likesData[resultat.textId] = resultat.likes;
        dislikesData[resultat.textId] = resultat.dislikes;

        var  flipbox = '<hr><div class="post">';

        // Construction de la flipbox
        flipbox += '<div class="flip-box clickable" data-keywords="' + keywords + '" data-category="' + category + '" data-title="' + resultat.title + '" data-text-id="' + resultat.textId + '">';


        flipbox += '<div class="flip-box-inner">';
        flipbox += '<div class="flip-box-front">';

        flipbox += '<h5 class="user-info">';

        // Ajouter une section pour l'image de profil
        flipbox += '<div class="profile-image">';
        flipbox += '<img src="' + resultat.profile_pic_url + '" alt="Profile Image">';
        flipbox += '</div>';
        
        // Ajouter une section pour le nom d'utilisateur
        flipbox += '<a class="no-flip" href="' + window.__u_url__ + '?@userid=@' + resultat.username + '" class="user-name">';
        flipbox += '<i style="margin: 8px;" class="fas fa-user"></i>' + resultat.username;
        flipbox += '</a>';
        
        // Ajouter une section pour le bouton Suivre
        flipbox += '<span class="follow-section no-flip">';
        flipbox += '<span class="user-icon"></span>';
        flipbox += '<div class="follow-button" onclick="followUser(' + resultat.users_uid + ')">Suivre</div>';
        // Ajouter un bouton pour signaler l'utilisateur
        flipbox += '<div class="report-button" onclick="reportUser(' + resultat.users_uid + ')">Signaler</div>';
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

        } else {
            flipbox += '<div class="sup-cadre">';
            flipbox += '<br>';

            // Ajouter une zone avec des sous-zones
            flipbox += '<div class="action-zone no-flip">';
            // Ajouter des icônes cliquables et d'autres contenus
            flipbox += '<div class="download-icon" onclick="downloadContent()"><i class="fas fa-download"></i></div>';
            flipbox += '<div class="other-content">Autre contenu ici</div>';
            flipbox += '</div>'; // Fermer la div action-zone
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
        
        
            // Construction des commentaires associés au post
            if (resultat.comments && resultat.comments.length > 0) {
                flipbox += '<div class="comments-display">';
                flipbox += buildCommentsHTML(resultat.comments);
                flipbox += '</div>'; // Fin de la div comments-zone
            }

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
    images.forEach(function(image) {
        // Ajoutez un écouteur d'événements au clic sur chaque image
        image.addEventListener('click', function() {
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
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }



    // Sélectionner tous les champs de texte par leur classe
    let champsTexte = document.querySelectorAll('.response-zone-textarea');

    // Ajouter les écouteurs d'événements à chaque champ de texte
    champsTexte.forEach(champTexte => {
        // Ajouter un écouteur d'événement pour détecter les mentions lors de la saisie
        champTexte.addEventListener('input', function() {
            detecterMentions(champTexte);
            gererClicMention();
        });
        
        // Ajouter un écouteur d'événement pour gérer les appuis sur les touches
        champTexte.addEventListener('keyup', gererAppuiTouche);
    });


}

// Fonction pour détecter les mentions dans un champ de texte et les remplacer par des liens cliquables
function detecterMentions(champTexte) {
    // Récupérer le contenu du champ de texte
    var contenu = champTexte.value;
    
    // Regex pour détecter les mentions avec le format @texte et #texte
    var regexMentions = /(@|#)(\w+)\s/g;
    
    // Remplacer les mentions par des liens cliquables
    var contenuModifie = contenu.replace(regexMentions, function(match, typeMention, texteMentionne) {
        // Déterminer le type de mention
        var lienDebut = (typeMention === '@') ? '@[' : '#[';
        var lienFin = ']';
        
        // Retourner le lien avec le texte mentionné comme contenu
        return  lienDebut + texteMentionne + lienFin;
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

    var contenuModifie = texte.replace(regexMentions1, function(match, texteMentionne) {
        // Retourner le lien avec le texte mentionné comme contenu
        return '<i class="far fa-user"></i><a onclick="gererClicMention()" class="mention-link"> @' + texteMentionne + '</a>';
    });      

    // Remplacer les mentions par des liens cliquables
    contenuModifie = contenuModifie.replace(regexMentions2, function(match, texteMentionne) {
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
    liensMention.forEach(function(lien) {
        lien.addEventListener('click', function(event) {
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
        { text: 'Signaler', action: 'signaler' }
        // Ajoutez d'autres options selon vos besoins
    ];
    
    // Parcourir chaque option et créer un lien pour chaque option
    options.forEach(option => {
        var lien = document.createElement('a');
        lien.href = '#';
        lien.textContent = option.text;
        lien.addEventListener('click', function() {
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
    boutonFermer.addEventListener('click', function() {
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
            window.location.href= window.__u_url__ + '?@userid=' + texteMentionne;
            break;
        case 'envoyerMessage':
            // Code pour envoyer un message
            break;
        case 'signaler':
            // Code pour signaler
            break;
        // Ajoutez d'autres cas selon vos besoins
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

//-------------------- Follow - Unfollow --------------------------------------------

function followUser(targetUserId) {
    // Récupérer l'ID de l'utilisateur connecté
    var followerId = window.__u__;

    console.log(targetUserId);
    console.log(followerId);
    
    var data = {
        followerId: followerId, // Utilisez la valeur correcte
        targetUserId: targetUserId // Utilisez la valeur correcte
    };
    
    $.ajax({
        url: window.__ajx__ + 'follow.php',
        type: "POST",
        data: data,
        success: function(response) {

            console.log("Utilisateur suivi avec succès !");

        },
        error: function(xhr, status, error) {
            console.error("Erreur Ajax: " + error);
        }
    });    
}


function envoyerCommentaire(parentId) {
    var commentaireContent = document.getElementById('zoneCommentaire' + parentId).value;
    console.log(commentaireContent);

    // Envoyer le commentaire via AJAX
    effectuerAction("envoyer_commentaire", {
        textId: parentId,
        commentaire: commentaireContent,
    });

    // Vider le contenu du textarea
    document.getElementById('zoneCommentaire' + parentId).value = "";
    // Recharger la page pour afficher les modifications
    // window.location.reload();
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
        url: window.__ajx__ + "actions.php",
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
        url:  window.__ajx__ + 'getUsersForChat.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Créer les composants HTML pour afficher les utilisateurs suivis
            var userListHTML = '<div class="user-list"><h2>Discussions</h2>';
            $.each(data, function(index, user) {
                userListHTML += '<div class="user-card" onclick="openChat(\'' + user.username + '\')">';
                userListHTML += '<p>' + user.username + '</p>';
                userListHTML += '</div>';
                // Vous pouvez également stocker les clés publiques et privées des utilisateurs dans des attributs data pour une utilisation ultérieure
            });
            userListHTML += '</div>';

            // Afficher la liste des utilisateurs suivis dans le conteneur approprié
            $('#userList').html(userListHTML);
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la récupération des utilisateurs suivis:', error);
        }
    });
}


// Charger la liste des utilisateurs au chargement de la page en utilisant Fetch
// window.onload = loadUsersWithFetch;

// Ou bien, charger la liste des utilisateurs au chargement de la page en utilisant jQuery
$(document).ready(loadUsersWithJQuery);


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
        keywordsArray.forEach(function(keyword) {
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
