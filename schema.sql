CREATE TABLE `pass` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(255),
  `hash_md5` CHAR(32),
  PRIMARY KEY (`id`),
  UNIQUE KEY `password` (`password`)
);
