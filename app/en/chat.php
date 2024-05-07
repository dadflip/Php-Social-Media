<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Afficher le chemin actuel du fichier pour déboguer
    //var_dump(__DIR__);

    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);
    

    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <!-- CSS pour la mise en page -->
    <style>
        .chat-container {
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
        }
        .chat-messages {
            height: 300px;
            overflow-y: scroll;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        #messageInput {
            width: -webkit-fill-available;
            padding: 5px;
            margin-bottom: 10px;
        }
        #sendButton {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <!-- Messages seront ajoutés ici dynamiquement -->
        </div>
        
        <input type="text" id="messageInput" placeholder="Type your message...">
        <button id="sendButton" onclick="sendMessage()">Send</button>
    </div>

    <!-- JavaScript pour la fonctionnalité de chat -->
    <script>
        // Fonction pour envoyer un message
        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim(); // Trim pour enlever les espaces vides au début et à la fin
            if (message === '') {
                return; // Ne rien faire si le champ est vide
            }
            // Ici, vous pouvez envoyer le message à l'API ou au serveur pour traitement et stockage
            // Puis, vous pouvez également ajouter le message à la liste des messages sur cette page
            addMessageToChat(message);
            // Effacer le champ de saisie après l'envoi du message
            messageInput.value = '';
        }

        // Fonction pour ajouter un message à la liste des messages dans le chat
        function addMessageToChat(message) {
            const chatMessages = document.getElementById('chatMessages');
            const messageElement = document.createElement('div');
            messageElement.textContent = message;
            chatMessages.appendChild(messageElement);
            // Faire défiler vers le bas pour afficher le dernier message ajouté
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>
</html>
