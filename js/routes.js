// Fonction pour afficher la barre de navigation quand la souris atteint le centre bas de l'écran
function showNavbar(event) {
    var navbar = document.getElementById('sliding-menu');
    var mouseY = event.clientY;
    var screenHeight = window.innerHeight;
    var threshold = screenHeight * 0.8; // Modifier cette valeur selon vos besoins

    if (mouseY >= threshold) {
        navbar.classList.add('active');
    } else {
        navbar.classList.remove('active');
    }
}

window.addEventListener('mousemove', showNavbar);