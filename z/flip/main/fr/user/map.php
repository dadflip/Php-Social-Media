<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Interactive</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="height: 500px;"></div>
    <script>
        var map = L.map('map').setView([48.8566, 2.3522], 13); // Centrez la carte sur Paris, France

        // Utilisation des tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Ajoutez vos marqueurs ou autres éléments de la carte ici
    </script>
</body>
</html>
