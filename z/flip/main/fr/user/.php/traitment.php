<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

try {
    // Instancier un objet de la classe DatabaseManager avec les informations de connexion
    $databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

    // Affecter le deuxième sous-tableau de $database_configs à $db_algo
    $db_algo = $database_configs[2];

    // Initialiser la connexion à la base de données algo
    $databaseManager->initializeConnection($db_algo['host'], $db_algo['user'], $db_algo['password'], $db_algo['name']);

    // Récupérer la connexion
    $data = $databaseManager->getConnection();

    // Récupérer les données de la requête Ajax
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;
    $currentTime = isset($_POST['currentTime']) ? $_POST['currentTime'] : null;
    $browserInfo = isset($_POST['browserInfo']) ? $_POST['browserInfo'] : null;

    // Extraire les champs de browser_info
    $browserName = $browserInfo['browser_name'];
    $browserVersion = $browserInfo['browser_version'];
    $userAgent = $browserInfo['user_agent'];
    // Ajoutez d'autres champs en fonction de votre structure

    //$stmt = $data->prepare("SELECT COUNT(*) FROM data WHERE (title = ? OR keywords = ?) AND user_id = ?");
    //$stmt->bind_param("ssi", $title, $keywords, $userId);

    // Vérifier si le titre ou les mots clés existent déjà
    $stmt = $data->prepare("SELECT keywords, title, category, u_current_date FROM data WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($existingKeywords, $existingTitle, $existingCategory, $existingCurrentDate);
    $stmt->fetch();
    $stmt->close();

    if ($existingKeywords == $keywords && $existingTitle == $title && $existingCategory == $category) {
        // Si le titre ou les mots clés existent déjà, vous pouvez choisir de mettre à jour les informations ici
        $stmtUpdate = $data->prepare("UPDATE data SET u_current_date = ? WHERE user_id = ?");
        $stmtUpdate->bind_param("si", $currentDate, $userId);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Répondre à la requête Ajax
        echo json_encode(['success' => true, 'message' => 'Les informations existent déjà et ont été mises à jour.']);
    } else {
        // Si le titre ou les mots clés n'existent pas, insérer les nouvelles informations
        $stmtInsert = $data->prepare("INSERT INTO data (user_id, title, keywords, category, u_current_date, u_current_time, browser_name, browser_version, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("issssssss", $userId, $title, $keywords, $category, $currentDate, $currentTime, $browserName, $browserVersion, $userAgent);
        $stmtInsert->execute();

        // Vérifier le succès de l'opération
        if ($stmtInsert->affected_rows > 0) {
            // Répondre à la requête Ajax
            echo json_encode(['success' => true, 'message' => 'Les informations ont été insérées avec succès.']);
        } else {
            // En cas d'échec
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion en base de données']);
        }

        // Fermer la connexion et la requête
        $stmtInsert->close();
    }

    // Vérifier les enregistrements datant de plus d'une semaine
    $oneWeekAgo = date('Y-m-d', strtotime('-1 week')); // Date d'il y a une semaine

    // Requête pour supprimer les enregistrements datant de plus d'une semaine
    $stmtDelete = $data->prepare("DELETE FROM data WHERE user_id = ? AND u_current_date < ?");
    $stmtDelete->bind_param("is", $userId, $oneWeekAgo);
    $stmtDelete->execute();

    // Vérifier le succès de l'opération de suppression
    if ($stmtDelete->affected_rows > 0) {
        // Répondre à la requête Ajax
        echo json_encode(['success' => true, 'message' => 'Les enregistrements datant de plus d\'une semaine ont été supprimés avec succès.']);
    } else {
        // Aucun enregistrement à supprimer
        echo json_encode(['success' => true, 'message' => 'Aucun enregistrement datant de plus d\'une semaine n\'a été trouvé.']);
    }

    // Fermer la connexion et la requête de suppression
    $stmtDelete->close();

    // Fermer la connexion à la base de données
    $data->close();

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

?>
