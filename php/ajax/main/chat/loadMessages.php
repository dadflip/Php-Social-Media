<?php
// Inclure les fichiers nécessaires
include '../../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Initialiser CuicuiManager
$cuicui_manager = new CuicuiManager($database_configs, DATASET);

// Récupérer les messages depuis la base de données
$messages = $cuicui_manager->getChatMessages();

// Afficher les messages
foreach ($messages as $message) {
    $username = $cuicui_manager->getUsernameById($message['chat_src_id']);
    $datetime = $message['datetime'];
    $content = $message['content'];
    
    // Déterminer la classe CSS en fonction de l'utilisateur
    $class = ($message['chat_src_id'] == $_SESSION['UID']) ? 'chat-bubble chat-bubble-user' : 'chat-bubble';
    
    // Afficher la bulle de chat
    echo '<div class="' . $class . '">';
    echo '<strong>' . $username  . ' :</strong>';
    echo '<div class="datetime-content">' . $datetime . '</div>';
    echo '<div class="chat-bubble-text">' . $content . '</div>';
    echo '</div>';
}

/*
$messages[] = array(
                'content' => $row['content'],
                'datetime' => $row['datetime'],
                'type' => $row['type'],
                'chat_src_id' => $row['chat_src_id'],
                'chat_dest_id' => $row['chat_dest_id']
            );*/

?>