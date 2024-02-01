DROP DATABASE IF EXISTS `eagle-fox`;
CREATE DATABASE IF NOT EXISTS `eagle-fox` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `eagle-fox`;

CREATE TABLE IF NOT EXISTS `rol`
(
    `id`   int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `user`
(
    `id`               int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre`           varchar(255) NOT NULL,
    `nombre_segundo`   varchar(255)          DEFAULT NULL,
    `apellido_primero` varchar(255) NOT NULL,
    `apellido_segundo` varchar(255) NOT NULL,
    `email`            varchar(255) NOT NULL UNIQUE,
    `password`         varchar(255) NOT NULL,
    `rol`              int          NOT NULL DEFAULT 3,
    FOREIGN KEY (`rol`) REFERENCES `rol` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `user_rol`
(
    `id`   int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` int NOT NULL,
    `rol`  int NOT NULL,
    FOREIGN KEY (`user`) REFERENCES `user` (`id`),
    FOREIGN KEY (`rol`) REFERENCES `rol` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `log`
(
    `id`      int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user`    int          NOT NULL,
    `message` varchar(255) NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_devices`
(
    `id`    int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid`  CHAR(36) NOT NULL UNIQUE,
    `token` CHAR(36) NOT NULL UNIQUE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_devices_user`
(
    `id`      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user`    int NOT NULL,
    `device`  int NOT NULL,
    `date`    datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user`) REFERENCES `user` (`id`),
    FOREIGN KEY (`device`) REFERENCES `iot_devices` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_data`
(
    `id`      int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `device`  int      NOT NULL,
    `date`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `latitud` double   NOT NULL,
    `longitud` double  NOT NULL,
    FOREIGN KEY (`device`) REFERENCES `iot_devices` (`id`)
) ENGINE = InnoDB;

INSERT IGNORE INTO `rol` (`name`)
VALUES ('ADMIN'),
       ('USER'),
       ('GUEST'),
       ('IoT');