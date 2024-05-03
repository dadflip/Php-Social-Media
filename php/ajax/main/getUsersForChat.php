<?php
// Inclure le fichier contenant la classe CuicuiManager
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Initialiser CuicuiManager avec les informations de connexion à la base de données
$cuicui_manager = new CuicuiManager($database_configs, DATASET);



try {

    if(isset($_SESSION['UID'])){
        // ID de l'utilisateur actuel (à remplacer par la manière dont vous obtenez l'ID de l'utilisateur actuel)
        $currentUserId = $_SESSION['UID'];
        
        // Requête SQL pour récupérer les utilisateurs suivis par l'utilisateur actuel
        $query = "SELECT u.UID, u.username, u.rsa_public_key, u.rsa_private_key
                  FROM follow f
                  INNER JOIN users u ON f.target_id = u.UID
                  WHERE f.follower_id = ?";
        
        // Préparer la requête
        $stmt = $cuicui_manager->prepare($query);
        $stmt->bind_param("i", $currentUserId);
        
        // Exécuter la requête
        $stmt->execute();
        
        // Récupérer les résultats
        $result = $stmt->get_result();
        
        $users = array();
        
        // Ajouter les utilisateurs suivis à un tableau
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        // Fermer la requête
        $stmt->close();
        
        // Fermer la connexion à la base de données
        $cuicui_manager->closeConnection();
        
        // Envoyer les données des utilisateurs au format JSON
        echo json_encode($users);
    } else {
        echo json_encode('');
    }

} catch (Exception $e) {
    // Gérer les exceptions
    echo "Erreur: " . $e->getMessage();
}
?>
