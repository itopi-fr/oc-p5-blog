-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 06 avr. 2023 à 16:27
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
  id int NOT NULL AUTO_INCREMENT,
  post_id int NOT NULL,
  author_id int NOT NULL,
  content varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  created_date date NOT NULL,
  last_update date DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY post_id (post_id),
  UNIQUE KEY user_id (author_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table file
--

DROP TABLE IF EXISTS file;
CREATE TABLE IF NOT EXISTS `file` (
  id int NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  url varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  ext varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  mime varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  size int NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table file
--

INSERT INTO file (id, title, url, ext, mime, size) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(130, 'kara_small.jpg', '/public/upload/user/kara_small_c3ffb8.jpg', 'jpg', 'image/jpeg', 8111),
(138, 'kara_small.jpg', '/public/upload/owner/kara_small_1748c8.jpg', 'jpg', 'image/jpeg', 8111),
(139, 'Specimen.pdf', '/public/upload/owner/Specimen_16a86d.pdf', 'pdf', 'application/pdf', 40766);

-- --------------------------------------------------------

--
-- Structure de la table post
--

DROP TABLE IF EXISTS post;
CREATE TABLE IF NOT EXISTS post (
  id int NOT NULL AUTO_INCREMENT,
  author_id int NOT NULL,
  feat_img_id int DEFAULT NULL,
  title varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  slug varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  excerpt varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  content varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  creation_date datetime NOT NULL,
  last_update datetime DEFAULT NULL,
  status varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (id),
  KEY author_id (author_id) USING BTREE,
  KEY feat_img_id (feat_img_id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  content varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  expiration_date datetime NOT NULL,
  type varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (id),
  KEY fk_token_user1_idx (user_id)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table token
--

INSERT INTO token (id, user_id, content, expiration_date, type) VALUES
(112, 93, 'a6a8f9e3643cb4d3cfdba812851cd7b0ac59e0486f0fe0d4fc51ec3f9e9e0011', '2023-03-31 11:01:12', 'user-validation');

-- --------------------------------------------------------

--
-- Structure de la table user
--

DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS `user` (
  id int NOT NULL AUTO_INCREMENT,
  avatar_id int DEFAULT NULL,
  pseudo varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  pass varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  role varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (id),
  KEY file_id (avatar_id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table user
--

INSERT INTO user (id, avatar_id, pseudo, pass, email, role) VALUES
(93, NULL, 'newuser', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1', 'pub@itopi.fr', 'user-validation'),
(94, 130, 'ownerree', 'a8c23cc814179578e3a774418ac5fc4702a66eb3b78c876df81b290465e6e334', 'md@itopi.fr', 'owner');

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
  PRIMARY KEY (owner_id),
  UNIQUE KEY user_id (user_id) USING BTREE,
  KEY user_owner_infos_ibfk_2 (photo_file_id),
  KEY user_owner_infos_ibfk_3 (cv_file_id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table user_owner_infos
--

INSERT INTO user_owner_infos (owner_id, user_id, photo_file_id, cv_file_id, first_name, last_name, catch_phrase) VALUES
(3, 94, 138, 139, 'Maximeeee', 'Hello Hello', 'Hello Catch ');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table comment
--
ALTER TABLE comment
  ADD CONSTRAINT fk_comment_post1 FOREIGN KEY (post_id) REFERENCES post (id),
  ADD CONSTRAINT fk_comment_user1 FOREIGN KEY (author_id) REFERENCES `user` (id);

--
-- Contraintes pour la table post
--
ALTER TABLE post
  ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (feat_img_id) REFERENCES `file` (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT post_ibfk_2 FOREIGN KEY (author_id) REFERENCES `user` (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table token
--
ALTER TABLE token
  ADD CONSTRAINT fk_token_user1 FOREIGN KEY (user_id) REFERENCES `user` (id);

--
-- Contraintes pour la table user
--
ALTER TABLE user
  ADD CONSTRAINT user_ibfk_2 FOREIGN KEY (avatar_id) REFERENCES `file` (id) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table user_owner_infos
--
ALTER TABLE user_owner_infos
  ADD CONSTRAINT user_owner_infos_ibfk_1 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT user_owner_infos_ibfk_2 FOREIGN KEY (photo_file_id) REFERENCES `file` (id) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT user_owner_infos_ibfk_3 FOREIGN KEY (cv_file_id) REFERENCES `file` (id) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
