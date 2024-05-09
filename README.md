# Cuicui App

Cuicui App est un réseau social textuel conçu pour permettre aux utilisateurs de partager des messages avec leurs amis et de rester connectés. Ce projet a été développé dans le cadre du projet de WE4A et SI40, visant à fournir une plateforme de communication conviviale.

## Fonctionnalités

- **Création de compte utilisateur :** Les utilisateurs peuvent créer un compte pour accéder à toutes les fonctionnalités de l'application, un accès sans compte mais avec des fonctionnalités limitées est également possible !.
- **Ajout d'amis et gestion des amis :** Les utilisateurs peuvent ajouter des amis et gérer leur liste d'amis via une interface conviviale.
- **Commentaires sur les publications des amis :** Les utilisateurs peuvent commenter les publications de leurs amis, favorisant ainsi l'interaction et l'engagement.
- **Fonction de signalement des utilisateurs :** Les utilisateurs peuvent signaler les comportements inappropriés ou les abus, contribuant ainsi à maintenir un environnement sûr et respectueux.
- **Gestion des notifications :** Les utilisateurs reçoivent des notifications pour les interactions pertinentes, telles que les nouvelles publications, etc.
- **Rapports sur l'utilisation du serveur :** Les administrateurs peuvent gérer l'application en bannisant en cas de comportements inapropriés.

## Configuration

Pour déployer Cuicui App, vous aurez besoin des éléments suivants :

- Serveur web avec PHP (version 7.x recommandée)
- Base de données MySQL (ou compatible)
- Téléchargement des fichiers Cuicui App depuis le dépôt Git
- Configuration du fichier `defs.php` avec les informations de connexion à la base de données

## Installation

1. Téléchargez les fichiers Cuicui App depuis le dépôt Git.
2. Configurez le fichier defs.functions.php avec les informations de connexion à votre base de données.
3. Configurez les informations de connexion à la base de données dans le fichier defs.sql et ouvrez la page '/app/index.php', puis suivez les instructions pour la configuration automatique. En cas de problème vous pouvez importez le script SQL inclus dans le dossier 'sql' pour créer les tables nécessaires dans votre base de données.
4. Placez les fichiers dans le répertoire de votre serveur web.
5. Accédez à l'application via votre navigateur web.

## Utilisation

1. Créez un compte utilisateur en fournissant les informations requises.
2. Connectez-vous avec votre compte utilisateur ou créez un nouveau compte si nécessaire.
3. Ajoutez des amis en utilisant la fonction de recherche d'utilisateurs et en suivant d'autres utilisateurs.
4. Partagez des messages avec vos amis en utilisant l'interface de publication.
5. Commentez les publications de vos amis pour interagir avec leur contenu.
6. Signalez les utilisateurs en cas de comportement inapproprié en utilisant la fonction de signalement.
7. Consultez les notifications pour rester informé des activités de vos amis et des interactions pertinentes.

## Auteurs

- [Dadflip](https://github.com/dadflip/)
- [Olivier723](https://github.com/Olivier723/)
