document.getElementById('send-button').addEventListener('click', () => {
    const message = document.getElementById('message-input').value;
    const recipientUserId = document.getElementById('recipient-user-id').value; // Supposons que vous avez un champ de formulaire pour sélectionner l'utilisateur destinataire
    fetchRSAKeysAndSendMessage(message, recipientUserId);
    document.getElementById('message-input').value = '';
});

function fetchRSAKeysAndSendMessage(message, recipientUserId) {
    // Fetch RSA public key of the recipient user from the server
    fetch('fetch_rsa_keys.php?user_id=' + recipientUserId)
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
