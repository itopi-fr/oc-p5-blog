-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 13 avr. 2023 à 10:43
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
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table file
--

INSERT INTO file (id, title, url, ext, mime, size) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(140, 'kara_small.jpg', '/public/upload/user/kara_small_66b837.jpg', 'jpg', 'image/jpeg', 8111),
(141, 'test.jpg', '/public/upload/owner/test_432d1d.jpg', 'jpg', 'image/jpeg', 693),
(142, 'vide.pdf', '/public/upload/owner/vide_740e40.pdf', 'pdf', 'application/pdf', 27706),
(143, 'test.jpg', '/public/upload/user/test_c1f81b.jpg', 'jpg', 'image/jpeg', 693);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
