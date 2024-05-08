<?php
class ChatManager
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    // Fonction pour envoyer un message
    public function sendMessage($senderId, $receiverId, $message)
    {
        $stmt = $this->database->prepare("INSERT INTO chat (content, datetime, type, chat_src_id, chat_dest_id) VALUES (?, NOW(), 'default', ?, ?)");
        $stmt->bind_param("sii", $message, $senderId, $receiverId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Fonction pour récupérer les messages entre deux utilisateurs
    public function getMessages($userId1, $userId2)
    {
        $stmt = $this->database->prepare("SELECT * FROM chat WHERE (chat_src_id = ? AND chat_dest_id = ?) OR (chat_src_id = ? AND chat_dest_id = ?) ORDER BY datetime ASC");
        $stmt->bind_param("iiii", $userId1, $userId2, $userId2, $userId1);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $messages;
    }

    // Fonction pour encrypter un message (exemple avec base64_encode)
    public function encryptMessage($message)
    {
        return base64_encode($message);
    }

    // Fonction pour décrypter un message (exemple avec base64_decode)
    public function decryptMessage($encryptedMessage)
    {
        return base64_decode($encryptedMessage);
    }

    // Fonction pour stocker les messages sous forme de JSON
    public function storeMessagesAsJson($messages)
    {
        $jsonMessages = json_encode($messages);
        file_put_contents('messages.json', $jsonMessages);
        return true;
    }

    // Fonction pour récupérer les messages stockés sous forme de JSON
    public function retrieveMessagesFromJson()
    {
        $jsonMessages = file_get_contents('messages.json');
        $messages = json_decode($jsonMessages, true);
        return $messages;
    }

    // Fonction pour encrypter un message avec la clé publique d'un utilisateur
    public function encryptMessageRSA($message, $receiverId)
    {
        // Récupérer la clé publique du destinataire depuis la base de données
        $publicKey = $this->getPublicKey($receiverId);

        // Encrypter le message avec la clé publique du destinataire
        openssl_public_encrypt($message, $encrypted, $publicKey);

        // Retourner le message encrypté
        return $encrypted;
    }

    // Fonction pour décrypter un message avec la clé privée de l'utilisateur
    public function decryptMessageRSA($encryptedMessage, $userId)
    {
        // Récupérer la clé privée de l'utilisateur depuis la base de données
        $privateKey = $this->getPrivateKey($userId);

        // Décrypter le message avec la clé privée de l'utilisateur
        openssl_private_decrypt($encryptedMessage, $decrypted, $privateKey);

        // Retourner le message décrypté
        return $decrypted;
    }

    // Fonction pour récupérer la clé publique d'un utilisateur depuis la base de données
    private function getPublicKey($userId)
    {
        $query = "SELECT rsa_public_key FROM users WHERE UID = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['rsa_public_key'];
    }

    // Fonction pour récupérer la clé privée d'un utilisateur depuis la base de données
    private function getPrivateKey($userId)
    {
        $query = "SELECT rsa_private_key FROM users WHERE UID = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param("i", $userId);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['rsa_private_key'];
    }
}
