/*
 MySQL DDL Mapping
 TODO: Create schema.
 */

CREATE TABLE `user`
(
    `id`               int          NOT NULL AUTO_INCREMENT,
    `nombre`           varchar(255) NOT NULL,
    `nombre_segundo`   varchar(255) DEFAULT NULL,
    `apellido_primero` varchar(255) NOT NULL,
    `apellido_segundo` varchar(255) NOT NULL,
    `email`            varchar(255) NOT NULL UNIQUE,
    `password`         varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
) ENGINE = InnoDB

CREATE TABLE `rol`
(
    `id`   int          NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
) ENGINE = InnoDB

INSERT INTO `rol` (`name`) VALUES
('ADMIN'),
('USER'),
('GUEST'),
('IoT');

CREATE TABLE `user_rol`
(
    `id`   int NOT NULL AUTO_INCREMENT,
    `user` int NOT NULL,
    `rol`  int NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES `user` (`id`),
    FOREIGN KEY (`rol`) REFERENCES `rol` (`id`)
) ENGINE = InnoDB

CREATE TABLE `log`