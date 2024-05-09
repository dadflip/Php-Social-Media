<?php

function generateProfileOptionsPanel($username, $settingsArray) {
    $html = '<button id="togglePanelButton" class="floating-button"><i class="fas fa-file-alt"></i></button>';
    $html .= '<div class="options-panel" id="optionsPanel">';
    $html .= '<div class="forms">';
    $html .= '<form method="post" action"#" class="user-info-change" id="info-change-more">';
    $html .= '<fieldset>';
    $html .= '<legend>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Plus';
            break;
        case "en":
        default:
            $html .= 'More';
            break;
    }

    $html .= '</legend>';
    $html .= '<label for="username-input"><i class="fas fa-user"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Pseudonyme';
            break;
        case "en":
        default:
            $html .= 'Username';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="username-input" type="text" value="' . $username . '" required minlength="4" maxlength="30" autocomplete="off">';

    $html .= '<label for="fullname-input"><i class="fas fa-address-card"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Nom complet';
            break;
        case "en":
        default:
            $html .= 'Full Name';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="fullname-input" type="text" value="' . $settingsArray['additional_info']['fullname'] . '" required maxlength="50">';

    $html .= '<label for="bio-extended-input"><i class="fas fa-file-alt"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Bio étendue';
            break;
        case "en":
        default:
            $html .= 'Extended Bio';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="bio-extended-input"  value="' . $settingsArray['additional_info']['bio_extended'] . '"  maxlength="250">';

    $html .= '<label for="location-input"><i class="fas fa-map-marker-alt"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Localisation';
            break;
        case "en":
        default:
            $html .= 'Location';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="location-input" type="text" value="' . $settingsArray['additional_info']['location'] . '" maxlength="100">';

    $html .= '<label for="social-links-input"><i class="fas fa-share-alt"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Liens sociaux';
            break;
        case "en":
        default:
            $html .= 'Social Links';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="social-links-input" type="text" value="' . $settingsArray['additional_info']['social_links'] . '" maxlength="100">';

    $html .= '<label for="occupation-input"><i class="fas fa-briefcase"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Occupation';
            break;
        case "en":
        default:
            $html .= 'Occupation';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="occupation-input" type="text" value="' . $settingsArray['additional_info']['occupation'] . '" maxlength="50">';

    $html .= '<label for="interests-input"><i class="fas fa-heart"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Centres d\'intérêt';
            break;
        case "en":
        default:
            $html .= 'Interests';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="interests-input" type="text" value="' . $settingsArray['additional_info']['interests'] . '" maxlength="100">';

    $html .= '<label for="languages-input"><i class="fas fa-language"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Langues parlées';
            break;
        case "en":
        default:
            $html .= 'Spoken Languages';
            break;
    }

    $html .= '</label>';
    $html .= '<input name="languages-input" type="text" value="' . $settingsArray['additional_info']['languages_spoken'] . '" maxlength="50">';

    $html .= '<label for="relationship-status-input"><i class="fas fa-ring"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Statut relationnel';
            break;
        case "en":
        default:
            $html .= 'Relationship Status';
            break;
    }

    $html .= '</label>';
    $html .= '<select name="relationship-status-input">';

    $relationship_status = $settingsArray['additional_info']['relationship_status'];

    switch ($GLOBALS['LANG']) {
        case "fr":
            // Options du statut relationnel avec vérification de la valeur par défaut
            $html .= '<option value="single" ' . ($relationship_status === "single" ? 'selected' : '') . '>Célibataire</option>';
            $html .= '<option value="in-a-relationship" ' . ($relationship_status === "in-a-relationship" ? 'selected' : '') . '>En couple</option>';
            $html .= '<option value="married" ' . ($relationship_status === "married" ? 'selected' : '') . '>Marié(e)</option>';
            $html .= '<option value="complicated" ' . ($relationship_status === "complicated" ? 'selected' : '') . '>Relation compliquée</option>';
            break;
        case "en":
        default:
            // Options du statut relationnel avec vérification de la valeur par défaut
            $html .= '<option value="single" ' . ($relationship_status === "single" ? 'selected' : '') . '>Single</option>';
            $html .= '<option value="in-a-relationship" ' . ($relationship_status === "in-a-relationship" ? 'selected' : '') . '>In a Relationship</option>';
            $html .= '<option value="married" ' . ($relationship_status === "married" ? 'selected' : '') . '>Married</option>';
            $html .= '<option value="complicated" ' . ($relationship_status === "complicated" ? 'selected' : '') . '>Complicated</option>';
            break;
    }

    $html .= '</select>';

    $html .= '<label for="birthday-input"><i class="fas fa-birthday-cake"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Anniversaire';
            break;
        case "en":
        default:
            $html .= 'Birthday';
            break;
    }

    $html .= '</label>';
    $html .= '<input type="date" name="birthday-input" value="' . $settingsArray['additional_info']['birthday'] . '" id="birthday-input">';

    $html .= '<label for="privacy-settings"><i class="fas fa-lock"></i> ';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Paramètres de confidentialité - Public';
            break;
        case "en":
        default:
            $html .= 'Privacy Settings - Public';
            break;
    }

    $html .= '</label>';

    $privacy_settings_checked = $settingsArray['additional_info']['privacy_settings'] === "on" ? 'checked' : '';

    $html .= '<input type="checkbox" value="on" name="privacy-settings" id="privacy-settings" ' . $privacy_settings_checked . '>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $html .= 'Notifications Push';
            break;
        case "en":
        default:
            $html .= 'Push Notifications';
            break;
    }

    $html .= '</label>';

    $notifications_checked = $settingsArray['notifications']['push'] === "on" ? 'checked' : '';

    $html .= '<input type="checkbox" value="on" name="notifications" id="notifications" ' . $notifications_checked . '>';

    $html .= '</fieldset>';
    $html .= '<input id="saveButton" type="submit" name="submit-more">';
    $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

?>
