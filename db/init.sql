DROP DATABASE IF EXISTS `eagle-fox`;
CREATE DATABASE IF NOT EXISTS `eagle-fox` DEFAULT CHARACTER SET utf8 COLLATE utf8mb3_unicode_ci;
USE `eagle-fox`;

/**
    Todas las tablas tienen campos para creado y actualizado porque así lo requiere Leaf en el modelo
    para hacer un timestamp automático.
    https://leafphp.dev/docs/mvc/models.html#leaf-model-conventions
 */

CREATE TABLE IF NOT EXISTS `user`
(
    `id`               int                                   NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre`           varchar(255)                          NOT NULL,
    `nombre_segundo`   varchar(255)                                   DEFAULT NULL,
    `apellido_primero` varchar(255)                          NOT NULL,
    `apellido_segundo` varchar(255)                          NOT NULL,
    `email`            varchar(255)                          NOT NULL UNIQUE,
    `password`         varchar(255)                          NOT NULL,
    `rol`              enum ('ADMIN', 'USER', 'GUEST','IOT') NOT NULL DEFAULT 'GUEST',
    `created_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `log`
(
    `id`      int          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user`    int          NOT NULL,
    `message` varchar(255) NOT NULL COMMENT 'Principalmente para control de acceso (no necesariamente los de Apache)',
    `created` datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user`) REFERENCES `user` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_devices`
(
    `id`    int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `uuid`  CHAR(36) NOT NULL UNIQUE COMMENT '128 bits UUID (RFC 4122)',
    `token` CHAR(64) NOT NULL UNIQUE COMMENT '256 bits token',
    `created_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_uuid` (`uuid`) USING HASH COMMENT 'Sólo soporta igualdad'

) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_devices_user`
(
    `id`     int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user`   int      NOT NULL,
    `device` int      NOT NULL,
    `created_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user`) REFERENCES `user` (`id`),
    FOREIGN KEY (`device`) REFERENCES `iot_devices` (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `iot_data`
(
    `id`       int      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `device`   int      NOT NULL,
    `location` POINT    NOT NULL,
    `created_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    SPATIAL INDEX `idx_location` (`location`),
    FOREIGN KEY (`device`) REFERENCES `iot_devices` (`id`)
) ENGINE = InnoDB;