/*
 MySQL DDL Mapping
 TODO: Create schema.
 */

CREATE DATABASE IF NOT EXISTS `eagle-fox` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `user`
(
    `id`               int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre`           varchar(255) NOT NULL,
    `nombre_segundo`   varchar(255)          DEFAULT NULL,
    `apellido_primero` varchar(255) NOT NULL,
    `apellido_segundo` varchar(255) NOT NULL,
    `email`            varchar(255) NOT NULL UNIQUE,
    `password`         varchar(255) NOT NULL,
    `rol`              int          NOT NULL DEFAULT 3 REFERENCES `rol` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `rol`
(
    `id`   int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) NOT NULL
) ENGINE = InnoDB;

INSERT IGNORE INTO `rol` (`name`)
VALUES ('ADMIN'),
       ('USER'),
       ('GUEST'),
       ('IoT');

CREATE TABLE IF NOT EXISTS `user_rol`
(
    `id`   int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` int NOT NULL REFERENCES `user` (`id`),
    `rol`  int NOT NULL REFERENCES `rol` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `log`
(
    `id`      int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user`    int          NOT NULL REFERENCES `user` (`id`),
    `message` varchar(255) NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_devices`
(
    `id`    int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid`  CHAR(36) NOT NULL UNIQUE,
    `owner` int      NOT NULL REFERENCES `user` (`id`),
    `token` CHAR(36) NOT NULL UNIQUE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_data`
(
    `id`      int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `device`  int      NOT NULL REFERENCES `iot_devices` (`id`),
    `date`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `latitud` double   NOT NULL,
    `longitud` double  NOT NULL
) ENGINE = ARCHIVE;