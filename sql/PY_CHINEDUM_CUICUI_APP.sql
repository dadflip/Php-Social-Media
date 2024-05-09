-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 09 mai 2024 à 23:51
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
(1, 'hey ? Tu vas bien', '2024-05-09 21:42:48', 'default', 12027319, 10230321),
(2, 'oui ça va', '2024-05-09 21:43:12', 'default', 12027319, 85512546),
(3, 'oui je vais bien !', '2024-05-09 21:44:21', 'default', 10230321, 12027319);

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
('663d385ead29a@85512546', 'Attends on peut aussi mentionner @[userB](il faut enter \'@\' suivi du nom d\'utilisateur et d\'un espace. si des crochets apparaissent \'[\' c\'est gagné !', '2024-05-09 20:55:58', 0, 85512546, '663d37fdc1718@85512546'),
('663d389f93156@85512546', 'Il y a une face caché derrière les post ! Il suffit d\'un clic ...', '2024-05-09 20:57:03', 0, 85512546, '663d3710ede46@10230321'),
('663d396e1ea37@85512546', 'Attention car si tu poste du contenu inapproprié, tu peux te faire ban par les admins, ou ton post peut être marqué comme sensible. Il apparaitra alors en rouge @[userA]', '2024-05-09 21:00:30', 0, 85512546, '663d390b8c494@85512546'),
('663d3a815ee10@85512546', 'Oui tu as raison !', '2024-05-09 21:05:05', 0, 85512546, '663d385ead29a@85512546'),
('663d3d24465b2@10230321', 'Exemple de contenu marqué comme inaproprié par les admins', '2024-05-09 21:16:20', 0, 10230321, '663d3add25ef6@10230321'),
('663d41abc6eca@12027319', 'Est-ce que tu as essayé la barre de recherche ?', '2024-05-09 21:35:39', 0, 12027319, '663d41681f56d@12027319');

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
(1, 85512546, '663d3710ede46@10230321', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 22:57:18'),
(7, 85512546, '663d390b8c494@85512546', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:00:48'),
(9, 12027319, '663d3add25ef6@10230321', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:07:22'),
(12, 10230321, '663d3add25ef6@10230321', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:15:48'),
(14, 12027319, '663d41681f56d@12027319', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:34:38'),
(19, 12027319, '663d3710ede46@10230321', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:42:11'),
(20, 12027319, '663d390b8c494@85512546', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:42:58'),
(21, 10230321, '663d41681f56d@12027319', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:44:05'),
(23, 10230321, '663d390b8c494@85512546', 'Netscape', '5.0 (Windows NT 10.0; Win64; x64) AppleWebKit', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) App', '2024-05-09 23:44:47');

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
(3, 10230321, 12027319),
(1, 12027319, 10230321),
(2, 12027319, 85512546);

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
(429476690, '663d37fdc1718@85512546', 'other', '/users/media/@userB-85512546.media/@userB_media_20240509225421.pdf', '2024-05-09 20:54:21'),
(676868901, '663d41681f56d@12027319', '', '', '2024-05-09 21:34:32'),
(701553733, '663d3710ede46@10230321', 'audio', '/users/media/@userA-10230321.media/@userA_audio_20240509225024.ogg', '2024-05-09 20:50:24'),
(893381623, '663d3add25ef6@10230321', 'video', '/users/media/@userA-10230321.media/@userA_vid_20240509230637.mp4', '2024-05-09 21:06:37'),
(954371625, '663d390b8c494@85512546', 'image', '/users/media/@userB-85512546.media/@userB_img_20240509225851.jpg', '2024-05-09 20:58:51');

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
(1, 10230321, '2024-05-09 22:50:24', 'Nouveau post créé', 'L\'utilisateur userA a créé un nouveau post.', 'post', 0),
(2, 85512546, '2024-05-09 22:54:21', 'Nouveau post créé', 'L\'utilisateur userB a créé un nouveau post.', 'post', 0),
(3, 85512546, '2024-05-09 22:58:51', 'Nouveau post créé', 'L\'utilisateur userB a créé un nouveau post.', 'post', 0),
(4, 10230321, '2024-05-09 23:06:37', 'Nouveau post créé', 'L\'utilisateur userA a créé un nouveau post.', 'post', 0),
(5, 10230321, '2024-05-09 23:07:25', 'Post Marked as Sensitive', 'The post with ID 663d3add25ef6@10230321 has been marked as sensitive.', 'post_sensitivity', 0),
(6, 12027319, '2024-05-09 23:34:32', 'Nouveau post créé', 'L\'utilisateur admin a créé un nouveau post.', 'post', 0),
(7, 10230321, '2024-05-09 23:36:36', 'Post Marked as Sensitive', 'The post with ID 663d3add25ef6@10230321 has been marked as sensitive.', 'post_sensitivity', 0),
(8, 10230321, '2024-05-09 23:41:09', 'Post Marked as Sensitive', 'The post with ID 663d3add25ef6@10230321 has been marked as sensitive.', 'post_sensitivity', 0);

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
('663d3710ede46@10230321', 10230321, 'Post de User A', 'Musique', 'musique, undertale', '2024-05-09 20:50:24', 0, 0, NULL, NULL, '/users/media/@userA-10230321.media/@userA_audio_20240509225024.ogg', 'Ceci est un contenu de type Son', 701553733, 0),
('663d37fdc1718@85512546', 85512546, 'On peut publier des documents pdf ??', 'Anecdotes', 'astuces, cuicui, app', '2024-05-09 20:54:21', 0, 0, NULL, NULL, '/users/media/@userB-85512546.media/@userB_media_20240509225421.pdf', 'On peut effectivement poster des documents pdf sur cuicui app: c\'est vraiment génial !', 429476690, 0),
('663d390b8c494@85512546', 85512546, 'Post image juste pour le fun', 'Amis', 'image, cuicui, app', '2024-05-09 20:58:51', 0, 0, '/users/media/@userB-85512546.media/@userB_img_20240509225851.jpg', NULL, '/users/media/@userB-85512546.media/@userB_img_20240509225851.jpg', 'image', 954371625, 0),
('663d3add25ef6@10230321', 10230321, 'Contenu inapproprié test', 'Amis', 'cuicui, app, test', '2024-05-09 21:06:37', 0, 0, NULL, '/users/media/@userA-10230321.media/@userA_vid_20240509230637.mp4', '/users/media/@userA-10230321.media/@userA_vid_20240509230637.mp4', 'Ceci est un contenu inapproprié', 893381623, 1),
('663d41681f56d@12027319', 12027319, 'Je sui l\'admin', 'Amis', 'astuces, cuicui, app, admin, modération', '2024-05-09 21:34:32', 0, 0, '', '', '', 'Je suis administrateur je peux marquer un post comme sensible, bannir un utilisateur en cliquant sur son nom d\'utilisateur sur son post afin d\'accéder à son profil ! l\'avertir, via une notification etc... je peut gérer les bans via l\'interface admin en cliquant sur le lien à côté de ma photo de profil', 676868901, 0);

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
(10230321, 0, 'userA', 'user_a@cuicui-app.fr', 'Je suis userA', '2024-05-09', '697aa03927398125bb6282e2f414a6be', '/users/media/@userA-10230321.profile-image/img_663d36d2998b0.jpg', 10230321, 10230321, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtyFTRkklbEzRTTzh/zk2\nGwwPVM58TomIglk8Llc5L5mpdnoeUhyUxo78hsuRWC+fMQjicknQ0TenW3yfUivf\nyAyTqlT47IHfHLQVmJpLsqbVRCMQaSsK4To948eOoLhQ69n1HFD/L9C2gwwkPmsx\n+PpqbHBrI+x2QceY7S4WyZy+BZmN3XyoG2uwuDZUwXeoRrNpveisBOave38XuQfn\nDvbPiADBaoVTSC8atpL3QZKtUp5iJZ1wj202vvUIFla6JA321wiIXGZC0Y+CSjnJ\n9DQ1QvBWJml7D6WdECnjOvShqxDdFXzeEtNIaYyeD+n9OMzBTC2Z/YZ5YADq6FbS\nVwIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC3IVNGSSVsTNFN\nPOH/OTYbDA9UznxOiYiCWTwuVzkvmal2eh5SHJTGjvyGy5FYL58xCOJySdDRN6db\nfJ9SK9/IDJOqVPjsgd8ctBWYmkuyptVEIxBpKwrhOj3jx46guFDr2fUcUP8v0LaD\nDCQ+azH4+mpscGsj7HZBx5jtLhbJnL4FmY3dfKgba7C4NlTBd6hGs2m96KwE5q97\nfxe5B+cO9s+IAMFqhVNILxq2kvdBkq1SnmIlnXCPbTa+9QgWVrokDfbXCIhcZkLR\nj4JKOcn0NDVC8FYmaXsPpZ0QKeM69KGrEN0VfN4S00hpjJ4P6f04zMFMLZn9hnlg\nAOroVtJXAgMBAAECggEALDOggH4GSOIvwMdZuztJanTaPHuMeH5s0x2Lz5mhBXK+\n+whVyjb42AZKQJThE5qG/5Rn5/MWMnGO9R+a6wAJByW1/K5+2MMuIaT783YGoGvx\nXP1jqCbJ/PwMVS7lJ7jlW1Aj//4y4g0sCGyFUQWZrcFvr5Xg3m5kStNM/66YtHe2\nwmFEslL0oXo1iGh9ST0TO//WNlJfOtL2pk91Zky5WGZ3aIOhHXHdlyf0+/QK0IYt\ntGpY1PWRmtA14teJf1RuSh1WC2AVI+jRCdcOQCrqU0Qp0GGeznieZ05WxZQ5d9Q5\nVp2FChWeRBHdbw6PQQHy+dUI4oJDQVaLZx2XcKRZoQKBgQDcBsJpoIwOiO3qc7lD\nwrUWk7+SFOOpflZxVCao6U8+3kqCBfygXBfFzfOzcPv3A3VIUImHUsSBRhyjw0Y9\nOiXKybQMvNS9NBdg9T3ojYr6+la4mIkE5SYTGzDHB5QhMIcrlwgQ6CvWxtorzRjR\nZcMhPJc3WvCUGBEufD5G9m6LYQKBgQDVEkSx8KPPPPKOF6jfSJmRhyR0WNxldfbM\nb3EJXlhg5kGCqcU+5+EDi2C2Xtwuchi64G9ZcGNaE16oosuy6y2l1IbzZ4y43K7V\n6BEGzXyfkkY1quPhICDt0ZGZ43nhiHp4WsqRtk7YnLgNv27qQ2pFtT5z1blRF3oO\nASRGao8wtwKBgQDJ/26m2J0cjvuVvg59NhlYk+ZRb9oe9nzX6IDhm+iD+EKBIovu\nkP3GPLEasYSxHzyiWkYYtZTwdfS/J/ypI2Qky48ADrBkz5kTC7N9d8z7Y68QJAHP\nO6z753+dicilu86s5yihlPUCZmdPxSievYUqU10kldm+rGXBvpDSR1XvoQKBgAqz\n7Ddz+waB7T0t+xGcN/qTa0Vc4VVHY6pJPshVsOLNLiU+GeZbB3aCHc2LXkwG9y8L\n7wvzFUqFBpA5/TlzjstxrYW0geEXVMDx80kAMW5ijpxLhT7dukm36TRttMWbcWty\nRBKlsbg3xxcJsqyZzlDKFeUDq1djcoIZlJTk7WRpAoGBAL4QHsmQCj0kRwJRgmy3\nIFaXkQ+Ke+tpHSZqpzd63XsRG0R9HcjuX21MvwVBecW52ZQmhhDzsXSugmpSkMKG\nvXFm87GA0wEjOpanihr7i5u0VFbjNKm+QueJGxhbYO829EyBZNsLG4qSGTdc4fJJ\nj1Bs0tvAgc4detDcZ0b07E8X\n-----END PRIVATE KEY-----\n'),
(12027319, 1, 'admin', 'admin@cuicui-app.fr', 'Je suis un administrateur !', '2024-05-09', '21232f297a57a5a743894a0e4a801fc3', '/users/media/@admin-12027319.profile-image/img_663d365b933be.jpg', 12027319, 12027319, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3cK0nQ0OFzyEvaGTAyLL\nXpD9jM5xNfXpx8jAIg+OC+N+VuJDwCvBWZvWpuYGqHlFOFUcQlMZ1no+u/6fpV+v\nDuF/gF4sxoFhGts1mPJV3Sl+NYE2lbeZBFpNjdvTRzOH0H9RJGPj/0keld99xsC+\nOGZQhG+0DISP6viXVvqgCXmOVphciOjO5DAbO1RilNjGiXO1wJ9rdJ8lhJ7t5B3o\nyNK6VKz7kEDp/RDRW52xQQuDwSbRmYjRBLSyOxDgGzAlUZnbEUL8Sn351sr8UmUL\ngWrurPDVnrcG1+JvxY/jOsj7DjFeq/vdVAATI8A8SxklETvz2ETTanKkBd3Uana8\niwIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDdwrSdDQ4XPIS9\noZMDIstekP2MznE19enHyMAiD44L435W4kPAK8FZm9am5gaoeUU4VRxCUxnWej67\n/p+lX68O4X+AXizGgWEa2zWY8lXdKX41gTaVt5kEWk2N29NHM4fQf1EkY+P/SR6V\n333GwL44ZlCEb7QMhI/q+JdW+qAJeY5WmFyI6M7kMBs7VGKU2MaJc7XAn2t0nyWE\nnu3kHejI0rpUrPuQQOn9ENFbnbFBC4PBJtGZiNEEtLI7EOAbMCVRmdsRQvxKffnW\nyvxSZQuBau6s8NWetwbX4m/Fj+M6yPsOMV6r+91UABMjwDxLGSURO/PYRNNqcqQF\n3dRqdryLAgMBAAECggEAArQf/ZYuKMxdFe7FWxFuh02fObs+mBjSa9Qfu4qTAcQZ\nQzfo+YvPBKeou0ospJst49ztxkU7GGlPIH2fNqXu/XB0XH1JcHYdV/V6Ns4li9e4\nqTBVGQgu/mWXcnZOohyXuEwFEjuBiRg8HjmmT+kDqK5epu6xtEFde9DiD3ZowxTJ\nfREenrztxTQNPyKen0kUuNQwYeU7i8r1F4B7GzOr6oZVK92yvb+MlfaQRLJnTSKH\nHzKwZD/4atEyYmLP5j6nPvao4fJmp04h6853Jq+L9NZC/Ujjby1I5xtI2mEPFS9e\nwsbQwq1I2L9ide1/Jk9crORcJKUA/5cGR/F0X05zqQKBgQD7QVb4i6i9bQ8pwB//\nmNFI/diOStwc63QfzIrA68/AhdmUUwVCvetd06B7u5IExPNFVsQ/otey0qzZFCZq\nsLlu6ier4Rl76/PCeR8pyFW2gYBydHRCC0pLA7qJtw1LGdxYnyDv/YXOvV9tFjdy\nYZnN+jQuCD6fM4+qbg1YckBKnwKBgQDh8scZD6hbHXk6ywb3rkRdNrp8Vs6zIih/\nAPJWuUHxuwswMhPSoYpOexfUa5rks+Yt17PDUyvwIDY95DWpTXNZ7l1oWHEUKwYj\nhUbo6zzxjXaOTvQtBtvjHNOlcYrG1DQZ5W1/Lz9r0IvBgdp3T4qPFCKs0eZHP4ez\nwY0ZXN/ylQKBgAKaZ7NFhycHRTvygKJeS/TMpHy869Dk45wcu2dKSim9eUafIZPS\ncejT83q0s8uW2spiHsBmCIQSyLWrDsbWM+WhSW3vdBsjk6/1H0yIhdLRPqpw6G9y\nvH9Qd5NLsTiQ+QZ2B5pU7xj4P0/bBhIKTtSiff3oGfX+S8PBRiQfcukTAoGBALKx\nBFCHl4fnuVaL9oivI5XskHOBW5tD6m722rFHdiWs3V5SXGb6Jp2GsAPozekNdX7P\n1nUlM9yGPR+Zee7ZN97pqqm5OETQ+dvhrYDLebk3PYmyW124zPHCfIedUjmI3WOV\nlJE/VpL3rW9uOM6e81/QdtPidJHoZnP9II/saZg5AoGAEhLhVfRug2rwf4LTGb9i\n4XE/qMZTnwQaAWLEOPhIm5xQ0RxV8/Qw6vDuenoGzjBrJWno1Fq+AwGHTIzhNI6t\nV6n9398vuCktImLUrrTV2YPZWtqFj3IdkYq/wWIIVNguO4HlkrTm4D+UVeShXgor\n0DsKs42XQ5qlAebhi0xPS6Q=\n-----END PRIVATE KEY-----\n'),
(85512546, 0, 'userB', 'user_b@cuicui-app.fr', 'Ma bio est vide !', '2024-05-09', '4e1bdf42c33b390163a92510397d97b0', '/users/media/@userB-85512546.profile-image/img_663d377de2c5d.ico', 85512546, 85512546, '-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAulrxFMXQ6nJ4k6Sxhkw0\n5vvV5t9Vq/AKdP7jCLs+TCQCea7AsQ6oFrb7F4TjMqt3fCpbQ1xkQTmF2a23G2nF\nekbb1QIjhR/VZfSChjV2mAdru6gkbq4hli+mK6Ue0fD3SGHZ/fcRhel0ndQqIu4I\nQTi4XWwiTgktNFTQOGinRcIQ3vL9gG9+ja9HlY/QzHAhLtdvwR7YUs9KoQ9n+ceZ\nrIDUx0076F3MYkgGQgBZat9h1+ZOcqhB/NIiv/RLYU79rXzs0FysNGLzQ/dOxh5D\nHipARpx4URFQDVSR6Vr7x0y6gNF1//Riq5mc6CPJ5pP3I+ANfLqjZU1RRFBiNrQN\n+wIDAQAB\n-----END PUBLIC KEY-----\n', '-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC6WvEUxdDqcniT\npLGGTDTm+9Xm31Wr8Ap0/uMIuz5MJAJ5rsCxDqgWtvsXhOMyq3d8KltDXGRBOYXZ\nrbcbacV6RtvVAiOFH9Vl9IKGNXaYB2u7qCRuriGWL6YrpR7R8PdIYdn99xGF6XSd\n1Coi7ghBOLhdbCJOCS00VNA4aKdFwhDe8v2Ab36Nr0eVj9DMcCEu12/BHthSz0qh\nD2f5x5msgNTHTTvoXcxiSAZCAFlq32HX5k5yqEH80iK/9EthTv2tfOzQXKw0YvND\n907GHkMeKkBGnHhREVANVJHpWvvHTLqA0XX/9GKrmZzoI8nmk/cj4A18uqNlTVFE\nUGI2tA37AgMBAAECggEALfCVVeWeJu2pQ9xp1w2aTQbwKEYOPKCkM0tSdKiUJvkJ\nky29WuieD1t0csvTpn4WqolWjjiMjne7w3nLlfpxMD+fmdc5ImxCkyu/sY4kej36\nFPzSXmaW6Qs+D6xY+LBKhZF5x93MeBKhKPdQm+PKLSnqaT1rB4nDlw8BdaZ29f8A\nxyOhW5ILABuhT99+qQotT+42eUNpewatPhusFw4s4RBVFXFGr/gItcGFuQj78DQH\nRvJstLfspF8DaOQ14IHOUJhf0fQ2PubW32m0Qp7lrkxXf2xB7myFTn6Ay36t0Ds4\nu4ni+l/nxo8kegsT6Ma8DBHbWIQJ4EFsyCWayZ8ewQKBgQD4yifHEiTt7g8Gw89H\nW3pl9wkzbDozqVKlVUcllPVbTTQk5USxueuFWeqYLY2Qpflc6ivQ5835tGd+sjEx\nZcDg/uYVQGORn082wmv7Y8N1Gje5tz0giSj5g91wvCJaLjv4q660lFM+qNsx3/bu\nxmfDul19171OXWBId2HGupz8wwKBgQC/wZEVW6qcgVngbFP8mRFD5k3JHIRCUWIl\n0oSYjNfCSEXOyQYAn2nWP2iX71O2LexNY/jtXKMeUb07KHO0DY+GbbTxIcBPdP0U\nsrg0k0yJ/NEIDaIgR3Tsc3mMpBIjOz5gf1agJAaEkN0MAOSyPx/kRFaFFeH6Anxz\nkwbF1g/2aQKBgQDPOLp3EoLV9xJnBROSEPzbDy+HuF6mByPCeExhXjgbSyvii2Bz\nVDN7CBDhnheq4sPYA2hpgZpLoVsO6WcWCPXlsgp8qvwEIBh/uOLuKgN0d1jxBFIS\n1ovdgFsiemF2kcn77yv+VwkbSNQCL9TivzlQitsyZU7VhesbkOxM9cSqtwKBgQCS\ncH+c4PlFNh9uHSxyjm65/OpQeL+kacRbgA6U9ZJcI5d0GN7FoPhRjvEQfsveJ+n7\nHCrhEkrb/65UvErIKUb/cU6qi4zy4TPZF7dHJ2pfQrxbuxWiMw/e9zzPJAlZ8Idt\naDG+cGSt3neBEDmWylBPocS1NAX0SOvD03drpFNiaQKBgQDo7LS5axpIOmUSfLDD\nTkZJ73HElJjrcITe7fzjUvHR9vkGz/bSNAElfc1lsZpPplf7ceWKs0FUfdvVEkC5\nGBfw7ioB0ezCkUhch4HJ2WaiojuksIoMN1eJ5STDfgyEB0ska8Py32h658xMx7b0\nfMtwnG6w0k9azfjVmzbsHeSAHA==\n-----END PRIVATE KEY-----\n');

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
(12027319, 'blue', 'fr', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"hello\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":true}}'),
(10230321, 'dark', 'fr', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"hello\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":true}}'),
(85512546, 'blue', 'fr', 1, 'private', '{\"notifications\":{\"email\":true,\"push\":true},\"privacy\":{\"post_visibility\":\"friends\"},\"other_preferences\":{\"autoplay_videos\":false,\"show_online_status\":true},\"additional_info\":{\"fullname\":\"John Doe\",\"bio_extended\":\"hello\",\"location\":\"New York\",\"social_links\":\"https:\\/\\/example.com\\/social\",\"occupation\":\"Web Developer\",\"interests\":\"Travel, Music\",\"languages_spoken\":\"English, French\",\"relationship_status\":\"single\",\"birthday\":\"1990-01-01\",\"privacy_settings\":true}}');

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
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `data`
--
ALTER TABLE `data`
  MODIFY `iddata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=954371626;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85512547;

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
