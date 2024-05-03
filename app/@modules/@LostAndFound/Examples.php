<?php

/* ---------------------------------------------------------------------------------
EXEMPLE UTLISATION :
Configuration de base des bases de données
$database_configs = array(
    Databases
    'databases' => array(
        -- dataset 0
        array(
            'host' => 'localhost',
            'name' => 'cuicui-db',
            'user' => 'root',
            'password' => ''
        ),
        -- dataset 1
        array(
            'host' => 'localhost',
            'name' => '-- set config --',
            'user' => '-- set config --',
            'password' => '-- set config --',
        ),
        -- dataset 2
        array(
            'host' => 'localhost',
            'name' => '-- set config --',
            'user' => '-- set config --',
            'password' => '-- set config --',
        )
    )
);

Création des bases de données à partir de la configuration
MultiDatasetDatabaseManager::createDatabasesFromConfig($database_configs);

Utilisation de CuicuiDB avec le premier dataset
$dataset_index = 0;
$cuicui_db = new CuicuiDB($database_configs, $dataset_index);

Création d'un compte utilisateur
$cuicui_db->create_account();

Sélection du deuxième dataset
$cuicui_db->selectDataset(1);

Création d'un autre compte utilisateur avec le deuxième dataset
$cuicui_db->create_account();

Fermeture de la connexion
$cuicui_db->closeConnection();
*/
?>