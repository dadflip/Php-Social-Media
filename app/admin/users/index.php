<?php
    include '../../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    session_start();

    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    } else {
        header('Location:' . $appdir['PATH_CUICUI_APP']);
    }

    $banned_users = $cuicui_manager->getBannedUsers();
    $sensitive_posts = $cuicui_manager->getSensitivePosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Admin Dashboard | Cuicui App</title>
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.theme.css"?> >
</head>
<body>
    <a href="../main.php">Retour</a>

    <div class="this-container">
        <h2>Utilisateurs Bannis</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banned_users as $user) : ?>
                    <tr>
                        <td><?php echo $user['UID']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <button onclick="unbanUser(<?php echo $user['UID']; ?>)">Débannir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="this-container">
        <h2>Posts Sensibles</h2>
        <table>
            <thead>
                <tr>
                    <th>ID du Post</th>
                    <th>Auteur</th>
                    <th>Contenu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sensitive_posts as $post) : ?>
                    <tr>
                        <td><?php echo $post['textId']; ?></td>
                        <td><?php echo $post['author']; ?></td>
                        <td><?php echo $post['text_content']; ?></td>
                        <td>
                            <button onclick="markAsNonSensitive('<?php echo $post['textId']; ?>')">Marquer comme non-sensible</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
</body>
</html>
