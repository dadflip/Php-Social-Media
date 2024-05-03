#!/bin/bash

# Demander à l'utilisateur d'entrer le chemin d'accès complet du dossier à modifier
read -p "Veuillez entrer le chemin d'accès complet du dossier à modifier : " dossier

# Vérifier si le dossier existe
if [ ! -d "$dossier" ]; then
    echo "Le dossier spécifié n'existe pas."
    exit 1
fi

# Récupérer l'utilisateur Apache en examinant la configuration du serveur web
utilisateur_apache=$(grep -oP '(?<=User ).*' /etc/apache2/apache2.conf)

# Vérifier si l'utilisateur Apache existe
if id "$utilisateur_apache" &>/dev/null; then
    # Donner les permissions à l'utilisateur Apache sur le dossier spécifié
    chown -R "$utilisateur_apache":"$utilisateur_apache" "$dossier"
    echo "Permissions accordées à l'utilisateur Apache : $utilisateur_apache sur $dossier"
else
    echo "L'utilisateur Apache ($utilisateur_apache) n'existe pas sur ce système."
fi
