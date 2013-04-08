CREATE TABLE `user`
(
    `user_id`       	INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username`     		VARCHAR(255) DEFAULT NULL UNIQUE,
    `email`         	VARCHAR(255) DEFAULT NULL UNIQUE,
    `surname` 			varchar(255) COLLATE utf8_bin NOT NULL,
  	`firstname` 		varchar(255) COLLATE utf8_bin NOT NULL,
    `display_name`  	VARCHAR(50) DEFAULT NULL,
    `password`      	VARCHAR(128) NOT NULL,
    `phonenumber`	 	varchar(15) COLLATE utf8_bin DEFAULT NULL,
  	`id_twitter` 		varchar(255) COLLATE utf8_bin DEFAULT NULL,
  	`meteo_location` 	varchar(255) COLLATE utf8_bin DEFAULT NULL,
  	`idsession` 		varchar(255) COLLATE utf8_bin DEFAULT NULL,
  	`is_sysadmin` 		tinyint(1) NOT NULL DEFAULT '0',
    `state`         	SMALLINT UNSIGNED
) ENGINE=InnoDB CHARSET="utf8";
