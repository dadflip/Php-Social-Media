<?php

// Inclusion des fichiers requis
include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

// Vérifier si l'identifiant de l'utilisateur est fourni dans la requête GET
if(isset($_GET['user_id'])) {
    // Récupérer l'identifiant de l'utilisateur depuis la requête GET
    $userId = $_GET['user_id'];

    // Instancier un objet de la classe DatabaseManager avec les informations de connexion
    $databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

    // Sélectionner la base de données
    $databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);
    $databaseManager->selectDatabase($db_flipapp['name']);

    // Requête SQL pour récupérer les clés RSA de l'utilisateur
    $query = "SELECT rsa_public_key FROM users WHERE user_id = ?";
    $stmt = $databaseManager->getAdminConnection()->prepare($query);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // L'utilisateur a été trouvé dans la base de données
            $row = $result->fetch_assoc();
            $rsaPublicKey = $row['rsa_public_key'];
            // Renvoyer les clés RSA au format JSON
            echo json_encode(['rsa_public_key' => $rsaPublicKey]);
        } else {
            // L'utilisateur n'a pas été trouvé dans la base de données
            echo json_encode(['error' => 'Utilisateur non trouvé.']);
        }
    } else {
        // La préparation de la requête a échoué
        echo json_encode(['error' => 'Erreur lors de la préparation de la requête.']);
    }
} else {
    // L'identifiant de l'utilisateur n'est pas fourni dans la requête GET
    echo json_encode(['error' => 'Identifiant de l\'utilisateur non fourni.']);
}

?>
