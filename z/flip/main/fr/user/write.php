<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Messaging</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="message-container">
        <div id="message-display"></div>
    </div>
    <textarea id="message-input" placeholder="Type your message..."></textarea>
    <select id="recipient-user-id">
        <!-- Options for selecting recipient user -->
    </select>
    <button id="send-button">Send</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        // Populate the select element with user options
        fetch('.php/fetch_users.php')
            .then(response => response.json())
            .then(data => {
                const selectElement = document.getElementById('recipient-user-id');
                data.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.user_id;
                    console.log(option.value);
                    option.textContent = user.username;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching users:', error));

        document.getElementById('send-button').addEventListener('click', () => {
            const message = document.getElementById('message-input').value;
            const recipientUserId = document.getElementById('recipient-user-id').value;
            fetchRSAKeysAndSendMessage(message, recipientUserId);
            document.getElementById('message-input').value = '';
        });

        function fetchRSAKeysAndSendMessage(message, recipientUserId) {
            console.log(recipientUserId);
            // Fetch RSA public key of the recipient user from the server
            fetch('.php/fetch_rsa_keys.php?user_id=' + recipientUserId)
                .then(response => response.json())
                .then(data => {
                    const publicKey = data.rsa_public_key;
                    encryptAndSendMessage(message, publicKey);
                })
                .catch(error => console.error('Error fetching RSA keys:', error));
        }

        function encryptAndSendMessage(message, publicKey) {
            // Encrypt message using recipient's public key
            const encryptedMessage = encryptRSA(message, publicKey);
            // Send encrypted message to the server
            sendMessage(encryptedMessage);
        }

        function encryptRSA(message, key) {
            // Implement RSA encryption logic here
            // This should use CryptoJS or some other library
            return CryptoJS.AES.encrypt(message, key).toString();
        }

        function sendMessage(message) {
            // Implement sending message to backend
            // You can use AJAX (e.g., fetch) to send message to PHP backend
            console.log('Sending message:', message);
            // Example of sending message to PHP backend
            fetch('send_message.php', {
                method: 'POST',
                body: JSON.stringify({ message: message }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
