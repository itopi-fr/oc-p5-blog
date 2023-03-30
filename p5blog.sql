-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 mars 2023 à 13:40
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `p5blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `author_id` int NOT NULL,
  `content` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`),
  UNIQUE KEY `user_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `file`
--

DROP TABLE IF EXISTS `file`;
CREATE TABLE IF NOT EXISTS `file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ext` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `size` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `file`
--

INSERT INTO `file` (`id`, `title`, `url`, `ext`, `mime`, `size`) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(47, 'test.jpg', '/public/upload/owner/test_a5eab1.jpg', 'jpg', 'image/jpeg', 693),
(49, 'vide.pdf', '/public/upload/owner/vide_83d6b9.pdf', 'pdf', 'application/pdf', 27706),
(50, 'kara_small.jpg', '/public/upload/user/kara_small_463b99.jpg', 'jpg', 'image/jpeg', 8111),
(51, 'kara_small.jpg', '/public/upload/user/kara_small_9bcb67.jpg', 'jpg', 'image/jpeg', 8111),
(52, 'kara_small.jpg', '/public/upload/user/kara_small_0f7618.jpg', 'jpg', 'image/jpeg', 8111),
(53, 'kara_small.jpg', '/public/upload/user/kara_small_8f59b7.jpg', 'jpg', 'image/jpeg', 8111),
(54, 'kara_small.jpg', '/public/upload/user/kara_small_372b54.jpg', 'jpg', 'image/jpeg', 8111),
(55, 'kara_small.jpg', '/public/upload/user/kara_small_6eaa0c.jpg', 'jpg', 'image/jpeg', 8111),
(56, 'vide.pdf', '/public/upload/owner/vide_f2ac5b.pdf', 'pdf', 'application/pdf', 27706),
(57, 'vide.pdf', '/public/upload/owner/vide_87c531.pdf', 'pdf', 'application/pdf', 27706),
(58, 'vide.pdf', '/public/upload/owner/vide_09678d.pdf', 'pdf', 'application/pdf', 27706),
(59, 'vide.pdf', '/public/upload/owner/vide_992c9a.pdf', 'pdf', 'application/pdf', 27706),
(60, 'vide.pdf', '/public/upload/owner/vide_8be775.pdf', 'pdf', 'application/pdf', 27706),
(61, 'kara_small.jpg', '/public/upload/owner/kara_small_6fddd7.jpg', 'jpg', 'image/jpeg', 8111),
(62, 'test.jpg', '/public/upload/owner/test_93e7ee.jpg', 'jpg', 'image/jpeg', 693),
(63, 'test.jpg', '/public/upload/owner/test_a35bc6.jpg', 'jpg', 'image/jpeg', 693),
(64, 'test.jpg', '/public/upload/owner/test_738ca2.jpg', 'jpg', 'image/jpeg', 693),
(65, 'kara_small.jpg', '/public/upload/owner/kara_small_0d232b.jpg', 'jpg', 'image/jpeg', 8111),
(66, 'test.jpg', '/public/upload/user/test_e38824.jpg', 'jpg', 'image/jpeg', 693),
(67, 'Specimen.pdf', '/public/upload/owner/Specimen_a96bd6.pdf', 'pdf', 'application/pdf', 40766);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `feat_img_id` int DEFAULT NULL,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `excerpt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_update` datetime DEFAULT NULL,
  `status` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`) USING BTREE,
  KEY `feat_img_id` (`feat_img_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `author_id`, `feat_img_id`, `title`, `slug`, `excerpt`, `content`, `creation_date`, `last_update`, `status`) VALUES
(13, 89, 2, 'Premier post', 'premier-post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit\r\n', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-03-01 11:37:28', '2023-03-01 11:37:28', 'pub'),
(14, 89, 2, 'Un deuxième post', 'un-deuxieme-post', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-03-02 11:37:28', '2023-03-02 11:37:28', 'pub'),
(15, 89, 2, 'Troisième post', 'troisieme-post', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-03-03 11:39:43', '2023-03-03 11:39:43', 'pub'),
(16, 89, 2, 'Un post brouillon', 'un-post-brouillon', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-03-04 11:39:43', '2023-03-04 11:39:43', 'draft'),
(17, 89, 2, 'Un post archivé', 'un-post-archive', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2023-02-01 11:41:15', '2023-02-01 11:41:15', 'arch');

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration_date` datetime NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_token_user1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `avatar_id` int DEFAULT NULL,
  `pseudo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file_id` (`avatar_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `avatar_id`, `pseudo`, `pass`, `email`, `role`) VALUES
(89, 66, 'owner', 'a8c23cc814179578e3a774418ac5fc4702a66eb3b78c876df81b290465e6e334', 'md@itopi.fr', 'owner');

-- --------------------------------------------------------

--
-- Structure de la table `user_owner_infos`
--

DROP TABLE IF EXISTS `user_owner_infos`;
CREATE TABLE IF NOT EXISTS `user_owner_infos` (
  `owner_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `photo_file_id` int DEFAULT NULL,
  `cv_file_id` int DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `catch_phrase` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`owner_id`),
  UNIQUE KEY `user_id` (`user_id`) USING BTREE,
  KEY `user_owner_infos_ibfk_2` (`photo_file_id`),
  KEY `user_owner_infos_ibfk_3` (`cv_file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_owner_infos`
--

INSERT INTO `user_owner_infos` (`owner_id`, `user_id`, `photo_file_id`, `cv_file_id`, `first_name`, `last_name`, `catch_phrase`) VALUES
(2, 89, 65, 67, 'Maxime', 'Degueldre', 'test');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`feat_img_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_token_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_owner_infos`
--
ALTER TABLE `user_owner_infos`
  ADD CONSTRAINT `user_owner_infos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_owner_infos_ibfk_2` FOREIGN KEY (`photo_file_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_owner_infos_ibfk_3` FOREIGN KEY (`cv_file_id`) REFERENCES `file` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
