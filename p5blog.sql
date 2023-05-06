-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 06 mai 2023 à 10:05
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table comment
--

INSERT INTO comment (com_id, post_id, author_id, content, created_date, last_update, status) VALUES
(50, 90, 123, 'Super article', '2023-05-06 09:53:54', NULL, 'valid'),
(51, 90, 123, 'Mais un peu long', '2023-05-06 09:54:13', NULL, 'wait');

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
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table file
--

INSERT INTO file (file_id, title, url, ext, mime, size) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(274, 'default-post-03.jpeg', '/public/upload/blog/post/default-post-03_8dfc49.jpeg', 'jpeg', 'image/jpeg', 43931),
(275, 'vide.pdf', '/public/upload/owner/vide_375222.pdf', 'pdf', 'application/pdf', 27706),
(277, 'avatar-default-1.jpeg', '/public/upload/owner/avatar-default-1_19c2f1.jpeg', 'jpeg', 'image/jpeg', 44797),
(278, 'avatar-default-1.jpeg', '/public/upload/user/avatar-default-1_01cd1e.jpeg', 'jpeg', 'image/jpeg', 44797),
(281, 'default-post-01.jpeg', '/public/upload/blog/post/default-post-01_c205e0.jpeg', 'jpeg', 'image/jpeg', 43857),
(282, 'default-post-05.jpeg', '/public/upload/blog/post/default-post-05_0ee4d6.jpeg', 'jpeg', 'image/jpeg', 50620),
(283, 'default-post-07.jpeg', '/public/upload/blog/post/default-post-07_ec4cdb.jpeg', 'jpeg', 'image/jpeg', 117247),
(284, 'default-post-01.jpeg', '/public/upload/blog/post/default-post-01_c4fc3f.jpeg', 'jpeg', 'image/jpeg', 43857),
(285, 'default-post-02.jpeg', '/public/upload/blog/post/default-post-02_4ae1c5.jpeg', 'jpeg', 'image/jpeg', 47307),
(286, 'avatar-default2.jpeg', '/public/upload/user/avatar-default2_f16413.jpeg', 'jpeg', 'image/jpeg', 43402);

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
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table post
--

INSERT INTO post (post_id, author_id, feat_img_id, title, slug, excerpt, content, creation_date, last_update, status) VALUES
(89, 94, 274, 'Lorem ipsum dolor sit amet', 'lorem-ipsum-dolor-sit-amet', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros felis, at efficitur nisi euismod a. Sed molestie neque lorem, quis tincidunt purus congue a. Integer feugiat vel elit non eleifend. Vestibulum ut ultricies elit. Nullam fermentum, quam et venenatis sagittis, orci tortor volutpat nisl, vulputate pretium elit odio a dui. Vivamus scelerisque, metus a pellentesque suscipit, metus lacus elementum leo, a vulputate lorem sem et lectus. Ut vitae elit quis justo iaculis fringilla. Mauris fermentum fermentum porta. Morbi euismod congue neque, eu efficitur orci varius sit amet. Donec pulvinar vel erat quis faucibus. Quisque nec luctus libero.', '2023-05-05 12:49:47', '2023-05-06 08:20:34', 'pub'),
(90, 94, 281, 'Quisque sit amet lectus', 'quisque-sit-amet-lectus', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Quisque sit amet lectus sit amet nulla placerat tempus in a lorem. Nunc euismod quam eu libero finibus, nec eleifend odio blandit. Sed euismod eros sit amet diam ultrices, sed semper tellus mattis. Phasellus ornare libero a erat interdum tempus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam congue magna vitae pretium semper. Cras pharetra tortor enim, non fermentum urna imperdiet vel. Vivamus ullamcorper lorem vel ligula semper pellentesque. Phasellus sagittis gravida libero, ut pulvinar lectus dapibus eget. Proin a lorem quis ex mollis condimentum bibendum sit amet libero.\r\n\r\nNullam placerat diam justo, eget hendrerit ante feugiat ornare. Nullam vel ligula sapien. Ut sodales fermentum lorem. Donec non sagittis nulla. Mauris nec placerat ex. Donec mi felis, euismod eget leo a, tempor malesuada diam. Quisque ornare ex vel molestie finibus. Aenean aliquam risus ac feugiat lacinia. Phasellus eros felis, placerat ac pretium eget, aliquam nec urna. Morbi a mauris eget erat scelerisque consequat.\r\n\r\n', '2023-05-06 09:16:11', '2023-05-06 10:03:29', 'pub'),
(91, 94, 282, 'Pellentesque viverra placerat', 'Pellentesque-viverra-placerat', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Pellentesque viverra placerat nisl eu porta. Nam malesuada nec lacus et molestie. Donec aliquam ultricies molestie. Etiam quis ante id turpis ultrices porttitor sed id erat. Nunc non pulvinar ante. Mauris rutrum dolor nec nisl pharetra interdum. Phasellus efficitur a nunc at consectetur. Nullam sollicitudin, risus eget molestie posuere, ligula neque aliquet risus, a venenatis massa sapien et ante. Nam pellentesque nulla et maximus molestie. Praesent neque metus, ultricies id porttitor nec, viverra et magna. Nullam ut volutpat quam. Phasellus eget tortor laoreet, efficitur nisl quis, dapibus turpis.\r\n\r\nNulla facilisi. Cras justo dolor, pulvinar ac ante sit amet, aliquet euismod enim. Maecenas ultrices congue nulla scelerisque suscipit. Donec varius nisi ac nulla pharetra gravida. Etiam fringilla convallis nisl et eleifend. Sed in sagittis odio, sed semper nisi. Aliquam luctus tortor vitae tellus mattis egestas. Donec sodales, quam eget commodo sodales, augue felis bibendum magna, sed feugiat est velit porttitor felis. Nam in orci nec erat aliquet efficitur. Cras vitae iaculis leo, vel dignissim odio.\r\n\r\nDonec ut enim eros. Praesent sit amet mattis tellus. Phasellus sed justo ipsum. Fusce neque mauris, hendrerit at ullamcorper eget, eleifend vitae leo. Nunc lacinia mi mauris, ac auctor nulla volutpat eu. Suspendisse bibendum pellentesque eros, pharetra gravida felis scelerisque ut. Nunc feugiat eleifend massa, non sagittis massa scelerisque non. Nullam vel orci sollicitudin, fermentum ligula a, auctor ipsum. Phasellus id feugiat velit. Vivamus urna nibh, tempus ut blandit in, varius at ante. Integer sit amet metus volutpat, egestas mi sit amet, maximus dolor.\r\n\r\n', '2023-05-06 09:16:20', '2023-05-06 10:00:02', 'pub'),
(92, 94, 283, 'Sed augue mi', 'sed-augue-mi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Sed augue mi, cursus vel nisi non, elementum suscipit nibh. Morbi sodales consectetur dui, eget vulputate dolor consectetur nec. In ornare odio purus, in bibendum velit suscipit in. Nullam lectus mi, lacinia sed sapien nec, cursus euismod sem. Maecenas hendrerit dictum aliquet. Praesent a faucibus mi, eget pharetra dolor. Aliquam lobortis dolor dui, a molestie turpis sagittis id. Sed in feugiat ante. Morbi sed purus ex. Cras id eros urna. Etiam tempor bibendum sollicitudin.\r\n\r\nNullam ullamcorper risus non mattis faucibus. Integer dictum, nunc consectetur volutpat aliquet, magna massa pharetra ligula, nec mattis purus purus in metus. Duis luctus justo dui, interdum porta velit dictum ac. Ut at consectetur libero, sit amet pellentesque libero. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Aenean varius sollicitudin malesuada. Donec in porttitor sem.\r\n\r\n', '2023-05-06 09:16:30', '2023-05-06 10:00:52', 'pub'),
(93, 94, 284, 'Phasellus orci lectus', 'phasellus-orci-lectus', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Phasellus orci lectus, volutpat eget consequat fermentum, hendrerit non lorem. Pellentesque venenatis enim et consectetur ultrices. Vivamus faucibus at orci volutpat mattis. Morbi a sapien at felis tempus lacinia a et turpis. Duis ut justo eget neque dictum condimentum. Mauris vestibulum sem velit, quis condimentum dolor placerat et. Vestibulum molestie at metus eu fermentum. Duis tincidunt justo vel metus scelerisque, nec tristique nulla dignissim. Nunc ut aliquam arcu, a pulvinar ex. Aenean vulputate turpis nec libero blandit hendrerit. Nullam felis nibh, fringilla ac maximus a, blandit sed arcu. Curabitur interdum orci eu orci egestas mattis. Fusce pretium, risus id lacinia accumsan, sem ligula placerat nisi, in placerat augue ligula non libero. Curabitur egestas maximus neque, non consequat eros luctus vitae. Donec vel fermentum dolor.\r\n\r\nMauris nec venenatis dui, eu aliquam ligula. Phasellus quis dapibus nulla. Donec id tortor nunc. Duis commodo mollis maximus. Sed quis justo sit amet ligula ultricies rhoncus id in lectus. Sed sagittis, erat et eleifend tempus, tortor ex aliquam ante, ac fringilla metus quam non neque. Pellentesque porta, erat ut condimentum volutpat, nunc massa porta tellus, eu mattis nunc eros non ipsum. Donec consectetur at dolor sed aliquet. In nec lectus eu tortor molestie aliquet nec ut erat. Praesent in magna egestas tortor interdum euismod id vitae mauris. Sed accumsan dolor id efficitur commodo. Nullam at ornare metus, eget lacinia enim.\r\n\r\n', '2023-05-06 09:16:42', '2023-05-06 10:01:34', 'pub'),
(94, 94, 285, 'Vestibulum ac imperdiet turpis', 'vestibulum-ac-imperdiet-turpis', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit eros fe...', 'Vestibulum ac imperdiet turpis. Fusce suscipit ligula mi, in consectetur tortor interdum at. Sed venenatis felis ac dignissim molestie. In non condimentum nunc. Nam eu mauris sed enim accumsan faucibus quis id magna. Aenean nisi turpis, porttitor ac condimentum et, ornare in arcu. Phasellus malesuada pellentesque nibh, volutpat porta ante gravida at. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas ultricies porta ligula vitae porta. Nullam non vestibulum dui. Maecenas ante mi, auctor vitae interdum ut, lobortis et ipsum. Nunc dictum tortor libero, quis tristique mauris maximus ut. Vivamus a tortor tortor.\r\n\r\nSuspendisse hendrerit viverra massa, sit amet pretium nibh. Mauris vehicula orci eu nibh imperdiet posuere. Pellentesque ac diam congue, sodales velit vel, lobortis sapien. Curabitur sit amet magna sit amet lorem tristique dapibus vitae in diam. Aliquam erat volutpat. Proin in tristique nulla, in tristique mauris. Proin tristique nisi in augue laoreet, sed feugiat purus sollicitudin. Etiam fermentum diam lorem, at porttitor risus finibus et. In a ultricies ante. Maecenas risus est, ultricies id diam ac, accumsan placerat mauris.\r\n\r\n', '2023-05-06 09:16:56', '2023-05-06 10:02:12', 'pub');

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
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table user
--

INSERT INTO user (user_id, avatar_id, pseudo, pass, email, role) VALUES
(94, 278, 'owner', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'md@itopi.fr', 'owner'),
(123, 286, 'newuser', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'pub@itopi.fr', 'user');

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
(3, 94, 277, 275, 'Maxime', 'Itopi', 'Développeur PHP / Symfony freelancee', 'https://github.com/itopi-fr', 'https://fr.linkedin.com');

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
