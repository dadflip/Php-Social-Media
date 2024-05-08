<?php
// Inclure les définitions et la classe ChatManager
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['ChatManager']);

// Vérifier si le message est envoyé via la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si le message et le nom d'utilisateur sont définis dans la requête POST
    if (isset($_POST['message']) && isset($_POST['username'])) {
        // Récupérer le message et le nom d'utilisateur depuis la requête POST
        $message = $_POST['message'];
        $username = $_POST['username'];

        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Créer une instance de ChatManager avec la connexion à la base de données
        $chatManager = new ChatManager($cuicui_manager);

        // Exemple : récupérer l'ID de l'utilisateur actuel à partir du nom d'utilisateur
        $userId = $_SESSION['UID'];

        // Exemple : définir l'ID du destinataire (peut être récupéré via l'interface utilisateur)
        $receiverId = $cuicui_manager->getIdByUsername($username); // ID du destinataire

        // Encrypter le message avec la clé publique du destinataire
        $encryptedMessage = $chatManager->encryptMessageRSA($message, $receiverId);

        // Envoyer le message
        $success = $chatManager->sendMessage($userId, $receiverId, $encryptedMessage);

        // Vérifier si l'envoi du message a réussi
        if ($success) {
            // Retourner une réponse JSON indiquant le succès
            echo json_encode(array('success' => true));
        } else {
            // Retourner une réponse JSON indiquant l'échec
            echo json_encode(array('success' => false, 'message' => 'Erreur lors de l\'envoi du message'));
        }
    } else {
        // Retourner une réponse JSON indiquant une erreur si le message ou le nom d'utilisateur n'est pas fourni dans la requête POST
        echo json_encode(array('success' => false, 'message' => 'Message ou nom d\'utilisateur non fourni dans la requête POST'));
    }
} else {
    // Retourner une réponse JSON indiquant une erreur si la méthode de requête n'est pas POST
    echo json_encode(array('success' => false, 'message' => 'Méthode de requête non autorisée'));
}
