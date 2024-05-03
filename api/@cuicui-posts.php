<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Interface de Filtrage</title>
    <!-- CSS pour la mise en page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            max-width: 800px;
            margin: 0 auto;
        }
        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 10px;
        }
        .sidebar button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .sidebar button:hover {
            background-color: #45a049;
        }
        .content {
            flex: 1;
            padding-left: 20px;
        }
        .filter-input {
            margin-bottom: 10px;
        }
        input#filterInput {
            padding: 1em;
            width: -webkit-fill-available;
            margin: 0.3em 0;
        }
        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Outils</h2>
            <button onclick="exportToCSV()"><i class="fas fa-file-csv"></i> Exporter en CSV</button>
            <button onclick="sortByDate()"><i class="fas fa-calendar-alt"></i> Trier par date</button>
            <a href="@cuicui-api-posts.data.cuicuiapp.php"><i class="fas fa-file"> Exploiter les données</i></a>
            <!-- Ajoutez d'autres boutons pour d'autres fonctionnalités -->
        </div>
        <div class="content">
            <h1>Publications</h1>
            <input type="text" id="filterInput" oninput="filterPosts()" placeholder="Filtrer par titre...">
            <div id="postsContainer"></div>
        </div>
    </div>

    <!-- JavaScript pour charger et filtrer les publications -->
    <script>
        // Déclarer une variable globale pour stocker les publications
        let allPosts = [];

        // Fonction pour charger les publications depuis l'API
        function loadPosts() {
            fetch('@cuicui-api-posts.data.cuicuiapp.php') // Remplacer l'URL par le chemin de votre API
            .then(response => response.json())
            .then(data => {
                allPosts = data; // Stocker les publications dans la variable globale
                displayPosts(allPosts);
            })
            .catch(error => console.error('Erreur lors du chargement des publications:', error));
        }

        // Fonction pour filtrer les publications par titre
        function filterPosts() {
            const filterValue = document.getElementById('filterInput').value.toLowerCase();
            const filteredPosts = allPosts.filter(post => post.title.toLowerCase().includes(filterValue));
            displayPosts(filteredPosts);
        }

        // Fonction pour afficher les publications dans la page
        function displayPosts(posts) {
            const postsContainer = document.getElementById('postsContainer');
            postsContainer.innerHTML = '';

            posts.forEach(post => {
                const postElement = document.createElement('div');
                postElement.classList.add('post');
                postElement.innerHTML = `
                    <h3>${post.title}</h3>
                    <p>${post.text_content}</p>
                    <!-- Ajoutez d'autres informations à afficher -->
                `;
                postsContainer.appendChild(postElement);
            });
        }

        // Fonction pour exporter les données au format CSV
        function exportToCSV() {
            const csvContent = "data:text/csv;charset=utf-8," 
                + allPosts.map(post => Object.values(post).join(',')).join('\n');
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "publications.csv");
            document.body.appendChild(link);
            link.click();
        }

        // Fonction pour trier les publications par date
        function sortByDate() {
            allPosts.sort((a, b) => new Date(a.date) - new Date(b.date));
            displayPosts(allPosts);
        }

        // Charger les publications au chargement de la page
        loadPosts();

        // Écouteur d'événement pour le filtrage en temps réel
        document.getElementById('filterInput').addEventListener('input', filterPosts);
    </script>
</body>
</html>
