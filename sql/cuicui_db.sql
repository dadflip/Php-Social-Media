-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 09 mai 2024 à 09:15
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
-- Structure de la table `bans`
--

CREATE TABLE `bans` (
  `userID` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `startTime` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Déchargement des données de la table `chat`
--

INSERT INTO `chat` (`chat_id`, `content`, `datetime`, `type`, `chat_src_id`, `chat_dest_id`) VALUES
(18, 'hey', '2024-05-09 05:03:53', 'default', 47452088, 90566459),
(19, 'hello', '2024-05-09 05:06:50', 'default', 47452088, 90566459),
(20, 'how are you ?', '2024-05-09 05:07:00', 'default', 47452088, 90566459),
(21, 'oh', '2024-05-09 05:31:27', 'default', 47452088, 90566459),
(22, 'test', '2024-05-09 05:31:34', 'default', 47452088, 90566459),
(23, 'coucou', '2024-05-09 05:31:42', 'default', 47452088, 90566459),
(24, 'hey', '2024-05-09 05:41:54', 'default', 47452088, 90566459),
(25, 'salut', '2024-05-09 05:44:00', 'default', 90566459, 47452088),
(26, 'ça va ?', '2024-05-09 05:45:54', 'default', 90566459, 47452088);

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
('663c2e5634bf2@90566459', 'cool', '2024-05-09 02:00:54', 0, 90566459, '663c0b4c93fa4@90566459'),
('663c2e5b25cba@90566459', 'yep', '2024-05-09 02:00:59', 0, 90566459, 'undefined');

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
(1, 90566459, '663bbea09b495@90566459', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-08 20:04:22'),
(5, 21427312, '663bbea09b495@90566459', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-08 21:49:54'),
(7, 21427312, '663bd7fc245b8@21427312', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-08 21:52:47'),
(44, 90566459, '663c0b4c93fa4@90566459', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 01:31:50'),
(46, 90566459, '663c0c85f251e@90566459', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 01:36:44'),
(94, 47452088, '663c2fa4e576a@47452088', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 04:06:37'),
(103, 47452088, '663c0b4c93fa4@90566459', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 04:09:23'),
(106, 90566459, '663c2fa4e576a@47452088', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 07:42:50');

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
(7, 47452088, 90566459),
(8, 90566459, 47452088);

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
(3, 90566459, '663c0b4c93fa4@90566459', 'like'),
(4, 90566459, '663c0b4c93fa4@90566459', 'like'),
(5, 90566459, '663c0b4c93fa4@90566459', 'dislike');

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
(118003931, '663bd7fc245b8@21427312', 'image', '/users/media/@aaaa-21427312.media/@aaaa_img_20240508215228.jfif', '2024-05-08 19:52:28'),
(413005551, '663c2fa4e576a@47452088', 'image', '/users/media/@dave-47452088.media/@dave_img_20240509040628.PNG', '2024-05-09 02:06:28'),
(778765614, '663c0c85f251e@90566459', 'audio', '/users/media/@test-90566459.media/@test_audio_20240509013637.ogg', '2024-05-08 23:36:37'),
(799276797, '663bbea09b495@90566459', 'image', '/users/media/@test-90566459.media/@test_img_20240508200416.PNG', '2024-05-08 18:04:16'),
(845708015, '663c0b4c93fa4@90566459', 'audio', '/users/media/@test-90566459.media/@test_audio_20240509013124.ogg', '2024-05-08 23:31:24');

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
(12, 90566459, '2024-05-09 02:12:21', 'Post Marked as Sensitive', 'The post with ID 663bbea09b495@90566459 has been marked as sensitive.', 'post_sensitivity', 1),
(13, 90566459, '2024-05-09 02:46:43', 'Post Marked as Sensitive', 'The post with ID 663bbea09b495@90566459 has been marked as sensitive.', 'post_sensitivity', 1),
(14, 90566459, '2024-05-09 03:39:35', 'Post Marked as Sensitive', 'The post with ID 663c0b4c93fa4@90566459 has been marked as sensitive.', 'post_sensitivity', 1),
(15, 47452088, '2024-05-09 04:06:28', 'Nouveau post créé', 'L\'utilisateur dave a créé un nouveau post.', 'post', 1);

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
  `media_id` int(11) NOT NULL,
  `sensitive_content` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`textId`, `users_uid`, `title`, `category`, `tags`, `post_date`, `likes`, `dislikes`, `content_img_url`, `content_vid_url`, `content_other_url`, `text_content`, `media_id`, `sensitive_content`) VALUES
('663c0b4c93fa4@90566459', 90566459, 'Music', 'Musique', 'musique, undertale', '2024-05-08 23:31:24', 2, 1, NULL, NULL, '/users/media/@test-90566459.media/@test_audio_20240509013124.ogg', 'On peut poster de la musique', 845708015, 0),
('663c2fa4e576a@47452088', 47452088, 'Image drôle', 'Humour', 'mdr, massage, barber', '2024-05-09 02:06:28', 0, 0, '/users/media/@dave-47452088.media/@dave_img_20240509040628.PNG', NULL, '/users/media/@dave-47452088.media/@dave_img_20240509040628.PNG', 'mdr il est en souffrance le couz\' ! Alala', 413005551, 0);

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
(47452088, 0, 'dave', 'dav@test.fr', 'ma bio', '2024-05-09', '1610838743cc90e3e4fdda748282d9b8', '/users/media/@dave-47452088.profile-image/img_663c2efb4e6b0.jpg', 47452088, 47452088, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAysfjRqUOHuILuSAuJSNf\nCpeRoZwiq2CtV4wtiTkNMDzcDb9hbK16yMGYfukGBJdLYe+4mBMRanQggrSWC4fm\nF74nYujDBL7kEDg+FL/jOUegRSIZ3+97PTfZYOD/wHY6rV4je3/MaFpA+omV/rYK\nbyjUMncSoVXgscfPhlr3HsyoRqDhzulJRHGndg/rOtJMnBCUo6CqbVCVIa+rE1k6\njRolrLlso8ILn6SRJAPtsWcQu6gddu9Z/vkLX34uYbP79Q8rYxmcLoIk2y64cXO3\n1+HoplXn4MlEzNFKLftpwMnqfzTaoPJovdb3ISM/cRfU7odrRGjehpp5twS7zZVF\n/wIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDKx+NGpQ4e4gu5\nIC4lI18Kl5GhnCKrYK1XjC2JOQ0wPNwNv2FsrXrIwZh+6QYEl0th77iYExFqdCCC\ntJYLh+YXvidi6MMEvuQQOD4Uv+M5R6BFIhnf73s9N9lg4P/AdjqtXiN7f8xoWkD6\niZX+tgpvKNQydxKhVeCxx8+GWvcezKhGoOHO6UlEcad2D+s60kycEJSjoKptUJUh\nr6sTWTqNGiWsuWyjwgufpJEkA+2xZxC7qB1271n++Qtffi5hs/v1DytjGZwugiTb\nLrhxc7fX4eimVefgyUTM0Uot+2nAyep/NNqg8mi91vchIz9xF9Tuh2tEaN6Gmnm3\nBLvNlUX/AgMBAAECggEAX81pFH61eaS8+iT4eCZKaDQsowgjnwkJTDPXbZ3NG82c\n/0e8LP6W5FMGi2rnI3MhM69wbDSSBVPA6k+MFf2KGQyHCiVCwbK4B+uydNYlcXHk\nB9VxGQnJFSaWJj2WxuR0m2S+6cF6npCJY3RuL4zsyBCnlAIKhLuQcR2f7295kgwy\naKAaX4TCJ2xQOe5sGCvThIPauYHZpGX99h1PyALTOAOawr+1Ja8JdSTCFa6CVzDv\nDtIB+holp1Pv94V0CQJoYACAZhx+wSJbjd22wVS1HhXVNvuofv9ktyRmFKYYqYMu\nGRK+z7CyFYfX+YtR5NNTihOP4Y7WLVMTL8oSKdrvTQKBgQDj3W+kEJbXAsJRGR6h\ndrIiCAmMd8SrPjut4INggPjqotTDwqlULeHWbF2uilkJcN0axP+D2iBap86yw7b+\nKpLHtQLYYQbwlpE/zIqMOfifTTQqaG0+j4QU8pQkp5Lp/exRNqiq3TfEFRvVA3Yn\np8NmGE15mf9T9avHfJuXq0YmDQKBgQDj0ZF9YKsoN/uMCY3AuN6MpJf1+5POnYAl\nPDn4v5KeCHvnanU7PKFdfkQ3gWqhy73vmWCy0AyEmvq2G7gDB9p2ptqzPCxrDDwL\nuJB7N7zKS2D3r4zhxDKB8iHzeVGB08qp+okaggXPX8t6rAZmpFUg61vSoFkl3Bl1\nY4Nlk41FOwKBgQCWKrqGTwB5VjIlK/7JveWLE+znC48qo2rFHi62jIh+aSz6jfD1\nrv8kRmo+uqIO3Lh6CdWyWS/1Tp2jpeOVAQ2WWy4y106xgyjZ+LxdyV6V7VUxlXxZ\nPE95VpxWw7DIUDVLGFBzujIkIR0kdpGJdMALKnz0ZI6mEENSxkXr4E3siQKBgDur\nLw0Ena+aAhNqiSsaAIgbsAVoUA1EqUSXYqOpIXCszi8NbgkG10jSgnF7Pv+DiorP\nxEjxPuYYZWP/HJIGo4yCJTPepc+f07ZKL+Vl8teVKJ+W5jJXndb1ITlQ5C052JHu\noaxEs9rdv0Cw/7gwgG9v1Yv0HYVSAYUCfG7FV7HzAoGATatqT9sgKnykAez7WUyY\ndOab5P05siAaBEOyoiQdD65XhmSWFWkCAakSj3zqEVAZ8+HFwCbXYOkexhLV5GtU\nb+yLxwHercVwCWGkXtqZ7/zjBD97ml7mv27sXidlqWfFeDtWz/9jgdhJOXXoHfwA\nfidtx883VKNm2gQqeer/FjI=\n-----END PRIVATE KEY-----\n'),
(90566459, 1, 'test', 'test@test.fr', 'bio', '2024-05-08', '098f6bcd4621d373cade4e832627b4f6', '/users/media/@test-90566459.profile-image/img_663bba8d004c3.jpg', 90566459, 90566459, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxUm26MvSJfjkp4potC2L\n2/grMkhi88NFojhuaxKvBWJuGgY2QgxP6LcBVbAt8FGILV0gF3Vn9MwPV5zVUQK9\n42FJNKvUNssYW9E2sMvb4WrTjefYSMJhj+ktR5zwLxkRs3iNeNpzOSS7/k2Jq76r\nBA5gwaMmhjrfXiSrNq5klAqyldDTbG9YfiKgJpCPzJOSZLz1jfI+OO6tAj4jYXjW\no90o4Aa19AdYQus0wjIJyVOJ+DO5UAKnL6EaNwoKJQ29tp42Gy3s6oCEHon8G+c0\nFtOyL65uNMUkFH3Xxu0yGRdzdzvkKC63wQZ1qiTCtgc+jJsjh8Z3y70Iq6hiCQS3\nVwIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDFSbboy9Il+OSn\nimi0LYvb+CsySGLzw0WiOG5rEq8FYm4aBjZCDE/otwFVsC3wUYgtXSAXdWf0zA9X\nnNVRAr3jYUk0q9Q2yxhb0Tawy9vhatON59hIwmGP6S1HnPAvGRGzeI142nM5JLv+\nTYmrvqsEDmDBoyaGOt9eJKs2rmSUCrKV0NNsb1h+IqAmkI/Mk5JkvPWN8j447q0C\nPiNheNaj3SjgBrX0B1hC6zTCMgnJU4n4M7lQAqcvoRo3CgolDb22njYbLezqgIQe\nifwb5zQW07Ivrm40xSQUfdfG7TIZF3N3O+QoLrfBBnWqJMK2Bz6MmyOHxnfLvQir\nqGIJBLdXAgMBAAECggEAVLzdsE3jPGYUKBYd9M3fVaLZQuaugc7suMmOO7UxsZle\nexUR9vp81BLOovRiNud+GyX1o73M4etH57S4Nux7jQ97NwFazBIdq0pIOFXHYqHU\nrg3X6yB6cSqRRUIWM9SLss9jzHXfqbqqeIbrS/ZAVCSs0F74LA1bZX9QftMW5jKU\nhnRPpd/gKdMnNKt993bx2YHXB2QIJo3ZjRen95eoSt10baiicdPDnZau4ZKyQnAj\nm/wH8XLFNhDK93vI/eeaGKRHQDGENskLcINI+CAjvR8pvJr935lilx+RruurdY7G\nq5YFH0WoLm2ERegu8PR9+0yfw2xApb7KjIr9LMR92QKBgQDxdMuDY5UroScFF+hM\n5cJORR/MPedG4YiU7PYPibLlUnhkVHBYnBTN4lg0/dtxzxsKtzcJZ/hqWKY4tQce\nSi8yU0+IsulvkWvP7zaWDTvkp28KPbsc/z0kLuq/Y9LziJe0AR/Qg1dQ0DBs9C5K\nYdfMEfBk1wN4rVAZpzaDDJdBYwKBgQDRK9qPAxTjhOQYopvVH25oDTWksfQIu9jd\nf0Q64hSlrK+mHTm6PUDW/rAUMyrOK4uhDo1tGBkE7bKrqoeM9QKvlOe1+qUfqcYP\n3sKsknAWoPV6P1/DqqgOTDs+mPvm3U8G29Mv8XbZ5zCJJeSsbgTNx8X9x0CN1pft\n+kEN9g4ufQKBgCAun/14JWa2SPwdzqGJMhe6kN1pFs1UvB6VYsp5YPW9wzwkIQE+\ndGtokrclar1FjGYmO1iumeaawDTos/UukBHWFtCyf/iV2lfRm2Xuh6AXTO6BgdIA\nlwaelj1CMdixyP59896X0uh7hyjOdukFF30kmvTlz5mjB/0TU2muIO7fAoGALJPr\nBVWDyyVUM0fcbCRBtF00VIA0YPQfuLzZvq7V9aUC3iUWW3GYcvlebz+kXMH9BPsD\n+v8Kvfkmfd1e/BRvWRoj862SKVhWRKWV2Y9v4fK1vjLpZtrk6/8lFScncEE0CWMp\n8+rDCJdzyZbQ0Eq2LWDo+jfPsyrLwET4PlB1H0UCgYBV/3ovQQzQPZAKDVpR+od7\nSA0UGDZCwAZrYlUj/bvFfGHi8gZu5xmZABuVeJKGWTru5U4C8dpEDLvyUVbRyj6c\ntor+ycFSMzYK+lkqAjgNhMCJDciS0qz1xjppwT2TuBCDRl7qw7rCJFqqFknl/mSu\nBCFs1/PBMGixqH/jlSGVgA==\n-----END PRIVATE KEY-----\n');

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
(90566459, 'dark', 'fr', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"hello\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":false}}'),
(47452088, 'dark', 'fr', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"hello\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":false}}');

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `data`
--
ALTER TABLE `data`
  MODIFY `iddata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=982720514;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99343030;

--
-- Contraintes pour les tables déchargées
--

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
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`users_uid`) REFERENCES `users` (`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`media_id`) REFERENCES `media` (`media_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`textId`) REFERENCES `media` (`posts_text_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
