<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Cartes à Collectionner - QCM</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="game-container">
        <h1>Jeu de Cartes à Collectionner - QCM</h1>
        <div id="collection-selector">
            <label for="collection">Choisir une collection :</label>
            <select id="collection">
                <!-- Les options de collection seront chargées dynamiquement depuis le dossier collections -->
            </select>
            <button id="start-game-btn">Commencer le jeu</button>
        </div>
        <div id="question-container">
            <!-- La question actuellement affichée -->
        </div>
        <div id="options-container">
            <!-- Les options de réponse -->
        </div>
        <div id="answer-container">
            <!-- La réponse correcte -->
        </div>
        <button id="next-question-btn" style="display: none;">Question suivante</button>
        <p id="exp-points">Points d'expérience : <span id="exp">0</span></p>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
