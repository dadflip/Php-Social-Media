<div class='input-field' id="email-field"> 
    <label for='email'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Adresse email' : 'Email Address'; ?></label>  
    <input type='email' name='email' id="email" placeholder='<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Entrez votre Email' : 'Enter your Email'; ?>' required autocomplete='off'>
</div>
<div class='input-field' id="password-field">
    <label for='password'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Mot de passe' : 'Password'; ?></label>  
    <input type='password' name='password' id="password" placeholder='<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Saisissez votre mot de passe' : 'Enter your password'; ?>' required autocomplete='off'>
</div>
<div class='input-field' id="confirm-password-field">
    <label for='confirmpass'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Confirmer le mot de passe' : 'Confirm Password'; ?></label>  
    <input type='password' name='confirmpass' id="confirmpass" placeholder='<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Confirmez votre mot de passe' : 'Confirm your password'; ?>' required autocomplete='off'>
</div>
<div class='input-field' id="username-field">
    <label for='username'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Nom d\'utilisateur' : 'Username'; ?></label>
    <input type='text' name='username' id="username" placeholder="<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Nom d\'utilisateur' : 'Username'; ?>" required minlength='4' maxlength='30' autocomplete='off'>
</div>
<div class="input-field" id="uid-field">
    <label for='uid'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Identifiant' : 'ID'; ?></label>
    <input type='text' name='uid' id="uid" placeholder="<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Identifiant' : 'ID'; ?>" required minlength='4' maxlength='30' autocomplete='off'>
</div>
<div class='input-field' id="birthday-field">
    <label for='birthday'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Entrez votre date de naissance' : 'Enter your date of birth'; ?></label>
    <input type='date' name="birthday" id="birthday">
</div>
<div class='input-field' id="profile-image-field">
    <label for='profile-image'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Image de profil' : 'Profile Image'; ?></label>
    <input type='file' name='profile-image' id="profile-image" accept="image/*" required>
</div>
<div class='input-field' id="language-field">
    <label for='language'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Langue' : 'Language'; ?></label>
    <select name="language" id="language" required>
        <option value="" disabled selected><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Sélectionnez votre langue' : 'Select your language'; ?></option>
        <option value="fr">Français</option>
        <option value="en">English</option>
        <!-- Add more language options as needed -->
    </select>
</div>
<div class='input-field' id="bio-field">
    <label for='bio'><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Bio' : 'Bio'; ?></label>
    <textarea name="bio" id="bio" placeholder="<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Saisissez votre bio' : 'Enter your bio'; ?>" required></textarea>
</div>
<input type="submit" name="submit" value="<?php echo ($GLOBALS['LANG'] === 'fr') ? 'Continuer' : 'Continue'; ?>" id="submit-button"> <!-- Add a submit button -->
<!-- <button id="next-step-button"><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Continuer' : 'Continue'; ?></button> -->
<a href="./login.php"><?php echo ($GLOBALS['LANG'] === 'fr') ? 'Je possède déjà un compte' : 'I already have an account'; ?></a>

<script>
    // Retrieve all fields and buttons
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

    // Hide all fields except the first one
    passwordField.style.display = "none";
    confirmPasswordField.style.display = "none";
    usernameField.style.display = "none";
    uidField.style.display = "none";
    birthdayField.style.display = "none";
    profileImage.style.display = "none";
    languageField.style.display = "none";
    bioField.style.display = "none";

    // Add event listeners for each field
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
