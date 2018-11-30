--
-- Estructura de tabla para la tabla `#__kwproducts_products`
--
DROP TABLE IF EXISTS `#__kwproducts_products`;
CREATE TABLE IF NOT EXISTS `#__kwproducts_products` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10)  NOT NULL DEFAULT '0',
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`publishdate` DATETIME NOT NULL ,
`product` VARCHAR(100)  NOT NULL ,
`alias` VARCHAR(255) COLLATE utf8_bin NOT NULL ,
`catid` TEXT NOT NULL ,
`introimage` VARCHAR(255)  NOT NULL ,
`media_gallery` tinyint(2) NOT NULL DEFAULT '0',
`images` TEXT NOT NULL ,
`video` VARCHAR(200)  NOT NULL ,
`description` TEXT NOT NULL ,
`metakey` TEXT NOT NULL ,
`metadesc` TEXT NOT NULL ,
`access` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`hits` INT(10) UNSIGNED NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
