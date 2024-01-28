/*
 MySQL DDL Mapping
 TODO: Create schema.
 */

CREATE DATABASE IF NOT EXISTS `eagle-fox` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `user`
(
    `id`               int          NOT NULL AUTO_INCREMENT,
    `nombre`           varchar(255) NOT NULL,
    `nombre_segundo`   varchar(255) DEFAULT NULL,
    `apellido_primero` varchar(255) NOT NULL,
    `apellido_segundo` varchar(255) NOT NULL,
    `email`            varchar(255) NOT NULL UNIQUE,
    `password`         varchar(255) NOT NULL,
    `rol`              int          NOT NULL DEFAULT 3 REFERENCES `rol` (`id`),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `rol`
(
    `id`   int          NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `rol` (`name`) VALUES
                               ('ADMIN'),
                               ('USER'),
                               ('GUEST'),
                               ('IoT') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

CREATE TABLE IF NOT EXISTS `user_rol`
(
    `id`   int NOT NULL AUTO_INCREMENT,
    `user` int NOT NULL,
    `rol`  int NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES `user` (`id`),
    FOREIGN KEY (`rol`) REFERENCES `rol` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `log`
(
    `id`      int          NOT NULL AUTO_INCREMENT,
    `user`    int          NOT NULL REFERENCES `user` (`id`),
    `message` varchar(255) NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;