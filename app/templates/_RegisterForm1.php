<div class='input-field' id="email-field"> 
    <label for='email'>Adresse email</label>  
    <input type='email' name='email' id="email" placeholder='Entrez votre Email' required autocomplete='off'>
</div>
<div class='input-field' id="password-field">
    <label for='password'>Mot de passe</label>  
    <input type='password' name='password' id="password" placeholder='Saisissez votre mot de passe' required autocomplete='off'>
</div>
<div class='input-field' id="confirm-password-field">
    <label for='confirmpass'>Confirmer le mot de passe</label>  
    <input type='password' name='confirmpass' id="confirmpass" placeholder='Confirmez votre mot de passe' required autocomplete='off'>
</div>
<div class='input-field' id="username-field">
    <label for='username'>Nom d'utilisateur</label>
    <input type='text' name='username' id="username" placeholder="Nom d'utilisateur" required minlength='4' maxlength='30' autocomplete='off'>
</div>
<div class="input-field" id="uid-field">
    <label for='uid'>Identifiant</label>
    <input type='text' name='uid' id="uid" placeholder="Identifiant" required minlength='4' maxlength='30' autocomplete='off'>
</div>
<div class='input-field' id="birthday-field">
    <label for='birthday'>Entrez votre date de naissance</label>
    <input type='date' name="birthday" id="birthday">
</div>
<div class='input-field' id="profile-image-field">
    <label for='profile-image'>Image de profil</label>
    <input type='file' name='profile-image' id="profile-image" accept="image/*" required>
</div>
<div class='input-field' id="language-field">
    <label for='language'>Langue</label>
    <select name="language" id="language" required>
        <option value="" disabled selected>Sélectionnez votre langue</option>
        <option value="fr">Français</option>
        <option value="en">English</option>
        <!-- Ajouter d'autres options de langue selon vos besoins -->
    </select>
</div>
<div class='input-field' id="bio-field">
    <label for='bio'>Bio</label>
    <textarea name="bio" id="bio" placeholder="Saisissez votre bio" required></textarea>
</div>
<input type="submit" name="submit" value="Continuer" id="submit-button"> <!-- Ajouter un bouton de soumission -->
<!-- <button id="next-step-button">Continuer</button> -->
<a href="./login.php">Je possède déjà un compte</a>

<script>
    // Récupérer tous les champs et les boutons
    const emailField = document.getElementById("email-field");
    const passwordField = document.getElementById("password-field");
    const confirmPasswordField = document.getElementById("confirm-password-field");
    const usernameField = document.getElementById("username-field");
    const uidField = document.getElementById("uid-field");
    const birthdayField = document.getElementById("birthday-field");
    const profileImage = document.getElementById("profile-image");
    const languageField = document.getElementById("language-field");
    const bioField = document.getElementById("bio-field");
    const submitButton = document.getElementById("submit-button");

    // Cacher tous les champs sauf le premier
    passwordField.style.display = "none";
    confirmPasswordField.style.display = "none";
    usernameField.style.display = "none";
    uidField.style.display = "none";
    birthdayField.style.display = "none";
    profileImage.style.display = "none";
    languageField.style.display = "none";
    bioField.style.display = "none";

    // Ajouter des écouteurs d'événements pour chaque champ
    email.addEventListener("input", function() {
        if (email.validity.valid) {
            passwordField.style.display = "flex";
        }
    });

    password.addEventListener("input", function() {
        if (password.validity.valid) {
            confirmPasswordField.style.display = "flex";
        }
    });

    confirmpass.addEventListener("input", function() {
        if (confirmpass.validity.valid) {
            usernameField.style.display = "flex";
        }
    });

    username.addEventListener("input", function() {
        if (username.validity.valid) {
            uidField.style.display = "flex";
        }
    });

    uid.addEventListener("input", function() {
        if (uid.validity.valid) {
            birthdayField.style.display = "flex";
        }
    });

    birthday.addEventListener("input", function() {
        if (birthday.validity.valid) {
            profileImage.style.display = "flex";
        }
    });

    profileImage.addEventListener("change", function() {
        if (profileImage.files.length > 0) {
            languageField.style.display = "flex";
        }
    });

    language.addEventListener("change", function() {
        if (language.value) {
            bioField.style.display = "flex";
        }
    });

    bio.addEventListener("input", function() {
        if (bio.value) {
            submitButton.style.display = "flex";
        }
    });
</script>
