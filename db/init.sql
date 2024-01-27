/*
 MySQL DDL Mapping
 TODO: Create schema.
 */

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

CREATE TABLE `user`
(
    `id`               int          NOT NULL AUTO_INCREMENT,
    `nombre`           varchar(255) NOT NULL,
    `nombre_segundo`   varchar(255)          DEFAULT NULL,
    `apellido_primero` varchar(255) NOT NULL,
    `apellido_segundo` varchar(255) NOT NULL,
    `email`            varchar(255) NOT NULL UNIQUE,
    `password`         varchar(255) NOT NULL,
    `rol`              int          NOT NULL DEFAULT 3,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`rol`) REFERENCES `rol` (`id`)
) ENGINE = InnoDB

CREATE TABLE `log`
(
    `id`     int           NOT NULL AUTO_INCREMENT,
    `user`   int           NOT NULL,
    `date`   datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip`     varchar(255)  NOT NULL, /* IPv4 or IPv6 support? we could use a char but premature optimization... TODO */
    `action` varchar(1024) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE = InnoDB