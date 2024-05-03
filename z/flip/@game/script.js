let currentQuestionIndex = 0;
let totalExp = 0;

// Charger les questions à partir du fichier JSON (à remplacer par votre propre méthode de chargement)
fetch('questions.json')
    .then(response => response.json())
    .then(data => {
        const questions = data.questions;
        displayQuestion(questions[currentQuestionIndex]);
    });

// Fonction pour afficher une question et ses options de réponse
function displayQuestion(question) {
    const questionContainer = document.getElementById("question-container");
    const optionsContainer = document.getElementById("options-container");
    const answerContainer = document.getElementById("answer-container");
    const nextBtn = document.getElementById("next-question-btn");
    const expSpan = document.getElementById("exp");

    questionContainer.textContent = question.question;

    optionsContainer.innerHTML = "";
    question.options.forEach(option => {
        const optionButton = document.createElement("button");
        optionButton.textContent = option;
        optionButton.classList.add("option");
        optionButton.addEventListener("click", () => checkAnswer(option, question.answer, nextBtn, expSpan));
        optionsContainer.appendChild(optionButton);
    });

    answerContainer.textContent = ""; // Réinitialiser la réponse précédente
    nextBtn.style.display = "none";
}

// Fonction pour vérifier la réponse sélectionnée
function checkAnswer(selectedAnswer, correctAnswer, nextBtn, expSpan) {
    const answerContainer = document.getElementById("answer-container");
    const expPoints = calculateExpPoints(); // Calculer les points d'expérience

    if (selectedAnswer === correctAnswer) {
        answerContainer.textContent = "Bonne réponse ! La réponse est : " + correctAnswer;
        totalExp += expPoints;
    } else {
        answerContainer.textContent = "Mauvaise réponse. La réponse correcte est : " + correctAnswer;
    }

    expSpan.textContent = totalExp;
    nextBtn.style.display = "block";
}

// Fonction pour calculer les points d'expérience en fonction du temps mis pour répondre (exemple)
function calculateExpPoints() {
    // Simulation de calcul de points d'expérience (à remplacer par votre propre logique)
    return Math.floor(Math.random() * 10) + 1; // Nombre aléatoire entre 1 et 10
}

// Gestionnaire d'événement pour le bouton "Question suivante"
document.getElementById("next-question-btn").addEventListener("click", () => {
    currentQuestionIndex++;
    fetch('questions.json')
        .then(response => response.json())
        .then(data => {
            const questions = data.questions;
            if (currentQuestionIndex < questions.length) {
                displayQuestion(questions[currentQuestionIndex]);
            } else {
                alert("Fin du jeu !");
            }
        });
});

window.onload = function() {
    // Charger les collections disponibles depuis le dossier "collections"
    loadCollections();

    // Écouter l'événement "click" sur le bouton "Commencer le jeu"
    document.getElementById("start-game-btn").addEventListener("click", function() {
        // Récupérer la collection sélectionnée
        var selectedCollection = document.getElementById("collection").value;

        // Charger les questions de la collection sélectionnée
        loadQuestions(selectedCollection);
    });
};

function loadQuestions(collectionName) {
    // Faire une requête fetch pour charger les questions de la collection sélectionnée
    fetch('collections/' + collectionName)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur de chargement de la collection');
            }
            return response.json();
        })
        .then(data => {
            const questions = data.questions;
            displayQuestion(questions[currentQuestionIndex]);
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}



// Charger les collections disponibles depuis le dossier "collections"
function loadCollections() {
    // Récupérer l'élément select pour les collections
    var collectionSelect = document.getElementById("collection");

    // Faire une requête fetch pour récupérer la liste des collections disponibles
    fetch("list-collections.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur de chargement des collections");
            }
            return response.json();
        })
        .then(collections => {
            if (Array.isArray(collections)) {
                // Si collections est un tableau, itérer à travers chaque élément et ajouter une option dans le select
                collections.forEach(collection => {
                    var option = document.createElement("option");
                    option.text = collection;
                    option.value = collection;
                    collectionSelect.appendChild(option);
                });
            } else {
                // Si collections est un objet, itérer à travers les clés de l'objet et ajouter une option dans le select
                Object.keys(collections).forEach(key => {
                    var option = document.createElement("option");
                    option.text = key;
                    option.value = collections[key];
                    collectionSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
        });
}

