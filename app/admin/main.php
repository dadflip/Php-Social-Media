<?php
    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    session_start();

    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    }else{
        header('Location:' . $appdir['PATH_CUICUI_APP']);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Flip App</title>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.theme.css"?> >
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2>Configurations rapides</h2>
        <ul>
            <li><a href="#">Utilisateurs</a></li>
            <li><a href="#">Préférences</a></li>
        </ul>
    </div>

    <nav class="navbar">
        <ul>
            <button id="toggleSidebar">☰</button> <!-- Bouton pour dévoiler ou masquer le panneau latéral -->
            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT']; ?>">Accueil</a></li>
            <li><a href="<?php echo $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['stats']; ?>">Profil</a></li>
            <li><a href="<?php echo $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['options']; ?>">Paramètres</a></li>
            <li><a href="<?php echo $GLOBALS['normalized_paths']['PATH_MODULES'] . $GLOBALS['php_files']['disconnect']; ?>">Déconnexion</a></li>
        </ul>
    </nav>

    <div class="container">
        <a href="users/" class="card-link">
            <span class="icon">👥</span>
            <span class="text">Gestion des Utilisateurs</span>
        </a>
        <a href="database/" class="card-link">
            <span class="icon">🔧</span>
            <span class="text">Gestion de la Configuration</span>
        </a>
        <a href="reports" class="card-link">
            <span class="icon">📊</span>
            <span class="text">Rapports</span>
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("toggleSidebar").addEventListener("click", function() {
                document.getElementById("sidebar").classList.toggle("show-sidebar");
            });
        });
    </script>
</body>
</html>

