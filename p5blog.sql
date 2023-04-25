-- Adminer 4.8.1 MySQL 8.0.31 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `p5blog`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `com_id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `author_id` int NOT NULL,
  `content` varchar(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  PRIMARY KEY (`com_id`),
  UNIQUE KEY `post_id` (`post_id`),
  UNIQUE KEY `user_id` (`author_id`),
  CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`),
  CONSTRAINT `fk_comment_user1` FOREIGN KEY (`author_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `file_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ext` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `size` int NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `file` (`file_id`, `title`, `url`, `ext`, `mime`, `size`) VALUES
(1,	'default-avatar',	'/public/assets/img/default-avatar.jpg',	'jpg',	'image/jpeg',	20),
(2,	'default-post',	'/public/assets/img/default-post.jpg',	'jpg',	'image/jpeg',	693),
(157,	'test.jpg',	'/public/upload/blog/post/test_97cac7.jpg',	'jpg',	'image/jpeg',	693),
(161,	'test.jpg',	'/public/upload/blog/post/test_a98bc6.jpg',	'jpg',	'image/jpeg',	693),
(172,	'kara_small.jpg',	'/public/upload/user/kara_small_4d30bc.jpg',	'jpg',	'image/jpeg',	8111),
(173,	'vide.pdf',	'/public/upload/owner/vide_bc36d2.pdf',	'pdf',	'application/pdf',	27706),
(174,	'test.jpg',	'/public/upload/owner/test_1981d3.jpg',	'jpg',	'image/jpeg',	693),
(177,	'test.jpg',	'/public/upload/user/test_9006c4.jpg',	'jpg',	'image/jpeg',	693),
(178,	'kara_small.jpg',	'/public/upload/owner/kara_small_f9498a.jpg',	'jpg',	'image/jpeg',	8111),
(179,	'Specimen.pdf',	'/public/upload/owner/Specimen_c34347.pdf',	'pdf',	'application/pdf',	40766),
(180,	'test.jpg',	'/public/upload/blog/post/test_157d4b.jpg',	'jpg',	'image/jpeg',	693),
(181,	'test.jpg',	'/public/upload/blog/post/test_a6b0b3.jpg',	'jpg',	'image/jpeg',	693),
(182,	'test.jpg',	'/public/upload/blog/post/test_bc21a5.jpg',	'jpg',	'image/jpeg',	693),
(183,	'kara_small.jpg',	'/public/upload/user/kara_small_dfcce8.jpg',	'jpg',	'image/jpeg',	8111),
(184,	'kara_small.jpg',	'/public/upload/blog/post/kara_small_d0826e.jpg',	'jpg',	'image/jpeg',	8111),
(185,	'kara_small.jpg',	'/public/upload/user/kara_small_1f9be5.jpg',	'jpg',	'image/jpeg',	8111),
(186,	'vide.pdf',	'/public/upload/owner/vide_6187f0.pdf',	'pdf',	'application/pdf',	27706),
(187,	'test.jpg',	'/public/upload/owner/test_da38f0.jpg',	'jpg',	'image/jpeg',	693),
(188,	'default-post.jpg',	'/public/upload/blog/post/default-post_3ebf8f.jpg',	'jpg',	'image/jpeg',	11571);

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `feat_img_id` int DEFAULT NULL,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `excerpt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_update` datetime DEFAULT NULL,
  `status` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `author_id` (`author_id`) USING BTREE,
  KEY `feat_img_id` (`feat_img_id`) USING BTREE,
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`feat_img_id`) REFERENCES `file` (`file_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `post` (`post_id`, `author_id`, `feat_img_id`, `title`, `slug`, `excerpt`, `content`, `creation_date`, `last_update`, `status`) VALUES
(23,	94,	2,	'Nam viverra commodo lacusd',	'nam-viverra-commodo-lacus',	'Nam viverra commodo lacus. Vestibulum ante ipsum',	'Nam viverra commodo lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris ac est sit amet arcu laoreet pellentesque. Integer sollicitudin, dui et tincidunt fermentum, velit purus lobortis metus, vitae tincidunt leo dui at enim. Vestibulum lacus lacus, hendrerit sed egestas vel, sodales vel nibh. Etiam condimentum finibus massa maximus dignissim. Etiam pharetra, mauris sed gravida fermentum, est lacus rutrum augue, eget fermentum tellus urna et ipsum. Donec iaculis, nisi sit amet semper sodales, turpis nisl blandit orci, vulputate cursus mauris diam ac lorem. Duis facilisis est eget fringilla maximus. Suspendisse sit amet libero auctor, pulvinar nulla id, porta nisi.\r\n\r\nFusce sollicitudin lorem a euismod ultrices. Quisque semper viverra sem, sit amet rutrum urna varius nec. Praesent ac turpis pretium, dictum lorem eu, sagittis ex. Donec rhoncus pellentesque metus eu vehicula. Maecenas sit amet est eu augue blandit pellentesque eget vitae lacus. Donec hendrerit nisl ut sapien pulvinar ultrices. Proin ex purus, gravida ut mauris nec, bibendum efficitur leo. Quisque placerat porta tortor quis dictum. Maecenas nec metus non felis maximus ultrices in vel risus. Nunc ultricies ut ex ut ultrices. Nunc quis tortor vitae ex cursus ornare sed ac tortor.\r\n\r\n',	'2023-04-06 10:39:39',	'2023-04-21 14:40:33',	'pub'),
(25,	94,	2,	'Maecenas feugiat interdum quam',	'maecenas-feugiat-interdum-quam',	'Maecenas feugiat interdum quam, vel dictum mauris elementum at.',	'Maecenas feugiat interdum quam, vel dictum mauris elementum at. Sed ut elementum sem. Mauris in semper tortor. Mauris ante tortor, tincidunt eget rhoncus et, venenatis eget nibh. Aliquam fermentum aliquet magna, a blandit tellus dignissim egestas. Morbi fermentum ornare blandit. Cras gravida lacinia semper. Sed venenatis ipsum ac quam suscipit pulvinar. Praesent blandit, augue et condimentum fringilla, magna nisi sagittis tellus, in posuere ipsum purus vel sem. Proin molestie ligula ex, quis porta ligula porttitor id. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque vehicula quam vel dapibus mollis. Sed luctus sed odio sed pharetra. Suspendisse finibus dui vitae sapien consequat, nec ultrices magna convallis. Vestibulum convallis nulla sed sapien vehicula, vitae euismod nibh aliquam.\r\n\r\nFusce sollicitudin lorem a euismod ultrices. Quisque semper viverra sem, sit amet rutrum urna varius nec. Praesent ac turpis pretium, dictum lorem eu, sagittis ex. Donec rhoncus pellentesque metus eu vehicula. Maecenas sit amet est eu augue blandit pellentesque eget vitae lacus. Donec hendrerit nisl ut sapien pulvinar ultrices. Proin ex purus, gravida ut mauris nec, bibendum efficitur leo. Quisque placerat porta tortor quis dictum. Maecenas nec metus non felis maximus ultrices in vel risus. Nunc ultricies ut ex ut ultrices. Nunc quis tortor vitae ex cursus ornare sed ac tortor.\r\n\r\n\r\n\r\n',	'2023-03-02 10:39:39',	'2023-04-24 12:10:09',	'pub'),
(26,	94,	157,	'Sed ut perspiciatis unde omnis iste natus error sit voluptatem',	'sed-ut-perspiciatis-unde-omnis-iste-natus',	'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolor...',	'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',	'2023-04-20 12:51:50',	'2023-04-20 16:07:57',	'draft'),
(29,	94,	161,	'Titre de l\'article',	'slug-article',	'test...',	'test',	'2023-04-20 13:37:51',	'2023-04-20 16:07:46',	'arch'),
(41,	94,	188,	'Nouveau',	'nouveau',	'Nouvel article...',	'Nouvel article',	'2023-04-25 12:49:18',	'2023-04-25 12:49:18',	'arch');

DROP TABLE IF EXISTS `social_network`;
CREATE TABLE `social_network` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  `img_url` varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  `link_url` varchar(254) COLLATE utf8mb4_general_ci NOT NULL,
  `sn_order` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_idx` (`sn_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `token_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` varchar(10000) COLLATE utf8mb4_general_ci NOT NULL,
  `expiration_date` datetime NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`token_id`),
  KEY `fk_token_user1_idx` (`user_id`),
  CONSTRAINT `fk_token_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `avatar_id` int DEFAULT NULL,
  `pseudo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `file_id` (`avatar_id`) USING BTREE,
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `file` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` (`user_id`, `avatar_id`, `pseudo`, `pass`, `email`, `role`) VALUES
(94,	185,	'owner',	'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca',	'md@itopi.fr',	'owner'),
(116,	NULL,	'newuser',	'dd87c23f8d5c9e464d4d4183e9522f614093e9eff6675c22e5bc150c4b82bfca',	'pub@itopi.fr',	'user');

DROP TABLE IF EXISTS `user_owner_infos`;
CREATE TABLE `user_owner_infos` (
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
  KEY `user_owner_infos_ibfk_3` (`cv_file_id`),
  CONSTRAINT `user_owner_infos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `user_owner_infos_ibfk_2` FOREIGN KEY (`photo_file_id`) REFERENCES `file` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_owner_infos_ibfk_3` FOREIGN KEY (`cv_file_id`) REFERENCES `file` (`file_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_owner_infos` (`owner_id`, `user_id`, `photo_file_id`, `cv_file_id`, `first_name`, `last_name`, `catch_phrase`) VALUES
(3,	94,	187,	186,	'Maximee',	'Hello Hello',	'Hello Catch Phrase yop');

-- 2023-04-25 12:52:53
