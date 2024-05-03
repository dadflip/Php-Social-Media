-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 02 mai 2024 à 11:01
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
-- Base de données : `cuicui_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `content` longtext NOT NULL COMMENT 'text_array',
  `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` enum('default','group','','') NOT NULL DEFAULT 'default',
  `chat_src_id` int(11) NOT NULL,
  `chat_dest_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `likes` int(10) NOT NULL,
  `users_uid` int(11) NOT NULL,
  `parent_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `content`, `datetime`, `likes`, `users_uid`, `parent_id`) VALUES
('663115dc03961@25147427', 'trop beau le logo mdr', '2024-04-30 16:01:32', 0, 25147427, '662fffb0d5107@25147427'),
('663115ee98f75@25147427', 'ouais c\'est vrai', '2024-04-30 16:01:50', 0, 25147427, '663115dc03961@25147427'),
('66311e38250f9@25147427', '@[flip]', '2024-04-30 16:37:12', 0, 25147427, '663115dc03961@25147427'),
('663120b657fb6@25147427', 'bonjour @[pierre]', '2024-04-30 16:47:50', 0, 25147427, '66311e38250f9@25147427'),
('663121983f370@25147427', '@[test]', '2024-04-30 16:51:36', 0, 25147427, '6630103ad8087@25147427'),
('66312383e8948@25147427', '@[uy]', '2024-04-30 16:59:47', 0, 25147427, '663121983f370@25147427'),
('6631243d8d83e@25147427', 'quoi ? tu as demandé à @[flip]?', '2024-04-30 17:02:53', 0, 25147427, '6630103ad8087@25147427'),
('6631284a3fa25@25147427', 'non ...', '2024-04-30 17:20:10', 0, 25147427, '6631243d8d83e@25147427'),
('66312873db836@25147427', '', '2024-04-30 17:20:51', 0, 25147427, '6631252488aed@25147427'),
('6631287f6fa9f@25147427', '?', '2024-04-30 17:21:03', 0, 25147427, '66312873db836@25147427'),
('663129747722c@25147427', '@[er]@[me]@[test]', '2024-04-30 17:25:08', 0, 25147427, '6631294c995d0@25147427'),
('66312add1596a@25147427', '#[test]', '2024-04-30 17:31:09', 0, 25147427, '66303ae813efd@25147427'),
('66312bed3127b@25147427', '#[hello] #[world]', '2024-04-30 17:35:41', 0, 25147427, '66312add1596a@25147427'),
('663147608a911@25147427', 'oui oui c\'est ça', '2024-04-30 19:32:48', 0, 25147427, '663121983f370@25147427'),
('6631477703a48@25147427', 'qoui', '2024-04-30 19:33:11', 0, 25147427, '6631284a3fa25@25147427'),
('6631478bf297e@25147427', 'hey', '2024-04-30 19:33:31', 0, 25147427, '6631477703a48@25147427'),
('663147a8965a6@25147427', 'ihih', '2024-04-30 19:34:00', 0, 25147427, '6631478bf297e@25147427'),
('663147ea87261@25147427', 'ouiii', '2024-04-30 19:35:06', 0, 25147427, '663147608a911@25147427'),
('66314814df3af@25147427', 'merci', '2024-04-30 19:35:48', 0, 25147427, '662fffb0d5107@25147427'),
('6631481db2de5@25147427', 'cool', '2024-04-30 19:35:57', 0, 25147427, '662fffb0d5107@25147427'),
('66314827ca2d0@25147427', 'mais ?', '2024-04-30 19:36:07', 0, 25147427, '662fffb0d5107@25147427'),
('66314994731ec@25147427', '@[me]', '2024-04-30 19:42:12', 0, 25147427, '663121983f370@25147427'),
('6631653832d88@25147427', '@[pepe]', '2024-04-30 21:40:08', 0, 25147427, '663121983f370@25147427'),
('663187d371562@25147427', 'hihi', '2024-05-01 00:07:47', 0, 25147427, '6631481db2de5@25147427'),
('663190da92696@65380836', 'test', '2024-05-01 00:46:18', 0, 65380836, '6630103ad8087@25147427'),
('66319173c909f@65380836', 'test', '2024-05-01 00:48:51', 0, 65380836, '6631294c995d0@25147427'),
('66319881302ea@65380836', 'cool @[rere]', '2024-05-01 01:18:57', 0, 65380836, '66319854d3da1@65380836');

-- --------------------------------------------------------

--
-- Structure de la table `data`
--

CREATE TABLE `data` (
  `iddata` int(11) NOT NULL,
  `users_uid` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL,
  `browser_name` varchar(45) NOT NULL,
  `browser_version` varchar(45) NOT NULL,
  `user_agent` varchar(45) NOT NULL,
  `_datetime_` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `data`
--

INSERT INTO `data` (`iddata`, `users_uid`, `posts_id`, `browser_name`, `browser_version`, `user_agent`, `_datetime_`) VALUES
(0, 65380836, 0, 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-01 04:17:05');

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

CREATE TABLE `follow` (
  `follow_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `follow`
--

INSERT INTO `follow` (`follow_id`, `follower_id`, `target_id`) VALUES
(58, 25147427, 15972122),
(61, 65380836, 15972122),
(62, 65380836, 25147427);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `users_uid` int(11) DEFAULT NULL,
  `text_id` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`like_id`, `users_uid`, `text_id`, `action`) VALUES
(63, 25147427, '662ff57220184@25147427', 'like'),
(64, 25147427, '662fffb0d5107@25147427', 'like'),
(65, 25147427, '662fffb0d5107@25147427', 'dislike'),
(66, 25147427, '662fffb0d5107@25147427', 'like'),
(67, 25147427, '662fffb0d5107@25147427', 'dislike'),
(68, 25147427, '6630103ad8087@25147427', 'like'),
(69, 25147427, '6630103ad8087@25147427', 'dislike'),
(70, 25147427, '6630103ad8087@25147427', 'dislike'),
(71, 25147427, '6630103ad8087@25147427', 'like'),
(72, 25147427, '6630103ad8087@25147427', 'like'),
(73, 25147427, '6630103ad8087@25147427', 'like'),
(74, 25147427, '6630103ad8087@25147427', 'dislike'),
(75, 25147427, '6630103ad8087@25147427', 'dislike'),
(76, 25147427, '6630103ad8087@25147427', 'dislike'),
(77, 25147427, '662fffb0d5107@25147427', 'dislike'),
(78, 25147427, '6631252488aed@25147427', 'dislike'),
(79, 65380836, '6631252488aed@25147427', 'like'),
(80, 65380836, '662fffb0d5107@25147427', 'like'),
(81, 25147427, '6633132fae8d4@25147427', 'like'),
(82, 25147427, '6633132fae8d4@25147427', 'dislike'),
(83, 25147427, '6633132fae8d4@25147427', 'like'),
(84, 25147427, '6633132fae8d4@25147427', 'dislike'),
(85, 25147427, '6633132fae8d4@25147427', 'like'),
(86, 25147427, '6633132fae8d4@25147427', 'like'),
(87, 25147427, '6633132fae8d4@25147427', 'dislike'),
(88, 25147427, '6633132fae8d4@25147427', 'dislike');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `posts_text_id` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`media_id`, `posts_text_id`, `type`, `url`, `creation_date`) VALUES
(29426182, '662fedd4c054b@25147427', 'image', '/../WE4A-Project/img/users/profile/media/@flip-25147427.media/@flip_img_20240429205828.png', '2024-04-29 18:58:28'),
(118990428, '6631e6bfb5bbb@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240501085247.png', '2024-05-01 06:52:47'),
(209912574, '662fee4738239@25147427', 'image', '/WE4A-Project/img/users/profile/media/@flip-25147427.media/@flip_img_20240429210023.png', '2024-04-29 19:00:23'),
(246514408, '662fe8f8c1627@25147427', 'image', '../../../img/users/profile/media/@flip-25147427.media/@flip_img_20240429203744.png', '2024-04-29 18:37:44'),
(278703276, '66319854d3da1@65380836', 'image', '/users/media/@rere-65380836.media/@rere_img_20240501031812.png', '2024-05-01 01:18:12'),
(304082470, '6630103ad8087@25147427', '', '', '2024-04-29 21:25:14'),
(328638749, '6631e64f3474c@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240501085055.png', '2024-05-01 06:50:55'),
(344246880, '662fec0f1fc43@25147427', 'image', '/WE4A-Project/img/users/profile/media/@flip-25147427.media/@flip_img_20240429205055.png', '2024-04-29 18:50:55'),
(462184071, '662fe67dae1be@25147427', 'image', '../../img/users/profile/media/@flip-25147427.media/@flip_img_20240429202709.png', '2024-04-29 18:27:09'),
(489619692, '662fe85418c83@25147427', 'image', 'C:\\xampp\\htdocs\\WE4A-Project\\app../img/users/profile/media/@flip-25147427.media/@flip_img_20240429203500.png', '2024-04-29 18:35:00'),
(490387977, '662ff3cec0b48@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240429212358.png', '2024-04-29 19:23:58'),
(610551216, '662fffb0d5107@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240429221440.png', '2024-04-29 20:14:40'),
(625858462, '66303ae813efd@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240430022720.png', '2024-04-30 00:27:20'),
(678706998, '662fef5abf968@25147427', 'image', '../../img/users/profile/media/@flip-25147427.media/@flip_img_20240429210458.png', '2024-04-29 19:04:58'),
(689987267, '6633132fae8d4@25147427', '', '', '2024-05-02 04:14:39'),
(699154939, '6631294c995d0@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240430192428.png', '2024-04-30 17:24:28'),
(733074182, '662fed6d1eb43@25147427', 'image', '../WE4A-Project/img/users/profile/media/@flip-25147427.media/@flip_img_20240429205645.png', '2024-04-29 18:56:45'),
(743321603, '662fe96c05e6b@25147427', 'image', '../../img/users/profile/media/@flip-25147427.media/@flip_img_20240429203940.png', '2024-04-29 18:39:40'),
(750270307, '662ff57220184@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240429213058.png', '2024-04-29 19:30:58'),
(798492320, '662ff0ed38c8d@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240429211141.png', '2024-04-29 19:11:41'),
(811650518, '6631252488aed@25147427', '', '', '2024-04-30 17:06:44'),
(855243899, '6631e7332f715@25147427', '', '', '2024-05-01 06:54:43');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `users_uid` int(11) DEFAULT NULL,
  `c_datetime` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text_content` text DEFAULT NULL,
  `notification_type` varchar(50) DEFAULT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `users_uid`, `c_datetime`, `title`, `text_content`, `notification_type`, `is_read`) VALUES
(5, 25147427, '2024-04-29 20:27:09', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(6, 25147427, '2024-04-29 20:35:00', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(7, 25147427, '2024-04-29 20:37:44', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(8, 25147427, '2024-04-29 20:39:40', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(9, 25147427, '2024-04-29 20:50:55', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(10, 25147427, '2024-04-29 20:56:45', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(12, 25147427, '2024-04-29 21:00:23', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(13, 25147427, '2024-04-29 21:04:58', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(20, 25147427, '2024-04-30 19:06:44', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(21, 25147427, '2024-04-30 19:24:28', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(22, 65380836, '2024-05-01 03:18:12', 'Nouveau post créé', 'L\'utilisateur rere a créé un nouveau post.', 'post', 1),
(23, 25147427, '2024-05-01 08:50:55', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(24, 25147427, '2024-05-01 08:52:47', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(25, 25147427, '2024-05-01 08:54:43', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(26, 25147427, '2024-05-02 06:14:39', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1);

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `textId` varchar(100) NOT NULL,
  `users_uid` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `tags` varchar(50) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(10) UNSIGNED DEFAULT 0,
  `dislikes` int(10) UNSIGNED DEFAULT 0,
  `content_img_url` varchar(255) DEFAULT NULL,
  `content_vid_url` varchar(255) DEFAULT NULL,
  `text_content` text NOT NULL,
  `media_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`textId`, `users_uid`, `title`, `category`, `tags`, `post_date`, `likes`, `dislikes`, `content_img_url`, `content_vid_url`, `text_content`, `media_id`) VALUES
('662fffb0d5107@25147427', 25147427, 'Test', 'Particulier', 'test', '2024-04-29 20:14:40', 3, 3, '/users/media/@flip-25147427.media/@flip_img_20240429221440.png', NULL, 'test abcd', 610551216),
('6630103ad8087@25147427', 25147427, 'test', 'Particulier', 'test', '2024-04-29 21:25:14', 4, 5, '', '', 'test', 304082470),
('66303ae813efd@25147427', 25147427, 'test', 'Particulier', 'animaux, chiens', '2024-04-30 00:27:20', 0, 0, '/users/media/@flip-25147427.media/@flip_img_20240430022720.png', NULL, 'nouveau formulaire', 625858462),
('6631252488aed@25147427', 25147427, 're', 'Particulier', 're,re', '2024-04-30 17:06:44', 1, 1, '', '', 're', 811650518),
('6631294c995d0@25147427', 25147427, 'uf', 'Particulier', 'uf', '2024-04-30 17:24:28', 0, 0, '/users/media/@flip-25147427.media/@flip_img_20240430192428.png', NULL, 'uf', 699154939),
('66319854d3da1@65380836', 65380836, 'c\'est rere', 'Particulier', 'hi, ho', '2024-05-01 01:18:12', 0, 0, '/users/media/@rere-65380836.media/@rere_img_20240501031812.png', NULL, 'rere', 278703276),
('6631e64f3474c@25147427', 25147427, 'Image', 'Amis', '', '2024-05-01 06:50:55', 0, 0, '/users/media/@flip-25147427.media/@flip_img_20240501085055.png', NULL, 'content', 328638749),
('6631e6bfb5bbb@25147427', 25147427, 'test', 'Amis', '', '2024-05-01 06:52:47', 0, 0, '/users/media/@flip-25147427.media/@flip_img_20240501085247.png', NULL, 'test', 118990428),
('6631e7332f715@25147427', 25147427, 'post avec plein de tags', 'Amis', 'tag1,tag2,tag3', '2024-05-01 06:54:43', 0, 0, '', '', 'regardez mes tags', 855243899),
('6633132fae8d4@25147427', 25147427, 'test', 'Animaux', 'animé,chat', '2024-05-02 04:14:39', 4, 4, '', '', 'wow', 689987267);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `UID` int(11) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `username` text NOT NULL DEFAULT 'user',
  `email` varchar(80) NOT NULL,
  `biography` text NOT NULL DEFAULT '',
  `registration_date` date NOT NULL DEFAULT current_timestamp(),
  `password` text NOT NULL,
  `profile_pic_url` varchar(255) NOT NULL DEFAULT 'placeholder',
  `user_data_id` int(11) NOT NULL,
  `user_chat_id` int(11) NOT NULL,
  `rsa_public_key` text DEFAULT NULL,
  `rsa_private_key` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`UID`, `isAdmin`, `username`, `email`, `biography`, `registration_date`, `password`, `profile_pic_url`, `user_data_id`, `user_chat_id`, `rsa_public_key`, `rsa_private_key`) VALUES
(15972122, 0, 'test', 'test@test.fr', 'test', '2024-04-28', '098f6bcd4621d373cade4e832627b4f6', '../../../img/users/profile/media/@test-15972122.profile-image/img_662e9766ca184.png', 15972122, 15972122, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnz4TniuOy5vrqQhe1Fd6\ndDTMULsf7X/T0UsVYA99e3Ag/LcfKlEovFr8k7+Achcd5WsiTlaVY1AbgvADxktp\nAGs2LjoxA4yrWV/6S+MO2by8fsuoFj3YmTwoaA6BiwO4YbvcNI6+j0Npr30oGQt2\nubJcq8bkZ2oQo9blpLGdb0hxvqliCXISprn464DOazQ8/xjdMwYjQkr3aLDpe8dG\ncghoCYhyPT956ijvLpo5vg7jrN/NcIGgm1J78nqyhxqP27kMIvxUXq+z3saCHRGl\nE7B8p/SUN6ma31/fLEarQJJ0Cm3o6cz5L1WNAd2MYfJR/Yahq0vKcRwS5lFmq93N\n9wIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCfPhOeK47Lm+up\nCF7UV3p0NMxQux/tf9PRSxVgD317cCD8tx8qUSi8WvyTv4ByFx3layJOVpVjUBuC\n8APGS2kAazYuOjEDjKtZX/pL4w7ZvLx+y6gWPdiZPChoDoGLA7hhu9w0jr6PQ2mv\nfSgZC3a5slyrxuRnahCj1uWksZ1vSHG+qWIJchKmufjrgM5rNDz/GN0zBiNCSvdo\nsOl7x0ZyCGgJiHI9P3nqKO8umjm+DuOs381wgaCbUnvyerKHGo/buQwi/FRer7Pe\nxoIdEaUTsHyn9JQ3qZrfX98sRqtAknQKbejpzPkvVY0B3Yxh8lH9hqGrS8pxHBLm\nUWar3c33AgMBAAECggEAI3i5+P096Tf5tiTxrr4vvkv2iTMtqQNU5SWjBMteic0P\n5AtMi2oZt19TLPw2+fMRS0Xile2uiDlTb4bUaGrq4FeBcamvulku4BFDmXziFXl3\nyYzHC/FJ2tRuMcOtcpBpFYg5xJFZfGMpWxh3gqCtmS7UesUqoqibPUvEyrtv1NZF\nsD/4yoUAJdGnyYxRb6RgPamxET3T5RMA9VG9cB14tydtHAxqhSfk/4NL0wAWajBV\nh45JSiQx/YqD/9Cpl1EQ/Hq6d/zKmjIDFrQIMiYVuSIRiKaLMdqzqgXOnSLR5CXC\nFPvheKi+9vPkqj/epL57SeFA304VHJ6/iodP+0EkxQKBgQDXaLq5blpE43Krl8Ys\n5LX+BpaoGhFvESwBEwIGa/VyOegVFV29EtH2aJLFJzlZflNRG70f+4tJ4qtm0nKr\nqMRG9QF+VvJ4Ispl5AXoePdDFDMp9Q0IJYQOC10HXVkAuYWFkKUTOX1RKYj8Rsej\ncYScNTHg48mdtUDpRA9+D+AoxQKBgQC9P+L6mITSi2dCd/ujKmom1yf/eTIPY8sK\n/Is/y4JyPj5txo4cDGhWficy7FSjJgU/FGneLF0AUSnf2oP/47fFaAhnngT1F2+v\ncylYQTQeg/Xjo6NTbCb6ng/ccpWv8l5WsC6WoxyJXbxwW1RSlUYjsTEHFs6rHw9b\nGSDM5rWviwKBgQC8CiaGXPjcDhk6BcHvyq+8UxANKsRxSnI5ddhctr2Ku1YHoaE0\nvksfaWZGSNldcFNXCHnug22yT/cESU3k3+yHbLWjDk+X4/b/PzCRIZfnrKiFRe/c\nXrOKdwz8stZRNaKDWCNiRttIkJOOdlMsMJpsqlsiUHbd/y3s84b6qu0paQKBgBdK\nAmO2WzoRv65VV5k3wgJvndN5laiBLl52v6glIjNu533Y3mojOL/UHHzOyoedSS3/\nFBwsN3Gvi0ip+m6GFnprmAUwVAnZTXw43tCmjYRn1t2hqJq+h3l19Bu72iHh4Kwo\nWENfZUaeS29EPfc/uXbw+kiWDO7QDrK2P+wXRCqLAoGBALTlPkuyLcOELp0m73AF\nlGXIc0oj85AKnYbx3heF+ZtK5zgXaJ5Y58I3wCF+kS7NAMMUug4Dkb7Y/GjyPicm\n9T5HYAgJQb3whaUE2xeqDY/6YTK5CUyCgmugULfMEOFKKNisKfWiFAbEcZ1U/Z84\nRXsvhc4/KXcu25RhXRmPYWLn\n-----END PRIVATE KEY-----\n'),
(25147427, 0, 'flip', 'flip@flip.fr', 'hihi', '2024-04-28', 'e6e5fd26daa9bca985675f67015fd882', '../../img/users/profile/media/@flip-25147427.profile-image/img_662e8d3c98ece.png', 25147427, 25147427, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsa+twbtrgZ8R1i5O07m/\niCzN5KMERRyvKt2GRUpXduUHlxHgap2u4SjuHtrvEk/3osD2UeQ30nBx+sfAAm1d\njIe7/WHDcJcPUqGN9pw+3GCQYYPg5ttinZtYYjVwT8C+CIWTSXhPB6oAd6PZnc2J\nUUMc7wZZ4nXeXYhaWc7AdygPOAmwPlSbuS94tck20ub7Ppa2ewzRKkeSOY780bNJ\n6zdUc2qu/0dJmA8VjKF7946F7nraklcxVqq445CF468gNeBsYeN9RbE+xhFDu8V0\nMge8nLVu+Pya+C+BlidqKWWAnCLbCznIlhfwF//0ycVzuUyu3GxPmMprTCu+cZ1T\n9QIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCxr63Bu2uBnxHW\nLk7Tub+ILM3kowRFHK8q3YZFSld25QeXEeBqna7hKO4e2u8ST/eiwPZR5DfScHH6\nx8ACbV2Mh7v9YcNwlw9SoY32nD7cYJBhg+Dm22Kdm1hiNXBPwL4IhZNJeE8HqgB3\no9mdzYlRQxzvBlnidd5diFpZzsB3KA84CbA+VJu5L3i1yTbS5vs+lrZ7DNEqR5I5\njvzRs0nrN1Rzaq7/R0mYDxWMoXv3joXuetqSVzFWqrjjkIXjryA14Gxh431FsT7G\nEUO7xXQyB7yctW74/Jr4L4GWJ2opZYCcItsLOciWF/AX//TJxXO5TK7cbE+YymtM\nK75xnVP1AgMBAAECggEAGpwDCvS81FQUkT0tG+MAzm1bRiatkIYLq4EyRvUrpQgG\ne3a3Y3AKzPhatK83x0fHf42jNb9WJsMlJpe8ZwCm1lFLc1YtNRLCPi8oeeqLNWiW\npqvfxeLL4WtusIJtN9xW+SK73HBScYThfglBITMzIcLJtff/BP1+2AX/9dwTEaTj\ndCNuogyLegqnpQvUOO6BV4N64+slH6eNOwfATIR8Xk9YHgEwh9pe1Oq3tbFrBk/q\ncMk3T85tUH1x+uuulzMp+/Wu/IoOdtwTEGCKseZhILJdnTmNQ0yvBSPKtp1sEU8h\nEzcqh4ZKJoAw5SlEFhHKdZE5i8R4t6fJF9E4qUVbbwKBgQDrnBRRwvp9PGkm1nDf\nvVFgrlxvW+KWgLq2pgTaiHKH+yFZcZpiytENQQ7QvcF0kCU2O1OW1Bco34ALUBkC\najno00ThVBJP/mI7rqcXltjGMmuvWW7e/vhFHW0dC6Wgj4pNdUuWIfwvk0lnuKHl\nGsqnwSffx8xxgO5P25rChtZIxwKBgQDBEE75G4CUPZ9LIzdI8KghE8ycWnc27Kwl\n9yDTJ1zTp3MnZ98tdshbHH5hhAYvFaPZljw/HNmclbeYymvakk8kZJXpFJkIAcSg\nTNTrMmvFxZy/enb5cAI22G4D6nPx/F6tsp40URkkFR8sdPXtlUpQCRtFtcvRueSD\nQ4mTqP5ZYwKBgQCtqcPIaEEd4lNXTySvpem/q4Vyh3XCnWwCiOh287AHzvFL7lJv\nkzL35AY5kkRv2He1O5FnUTko5WSJ8iYdA5Nfq6nPtJoy4Dwj5Z4/4u4xWKTLAM6a\nC7GPsBrnF9ijOmokodpu9OnfX17x1sNuDYtmjxcG+UwWNYRy1FmEHMMBrQKBgE6y\nMR7FoSHMBJSCJFTtE/t3sJ3DGnke/AT1uUGY9/OmfAACDSzhEEZjVPi28jZbIdDd\ntm0be3CibpYvXMtZfD0fayP5K2/iGJ4m81tz0A5rwLskVj7S83QbwKyD8wMelhFD\nj81PiWn12iErA9v2ULZTH/TIZ+5zUbxX2UqBRjx1AoGBAIjApR46CZzoXqeIRSJE\n6SIKVfrUl34c9to1y0izW6gJXocuw7LVznfbYGGbZ5Cm4ghfqXJF6CmZyRx/pyhb\nc/4xYtavJRR0n5HQO4Nl165ri+mhneRBgFgwVdqXhmqMHNUOCN0JOinUgShWU+c8\nhMEDl2a7WCf9TAYzEL74+noF\n-----END PRIVATE KEY-----\n'),
(41204447, 0, 'pepe', 'pepe@test.fr', 'test', '2024-04-28', '926e27eecdbc7a18858b3798ba99bddd', '../../../img/users/profile/media/@pepe-41204447.profile-image/img_662eaacede859.png', 41204447, 41204447, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn5X+X/TcmkdrsknOs6Tp\nE9ShxSs6xEMZ4ybNgyXuI1+P2sPkLAnaI3VioEwcJBRBEkiRHXdJXSrPbGiem9tC\nQjX7GQaIOdnDQb0oaN8Ku4C2YvnJKWqQttLWX0VLhDwCmdtxUBPOo1Xcr64c7JT8\nz5sVFhaVcl91E5BMzx6YI/BH6wFzKHq+7l3tVLc32zfsSShCUATafbqGcgb+PY2r\npCrjgVb4/shdF6IV8OfmDvtT+qkC+TplTbqYyyE1i1U/AGsmUiwBRt7y829SLWJ3\nYJZF8JC0JkS3N9nHsGRevu4dDEN+TbOn/7Iryy7Dew84r5gy0ckFeRc71hmKZBt+\nSQIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCflf5f9NyaR2uy\nSc6zpOkT1KHFKzrEQxnjJs2DJe4jX4/aw+QsCdojdWKgTBwkFEESSJEdd0ldKs9s\naJ6b20JCNfsZBog52cNBvSho3wq7gLZi+ckpapC20tZfRUuEPAKZ23FQE86jVdyv\nrhzslPzPmxUWFpVyX3UTkEzPHpgj8EfrAXMoer7uXe1UtzfbN+xJKEJQBNp9uoZy\nBv49jaukKuOBVvj+yF0XohXw5+YO+1P6qQL5OmVNupjLITWLVT8AayZSLAFG3vLz\nb1ItYndglkXwkLQmRLc32cewZF6+7h0MQ35Ns6f/sivLLsN7DzivmDLRyQV5FzvW\nGYpkG35JAgMBAAECggEACJJlwg4eYopFjNanRXT5/Z7lO/iXeBh8/0hQfHC0IUhH\nxNUGBrhWWqM6gFu2NnK0EBRZXxjO/JpV3xlus5AMBHYInigaQ+k55YHdXGZNAEdP\nBKZgGY5n/+0q2jeYCwR7xOHjOabmtlVG7+5m27pUKtHGSMc+910mcvEqsjfCRxcF\nZ3tuDVvLld1w9nmzGBz+0Hr9PEIL7UQuuGnCqLNbNfNxd2IjUUUwyfby4GGIk+ym\nkckUHJJTphjf9cW5bqMQaQvjExXd6UiioGp2xLEcnl94hj/rn2u7I+HnwEgfinuR\nX6p2zahnQUKMRnJkY9UW87q4Bpeg5tGFztlyaYZVBQKBgQDXME1ibtTxtNDAp+xR\n6zaVpU8guJiRvwmqQMIOO2UY5E/HXyocGnNu3P8wQJ6/71Y2wQs9zgsqzw48JzNQ\n5BjJoVlapqWhQf/jBI/PtBPCnbKD462XRNV+drcvE086jIMhV4Eex55uivQX7CwG\nXimhFmsH9v1V/VJTqH5y7TQqDwKBgQC92hpN5pZNGMfNSGaoqPowD+ocZUgc3r1A\nzEeyHdmwiygjEcEJRFj4PS+oOmaa01PGFefeNf0E2jy+A2EotdxucKlTx9j5aQeP\nnd4SJh7ys2DsCCvjjMnAE6ED/16pr+aqqTVFSnqBSY8tCKHtT962tTpVY75xawqD\n/yzlprOKJwKBgCw8Iaw6XEvTf5Ya7tloILBiawGu8K4FN1doOHv0ebPEOyIgzec5\nCMGhbFPe9MM/z9kxlb3+vQzvw11aMZJPUUkhgdzApQuhJZjlNyrbObSn9ipmgyi1\nXa3BeaaTsZW3jL0t370UC91tcv/37JTihmw2z4mznCAxEttfp564ZCDBAoGARYoO\n2nvcj87E2I9tcte6FEKwCMicWl7HyWVJ2ZcPdpfyEq5dG4zaXcrjmuYRUSKY69R4\nQq9Zxx4844iPXP8IB7fifdqUwf5Tk6pKfjFSYqmLlckEcmvONjm6X6F5RgQ1JEuo\nRIIcTyDnfaJM1YTM2fWVsVNAnYCMsH6WLmQaHT8CgYAmUpMugWvxNCLxO6qinGFr\nw1tlK/fdhSmdVIAYWXvh7aFmoYyuiuRQvKw70tVGabkgzc/ODzpVgiZO0LHlgBl3\nK/nSUSbhCtzOWbu9gXl7lVUCQlBwTofyt6iOQRvq5MTZGcgzAyEEIg6cexcVD48W\ne0ZgL6eWIzRgJMuyEEiILg==\n-----END PRIVATE KEY-----\n'),
(65380836, 0, 'rere', 're@re.com', 'Je suis là', '2024-05-01', 'bd134207f74532a8b094676c4a2ca9ed', '/users/media/@rere-65380836.profile-image/img_663188447112a.png', 65380836, 65380836, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk4ZWHf8nKAUC6i17oHDi\nlKuSgr2P1Fnqxjs3ttTwH9IW19iR6BqpcGAoW8KDu4L9ZuK3NFiYVC72AqL8SMhY\nFeaLdWme/Tm55zWbvdlPlxdpomBkGw/6mg7o5eaCHHPqdh38SukgNE7v7yP1knGj\n8R7Vny8d8UHxAMflz24b9vMfV7ibP90IOFDAjcvqmmGTvd1yaaxPHuPNeJBp+hGF\n8rcJC02hZdUkhPBhCIsZuh7r/ixV+Frcfqm2PwGMObX0RkQx9K+c93o49MqmtpSk\nfdH6wJ7/gBAXcGoPtfSBkk8NGeHv9t02JQnam7UCzAaKsWbMaFnzvnPtb6NCpw4S\nuQIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCThlYd/ycoBQLq\nLXugcOKUq5KCvY/UWerGOze21PAf0hbX2JHoGqlwYChbwoO7gv1m4rc0WJhULvYC\novxIyFgV5ot1aZ79ObnnNZu92U+XF2miYGQbD/qaDujl5oIcc+p2HfxK6SA0Tu/v\nI/WScaPxHtWfLx3xQfEAx+XPbhv28x9XuJs/3Qg4UMCNy+qaYZO93XJprE8e4814\nkGn6EYXytwkLTaFl1SSE8GEIixm6Huv+LFX4Wtx+qbY/AYw5tfRGRDH0r5z3ejj0\nyqa2lKR90frAnv+AEBdwag+19IGSTw0Z4e/23TYlCdqbtQLMBoqxZsxoWfO+c+1v\no0KnDhK5AgMBAAECggEAM7mVx0W3snHzYC1bW9lIsIzpvVp+rBts1F1d/wZxPQ76\nAGtIWUPD5glpDSdJ7fyTIbhbOQjZPCcNDcacw14veFpRynSikLHlSzqCJVIk9Fp6\nqLq4Tr7PBKY2pakpNJW1/v0rgQ51QrHssYH/r/2VWE0R5JLuIjGXMUlle9HlDzQO\nHg+mJeVuYMbG+JkUMwOTDw2zHTlId7qJuRzYvP37D0l1dAayJjUrk8F/YH50dxD6\nAIOy1/ykON5OT8bVDF5osOEX40TIKm4WcapN+KgkMwIsW4hX0lVLxnTQkbGeswIT\nuB2eVB/mJPNIK21Kl8FBFpy6/32fSPHaDHGkt6lAuwKBgQDFKxERfdMtyq/G7B9F\nU+FvTkw4448aCO+cInO6MjyD1l2wfZzFGbD+Pnj111EdCBWMOTuEOWWLxEBD1ly3\n5dmi9dyXpek0M9U4imgsx19tP21bIZEJJlVWktVmpFWfTA5URGuB/1xvzezdWwJn\nk6hffeseJPVloYdXWHDFVVTXiwKBgQC/izFWQkY7zASWmKqdWmxEKG+G/4oS2B9C\nGgdXqfG9aznb9FMDffQ2fGu4J0NNIdSCj/ECoq7WBGXhPKzGV/SSEpeT7l2loUFn\nYc2u5JKbBmrTLFzivZsctqXjs/lgak6eIIgOFJP+zFf5INdCbsPGaU0Pg8v0sno2\n44YZjK5nSwKBgFnHnIQMeWa2jofT6QFWiqZ2Trrw066VNxYvkebNDUUzDYJaEFKn\nujJrHAVFwEep8LXjMbipshWMjX7WU23hmdJchc//2krM92BBleId/tPuN8txY2t5\nKw662bVdSye4KCYy6YlOUDcQ27SHxnaHg/nHipI1XhCjFs+tE9ynWHS1AoGBAJs9\nuRLsS/gS9nUH7t/xdWYYT6veWI3FVDBC4EiQmDipOw+mCdH+t3uhg7yRNd7B2Hap\nKvCz4XfbwKa4gl+O0c3B+9ANlSC0fUnI3ucsohW7McFNZ0rt2VWAQxs1tpwKDQXj\n3+FplDkibPqhP1W8aDfbCHXWn+sG7D6oUPtMJd3ZAoGBAIUqxDdPk7IPREhA8oYE\nLgI6lVgcKz9OlRqt0BeHgXN54QXR5Lr9g6apAcwb0GBkNI/O2dW9EfTLb+TMUzQT\ne7VGgz93LaGN2xTbOaqNPCdsExsfVIAjf0ogzhw4jX5R1ZIsDCdBF4dnesx3STON\nzrfOaETrmkSuRGJKBWxlUxQ6\n-----END PRIVATE KEY-----\n');

-- --------------------------------------------------------

--
-- Structure de la table `user_settings`
--

CREATE TABLE `user_settings` (
  `users_uid` int(11) NOT NULL,
  `theme` text NOT NULL,
  `lang` text NOT NULL,
  `hasAcceptedConditions` tinyint(1) NOT NULL,
  `profileVisibility` varchar(10) NOT NULL,
  `settings_array` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_settings`
--

INSERT INTO `user_settings` (`users_uid`, `theme`, `lang`, `hasAcceptedConditions`, `profileVisibility`, `settings_array`) VALUES
(25147427, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(15972122, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(41204447, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(65380836, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `chat_src_id` (`chat_src_id`),
  ADD KEY `chat_dest_id` (`chat_dest_id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_uid` (`users_uid`),
  ADD KEY `fk_text_id` (`parent_id`) USING BTREE;

--
-- Index pour la table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`iddata`) USING BTREE,
  ADD UNIQUE KEY `iddata_uq` (`iddata`) USING BTREE,
  ADD KEY `fk_uid` (`users_uid`),
  ADD KEY `posts_id` (`posts_id`);

--
-- Index pour la table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`follow_id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`target_id`),
  ADD KEY `follower_id` (`follower_id`,`target_id`),
  ADD KEY `followee_id` (`target_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `text_id` (`text_id`),
  ADD KEY `user_id` (`users_uid`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`),
  ADD UNIQUE KEY `posts_text_id` (`posts_text_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`textId`),
  ADD KEY `fk_uid` (`users_uid`),
  ADD KEY `fk_media` (`media_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UID`),
  ADD KEY `user_data_id` (`user_data_id`),
  ADD KEY `fk_chat` (`user_chat_id`);

--
-- Index pour la table `user_settings`
--
ALTER TABLE `user_settings`
  ADD KEY `users_uid` (`users_uid`) USING BTREE;

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=908107007;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99343030;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `users` (`user_chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`chat_src_id`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_ibfk_3` FOREIGN KEY (`chat_dest_id`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`target_id`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follow_ibfk_3` FOREIGN KEY (`follower_id`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`text_id`) REFERENCES `posts` (`textId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`media_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`textId`) REFERENCES `media` (`posts_text_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
