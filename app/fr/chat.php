<?php 
    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
</head>
<body>
    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <!-- Messages seront ajoutés ici dynamiquement -->
        </div>
        
        <input type="text" id="messageInput" placeholder="Tapez votre message...">
        <button id="sendButton" onclick="sendMessage()">Envoyer</button>
    </div>

    <script>
        window.__u_url__ = atob("<?php echo base64_encode($GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] . "/" . $GLOBALS["LANG"] . $GLOBALS["php_files"]["user"]); ?>");
        window.__ajx__ = atob("<?php echo base64_encode($appdir['PATH_PHP_DIR'] . '/ajax/main/'); ?>");
        window.__u__ = atob("<?php if(isset($_SESSION['UID'])){echo base64_encode($_SESSION['UID']);} ?>");
        window.__img_u__ = atob("<?php if(isset($_SESSION['pfp_url'])){ echo base64_encode($_SESSION['pfp_url']);} ?>");
    </script>

    <!-- JavaScript pour la fonctionnalité de chat -->
    <script>
        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message === '') {
                return;
            }

            const username = getUrlParameter('user'); // Récupérer le nom d'utilisateur de l'URL
            if (!username) {
                console.error('Nom d\'utilisateur non trouvé dans l\'URL');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', window.__ajx__ + 'chat.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        addMessageToChat(message);
                        messageInput.value = '';
                    } else {
                        console.error('Erreur lors de l\'envoi du message');
                    }
                }
            };
            xhr.send('username=' + encodeURIComponent(username) + '&message=' + encodeURIComponent(message));
        }

        function addMessageToChat(message) {
            const chatMessages = document.getElementById('chatMessages');
            const messageElement = document.createElement('div');
            messageElement.textContent = message;
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            const results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }
    </script>
</body>
</html>
