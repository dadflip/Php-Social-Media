<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Collection de Cartes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="collection-form">
        <h1>Créer une Collection de Cartes</h1>
        <form id="card-form">
            <div class="form-group">
                <label for="question">Question :</label>
                <input type="text" id="question" name="question" required>
            </div>
            <div class="form-group">
                <label for="options">Options de réponse (séparées par des virgules) :</label>
                <input type="text" id="options" name="options" required>
            </div>
            <div class="form-group">
                <label for="answer">Réponse Correcte :</label>
                <input type="text" id="answer" name="answer" required>
            </div>
            <button type="submit">Ajouter cette carte</button>
        </form>
        <button id="generate-collection-btn">Générer Collection JSON</button>
    </div>
</body>

<script type="text/javascript">
    /* Créer des collections de cartes */

const cardForm = document.getElementById("card-form");
const collection = [];

cardForm.addEventListener("submit", function(event) {
    event.preventDefault();
    const questionInput = document.getElementById("question").value;
    const optionsInput = document.getElementById("options").value.split(",").map(option => option.trim());
    const answerInput = document.getElementById("answer").value;

    const card = {
        question: questionInput,
        options: optionsInput,
        answer: answerInput
    };

    collection.push(card);
    console.log(collection);

    cardForm.reset();
});

document.getElementById("generate-collection-btn").addEventListener("click", function() {
    const jsonData = {
        questions: collection
    };

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'generate-collection.php', true);
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            console.log('Fichier JSON généré avec succès.');
        }
    };
    xhr.send(JSON.stringify(jsonData));
});
</script>
</html>
