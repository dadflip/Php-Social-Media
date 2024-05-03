// Récupérer le bouton et le panneau
var button = document.getElementById('togglePanelButton');
var panel = document.getElementById('optionsPanel');

// Variables pour stocker la position de la souris et le décalage du bouton
var offsetX, offsetY;

// Écouter l'événement de clic sur le bouton
button.addEventListener('mousedown', function(e) {
    // Empêcher le comportement par défaut du clic
    e.preventDefault();

    // Calculer le décalage entre la position de la souris et la position du bouton
    offsetX = e.clientX - button.offsetLeft;
    offsetY = e.clientY - button.offsetTop;

    // Écouter l'événement de déplacement de la souris
    document.addEventListener('mousemove', moveButton);
    
    // Écouter l'événement de relâchement du clic de souris
    document.addEventListener('mouseup', releaseButton);
});

// Fonction pour déplacer le bouton selon la position de la souris
function moveButton(e) {
    // Mettre à jour la position du bouton en fonction de la position de la souris et du décalage
    button.style.left = (e.clientX - offsetX) + 'px';
    button.style.top = (e.clientY - offsetY) + 'px';
}

// Fonction pour relâcher le bouton et arrêter de suivre le déplacement
function releaseButton() {
    // Arrêter d'écouter l'événement de déplacement de la souris
    document.removeEventListener('mousemove', moveButton);
    
    // Arrêter d'écouter l'événement de relâchement du clic de souris
    document.removeEventListener('mouseup', releaseButton);
}

document.getElementById('togglePanelButton').addEventListener('click', function() {
    var panel = document.getElementById('optionsPanel');
    if (panel.style.right === '-400px') {
        panel.style.right = '0'; // Afficher le panneau en le déplaçant à droite
    } else {
        panel.style.right = '-400px'; // Masquer le panneau en le déplaçant hors de l'écran
    }
});
