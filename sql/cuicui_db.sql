-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 mai 2024 à 09:40
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
('6638d6809813e@25147427', 'mama', '2024-05-06 13:09:20', 0, 25147427, '6638bde83860e@25147427'),
('6638d6868bb39@25147427', 'uf', '2024-05-06 13:09:26', 0, 25147427, 'undefined'),
('6638d6e8c2893@25147427', 'hep', '2024-05-06 13:11:04', 0, 25147427, '6638d6809813e@25147427'),
('6638d6efeb49c@25147427', 'hhihi', '2024-05-06 13:11:11', 0, 25147427, '6638bde83860e@25147427'),
('6638d78284bfc@25147427', 'mais ?', '2024-05-06 13:13:38', 0, 25147427, '6638bde83860e@25147427'),
('6638d7b557bd7@25147427', '@coucou', '2024-05-06 13:14:29', 0, 25147427, '6638bde83860e@25147427'),
('6638d7c359348@25147427', '@[hello]', '2024-05-06 13:14:43', 0, 25147427, '6638bde83860e@25147427'),
('6638dc72b2299@25147427', '@[rere]', '2024-05-06 13:34:42', 0, 25147427, '6638d6809813e@25147427'),
('663901e55b4f4@25147427', 'wow', '2024-05-06 16:14:29', 0, 25147427, '6638eca072764@25147427'),
('66394e138c367@95766372', 'hey', '2024-05-06 21:39:31', 0, 95766372, '66394e03bab39@95766372'),
('66394e1b5a234@95766372', '@[lulu]', '2024-05-06 21:39:39', 0, 95766372, '66394e03bab39@95766372'),
('66394e2d70c54@95766372', '@[rere]', '2024-05-06 21:39:57', 0, 95766372, '66394bbe99e7b@95766372'),
('66394fbd9fbb1@95766372', 'uwu', '2024-05-06 21:46:37', 0, 95766372, '66394bbe99e7b@95766372');

-- --------------------------------------------------------

--
-- Structure de la table `data`
--

CREATE TABLE `data` (
  `iddata` int(11) NOT NULL,
  `users_uid` int(11) NOT NULL,
  `posts_id` varchar(50) NOT NULL,
  `browser_name` varchar(45) NOT NULL,
  `browser_version` varchar(45) NOT NULL,
  `user_agent` varchar(45) NOT NULL,
  `_datetime_` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `data`
--

INSERT INTO `data` (`iddata`, `users_uid`, `posts_id`, `browser_name`, `browser_version`, `user_agent`, `_datetime_`) VALUES
(5, 25147427, '662fffb0d5107@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 13:45:21'),
(7, 25147427, '6631e64f3474c@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 13:45:39'),
(8, 25147427, '6638bde83860e@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 13:53:27'),
(15, 25147427, '6638e19ed5652@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 15:56:54'),
(25, 25147427, '6638e3657ddb8@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 16:10:25'),
(29, 25147427, '6638fb3cde9d9@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 17:49:03'),
(30, 25147427, '6638eca072764@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 17:49:25'),
(36, 25147427, '6638f9c981ce0@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 18:15:46'),
(40, 47846142, '6638fb3cde9d9@25147427', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 21:02:04'),
(44, 25147427, '66392a08a78ca@47846142', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 22:47:39'),
(45, 95766372, '66394bbe99e7b@95766372', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 23:30:18'),
(51, 95766372, '66394e03bab39@95766372', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 23:41:32'),
(55, 95766372, '6639509ec36fe@95766372', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-06 23:50:44');

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
(63, 25147427, 25147427),
(66, 25147427, 65380836),
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
(108, 25147427, '6638bde83860e@25147427', 'like');

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
(221601156, '6638bde83860e@25147427', 'video', '/users/media/@flip-25147427.media/@flip_vid_20240506132424.mp4', '2024-05-06 11:24:24'),
(289669989, '6639509ec36fe@95766372', 'other', '/users/media/@flip-95766372.media/@flip_media_20240506235022.pdf', '2024-05-06 21:50:22'),
(454433844, '66394bbe99e7b@95766372', 'audio', '/users/media/@flip-95766372.media/@flip_audio_20240506232934.ogg', '2024-05-06 21:29:34'),
(468608315, '66394f3356c67@95766372', 'audio', '/users/media/@flip-95766372.media/@flip_audio_20240506234419.ogg', '2024-05-06 21:44:19'),
(480480035, '6638e3657ddb8@25147427', 'image', '/users/media/@flip-25147427.media/@flip_img_20240506160421.png', '2024-05-06 14:04:21'),
(484580462, '6638eca072764@25147427', 'other', '/users/media/@flip-25147427.media/@flip_media_20240506164344.pdf', '2024-05-06 14:43:44'),
(651989404, '6638f9c981ce0@25147427', 'audio', '/users/media/@flip-25147427.media/@flip_audio_20240506173953.mp3', '2024-05-06 15:39:53'),
(681556813, '66394e03bab39@95766372', 'video', '/users/media/@flip-95766372.media/@flip_vid_20240506233915.mp4', '2024-05-06 21:39:15'),
(973649071, '6638fb3cde9d9@25147427', 'audio', '/users/media/@flip-25147427.media/@flip_audio_20240506174604.ogg', '2024-05-06 15:46:04'),
(982720513, '66392a08a78ca@47846142', 'audio', '/users/media/@tester-47846142.media/@tester_audio_20240506210544.ogg', '2024-05-06 19:05:44');

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
(28, 25147427, '2024-05-06 15:56:46', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(29, 25147427, '2024-05-06 16:04:21', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(30, 25147427, '2024-05-06 16:43:44', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(31, 25147427, '2024-05-06 17:39:53', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(32, 25147427, '2024-05-06 17:46:04', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(33, 47846142, '2024-05-06 21:05:44', 'Nouveau post créé', 'L\'utilisateur tester a créé un nouveau post.', 'post', 1),
(34, 95766372, '2024-05-06 23:29:34', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(35, 95766372, '2024-05-06 23:39:15', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(36, 95766372, '2024-05-06 23:44:19', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1),
(37, 95766372, '2024-05-06 23:50:22', 'Nouveau post créé', 'L\'utilisateur flip a créé un nouveau post.', 'post', 1);

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
  `content_other_url` varchar(255) DEFAULT NULL,
  `text_content` text NOT NULL,
  `media_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`textId`, `users_uid`, `title`, `category`, `tags`, `post_date`, `likes`, `dislikes`, `content_img_url`, `content_vid_url`, `content_other_url`, `text_content`, `media_id`) VALUES
('66394bbe99e7b@95766372', 95766372, 'hello', 'Amis', 'musique,', '2024-05-06 21:29:34', 0, 0, NULL, NULL, '/users/media/@flip-95766372.media/@flip_audio_20240506232934.ogg', 'hey', 454433844),
('66394e03bab39@95766372', 95766372, 'Hello world', 'Loisirs', 'hello world', '2024-05-06 21:39:15', 0, 0, NULL, '/users/media/@flip-95766372.media/@flip_vid_20240506233915.mp4', '/users/media/@flip-95766372.media/@flip_vid_20240506233915.mp4', 'hello', 681556813),
('66394f3356c67@95766372', 95766372, 'hey', 'Éducation', 'what, where, now', '2024-05-06 21:44:19', 0, 0, NULL, NULL, '/users/media/@flip-95766372.media/@flip_audio_20240506234419.ogg', 'how', 468608315),
('6639509ec36fe@95766372', 95766372, 'doc', 'Santé', '', '2024-05-06 21:50:22', 0, 0, NULL, NULL, '/users/media/@flip-95766372.media/@flip_media_20240506235022.pdf', 'document', 289669989);

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
(41204447, 0, 'pepe', 'pepe@test.fr', 'test', '2024-04-28', '926e27eecdbc7a18858b3798ba99bddd', '../../../img/users/profile/media/@pepe-41204447.profile-image/img_662eaacede859.png', 41204447, 41204447, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn5X+X/TcmkdrsknOs6Tp\nE9ShxSs6xEMZ4ybNgyXuI1+P2sPkLAnaI3VioEwcJBRBEkiRHXdJXSrPbGiem9tC\nQjX7GQaIOdnDQb0oaN8Ku4C2YvnJKWqQttLWX0VLhDwCmdtxUBPOo1Xcr64c7JT8\nz5sVFhaVcl91E5BMzx6YI/BH6wFzKHq+7l3tVLc32zfsSShCUATafbqGcgb+PY2r\npCrjgVb4/shdF6IV8OfmDvtT+qkC+TplTbqYyyE1i1U/AGsmUiwBRt7y829SLWJ3\nYJZF8JC0JkS3N9nHsGRevu4dDEN+TbOn/7Iryy7Dew84r5gy0ckFeRc71hmKZBt+\nSQIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCflf5f9NyaR2uy\nSc6zpOkT1KHFKzrEQxnjJs2DJe4jX4/aw+QsCdojdWKgTBwkFEESSJEdd0ldKs9s\naJ6b20JCNfsZBog52cNBvSho3wq7gLZi+ckpapC20tZfRUuEPAKZ23FQE86jVdyv\nrhzslPzPmxUWFpVyX3UTkEzPHpgj8EfrAXMoer7uXe1UtzfbN+xJKEJQBNp9uoZy\nBv49jaukKuOBVvj+yF0XohXw5+YO+1P6qQL5OmVNupjLITWLVT8AayZSLAFG3vLz\nb1ItYndglkXwkLQmRLc32cewZF6+7h0MQ35Ns6f/sivLLsN7DzivmDLRyQV5FzvW\nGYpkG35JAgMBAAECggEACJJlwg4eYopFjNanRXT5/Z7lO/iXeBh8/0hQfHC0IUhH\nxNUGBrhWWqM6gFu2NnK0EBRZXxjO/JpV3xlus5AMBHYInigaQ+k55YHdXGZNAEdP\nBKZgGY5n/+0q2jeYCwR7xOHjOabmtlVG7+5m27pUKtHGSMc+910mcvEqsjfCRxcF\nZ3tuDVvLld1w9nmzGBz+0Hr9PEIL7UQuuGnCqLNbNfNxd2IjUUUwyfby4GGIk+ym\nkckUHJJTphjf9cW5bqMQaQvjExXd6UiioGp2xLEcnl94hj/rn2u7I+HnwEgfinuR\nX6p2zahnQUKMRnJkY9UW87q4Bpeg5tGFztlyaYZVBQKBgQDXME1ibtTxtNDAp+xR\n6zaVpU8guJiRvwmqQMIOO2UY5E/HXyocGnNu3P8wQJ6/71Y2wQs9zgsqzw48JzNQ\n5BjJoVlapqWhQf/jBI/PtBPCnbKD462XRNV+drcvE086jIMhV4Eex55uivQX7CwG\nXimhFmsH9v1V/VJTqH5y7TQqDwKBgQC92hpN5pZNGMfNSGaoqPowD+ocZUgc3r1A\nzEeyHdmwiygjEcEJRFj4PS+oOmaa01PGFefeNf0E2jy+A2EotdxucKlTx9j5aQeP\nnd4SJh7ys2DsCCvjjMnAE6ED/16pr+aqqTVFSnqBSY8tCKHtT962tTpVY75xawqD\n/yzlprOKJwKBgCw8Iaw6XEvTf5Ya7tloILBiawGu8K4FN1doOHv0ebPEOyIgzec5\nCMGhbFPe9MM/z9kxlb3+vQzvw11aMZJPUUkhgdzApQuhJZjlNyrbObSn9ipmgyi1\nXa3BeaaTsZW3jL0t370UC91tcv/37JTihmw2z4mznCAxEttfp564ZCDBAoGARYoO\n2nvcj87E2I9tcte6FEKwCMicWl7HyWVJ2ZcPdpfyEq5dG4zaXcrjmuYRUSKY69R4\nQq9Zxx4844iPXP8IB7fifdqUwf5Tk6pKfjFSYqmLlckEcmvONjm6X6F5RgQ1JEuo\nRIIcTyDnfaJM1YTM2fWVsVNAnYCMsH6WLmQaHT8CgYAmUpMugWvxNCLxO6qinGFr\nw1tlK/fdhSmdVIAYWXvh7aFmoYyuiuRQvKw70tVGabkgzc/ODzpVgiZO0LHlgBl3\nK/nSUSbhCtzOWbu9gXl7lVUCQlBwTofyt6iOQRvq5MTZGcgzAyEEIg6cexcVD48W\ne0ZgL6eWIzRgJMuyEEiILg==\n-----END PRIVATE KEY-----\n'),
(65380836, 0, 'rere', 're@re.com', 'Je suis là', '2024-05-01', 'bd134207f74532a8b094676c4a2ca9ed', '/users/media/@rere-65380836.profile-image/img_663188447112a.png', 65380836, 65380836, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk4ZWHf8nKAUC6i17oHDi\nlKuSgr2P1Fnqxjs3ttTwH9IW19iR6BqpcGAoW8KDu4L9ZuK3NFiYVC72AqL8SMhY\nFeaLdWme/Tm55zWbvdlPlxdpomBkGw/6mg7o5eaCHHPqdh38SukgNE7v7yP1knGj\n8R7Vny8d8UHxAMflz24b9vMfV7ibP90IOFDAjcvqmmGTvd1yaaxPHuPNeJBp+hGF\n8rcJC02hZdUkhPBhCIsZuh7r/ixV+Frcfqm2PwGMObX0RkQx9K+c93o49MqmtpSk\nfdH6wJ7/gBAXcGoPtfSBkk8NGeHv9t02JQnam7UCzAaKsWbMaFnzvnPtb6NCpw4S\nuQIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCThlYd/ycoBQLq\nLXugcOKUq5KCvY/UWerGOze21PAf0hbX2JHoGqlwYChbwoO7gv1m4rc0WJhULvYC\novxIyFgV5ot1aZ79ObnnNZu92U+XF2miYGQbD/qaDujl5oIcc+p2HfxK6SA0Tu/v\nI/WScaPxHtWfLx3xQfEAx+XPbhv28x9XuJs/3Qg4UMCNy+qaYZO93XJprE8e4814\nkGn6EYXytwkLTaFl1SSE8GEIixm6Huv+LFX4Wtx+qbY/AYw5tfRGRDH0r5z3ejj0\nyqa2lKR90frAnv+AEBdwag+19IGSTw0Z4e/23TYlCdqbtQLMBoqxZsxoWfO+c+1v\no0KnDhK5AgMBAAECggEAM7mVx0W3snHzYC1bW9lIsIzpvVp+rBts1F1d/wZxPQ76\nAGtIWUPD5glpDSdJ7fyTIbhbOQjZPCcNDcacw14veFpRynSikLHlSzqCJVIk9Fp6\nqLq4Tr7PBKY2pakpNJW1/v0rgQ51QrHssYH/r/2VWE0R5JLuIjGXMUlle9HlDzQO\nHg+mJeVuYMbG+JkUMwOTDw2zHTlId7qJuRzYvP37D0l1dAayJjUrk8F/YH50dxD6\nAIOy1/ykON5OT8bVDF5osOEX40TIKm4WcapN+KgkMwIsW4hX0lVLxnTQkbGeswIT\nuB2eVB/mJPNIK21Kl8FBFpy6/32fSPHaDHGkt6lAuwKBgQDFKxERfdMtyq/G7B9F\nU+FvTkw4448aCO+cInO6MjyD1l2wfZzFGbD+Pnj111EdCBWMOTuEOWWLxEBD1ly3\n5dmi9dyXpek0M9U4imgsx19tP21bIZEJJlVWktVmpFWfTA5URGuB/1xvzezdWwJn\nk6hffeseJPVloYdXWHDFVVTXiwKBgQC/izFWQkY7zASWmKqdWmxEKG+G/4oS2B9C\nGgdXqfG9aznb9FMDffQ2fGu4J0NNIdSCj/ECoq7WBGXhPKzGV/SSEpeT7l2loUFn\nYc2u5JKbBmrTLFzivZsctqXjs/lgak6eIIgOFJP+zFf5INdCbsPGaU0Pg8v0sno2\n44YZjK5nSwKBgFnHnIQMeWa2jofT6QFWiqZ2Trrw066VNxYvkebNDUUzDYJaEFKn\nujJrHAVFwEep8LXjMbipshWMjX7WU23hmdJchc//2krM92BBleId/tPuN8txY2t5\nKw662bVdSye4KCYy6YlOUDcQ27SHxnaHg/nHipI1XhCjFs+tE9ynWHS1AoGBAJs9\nuRLsS/gS9nUH7t/xdWYYT6veWI3FVDBC4EiQmDipOw+mCdH+t3uhg7yRNd7B2Hap\nKvCz4XfbwKa4gl+O0c3B+9ANlSC0fUnI3ucsohW7McFNZ0rt2VWAQxs1tpwKDQXj\n3+FplDkibPqhP1W8aDfbCHXWn+sG7D6oUPtMJd3ZAoGBAIUqxDdPk7IPREhA8oYE\nLgI6lVgcKz9OlRqt0BeHgXN54QXR5Lr9g6apAcwb0GBkNI/O2dW9EfTLb+TMUzQT\ne7VGgz93LaGN2xTbOaqNPCdsExsfVIAjf0ogzhw4jX5R1ZIsDCdBF4dnesx3STON\nzrfOaETrmkSuRGJKBWxlUxQ6\n-----END PRIVATE KEY-----\n'),
(95766372, 0, 'flip', 'flip@flip.fr', 'euh', '2024-05-06', 'e6e5fd26daa9bca985675f67015fd882', '/users/media/@flip-95766372.profile-image/img_663942c2ba626.ico', 95766372, 95766372, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyULXPyCLo+OIot+fGlwK\nGJBm/NBBF77MfHdm5BsI/npTvHmayI78guE11xWhZ1YF/kJ4UNxTkbGKsUa0dvNJ\nNlEApNlGVtZA26aCcb5391ERWiPSeCr6RUiFVshi9HWIVkQfeeDyOolE+b6dxeDF\nEMBg3bFnr3amGTVbzNA+DjHbmuqoX5zsW7doqgHYLsfmKki/2xkvoUvmpE9sVjdJ\nG3ymTkaRgS+w3tranBERKTcJn17DjbOI1JlGQ5dToVFDpfQbzuUCFa4SggBOB2gU\nlcDKlkNyOkGLWj/UUmMi1t8AzVKvAijmb0Q5EZyWuwwGgTSn9v1xWQhaxt97JVtP\nVQIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDJQtc/IIuj44ii\n358aXAoYkGb80EEXvsx8d2bkGwj+elO8eZrIjvyC4TXXFaFnVgX+QnhQ3FORsYqx\nRrR280k2UQCk2UZW1kDbpoJxvnf3URFaI9J4KvpFSIVWyGL0dYhWRB954PI6iUT5\nvp3F4MUQwGDdsWevdqYZNVvM0D4OMdua6qhfnOxbt2iqAdgux+YqSL/bGS+hS+ak\nT2xWN0kbfKZORpGBL7De2tqcEREpNwmfXsONs4jUmUZDl1OhUUOl9BvO5QIVrhKC\nAE4HaBSVwMqWQ3I6QYtaP9RSYyLW3wDNUq8CKOZvRDkRnJa7DAaBNKf2/XFZCFrG\n33slW09VAgMBAAECggEAAmIQOmcgQCiJjaQoNh+w3umWzpeIrcdMlIy1Yy4OxTSd\nmTW3Z3iJ9OLFyG4xIxAZshEHa62wL6FyivbTXDU1QLdk0o7gDlcrHW6zTLV8pikH\nk5GpBFM982QQcB6fTVW2746O8NqBA8BsWKixJY6rxq8Adp+LIsC85r+C/sHYekKH\nfu4bdHViP+VCaWu82FXR4ok8L0hTphW8d0/qgjxo3BJzKytTMto2oMD6oT/y+/7t\nci7QIZQuG+1JVwvt2ao/dJdYrLnKIFO7CHeVoUTxHjSv0fC3JkiZdHc6ghdaQ4Z4\ntoa0V3OsylmZz1vINgHh5LSolBxbikPxK/VVfWLtAQKBgQDl63/vtvdeth+Ab9uH\nSP9eksCCxjVYasW4UOnzvITORJLbyv24LUYxPL1sgG6oNuxMC9k4eiwODZWiy2n2\n0foElhaxoJAzYQgg0HMvxrshC0pv4v3v64kjhYKew+brPUVL986ecbWVN4+wiizh\ncge2RCwMnFM1axk5Cgp9AqgC2QKBgQDgFyKtQXEyLFpx5k55x9+MwLrOEMM0G6zt\nUfNixuuFPL/Qk53XSjWL2arUTO2Ml+3kzC2v5T2vho3G/QlwE+1PR67Pw/OJY4px\noNpIMqGbaOCSk2y8sxeAjUGBqENbmVubbse8uAhZyWP8VhYeQwxDgiFBXlCeCH8n\nDIAPtltq3QKBgQCB037ETx5TXqdfEMp3E6MPNfJ6ZY2NxYyrzRStyo97IYGDlpW5\nv+bYuqGDeOoDFjBLqI0ZtPLXZJhP+mh1NI/msxFRb4a9XFcIoGvKWH8l44g+sYAO\nT4N6iVL+b0QfJNPSV7VWBoMwJNWALCW3j3oHU2tEG6loincNnDvb5voT0QKBgQCM\nsZnhA5SJdtV2a63bwgT2P09J77ZPcgzZl7lizBe1OJYJ45SXRg1md8xMrNv8iKsh\nGpyYsipPmQBM7jPNvYUiYJQ99/5gxTF39BD4gWl7xJYzEQKVWzAnUwjM2hMoYJnm\ndeBL+ZOso8f19yq950S84ef1Pb0J67XBk5SXyNuOtQKBgE+FKfwnRYeWGvsSU/ek\nljlOihdbcgMGaucUDqFy0zEz6wr5KEgyC0jJLCvNTM5VySOm/CrUt1KVWzQKnoD3\n4qNewcxWku2m8nAU8XI6QFFbFoRCQcGYQOUtFaBRepHDrVi8IDbdnfI0JOzmVjvk\noI/kR1Us3ac8RGsx1LJU0KGr\n-----END PRIVATE KEY-----\n');

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
(25147427, 'blue', 'en', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(15972122, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(41204447, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(65380836, 'blue', 'fr', 1, 'private', '{\"param1\":\"valeur1\",\"param2\":\"valeur2\",\"param3\":\"valeur3\"}'),
(47846142, 'blue', 'en', 1, 'private', '{\"additional_info\":{\"fullname\":\"chris duplant\",\"bio_extended\":\"hey\",\"location\":\"Bourogne\",\"social_links\":\"https:\\/\\/test.com\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"in-a-relationship\",\"birthday\":\"2001-06-30\",\"privacy_settings\":0}}'),
(95766372, 'dark', 'en', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"it\'s me mario !\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":\"on\"}}');

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
  ADD UNIQUE KEY `unique_post_user` (`posts_id`,`users_uid`),
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
-- AUTO_INCREMENT pour la table `data`
--
ALTER TABLE `data`
  MODIFY `iddata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=982720514;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
