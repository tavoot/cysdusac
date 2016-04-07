-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema cysdusac
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cysdusac
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cysdusac` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `cysdusac` ;

-- -----------------------------------------------------
-- Table `cysdusac`.`centro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`centro` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`centro` (
  `id` INT NOT NULL COMMENT '',
  `tipo` VARCHAR(64) NULL COMMENT '',
  `nombre` VARCHAR(128) NULL COMMENT '',
  `siglas` VARCHAR(32) NULL COMMENT '',
  `pais` VARCHAR(64) NULL COMMENT '',
  `sitio_web` VARCHAR(128) NULL COMMENT '',
  `direccion` VARCHAR(256) NULL COMMENT '',
  `telefono` VARCHAR(16) NULL COMMENT '',
  `imagen` VARCHAR(64) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cysdusac`.`contacto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`contacto` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`contacto` (
  `id` INT NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NULL COMMENT '',
  `email` VARCHAR(45) NULL COMMENT '',
  `telefono` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cysdusac`.`centro_contacto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`centro_contacto` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`centro_contacto` (
  `centro_id` INT NOT NULL COMMENT '',
  `contacto_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`centro_id`, `contacto_id`)  COMMENT '',
  CONSTRAINT `fk_centro_has_contacto_centro`
    FOREIGN KEY (`centro_id`)
    REFERENCES `cysdusac`.`centro` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_centro_has_contacto_contacto1`
    FOREIGN KEY (`contacto_id`)
    REFERENCES `cysdusac`.`contacto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_centro_has_contacto_contacto1_idx` ON `cysdusac`.`centro_contacto` (`contacto_id` ASC)  COMMENT '';

CREATE INDEX `fk_centro_has_contacto_centro_idx` ON `cysdusac`.`centro_contacto` (`centro_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`canal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`canal` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`canal` (
  `id` INT NOT NULL COMMENT '',
  `tipo` VARCHAR(45) NULL COMMENT '',
  `titulo` VARCHAR(45) NULL COMMENT '',
  `descripcion` VARCHAR(45) NULL COMMENT '',
  `enlace` VARCHAR(45) NULL COMMENT '',
  `lenguaje` VARCHAR(45) NULL COMMENT '',
  `centro_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_canal_centro1`
    FOREIGN KEY (`centro_id`)
    REFERENCES `cysdusac`.`centro` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_canal_centro1_idx` ON `cysdusac`.`canal` (`centro_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`item` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`item` (
  `id` INT NOT NULL COMMENT '',
  `titulo` VARCHAR(45) NULL COMMENT '',
  `enlace` VARCHAR(45) NULL COMMENT '',
  `descripcion` VARCHAR(45) NULL COMMENT '',
  `fecha_publicacion` VARCHAR(45) NULL COMMENT '',
  `canal_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `canal_id`)  COMMENT '',
  CONSTRAINT `fk_item_canal1`
    FOREIGN KEY (`canal_id`)
    REFERENCES `cysdusac`.`canal` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_item_canal1_idx` ON `cysdusac`.`item` (`canal_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`usuario` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`usuario` (
  `id` INT NOT NULL COMMENT '',
  `tipo` VARCHAR(45) NULL COMMENT '',
  `usuario` VARCHAR(45) NULL COMMENT '',
  `password` VARCHAR(45) NULL COMMENT '',
  `email` VARCHAR(45) NULL COMMENT '',
  `pais` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cysdusac`.`version`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`version` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`version` (
  `id` INT NOT NULL COMMENT '',
  `fecha` VARCHAR(45) NULL COMMENT '',
  `version` VARCHAR(45) NULL COMMENT '',
  `centro_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_version_centro1`
    FOREIGN KEY (`centro_id`)
    REFERENCES `cysdusac`.`centro` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_version_centro1_idx` ON `cysdusac`.`version` (`centro_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`cambio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`cambio` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`cambio` (
  `id` INT NOT NULL COMMENT '',
  `tipo` VARCHAR(45) NULL COMMENT '',
  `version_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `version_id`)  COMMENT '',
  CONSTRAINT `fk_cambio_version1`
    FOREIGN KEY (`version_id`)
    REFERENCES `cysdusac`.`version` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_cambio_version1_idx` ON `cysdusac`.`cambio` (`version_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`usuario_centro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`usuario_centro` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`usuario_centro` (
  `usuario_id` INT NOT NULL COMMENT '',
  `centro_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`usuario_id`, `centro_id`)  COMMENT '',
  CONSTRAINT `fk_usuario_has_centro_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `cysdusac`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_centro_centro1`
    FOREIGN KEY (`centro_id`)
    REFERENCES `cysdusac`.`centro` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_usuario_has_centro_centro1_idx` ON `cysdusac`.`usuario_centro` (`centro_id` ASC)  COMMENT '';

CREATE INDEX `fk_usuario_has_centro_usuario1_idx` ON `cysdusac`.`usuario_centro` (`usuario_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`detalle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`detalle` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`detalle` (
  `id` INT NOT NULL COMMENT '',
  `mision` VARCHAR(45) NULL COMMENT '',
  `vision` VARCHAR(45) NULL COMMENT '',
  `descripcion` VARCHAR(45) NULL COMMENT '',
  `centro_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  CONSTRAINT `fk_detalle_centro1`
    FOREIGN KEY (`centro_id`)
    REFERENCES `cysdusac`.`centro` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_detalle_centro1_idx` ON `cysdusac`.`detalle` (`centro_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `cysdusac`.`catalogo_tipo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`catalogo_tipo` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`catalogo_tipo` (
  `id` INT NOT NULL COMMENT '',
  `nombre` VARCHAR(45) NULL COMMENT '',
  `descripcion` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cysdusac`.`catalogo_valor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cysdusac`.`catalogo_valor` ;

CREATE TABLE IF NOT EXISTS `cysdusac`.`catalogo_valor` (
  `id` INT NOT NULL COMMENT '',
  `valor` VARCHAR(45) NULL COMMENT '',
  `catalogo_tipo_id` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id`, `catalogo_tipo_id`)  COMMENT '',
  CONSTRAINT `fk_catalogo_valor_catalogo_tipo1`
    FOREIGN KEY (`catalogo_tipo_id`)
    REFERENCES `cysdusac`.`catalogo_tipo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_catalogo_valor_catalogo_tipo1_idx` ON `cysdusac`.`catalogo_valor` (`catalogo_tipo_id` ASC)  COMMENT '';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
