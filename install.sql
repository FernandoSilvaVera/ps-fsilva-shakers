CREATE TABLE IF NOT EXISTS `ps_customer_loyalty` (
    `id_loyalty` INT(11) NOT NULL AUTO_INCREMENT,
    `id_customer` INT(11) NOT NULL,
    `points` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_loyalty`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
