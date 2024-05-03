<!DOCTYPE html>
<html lang="en">

<script>
    document.getElementById("toggleSidebar").addEventListener("click", function() {
        document.getElementById("sidebar").classList.toggle("show-sidebar");
    });
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Flip App</title>
    <link rel="stylesheet" href="../assets/style/flip_admin_theme.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2>Configurations rapides</h2>
        <ul>
            <li><a href="#">Paramètres</a></li>
            <li><a href="#">Utilisateurs</a></li>
            <li><a href="#">Préférences</a></li>
        </ul>
    </div>

    <nav class="navbar">
        <ul>
            <button id="toggleSidebar">☰</button> <!-- Bouton pour dévoiler ou masquer le panneau latéral -->
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Profil</a></li>
            <li><a href="#">Paramètres</a></li>
            <li><a href="#">Déconnexion</a></li>
        </ul>
    </nav>

    <div class="container">
        <a href="#" class="card-link">
            <span class="icon">👥</span>
            <span class="text">Gestion des Utilisateurs</span>
        </a>
        <a href="database/" class="card-link">
            <span class="icon">🔧</span>
            <span class="text">Gestion de la Configuration</span>
        </a>
        <a href="#" class="card-link">
            <span class="icon">📊</span>
            <span class="text">Rapports</span>
        </a>
    </div>
</body>
</html>
