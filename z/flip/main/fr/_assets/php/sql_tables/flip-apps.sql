-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 18 mars 2024 à 12:42
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `flip_apps`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(3) NOT NULL,
  `id_membre` int(3) DEFAULT NULL,
  `montant` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en cours de traitement','envoyé','livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(3) NOT NULL,
  `id_commande` int(3) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `quantite` int(3) NOT NULL,
  `prix` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `code_postal` int(5) UNSIGNED ZEROFILL NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `statut` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `contenu` text DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages_categories`
--

CREATE TABLE `messages_categories` (
  `id_message` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages_mots_cles`
--

CREATE TABLE `messages_mots_cles` (
  `id_message` int(11) NOT NULL,
  `id_mot_cle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages_utilisateurs`
--

CREATE TABLE `messages_utilisateurs` (
  `id_message` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modules_utilisateur`
--

CREATE TABLE `modules_utilisateur` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `width` int(10) NOT NULL,
  `height` int(10) NOT NULL,
  `centerX` double NOT NULL,
  `centerY` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mots_cles`
--

CREATE TABLE `mots_cles` (
  `id` int(11) NOT NULL,
  `mot_cle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(3) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(5) NOT NULL,
  `public` enum('m','f','mixte') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` int(3) NOT NULL,
  `stock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `todo_tasks`
--

CREATE TABLE `todo_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(255) NOT NULL,
  `is_verified` int(1) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `verification_token`, `is_verified`, `reg_date`) VALUES
(16991262, 'Davide', 'Chinedum', 'dadflip', 'dadflipper.1@gmail.com', '$2y$10$beZW0oxhA3Lgs1A0cowDxOMCyejt8f9Aip8g7GLkrHPyMlTHRHewC', '1282a148c01e07f4fd326f13a4f651686e2a39a7e20553e81d964777ddfe3cb0', 0, '2024-01-29 01:13:20'),
(89453032, 'user', 'user', 'user', 'user@user.com', '$2y$10$Z.G1Q/7U5BatDn41dkM3ueANR7rXjL2X2ZFWVNqT4qh9iYbO5/7Je', '18503ad95b1b03c8675026dbbae4fe0a2b28338b4c220e203f43d8b8a4d0b4eb', 0, '2024-01-29 01:38:18');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages_categories`
--
ALTER TABLE `messages_categories`
  ADD PRIMARY KEY (`id_message`,`id_categorie`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `messages_mots_cles`
--
ALTER TABLE `messages_mots_cles`
  ADD PRIMARY KEY (`id_message`,`id_mot_cle`),
  ADD KEY `id_mot_cle` (`id_mot_cle`);

--
-- Index pour la table `messages_utilisateurs`
--
ALTER TABLE `messages_utilisateurs`
  ADD PRIMARY KEY (`id_message`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `modules_utilisateur`
--
ALTER TABLE `modules_utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `mots_cles`
--
ALTER TABLE `mots_cles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Index pour la table `todo_tasks`
--
ALTER TABLE `todo_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modules_utilisateur`
--
ALTER TABLE `modules_utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT pour la table `mots_cles`
--
ALTER TABLE `mots_cles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `todo_tasks`
--
ALTER TABLE `todo_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92722930;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages_categories`
--
ALTER TABLE `messages_categories`
  ADD CONSTRAINT `messages_categories_ibfk_1` FOREIGN KEY (`id_message`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `messages_categories_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `messages_mots_cles`
--
ALTER TABLE `messages_mots_cles`
  ADD CONSTRAINT `messages_mots_cles_ibfk_1` FOREIGN KEY (`id_message`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `messages_mots_cles_ibfk_2` FOREIGN KEY (`id_mot_cle`) REFERENCES `mots_cles` (`id`);

--
-- Contraintes pour la table `messages_utilisateurs`
--
ALTER TABLE `messages_utilisateurs`
  ADD CONSTRAINT `messages_utilisateurs_ibfk_1` FOREIGN KEY (`id_message`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `messages_utilisateurs_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `modules_utilisateur`
--
ALTER TABLE `modules_utilisateur`
  ADD CONSTRAINT `modules_utilisateur_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `todo_tasks`
--
ALTER TABLE `todo_tasks`
  ADD CONSTRAINT `todo_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
