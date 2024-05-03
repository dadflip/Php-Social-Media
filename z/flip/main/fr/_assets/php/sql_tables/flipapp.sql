-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 18 mars 2024 à 14:52
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
-- Base de données : `flipapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `text_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `text_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `text_id`, `action`) VALUES
(45, 16991262, 60, 'like'),
(46, 16991262, 61, 'like'),
(47, 16991262, 62, 'like'),
(48, 16991262, 64, 'dislike'),
(49, 16991262, 63, 'like'),
(50, 16991262, 65, 'like'),
(51, 16991262, 69, 'like'),
(52, 16991262, 66, 'like'),
(53, 16991262, 70, 'like'),
(54, 16991262, 72, 'like'),
(55, 16991262, 71, 'like'),
(56, 16991262, 67, 'like'),
(57, 16991262, 68, 'like'),
(58, 16991262, 73, 'like');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `text_id` int(11) DEFAULT NULL,
  `media_url` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`media_id`, `text_id`, `media_url`, `media_type`, `created_at`) VALUES
(49, 60, '.php/media16991262/16991262_img65b76af2eb7e9.jfif', 'image', '2024-01-29 09:08:02'),
(50, 61, '.php/media16991262/16991262_img65b76b56aa3b7.jpg', 'image', '2024-01-29 09:09:42'),
(51, 62, '.php/media16991262/16991262_img65b76b9e93d05.PNG', 'image', '2024-01-29 09:10:54'),
(52, 63, '.php/media16991262/16991262_vid65b76c082ceaf.mp4', 'video', '2024-01-29 09:12:40'),
(53, 64, '', '', '2024-01-29 09:17:26'),
(54, 65, '.php/media16991262/16991262_img65b7769c33ebb.PNG', 'image', '2024-01-29 09:57:48'),
(55, 66, '.php/media16991262/16991262_img65b7771cd635f.jpg', 'image', '2024-01-29 09:59:56'),
(56, 67, '', '', '2024-01-29 10:00:44'),
(57, 68, '.php/media16991262/16991262_img65b77799e99a0.jpg', 'image', '2024-01-29 10:02:01'),
(58, 69, '.php/media16991262/16991262_img65b777c68bb9c.jfif', 'image', '2024-01-29 10:02:46'),
(59, 70, '', '', '2024-01-29 10:03:27'),
(60, 71, '.php/media16991262/16991262_img65b7782346a57.PNG', 'image', '2024-01-29 10:04:19'),
(61, 72, '.php/media16991262/16991262_img65c3a2aa3eed0.jfif', 'image', '2024-02-07 15:32:58'),
(62, 73, '', '', '2024-03-04 18:46:15');

-- --------------------------------------------------------

--
-- Structure de la table `texts`
--

CREATE TABLE `texts` (
  `text_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `keywords` text DEFAULT NULL,
  `likes` int(10) DEFAULT 0,
  `dislikes` int(10) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `texts`
--

INSERT INTO `texts` (`text_id`, `user_id`, `title`, `category`, `content`, `keywords`, `likes`, `dislikes`, `image_url`, `video_url`) VALUES
(60, 16991262, 'Chien', 'Particulier', 'les chiens sont des animaux apparmement ! Et c\'est bien vrai !', 'animaux, chiens', 8, 2, 'media16991262/16991262_img65b76af2eb7e9.jfif', NULL),
(61, 16991262, 'Fond d\'écran cool !', 'Particulier', 'fond écran', '', 5, 3, 'media16991262/16991262_img65b76b56aa3b7.jpg', NULL),
(62, 16991262, 'Post important', 'Particulier', 'Bienvenue sur flip ! Voici à quoi ça ressemblait il y a peu ! Belle évolution n\'est-ce pas ?', 'flip, app, dadflip', 4, 0, 'media16991262/16991262_img65b76b9e93d05.PNG', NULL),
(63, 16991262, 'Vidéo', 'Particulier', 'Il est tout à fait possible de mettre des vidéos sur Flip ! Bientôt la fonction vocal et stream pourra être ajoutée ... J\'espère !', 'flip, app, dadflip', 8, 1, NULL, 'media16991262/16991262_vid65b76c082ceaf.mp4'),
(64, 16991262, 'Contenu texte sans image', 'Particulier', 'On peut aussi n\'entrer que du texte. Dans ce cas, le cadre réservé aux images sera vide', 'tuto, flip, app', 6, 1, '', ''),
(65, 16991262, 'Mais mdr ???', 'Particulier', 'Cette image est particulièrement drôle !', '', 5, 0, 'media16991262/16991262_img65b7769c33ebb.PNG', NULL),
(66, 16991262, 'Chat Pitre', 'Particulier', 'C\'est un chat !', 'chat, fantaisie', 4, 0, 'media16991262/16991262_img65b7771cd635f.jpg', NULL),
(67, 16991262, 'Hélicoptère', 'Particulier', 'Appareil équipé d\'hélices permettant de s\'élever en altitude', 'Hélicoptère', 2, 0, '', ''),
(68, 16991262, 'New component', 'Particulier', 'new', '', 1, 0, 'media16991262/16991262_img65b77799e99a0.jpg', NULL),
(69, 16991262, 'Debian', 'Particulier', 'Distribution linux. Dérivés : Ubuntu', 'tech, linux', 1, 0, 'media16991262/16991262_img65b777c68bb9c.jfif', NULL),
(70, 16991262, 'Interface de geany RDX', 'Particulier', 'voici l\'interface de geany RDX', 'code, geany, rdx, dadflip, apps', 1, 0, '', ''),
(71, 16991262, 'Oups, j\'ai oublié de poster l\'image de l\'interface', 'Particulier', 'image interface geany rdx', 'geany, rdx', 1, 0, 'media16991262/16991262_img65b7782346a57.PNG', NULL),
(72, 16991262, 'tests', 'Particulier', 'tes(t)', 'test', 4, 0, 'media16991262/16991262_img65c3a2aa3eed0.jfif', NULL),
(73, 16991262, 'Test', 'Particulier', 'ABCDEF', 'test', 3, 0, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `accepted_conditions` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `accepted_conditions`) VALUES
(16991262, 'dadflip', '$2y$10$beZW0oxhA3Lgs1A0cowDxOMCyejt8f9Aip8g7GLkrHPyMlTHRHewC', 'dadflipper.1@gmail.com', 0),
(89453032, 'user', '$2y$10$Z.G1Q/7U5BatDn41dkM3ueANR7rXjL2X2ZFWVNqT4qh9iYbO5/7Je', 'user@user.com', 0);


CREATE TABLE `login` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  cookie_value VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `text_id` (`text_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `text_id` (`text_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `text_id` (`text_id`);

--
-- Index pour la table `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`text_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `texts`
--
ALTER TABLE `texts`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`text_id`) REFERENCES `texts` (`text_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`text_id`) REFERENCES `texts` (`text_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`text_id`) REFERENCES `texts` (`text_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `texts`
--
ALTER TABLE `texts`
  ADD CONSTRAINT `texts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
