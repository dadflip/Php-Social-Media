<?php

include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Affecter le premier sous-tableau de $database_configs à $db_flipapp
$db_flipapp = $database_configs[0];

// Initialiser la connexion à la base de données flipapp
$databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

// Sélectionner la base de données
$databaseManager->selectDatabase($db_flipapp['name']);

// Requête SQL pour sélectionner les utilisateurs sans clés RSA
$query = "SELECT user_id, username FROM users WHERE rsa_public_key IS NULL OR rsa_private_key IS NULL";
$result = $databaseManager->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        $username = $row['username'];

        // Récupérer le mot de passe de l'utilisateur à partir de la base de données
        $passwordQuery = "SELECT password FROM users WHERE user_id = ?";
        $stmtPassword = $databaseManager->getAdminConnection()->prepare($passwordQuery);
        $stmtPassword->bind_param("i", $userId);
        $stmtPassword->execute();
        $passwordResult = $stmtPassword->get_result();
        $userPasswordRow = $passwordResult->fetch_assoc();

        // Vérifier si le mot de passe de l'utilisateur est récupéré avec succès
        if ($userPasswordRow) {
            $userPassword = $userPasswordRow['password'];

            // Générer une paire de clés RSA pour l'utilisateur
            $rsaKeys = generateRSAKeys();
            $publicKey = $rsaKeys['publicKey'];
            $privateKey = $rsaKeys['privateKey'];

            // Chiffrer la clé privée RSA avec le mot de passe de l'utilisateur
            $encryptedPrivateKey = openssl_encrypt($privateKey, 'aes-256-cbc', $userPassword, 0, substr(sha1($userPassword), 0, 16));

            // Mettre à jour la base de données avec les clés RSA générées
            $updateQuery = "UPDATE users SET rsa_public_key = ?, rsa_private_key = ? WHERE user_id = ?";
            $stmt = $databaseManager->getAdminConnection()->prepare($updateQuery);
            $stmt->bind_param("ssi", $publicKey, $encryptedPrivateKey, $userId);
            $stmt->execute();

            // Afficher le nom de l'utilisateur et les clés RSA générées
            echo "Clés RSA générées avec succès pour l'utilisateur $username (ID: $userId).<br>";
            echo "Clé publique RSA : $publicKey<br>";
            echo "Clé privée RSA (chiffrée) : $encryptedPrivateKey<br>";
        } else {
            echo "Impossible de récupérer le mot de passe de l'utilisateur.";
        }
    }
    echo "Tous les utilisateurs sans clé RSA ont été traités.";
} else {
    echo "Aucun utilisateur sans clé RSA trouvé dans la base de données.";
}

function generateRSAKeys() {
    $config = array(
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    $rsaKey = openssl_pkey_new($config);
    openssl_pkey_export($rsaKey, $privateKey);
    $publicKey = openssl_pkey_get_details($rsaKey)['key'];
    return array('publicKey' => $publicKey, 'privateKey' => $privateKey);
}

?>
