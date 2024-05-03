<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --------------------------- include --------------------------------

include('../authentication/functions.php');

// ---------------------- database manager ----------------------------

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Affecter le premier sous-tableau de $database_configs à $db_flipapp
$db_flipapp = $database_configs[0];

// Initialiser la connexion à la base de données flipapp
$databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

// Sélectionner la base de données
$databaseManager->selectDatabase($db_flipapp['name']);

// ---------------------------------------------------------------------

if(!isset($_SESSION['user_id']) &&  !isset($_SESSION['email'])){
    header('Location:' . path_FLIP_APP);
    exit();
}else{
    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="../_assets/css/flip.style.css">
    <title>Settings</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div id="myModal" class="modal">
          <span class="close">&times;</span>
          <img class="modal-content" id="modalImg">
        </div>       
    </header>

    <div class="header">
        <!-- Bouton pour afficher/masquer le panneau -->
        <button class="page_button1" id="toggleNavBtn">...</button>

        <div class="user-info-bar">
            <?php
            // Vérifiez si l'utilisateur est authentifié
            if (isset($_SESSION['user_id'])) {
                // Récupérez le nom d'utilisateur à partir de la session
                $userName = $_SESSION['username'];
                $userId = $_SESSION['user_id'];

                // Affichez le nom d'utilisateur en majuscules et en gras
                echo '<span class="user-name">' . strtoupper($userName) . '</span>';
            }
            ?>
        </div>

        <div class="topnav">
            <a href="/dadflip/">APPLICATIONS</a>
            <a href="@me.php?usr=<?php echo $userId; ?>">PROFIL</a>
            <a href="#">PARAMETRES</a>
            <a class="split" href="exit.php">DECONNEXION</a>

            <!-- Barre de recherche -->
            <div class="search-bar">
                <input id="barre-recherche" type="text" placeholder="SEARCH BOX : What is your request?" onkeydown="handleKeyPress(event)">
                <div class="search-icon" onclick="effectuerRecherche()">&#10147;</div>
            </div>
        </div>
    </div>
    <div class="settings-container">
        <h2>Settings</h2>
        <div class="tab">
            <button class="tablinks active" onclick="openTab(event, 'personal')">Personal Information</button>
            <button class="tablinks" onclick="openTab(event, 'privacy')">Privacy & Security</button>
            <button class="tablinks" onclick="openTab(event, 'notifications')">Notifications</button>
            <button class="tablinks" onclick="openTab(event, 'extras')">Extras</button>
            <button class="tablinks" onclick="openTab(event, 'support')">Support</button>
        </div>

        <div id="personal" class="tabcontent" style="display:block;">
            <form action="/update-personal-info" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="JohnDoe123">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="johndoe@example.com">
                <label for="profilePic">Profile Picture:</label>
                <input type="file" id="image-input" accept="image/*">
                <div id="image-preview-container">
                    <img id="image-preview" src="#" alt="Image Preview">
                </div>

                <input type="submit" value="Save Personal Info">
            </form>
        </div>

        <div id="privacy" class="tabcontent">
            <form action="/update-privacy" method="post">
                <label for="privacy">Privacy Settings:</label>
                <select id="privacy" name="privacy">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="friends">Friends Only</option>
                </select>
                <label for="ads">Manage Ad Preferences:</label>
                <input type="checkbox" id="ads" name="ads"> Allow personalized ads
                <label for="blockedList">Blocked Users:</label>
                <textarea id="blockedList" name="blockedList" rows="4" placeholder="Enter usernames of users you want to block"></textarea>
                <input type="submit" value="Save Privacy & Security Settings">
            </form>
        </div>

        <div id="notifications" class="tabcontent">
            <form action="/update-notifications" method="post">
                <label for="emailNotifications">Email Notifications:</label>
                <input type="checkbox" id="emailNotifications" name="emailNotifications" checked> Receive email notifications
                <label for="pushNotifications">Push Notifications:</label>
                <input type="checkbox" id="pushNotifications" name="pushNotifications"> Receive push notifications
                <input type="submit" value="Save Notification Settings">
            </form>
        </div>

        <div id="extras" class="tabcontent">
            <h3>Sharing & Invitations</h3>
            <button class="button" onclick="share()">Share</button>
            <button class="button" onclick="inviteFriends()">Invite Friends</button>
            <h3>Device Permissions</h3>
            <label for="camera">Camera Access:</label>
            <select id="camera" name="camera">
                <option value="allow">Allow</option>
                <option value="block">Block</option>
            </select>
            <label for="location">Location Access:</label>
            <select id="location" name="location">
                <option value="allow">Allow</option>
                <option value="block">Block</option>
            </select>
            <h3>Accessibility</h3>
            <label for="textSize">Text Size:</label>
            <select id="textSize" name="textSize">
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>
            <h3>Language</h3>
            <label for="language">Language Preference:</label>
            <select id="language" name="language">
                <option value="en">English</option>
                <option value="fr">French</option>
                <!-- Add more language options -->
            </select>
            <h3>Donations & Fundraisers</h3>
            <button class="button" onclick="donate()">Donate</button>
            <button class="button" onclick="startFundraiser()">Start Fundraiser</button>
            <h3>Account Type</h3>
            <label for="accountType">Account Type:</label>
            <select id="accountType" name="accountType">
                <option value="personal">Personal</option>
                <option value="professional">Professional</option>
                <option value="school">School</option>
                <option value="business">Business</option>
            </select>
            <input type="submit" value="Save Extras">
        </div>

        <div id="support" class="tabcontent">
            <h3>Support</h3>
            <p>If you enjoy using our application, consider supporting us!</p>
            <button class="button" onclick="buyPoints()">Buy Points</button>
            <h3>Points & Rewards</h3>
            <p>Your current points: <span id="points">100</span></p>
            <h3>Badges & Achievements</h3>
            <div id="badges">
                <!-- Display badges dynamically -->
                <p>No badges earned yet.</p>
            </div>
            <h3>Collection</h3>
            <div class="collection">
                <h4>Music Collection</h4>
                <ul>
                    <li>Song 1</li>
                    <li>Song 2</li>
                    <li>Song 3</li>
                    <!-- Add more songs dynamically -->
                </ul>
                <h4>Card Collection</h4>
                <ul>
                    <li>Card 1</li>
                    <li>Card 2</li>
                    <li>Card 3</li>
                    <!-- Add more cards dynamically -->
                </ul>
                <!-- Add more collection items -->
            </div>
        </div>
    </div>

    <!-- Section pour le profil personnel -->
    <section id="personal-profile">
        <div class="personal-details">
            <h4>About Me</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam faucibus lacus vel est accumsan, vel vulputate orci pharetra.</p>
            <h4>Interests</h4>
            <ul>
                <li>Cooking</li>
                <li>Traveling</li>
                <li>Reading</li>
            </ul>
        </div>
    </section>

    <!-- Section pour le profil professionnel -->
    <section id="business-profile">
        <h2>Business Profile</h2>
        <div class="profile-info">
            <img src="business-logo.jpg" alt="Business Logo">
            <h3>XYZ Company</h3>
            <p>@xyzcompany</p>
        </div>
        <div class="business-details">
            <h4>About Us</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam faucibus lacus vel est accumsan, vel vulputate orci pharetra.</p>
            <h4>Services</h4>
            <ul>
                <li>Web Design</li>
                <li>Marketing Consulting</li>
                <li>Graphic Design</li>
            </ul>
        </div>
    </section>

    <!-- Section pour le profil scolaire -->
    <section id="school-profile">
        <h2>School Profile</h2>
        <div class="profile-info">
            <img src="school-logo.jpg" alt="School Logo">
            <h3>XYZ School</h3>
            <p>@xyzschool</p>
        </div>
        <div class="school-details">
            <h4>About Us</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam faucibus lacus vel est accumsan, vel vulputate orci pharetra.</p>
            <h4>Programs</h4>
            <ul>
                <li>Mathematics</li>
                <li>Science</li>
                <li>Art</li>
            </ul>
        </div>
    </section>

    <!-- Section pour le profil d'entreprise -->
    <section id="company-profile">
        <h2>Company Profile</h2>
        <div class="profile-info">
            <img src="company-logo.jpg" alt="Company Logo">
            <h3>XYZ Corporation</h3>
            <p>@xyzcorporation</p>
        </div>
        <div class="company-details">
            <h4>About Us</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam faucibus lacus vel est accumsan, vel vulputate orci pharetra.</p>
            <h4>Products</h4>
            <ul>
                <li>Electronics</li>
                <li>Software</li>
                <li>Automobiles</li>
            </ul>
        </div>
    </section>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function share() {
            // Code to handle sharing functionality
            alert("Share functionality is not implemented yet.");
        }

        function inviteFriends() {
            // Code to handle inviting friends functionality
            alert("Invite friends functionality is not implemented yet.");
        }

        function donate() {
            // Code to handle donation functionality
            alert("Donate functionality is not implemented yet.");
        }

        function startFundraiser() {
            // Code to handle starting fundraiser functionality
            alert("Start fundraiser functionality is not implemented yet.");
        }

        function buyPoints() {
            // Code to handle buying points
            alert("Buy points functionality is not implemented yet.");
        }


        document.getElementById('image-input').addEventListener('change', function(e){
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function(event){
                var img = new Image();
                img.src = event.target.result;
                img.onload = function(){
                    var imagePreview = document.getElementById('image-preview');
                    imagePreview.src = this.src;
                    if (this.naturalWidth > this.naturalHeight) {
                        imagePreview.style.maxWidth = '100%';
                        imagePreview.style.height = 'auto';
                    } else {
                        imagePreview.style.height = '100%';
                        imagePreview.style.width = 'auto';
                    }
                    var cropper = new Cropper(imagePreview, {
                        aspectRatio: 1 / 1, // Ratio de l'aspect
                        viewMode: 2, // Vue libre
                        dragMode: 'move', // Déplacement libre
                        autoCropArea: 1, // Zone de recadrage automatique
                        restore: false, // Ne pas restaurer le recadrage initial après une mise à l'échelle
                        guides: true, // Afficher les guides
                        center: true, // Afficher le centre
                        highlight: true, // Afficher les points de recadrage
                        cropBoxResizable: false, // Ne pas autoriser le redimensionnement de la zone de recadrage
                        minCropBoxWidth: 100, // Largeur minimale de la zone de recadrage
                        minCropBoxHeight: 100, // Hauteur minimale de la zone de recadrage
                        responsive: true, // Activer le mode responsive
                        toggleDragModeOnDblclick: false, // Ne pas activer le changement de mode de déplacement sur double clic
                        ready: function () {
                            // Actions après que Cropper est initialisé
                        }
                    });
                };
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('save-button').addEventListener('click', function(){
            var cropper = document.getElementById('image-preview').cropper;
            var croppedCanvas = cropper.getCroppedCanvas({
                width: 300, // Largeur de l'image recadrée
                height: 300 // Hauteur de l'image recadrée
            });
            var croppedImage = croppedCanvas.toDataURL(); // Obtient l'image recadrée en base64
            // Envoyer croppedImage au serveur ou faire d'autres opérations avec l'image
        });

    </script>
</body>
</html>
