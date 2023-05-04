-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 mai 2023 à 13:46
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
-- Base de données : p5blog
--

-- --------------------------------------------------------

--
-- Structure de la table comment
--

DROP TABLE IF EXISTS comment;
CREATE TABLE IF NOT EXISTS `comment` (
  com_id int NOT NULL AUTO_INCREMENT,
  post_id int NOT NULL,
  author_id int NOT NULL,
  content varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  created_date datetime NOT NULL,
  last_update datetime DEFAULT NULL,
  status varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (com_id),
  KEY post_id (post_id) USING BTREE,
  KEY user_id (author_id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table file
--

DROP TABLE IF EXISTS file;
CREATE TABLE IF NOT EXISTS `file` (
  file_id int NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  url varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  ext varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  mime varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  size int NOT NULL,
  PRIMARY KEY (file_id)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table file
--

INSERT INTO file (file_id, title, url, ext, mime, size) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(194, 'vide.pdf', '/public/upload/owner/vide_bf1c41.pdf', 'pdf', 'application/pdf', 27706),
(213, 'avatar-default.jpeg', '/public/upload/owner/avatar-default_1296ae.jpeg', 'jpeg', 'image/jpeg', 44797),
(214, 'avatar-default.jpeg', '/public/upload/user/avatar-default_7a793f.jpeg', 'jpeg', 'image/jpeg', 44797);

-- --------------------------------------------------------

--
-- Structure de la table post
--

DROP TABLE IF EXISTS post;
CREATE TABLE IF NOT EXISTS post (
  post_id int NOT NULL AUTO_INCREMENT,
  author_id int NOT NULL,
  feat_img_id int DEFAULT NULL,
  title varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  slug varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  excerpt varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  content varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  creation_date datetime NOT NULL,
  last_update datetime DEFAULT NULL,
  status varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (post_id),
  KEY author_id (author_id) USING BTREE,
  KEY feat_img_id (feat_img_id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table social_network
--

DROP TABLE IF EXISTS social_network;
CREATE TABLE IF NOT EXISTS social_network (
  id int NOT NULL AUTO_INCREMENT,
  title varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  img_url varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  link_url varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  sn_order int NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY order_idx (sn_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table token
--

DROP TABLE IF EXISTS token;
CREATE TABLE IF NOT EXISTS token (
  token_id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  content varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  expiration_date datetime NOT NULL,
  type varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (token_id),
  KEY fk_token_user1_idx (user_id)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table token
--

INSERT INTO token (token_id, user_id, content, expiration_date, type) VALUES
(155, 119, 'ff3503c6a97104854a59b8a264cf1763249491d9f596417474e84baaa980246c', '2023-04-26 11:31:35', 'user-validation'),
(156, 118, '028cf0e14d38b69c4b450d5d54633f2ddb4e1f49c6f2f4c52d2e71abf939596a', '2023-05-03 16:36:47', 'reset-pass');

-- --------------------------------------------------------

--
-- Structure de la table user
--

DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS `user` (
  user_id int NOT NULL AUTO_INCREMENT,
  avatar_id int DEFAULT NULL,
  pseudo varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  pass varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  role varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (user_id),
  KEY file_id (avatar_id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table user
--

INSERT INTO user (user_id, avatar_id, pseudo, pass, email, role) VALUES
(94, 214, 'ownerrrr', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'md@itopi.fr', 'owner'),
(118, NULL, 'newuser2', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'pub@itopi.fr', 'user'),
(119, NULL, 'newuser3', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'pub2@itopi.fr', 'user-banned');

-- --------------------------------------------------------

--
-- Structure de la table user_owner_infos
--

DROP TABLE IF EXISTS user_owner_infos;
CREATE TABLE IF NOT EXISTS user_owner_infos (
  owner_id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  photo_file_id int DEFAULT NULL,
  cv_file_id int DEFAULT NULL,
  first_name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  last_name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  catch_phrase varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  sn_github varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  sn_linkedin varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (owner_id),
  UNIQUE KEY user_id (user_id) USING BTREE,
  KEY user_owner_infos_ibfk_2 (photo_file_id),
  KEY user_owner_infos_ibfk_3 (cv_file_id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table user_owner_infos
--

INSERT INTO user_owner_infos (owner_id, user_id, photo_file_id, cv_file_id, first_name, last_name, catch_phrase, sn_github, sn_linkedin) VALUES
(3, 94, 213, 194, 'Maxime', 'Itopi', 'Développeur PHP / Symfony freelance', 'https://github.com/itopi-fr', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table post
--
ALTER TABLE post
  ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (feat_img_id) REFERENCES `file` (file_id) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table token
--
ALTER TABLE token
  ADD CONSTRAINT fk_token_user1 FOREIGN KEY (user_id) REFERENCES `user` (user_id);

--
-- Contraintes pour la table user
--
ALTER TABLE user
  ADD CONSTRAINT user_ibfk_2 FOREIGN KEY (avatar_id) REFERENCES `file` (file_id) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table user_owner_infos
--
ALTER TABLE user_owner_infos
  ADD CONSTRAINT user_owner_infos_ibfk_1 FOREIGN KEY (user_id) REFERENCES `user` (user_id) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT user_owner_infos_ibfk_2 FOREIGN KEY (photo_file_id) REFERENCES `file` (file_id) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT user_owner_infos_ibfk_3 FOREIGN KEY (cv_file_id) REFERENCES `file` (file_id) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
