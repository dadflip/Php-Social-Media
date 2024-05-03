<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des notifications</title>
    <!-- Inclure Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- CSS pour la mise en page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .filter-input {
            margin-bottom: 10px;
        }
        .notification {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .notification:hover {
            background-color: #f4f4f4;
        }
        .notification i {
            font-size: 24px;
            margin-right: 10px;
        }
        .notification h2 {
            margin: 0;
            font-size: 18px;
        }
        .notification p {
            margin: 10px 0 0;
            font-size: 16px;
        }
        input#filterInput {
            padding: 1em;
            width: -webkit-fill-available;
            margin: 0.3em 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des notifications</h1>
        <input type="text" id="filterInput" oninput="filterNotifications()" placeholder="Filtrer par titre...">
        <div id="notificationsContainer"></div>
    </div>

    <!-- JavaScript pour charger et filtrer les notifications -->
    <script>
        // Déclarer une variable globale pour stocker les notifications
        let allNotifications = [];

        // Fonction pour charger les notifications depuis l'API
        function loadNotifications() {
            fetch('@cuicui-api-notifications.cuicuiapp.php') // Remplacer l'URL par le chemin de votre API
            .then(response => response.json())
            .then(data => {
                allNotifications = data; // Stocker les notifications dans la variable globale
                displayNotifications(allNotifications);
            })
            .catch(error => console.error('Erreur lors du chargement des notifications:', error));
        }

        // Fonction pour filtrer les notifications par titre
        function filterNotifications() {
            const filterValue = document.getElementById('filterInput').value.toLowerCase();
            const filteredNotifications = allNotifications.filter(notification => notification.title.toLowerCase().includes(filterValue));
            displayNotifications(filteredNotifications);
        }

        // Fonction pour afficher les notifications dans la page
        function displayNotifications(notifications) {
            const notificationsContainer = document.getElementById('notificationsContainer');
            notificationsContainer.innerHTML = '';

            notifications.forEach(notification => {
                const notificationElement = document.createElement('div');
                notificationElement.classList.add('notification');
                notificationElement.innerHTML = `
                    <i class="fas fa-bell"></i>
                    <h2>${notification.title}</h2>
                    <p>${notification.text_content}</p>
                    <!-- Ajoutez d'autres informations à afficher -->
                `;
                notificationsContainer.appendChild(notificationElement);
            });
        }

        loadNotifications();

        // Écouteur d'événement pour le filtrage en temps réel
        document.getElementById('filterInput').addEventListener('input', filterNotifications);
    </script>
</body>
</html>
