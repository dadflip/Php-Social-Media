<?php
    // Inclure le fichier de définition des fonctions
    include '../../defs.functions.php';
    // Inclure le fichier CuicuiManager
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    session_start();

    // Vérifier si l'utilisateur est un administrateur
    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        // Initialiser CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        // Initialiser CuicuiSession
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    } else {
        // Rediriger vers la page d'accueil
        header('Location:' . $appdir['PATH_CUICUI_APP']);
    }

    // Récupérer les utilisateurs bannis
    $banned_users = $cuicui_manager->getBannedUsers();

    // Récupérer les posts marqués comme sensibles
    $sensitive_posts = $cuicui_manager->getSensitivePosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Cuicui App</title>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.admin.theme.css"?> >
    <style>
        body {
            display: flex;
            flex-direction: column;
        }

        .this-container {
            margin-bottom: 20px;
        }

        .this-container h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        table th {
            background-color: #f8f8f821;
            font-weight: bold;
        }

        table tbody tr:hover {
            background-color: #2a45e0;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none; /* Supprimer le soulignement par défaut */
            color: #007bff; /* Couleur du texte */
            font-weight: bold; /* Gras */
            padding: 8px 16px; /* Espacement autour du texte */
            border: 2px solid #007bff; /* Bordure */
            border-radius: 4px; /* Coins arrondis */
            transition: background-color 0.3s, color 0.3s; /* Transition fluide */
        }

        a:hover {
            background-color: #007bff; /* Changement de couleur de fond au survol */
            color: #fff; /* Changement de couleur du texte au survol */
        }
    </style>
</head>
<body>
    <!-- Ajouter la barre de navigation et le panneau latéral ici si nécessaire -->
    <a href="../main.php">Retour</a>

    <!-- Afficher les utilisateurs bannis -->
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
                            <!-- Ajouter d'autres boutons d'actions si nécessaire -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Afficher les posts sensibles -->
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
                            <!-- Ajouter d'autres boutons d'actions si nécessaire -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
        window.__ajx__ = atob("<?php echo base64_encode($appdir['PATH_PHP_DIR'] . '/ajax/main/'); ?>");
    </script>

    <script>
        function unbanUser(userId) {
            // Appeler la fonction PHP pour débannir l'utilisateur avec l'ID userId via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", window.__ajx__ + "unbanUser.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Vérifier la réponse et effectuer des actions en conséquence
                    alert(xhr.responseText); // Afficher la réponse (pour le débogage)
                    location.reload();
                    // Recharger la page ou mettre à jour l'interface utilisateur si nécessaire
                }
            };
            xhr.send("userId=" + userId);
        }

        function markAsNonSensitive(postId) {
            // Appeler la fonction PHP pour marquer le post avec l'ID postId comme non-sensible via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", window.__ajx__ + "markAsNonSensitive.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Vérifier la réponse et effectuer des actions en conséquence
                    alert(xhr.responseText); // Afficher la réponse (pour le débogage)
                    location.reload();
                    // Recharger la page ou mettre à jour l'interface utilisateur si nécessaire
                }
            };
            xhr.send("postId=" + postId);
        }
    </script>

</body>
</html>
