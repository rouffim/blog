drop schema IF EXISTS `blog`;
CREATE DATABASE  IF NOT EXISTS `blog` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `blog`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*SET FOREIGN_KEY_CHECKS=0;*/

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `excerpt` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` MEDIUMTEXT NOT NULL,
  `image_extension` varchar(32) DEFAULT NULL,
  `nb_views` int(11) NOT NULL DEFAULT '0',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_article_uuid` (`uuid`),
  KEY `article_user_idx` (`id_user`),
  FULLTEXT KEY `ft_article_title` (`title`),
  CONSTRAINT `fk_article_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- DROP TABLE IF EXISTS `tag`;
-- CREATE TABLE `tag` (
--   `id` bigint(20) NOT NULL AUTO_INCREMENT,
--   `uuid` varchar(37) NOT NULL,
--   `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
--   `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `uq_tag_uuid` (`uuid`),
--   UNIQUE KEY `uq_tag_title` (`title`),
--   FULLTEXT KEY `ft_tag_title` (`title`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- DROP TABLE IF EXISTS `article_has_tag`;
-- CREATE TABLE `article_has_tag` (
--   `id` bigint(20) NOT NULL AUTO_INCREMENT,
--   `uuid` varchar(37) NOT NULL,
--   `id_article` bigint(20) NOT NULL,
--   `id_tag` bigint(20) NOT NULL,
--   `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `uq_aht` (`id_article`, `id_tag`),
--   UNIQUE KEY `uq_aht_uuid` (`uuid`),
--   KEY `aht_article_idx` (`id_article`),
--   KEY `aht_tag_idx` (`id_tag`),
--   CONSTRAINT `fk_aht_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
--   CONSTRAINT `fk_aht_tag` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
-- ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `article_has_like`;
CREATE TABLE `article_has_like` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) NOT NULL,
  `id_article` bigint(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ahl` (`id_article`, `id_user`),
  UNIQUE KEY `uq_ahl_uuid` (`uuid`),
  KEY `ahl_article_idx` (`id_article`),
  KEY `ahl_user_idx` (`id_user`),
  CONSTRAINT `fk_ahl_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_ahl_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `article_has_comment`;
CREATE TABLE `article_has_comment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) NOT NULL,
  `content` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `id_article` bigint(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ahc_uuid` (`uuid`),
  KEY `ahc_article_idx` (`id_article`),
  KEY `ahc_user_idx` (`id_user`),
  CONSTRAINT `fk_ahc_article` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_ahc_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(37) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_extension` varchar(32) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_role` int(11) NOT NULL,
  `version` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_uuid` (`uuid`),
  UNIQUE KEY `uq_user_email` (`email`),
  KEY `user_role_idx` (`id_role`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `role_has_permission`;
CREATE TABLE `role_has_permission` (
  `id_role` int(11) NOT NULL,
  `id_permission` int(11) NOT NULL,
  PRIMARY KEY (`id_role`,`id_permission`),
  KEY `k_permission_has_role_role1_idx` (`id_role`),
  KEY `k_permission_has_role_permission1_idx` (`id_permission`),
  CONSTRAINT `fk_permission_has_role_permission1` FOREIGN KEY (`id_permission`) REFERENCES `permission` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_permission_has_role_role1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-14  9:54:17
