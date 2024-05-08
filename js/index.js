function showTab(tabId) {
    // Récupérer tous les conteneurs de contenu d'onglet
    var tabContents = document.querySelectorAll('.tab-content');

    // Parcourir tous les conteneurs de contenu d'onglet
    tabContents.forEach(function (tabContent) {
        // Masquer tous les conteneurs de contenu d'onglet
        tabContent.style.display = 'none';
    });

    // Afficher le conteneur de contenu de l'onglet sélectionné
    var selectedTab = document.getElementById(tabId);
    if (selectedTab) {
        selectedTab.style.display = 'block';

        // Mettre à jour l'URL avec l'ID de l'onglet
        history.pushState(null, null, '#'+tabId);
    }
}


window.onload = function() {
    // Récupérer l'ID de l'onglet à partir de l'URL
    var url = window.location.href;
    var tabId = url.split('#')[1]; // Récupérer la partie après le #
    
    if (tabId) {
        // Si un ID d'onglet est présent dans l'URL, afficher cet onglet
        showTab(tabId);
    } else {
        // Sinon, afficher par défaut le premier onglet
        showTab('tab1');
    }
};

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

    // Inversez l'état d'affichage du formulaire
    if (newTopicForm.style.display === "none") {
        newTopicForm.style.display = "block";
    } else {
        newTopicForm.style.display = "none";
    }
}

function toggleMenu() {
    var menu = document.getElementById("menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// ----------------- User actions ---------------------------------------------

// Fonction pour ajouter des médias en utilisant la fonction captureMedia
function addMedia() {
    uploadMedia();
}

// Fonction pour poster une image en utilisant la fonction captureMedia
function postImage() {
    captureMedia("image");
}

// Fonction pour enregistrer du son en utilisant la fonction captureMedia
function recordAudio() {
    captureMedia("audio");
}

// Fonction pour enregistrer du son en utilisant la fonction captureMedia
function stream() {
    captureMedia("video");
}

function displayImageInModal(imageDataURL) {
    // Créer une fenêtre modale pour afficher l'image
    var modal = document.createElement("div");
    modal.classList.add("modal");

    // Créer une image pour afficher l'image capturée
    var img = document.createElement("img");
    img.src = imageDataURL;
    modal.appendChild(img);

    // Ajouter un bouton de téléchargement
    var downloadBtn = document.createElement("button");
    downloadBtn.textContent = "Télécharger";
    downloadBtn.onclick = function () {
        var a = document.createElement("a");
        a.href = imageDataURL;
        a.download = "image.png";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };
    modal.appendChild(downloadBtn);

    // Ajouter un bouton pour fermer la fenêtre modale
    var closeBtn = document.createElement("button");
    closeBtn.textContent = "Fermer";
    closeBtn.onclick = function () {
        document.body.removeChild(modal);
    };
    modal.appendChild(closeBtn);

    // Ajouter la fenêtre modale à la page
    document.body.appendChild(modal);

    // Variables pour suivre le déplacement de la souris
    var offsetX = 0;
    var offsetY = 0;
    var isDragging = false;

    // Fonction pour déplacer la fenêtre modale
    function dragModal(e) {
        if (isDragging) {
            var x = e.clientX - offsetX;
            var y = e.clientY - offsetY;
            modal.style.left = x + "px";
            modal.style.top = y + "px";
        }
    }

    // Événement pour commencer le déplacement de la souris
    modal.addEventListener("mousedown", function (e) {
        isDragging = true;
        offsetX = e.clientX - modal.getBoundingClientRect().left;
        offsetY = e.clientY - modal.getBoundingClientRect().top;
    });

    // Événement pour arrêter le déplacement de la souris
    window.addEventListener("mouseup", function () {
        isDragging = false;
    });

    // Événement pour déplacer la fenêtre modale lorsque la souris est déplacée
    window.addEventListener("mousemove", dragModal);

    // Variables pour suivre le redimensionnement
    var isResizing = false;
    var prevX = 0;
    var prevY = 0;

    // Fonction pour redimensionner la fenêtre modale
    function resizeModal(e) {
        if (isResizing) {
            var newWidth = modal.offsetWidth + (e.clientX - prevX);
            var newHeight = modal.offsetHeight + (e.clientY - prevY);
            modal.style.width = newWidth + "px";
            modal.style.height = newHeight + "px";
            prevX = e.clientX;
            prevY = e.clientY;
        }
    }

    // Événement pour commencer le redimensionnement
    modal.addEventListener("mousedown", function (e) {
        if (e.target === modal) {
            isResizing = true;
            prevX = e.clientX;
            prevY = e.clientY;
        }
    });

    // Événement pour arrêter le redimensionnement
    window.addEventListener("mouseup", function () {
        isResizing = false;
    });

    // Événement pour redimensionner la fenêtre modale lorsque la souris est déplacée
    window.addEventListener("mousemove", resizeModal);

    // Fermer la fenêtre modale lorsqu'on clique en dehors de l'image
    window.onclick = function (event) {
        if (event.target === modal) {
            document.body.removeChild(modal);
        }
    };
}

// Fonction pour capturer une vidéo avec la caméra
function captureVideo() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            // Afficher le flux vidéo dans une fenêtre modale
            var modal = displayModal();
            var videoElement = document.createElement("video");
            videoElement.id = "videoPlayer";
            videoElement.controls = true;
            videoElement.autoplay = true;
            modal.appendChild(videoElement);
            videoElement.srcObject = stream;

            // Enregistrer la vidéo
            var mediaRecorder = new MediaRecorder(stream);
            var chunks = [];

            mediaRecorder.ondataavailable = function (e) {
                if (e.data.size > 0) {
                    chunks.push(e.data);
                }
            };

            mediaRecorder.onstop = function () {
                var blob = new Blob(chunks, { type: "video/webm" });
                var videoURL = URL.createObjectURL(blob);
                videoElement.src = videoURL;
                closeModal(); // Fermer le modal après l'enregistrement

                // Fermer le flux vidéo
                stream.getTracks().forEach((track) => track.stop());
            };

            mediaRecorder.start();

            // Arrêter l'enregistrement après quelques secondes (à ajuster selon vos besoins)
            setTimeout(function () {
                mediaRecorder.stop();
            }, 15000); // Enregistrer pendant 15 secondes par défaut
        })
        .catch(function(error) {
            console.error("Erreur lors de l'accès à la caméra:", error);
        });
}


function captureMedia(mediaType) {
    navigator.mediaDevices
        .getUserMedia({ video: true, audio: true })
        .then(function (stream) {
            if (mediaType === "image") {
                // Capturer une photo avec la caméra
                var video = document.createElement("video");
                video.srcObject = stream;
                video.autoplay = true;
                document.body.appendChild(video);

                video.onloadedmetadata = function () {
                    var canvas = document.createElement("canvas");
                    var context = canvas.getContext("2d");
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Afficher l'image dans une fenêtre modale avec des outils de traitement
                    displayImageInModal(canvas.toDataURL("image/png"));

                    // Supprimer le composant vidéo du DOM
                    video.remove();

                    // Fermer le flux vidéo
                    stream.getTracks().forEach((track) => track.stop());
                };
            } else if (mediaType === "video") {
                // Utiliser la fonction captureVideo pour capturer la vidéo dans la même fenêtre modale
                captureVideo();
            } else if (mediaType === "audio") {
                console.log('audio');
            }
        })
        .catch(function (error) {
            console.error("Erreur lors de l'accès à la caméra/microphone:", error);
        });
}

// Fonction pour afficher une fenêtre modale
function displayModal() {
    var modal = document.createElement("div");
    modal.className = "modal";
    document.body.appendChild(modal);
    return modal;
}

// Fonction pour fermer la fenêtre modale et nettoyer le résidu vidéo
function closeModal() {
    var modal = document.querySelector(".modal");
    if (modal) {
        // Arrêter tous les lecteurs vidéo dans le modal
        var videoElements = modal.querySelectorAll("video");
        videoElements.forEach(function(videoElement) {
            videoElement.pause(); // Pause la vidéo
            videoElement.srcObject = null; // Supprime la source
        });

        // Retirer le modal du DOM
        modal.remove();
    }
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

// ------------------------------------------------------

/*
// Effacer toutes les variables globales en fin de session
window.addEventListener('unload', function() {
    sessionStorage.clear(); // Efface toutes les variables de session
    localStorage.clear();   // Efface toutes les variables locales
});
*/