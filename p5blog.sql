-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 27 avr. 2023 à 07:31
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table comment
--

INSERT INTO comment (com_id, post_id, author_id, content, created_date, last_update, status) VALUES
(5, 23, 118, 'mon commentaire', '2023-04-25 00:00:00', '2023-04-25 00:00:00', 'wait'),
(7, 23, 118, 'test', '2023-04-25 00:00:00', NULL, 'valid'),
(9, 26, 94, 'bla bla\r\n', '2023-04-26 16:03:32', NULL, 'valid'),
(10, 26, 94, 'test', '2023-04-26 16:21:42', NULL, 'wait'),
(11, 26, 94, 'test', '2023-04-26 16:40:38', NULL, 'valid'),
(12, 26, 94, 'test ', '2023-04-26 16:41:59', NULL, 'wait'),
(13, 26, 94, 'test test', '2023-04-26 16:42:13', NULL, 'valid'),
(14, 26, 94, 'test 2', '2023-04-26 16:43:06', NULL, 'valid'),
(15, 26, 94, 'test 2', '2023-04-26 16:43:30', NULL, 'valid'),
(16, 26, 94, 'test2', '2023-04-26 16:44:14', NULL, 'valid'),
(18, 25, 118, 'salut', '2023-04-27 06:46:06', NULL, 'valid');

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
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table file
--

INSERT INTO file (file_id, title, url, ext, mime, size) VALUES
(1, 'default-avatar', '/public/assets/img/default-avatar.jpg', 'jpg', 'image/jpeg', 20),
(2, 'default-post', '/public/assets/img/default-post.jpg', 'jpg', 'image/jpeg', 693),
(157, 'test.jpg', '/public/upload/blog/post/test_97cac7.jpg', 'jpg', 'image/jpeg', 693),
(161, 'test.jpg', '/public/upload/blog/post/test_a98bc6.jpg', 'jpg', 'image/jpeg', 693),
(187, 'test.jpg', '/public/upload/owner/test_da38f0.jpg', 'jpg', 'image/jpeg', 693),
(190, 'test.jpg', '/public/upload/user/test_289012.jpg', 'jpg', 'image/jpeg', 693),
(191, 'Specimen.pdf', '/public/upload/owner/Specimen_08d5b2.pdf', 'pdf', 'application/pdf', 40766),
(192, 'kara_small.jpg', '/public/upload/user/kara_small_11e346.jpg', 'jpg', 'image/jpeg', 8111),
(193, 'default-post.jpg', '/public/upload/blog/post/default-post_fd4a1c.jpg', 'jpg', 'image/jpeg', 11571);

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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table post
--

INSERT INTO post (post_id, author_id, feat_img_id, title, slug, excerpt, content, creation_date, last_update, status) VALUES
(23, 94, 2, 'Nam viverra commodo lacusd', 'nam-viverra-commodo-lacus', 'Nam viverra commodo lacus. Vestibulum ante ipsum', 'Nam viverra commodo lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris ac est sit amet arcu laoreet pellentesque. Integer sollicitudin, dui et tincidunt fermentum, velit purus lobortis metus, vitae tincidunt leo dui at enim. Vestibulum lacus lacus, hendrerit sed egestas vel, sodales vel nibh. Etiam condimentum finibus massa maximus dignissim. Etiam pharetra, mauris sed gravida fermentum, est lacus rutrum augue, eget fermentum tellus urna et ipsum. Donec iaculis, nisi sit amet semper sodales, turpis nisl blandit orci, vulputate cursus mauris diam ac lorem. Duis facilisis est eget fringilla maximus. Suspendisse sit amet libero auctor, pulvinar nulla id, porta nisi.\r\n\r\nFusce sollicitudin lorem a euismod ultrices. Quisque semper viverra sem, sit amet rutrum urna varius nec. Praesent ac turpis pretium, dictum lorem eu, sagittis ex. Donec rhoncus pellentesque metus eu vehicula. Maecenas sit amet est eu augue blandit pellentesque eget vitae lacus. Donec hendrerit nisl ut sapien pulvinar ultrices. Proin ex purus, gravida ut mauris nec, bibendum efficitur leo. Quisque placerat porta tortor quis dictum. Maecenas nec metus non felis maximus ultrices in vel risus. Nunc ultricies ut ex ut ultrices. Nunc quis tortor vitae ex cursus ornare sed ac tortor.\r\n\r\n', '2023-04-06 10:39:39', '2023-04-21 14:40:33', 'pub'),
(25, 94, 2, 'Maecenas feugiat interdum quam', 'maecenas-feugiat-interdum-quam', 'Maecenas feugiat interdum quam, vel dictum mauris elementum at.', 'Maecenas feugiat interdum quam, vel dictum mauris elementum at. Sed ut elementum sem. Mauris in semper tortor. Mauris ante tortor, tincidunt eget rhoncus et, venenatis eget nibh. Aliquam fermentum aliquet magna, a blandit tellus dignissim egestas. Morbi fermentum ornare blandit. Cras gravida lacinia semper. Sed venenatis ipsum ac quam suscipit pulvinar. Praesent blandit, augue et condimentum fringilla, magna nisi sagittis tellus, in posuere ipsum purus vel sem. Proin molestie ligula ex, quis porta ligula porttitor id. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque vehicula quam vel dapibus mollis. Sed luctus sed odio sed pharetra. Suspendisse finibus dui vitae sapien consequat, nec ultrices magna convallis. Vestibulum convallis nulla sed sapien vehicula, vitae euismod nibh aliquam.\r\n\r\nFusce sollicitudin lorem a euismod ultrices. Quisque semper viverra sem, sit amet rutrum urna varius nec. Praesent ac turpis pretium, dictum lorem eu, sagittis ex. Donec rhoncus pellentesque metus eu vehicula. Maecenas sit amet est eu augue blandit pellentesque eget vitae lacus. Donec hendrerit nisl ut sapien pulvinar ultrices. Proin ex purus, gravida ut mauris nec, bibendum efficitur leo. Quisque placerat porta tortor quis dictum. Maecenas nec metus non felis maximus ultrices in vel risus. Nunc ultricies ut ex ut ultrices. Nunc quis tortor vitae ex cursus ornare sed ac tortor.\r\n\r\n\r\n\r\n', '2023-03-02 10:39:39', '2023-04-25 13:53:42', 'pub'),
(26, 94, 157, 'Sed ut perspiciatis unde omnis', 'sed-ut-perspiciatis-unde-omnis-iste-natus', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolor...', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', '2023-04-20 12:51:50', '2023-04-26 22:44:40', 'pub'),
(29, 94, 161, 'Titre de l\'article', 'slug-article', 'test...', 'test', '2023-04-20 13:37:51', '2023-04-20 16:07:46', 'arch'),
(43, 94, 193, 'new art', 'new-art', 'new art...', 'new art', '2023-04-26 11:12:52', '2023-04-26 11:12:52', 'draft');

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
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table token
--

INSERT INTO token (token_id, user_id, content, expiration_date, type) VALUES
(155, 119, 'ff3503c6a97104854a59b8a264cf1763249491d9f596417474e84baaa980246c', '2023-04-26 11:31:35', 'user-validation');

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
(94, 192, 'ownerrrr', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'md@itopi.fr', 'owner'),
(118, NULL, 'newuser2', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'pub@itopi.fr', 'user'),
(119, NULL, 'newuser3', 'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca', 'pub2@itopi.fr', 'user-validation');

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
(3, 94, 187, 191, 'Maximee', 'Hello Hello', 'Hello Catch Phrase yop');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table post
--
ALTER TABLE post
  ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (feat_img_id) REFERENCES `file` (file_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT post_ibfk_2 FOREIGN KEY (author_id) REFERENCES `user` (user_id) ON DELETE CASCADE ON UPDATE CASCADE;

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
