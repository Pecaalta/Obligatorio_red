SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `api` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `api`.`adjuntos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(45) NULL DEFAULT NULL,
  `idpublicacion` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_adjuntos_publicacion1_idx` (`idpublicacion` ASC),
  CONSTRAINT `fk_adjuntos_publicacion1`
    FOREIGN KEY (`idpublicacion`)
    REFERENCES `api`.`publicacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `api`.`categoria` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `api`.`favoritos` (
  `publicacion_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`publicacion_id`, `user_id`),
  INDEX `fk_publicacion_has_user_user1_idx` (`user_id` ASC),
  INDEX `fk_publicacion_has_user_publicacion_idx` (`publicacion_id` ASC),
  CONSTRAINT `fk_publicacion_has_user_publicacion`
    FOREIGN KEY (`publicacion_id`)
    REFERENCES `api`.`publicacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publicacion_has_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `api`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `api`.`publicacion` (
  `title` VARCHAR(30) NOT NULL,
  `tag` VARCHAR(45) NULL DEFAULT NULL,
  `introduction` VARCHAR(45) NULL DEFAULT NULL,
  `full` VARCHAR(45) NOT NULL,
  `creation` TIMESTAMP NULL DEFAULT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `state` TINYINT(4) NULL DEFAULT '0',
  `author` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_publicacion_user1_idx` (`author` ASC),
  CONSTRAINT `fk_publicacion_user1`
    FOREIGN KEY (`author`)
    REFERENCES `api`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `api`.`publicacion_categoria` (
  `publicacion_id` INT(11) NOT NULL,
  `categoria_id` INT(11) NOT NULL,
  PRIMARY KEY (`publicacion_id`, `categoria_id`),
  INDEX `fk_publicacion_has_categoria_categoria1_idx` (`categoria_id` ASC),
  INDEX `fk_publicacion_has_categoria_publicacion1_idx` (`publicacion_id` ASC),
  CONSTRAINT `fk_publicacion_has_categoria_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `api`.`categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_publicacion_has_categoria_publicacion1`
    FOREIGN KEY (`publicacion_id`)
    REFERENCES `api`.`publicacion` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `api`.`user` (
  `id` INT(11) NOT NULL,
  `avatar` VARCHAR(45) NULL DEFAULT NULL,
  `nick` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
