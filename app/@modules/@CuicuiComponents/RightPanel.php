<?php
function generateProfileOptionsPanel($username) {
    $html = '<button id="togglePanelButton" class="floating-button"><i class="fas fa-file-alt"></i></button>';
    $html .= '<div class="options-panel" id="optionsPanel">';
    $html .= '<div class="forms">';
    $html .= '<form method="post" class="user-info-change" id="info-change">';
    $html .= '<fieldset>';
    $html .= '<legend>Plus</legend>';
    $html .= '<label for="username-input"><i class="fas fa-user"></i> Pseudonyme</label>';
    $html .= '<input name="username-input" type="text" value="' . $username . '" required minlength="4" maxlength="30" autocomplete="off">';

    // Ajouter l'icône pour le nom complet
    $html .= '<label for="fullname-input"><i class="fas fa-address-card"></i> Nom complet</label>';
    $html .= '<input name="fullname-input" type="text" required maxlength="50">';

    // Ajouter l'icône pour la bio étendue
    $html .= '<label for="bio-extended-input"><i class="fas fa-file-alt"></i> Bio étendue</label>';
    $html .= '<textarea id="bio-extended-input" class="biography" rows="5" name="bio-extended" maxlength="250"></textarea>';

    // Ajouter l'icône pour la localisation
    $html .= '<label for="location-input"><i class="fas fa-map-marker-alt"></i> Localisation</label>';
    $html .= '<input name="location-input" type="text" maxlength="100">';

    // Ajouter l'icône pour les liens sociaux
    $html .= '<label for="social-links-input"><i class="fas fa-share-alt"></i> Liens sociaux</label>';
    $html .= '<input name="social-links-input" type="text" maxlength="100">';

    // Ajouter l'icône pour l'occupation
    $html .= '<label for="occupation-input"><i class="fas fa-briefcase"></i> Occupation</label>';
    $html .= '<input name="occupation-input" type="text" maxlength="50">';

    // Ajouter l'icône pour les centres d'intérêt
    $html .= '<label for="interests-input"><i class="fas fa-heart"></i> Centres d\'intérêt</label>';
    $html .= '<input name="interests-input" type="text" maxlength="100">';

    // Ajouter l'icône pour les langues parlées
    $html .= '<label for="languages-input"><i class="fas fa-language"></i> Langues parlées</label>';
    $html .= '<input name="languages-input" type="text" maxlength="50">';

    // Ajouter l'icône pour le statut relationnel
    $html .= '<label for="relationship-status-input"><i class="fas fa-ring"></i> Statut relationnel</label>';
    $html .= '<select name="relationship-status-input">';
    $html .= '<option value="single">Célibataire</option>';
    $html .= '<option value="in-a-relationship">En couple</option>';
    $html .= '<option value="married">Marié(e)</option>';
    $html .= '<option value="complicated">Relation compliquée</option>';
    $html .= '</select>';

    // Ajouter l'icône pour l'anniversaire
    $html .= '<label for="birthday-input"><i class="fas fa-birthday-cake"></i> Anniversaire</label>';
    $html .= '<input type="date" name="birthday-input" id="birthday-input">';

    // Ajouter l'icône pour les paramètres de confidentialité
    $html .= '<label for="privacy-settings"><i class="fas fa-lock"></i> Paramètres de confidentialité</label>';
    $html .= '<input type="checkbox" name="privacy-settings" id="privacy-settings">';

    $html .= '</fieldset>';
    $html .= '<button id="saveButton" type="submit">Changer le profil</button>';
    $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}
