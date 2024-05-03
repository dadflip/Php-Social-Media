<!-- Navigation Panel -->
<nav id="floatingNav" class="floating-nav">
    <ul>
        <li><a href="#"><i class="fas fa-home"></i><span class="nav-text">Accueil</span></a></li>
        <li><a href="#"><i class="fas fa-user"></i><span class="nav-text">Profil</span></a></li>
        <li><a href="#"><i class="fas fa-cog"></i><span class="nav-text">Paramètres</span></a></li>
        <li><a href="#"><i class="fas fa-sign-out-alt"></i><span class="nav-text">Déconnexion</span></a></li>
    </ul>
</nav>


<style type="text/css">
/* Bouton pour afficher/masquer le panneau */
#toggleNavBtn {
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 2001; /* Pour être au-dessus du panneau de navigation */
}

/* Floating Navigation */
.floating-nav {
    position: fixed;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    background-color: #ffffff;
    border: 1px groove;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 5px;
    z-index: 2000;
    width: 200px;
    height: 350px;
    transition: width 0.3s ease, height 0.3s ease;
    display: none; /* Par défaut, le panneau est masqué */
}


.floating-nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.floating-nav ul li {
    margin-bottom: 10px;
}

.floating-nav ul li a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center; /* Centrer horizontalement */
    transition: color 0.3s ease;
    padding: 10px; /* Ajout de marge intérieure */
    border-radius: 5px; /* Coins arrondis */
    border: 1px solid #ccc; /* Bordure */
}

.floating-nav ul li a .nav-text {
    margin-left: 10px;
    transition: opacity 0.3s ease;
    opacity: 1;
}

.floating-nav ul li a:hover {
    color: #555;
    background-color: #f0f0f0; /* Changement de couleur de fond au survol */
}

.floating-nav ul li a:hover .nav-text {
    opacity: 0;
}
</style>
<script type="text/javascript">
// JavaScript pour afficher/masquer le panneau de navigation
const toggleNavBtn = document.getElementById('toggleNavBtn');
const floatingNav = document.getElementById('floatingNav');

toggleNavBtn.addEventListener('click', function() {
    floatingNav.classList.toggle('show-nav'); // Ajoute ou retire la classe 'show-nav'
});
</script>