-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 09 mars 2023 à 13:59
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `file`
--

INSERT INTO `file` (`id`, `title`, `url`, `ext`, `mime`, `size`) VALUES
(1, 'default', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(11, 'test.jpg', '/public/upload/user/test_91abf52c0734836c.jpg', 'jpg', 'image/jpeg', 693),
(12, 'test.jpg', '/public/upload/user/test_e5d302314511681f.jpg', 'jpg', 'image/jpeg', 693),
(13, 'test.jpg', '/public/upload/user/test_c24a5ca9f81c7d02.jpg', 'jpg', 'image/jpeg', 693),
(14, 'test.jpg', '/public/upload/user/test_9f549f3d3daf492b.jpg', 'jpg', 'image/jpeg', 693),
(15, 'test.jpg', '/public/upload/user/test_7d833142690c2e71.jpg', 'jpg', 'image/jpeg', 693),
(16, 'Specimen.pdf', '/public/upload/user/Specimen_a6393bd4bb836998.pdf', 'pdf', 'application/pdf', 40766),
(17, 'test.jpg', '/public/upload/user/test_d0ca314a25aa5328.jpg', 'jpg', 'image/jpeg', 693),
(18, 'Specimen.pdf', '/public/upload/user/Specimen_945fd742fc3c617f.pdf', 'pdf', 'application/pdf', 40766),
(19, 'test.jpg', '/public/upload/user/test_f454ee5791e81440.jpg', 'jpg', 'image/jpeg', 693),
(20, 'test.jpg', '/public/upload/user/test_4daeebf4802b4e62.jpg', 'jpg', 'image/jpeg', 693),
(21, 'test.jpg', '/public/upload/user/test_126205aa.jpg', 'jpg', 'image/jpeg', 693),
(22, 'test.jpg', '/public/upload/user/test_5417.jpg', 'jpg', 'image/jpeg', 693),
(23, 'test.jpg', '/public/upload/user/test_bd566e.jpg', 'jpg', 'image/jpeg', 693),
(24, 'test.jpg', '/public/upload/user/test_2d8469.jpg', 'jpg', 'image/jpeg', 693),
(25, 'test.jpg', '/public/upload/user/test_b19283.jpg', 'jpg', 'image/jpeg', 693),
(26, 'test.jpg', '/public/upload/user/test_80e151.jpg', 'jpg', 'image/jpeg', 693),
(27, 'test.jpg', '/public/upload/user/test_e38d8e.jpg', 'jpg', 'image/jpeg', 693),
(28, 'test.jpg', '/public/upload/user/test_26f17b.jpg', 'jpg', 'image/jpeg', 693),
(29, 'test.jpg', '/public/upload/user/test_f50a16.jpg', 'jpg', 'image/jpeg', 693),
(30, 'test.jpg', '/public/upload/user/test_04975f.jpg', 'jpg', 'image/jpeg', 693),
(31, 'test.jpg', '/public/upload/user/test_cf310a.jpg', 'jpg', 'image/jpeg', 693);

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
  `creation_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  `status` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `author_id` (`author_id`),
  UNIQUE KEY `feat_img_id` (`feat_img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `token`
--

INSERT INTO `token` (`id`, `user_id`, `content`, `expiration_date`, `type`) VALUES
(35, 1, '0129acde8b8d13287190810dc4b3d1de8f2ec7bfea10ab3fb92dc1de06f54628', '2023-03-08 11:36:56', 'password_change');

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
  UNIQUE KEY `file_id` (`avatar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `avatar_id`, `pseudo`, `pass`, `email`, `role`) VALUES
(1, 1, 'pseudowner', 'owner', 'owner@test.fr', 'owner');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_owner_infos`
--

INSERT INTO `user_owner_infos` (`owner_id`, `user_id`, `photo_file_id`, `cv_file_id`, `first_name`, `last_name`, `catch_phrase`) VALUES
(1, 1, 1, 1, 'Maxime', 'Owner', 'Hello world');

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
