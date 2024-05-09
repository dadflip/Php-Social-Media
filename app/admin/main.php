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
<html lang="fr">
<head>
    <title>Admin Dashboard | Cuicui App</title>
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.theme.css"?> >
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h2>Configurations rapides</h2>
        <ul>
            <li><a href="users/">Utilisateurs</a></li>
            <li><a href="<?php echo $GLOBALS['normalized_paths']['PATH_API_DIR'] ?>">API</a></li>
        </ul>
    </div>

    <nav class="admin-navbar">
        <ul>
            <button id="toggleSidebar">☰</button>
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
        <a href="reports/" class="card-link">
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

<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</html>

