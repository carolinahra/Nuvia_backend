-- =============================================================
--  Base de datos: Nuvia
--  Proyecto:      Nuvia_backend (TFG - DAW 2º Curso)
-- =============================================================

CREATE DATABASE IF NOT EXISTS `Nuvia`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `Nuvia`;

SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Tabla: diet
-- Planes de dieta predefinidos que el usuario puede elegir.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `diet` (
    `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                 VARCHAR(255) NOT NULL,
    `description`          VARCHAR(255) DEFAULT NULL,
    `total_daily_calories` INT UNSIGNED DEFAULT NULL,
    `goal`                 VARCHAR(255) DEFAULT NULL        COMMENT 'perder | mantener | ganar',
    `diet_type`            VARCHAR(255) DEFAULT NULL        COMMENT 'vegano, vegetariano, etc.',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_diet_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: routine
-- Rutinas de entrenamiento predefinidas.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `routine` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`             VARCHAR(255) NOT NULL,
    `description`      VARCHAR(255) DEFAULT NULL,
    `duration_minutes` INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_routine_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: user
-- Datos básicos del usuario y preferencias por defecto.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
    `id`                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                VARCHAR(255) NOT NULL,
    `username`            VARCHAR(255) NOT NULL,
    `email`               VARCHAR(255) NOT NULL,
    `password`            VARCHAR(255) NOT NULL,
    `height_cm`           INT UNSIGNED DEFAULT NULL,
    `birthdate`           DATE         DEFAULT NULL,
    `sex`                 VARCHAR(255) DEFAULT NULL,
    `activity_level`      VARCHAR(255) DEFAULT NULL,
    `goal`                VARCHAR(255) DEFAULT NULL,
    `default_diet_id`     INT UNSIGNED DEFAULT NULL,
    `default_routine_id`  INT UNSIGNED DEFAULT NULL,
    `is_admin`            BOOLEAN      NOT NULL DEFAULT FALSE,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_user_username` (`username`),
    UNIQUE KEY `uq_user_email`    (`email`),
    CONSTRAINT `fk_user_diet`
        FOREIGN KEY (`default_diet_id`)    REFERENCES `diet`    (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_user_routine`
        FOREIGN KEY (`default_routine_id`) REFERENCES `routine` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: session
-- Sesiones activas de usuario (autenticación por token).
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `session` (
    `id`                        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`                   INT UNSIGNED NOT NULL,
    `token`                     VARCHAR(255) NOT NULL,
    `session_extension_minutes` INT UNSIGNED NOT NULL DEFAULT 30,
    `created_at`                DATETIME     NOT NULL,
    `updated_at`                DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_session_token` (`token`),
    CONSTRAINT `fk_session_user`
        FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: user_weight_log
-- Histórico de pesos del usuario.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_weight_log` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED  NOT NULL,
    `weight_kg`  DECIMAL(5,2)  NOT NULL,
    `created_at` DATETIME      NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_weight_log_user`
        FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: dish
-- Platos y recetas disponibles en el sistema.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dish` (
    `id`                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                 VARCHAR(255) NOT NULL,
    `ingredients`          VARCHAR(255) DEFAULT NULL,
    `calories_per_serving` INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_dish_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: diet_meal
-- Qué platos van en cada comida de una dieta.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `diet_meal` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `diet_id`     INT UNSIGNED NOT NULL,
    `dish_id`     INT UNSIGNED NOT NULL,
    `meal_type`   VARCHAR(255) NOT NULL                    COMMENT 'desayuno | media-mañana | comida | cena',
    `day_of_week` INT UNSIGNED DEFAULT NULL                COMMENT '1=lunes … 7=domingo',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_diet_meal_diet`
        FOREIGN KEY (`diet_id`) REFERENCES `diet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_diet_meal_dish`
        FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: user_meal_log
-- Comidas registradas por el usuario.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_meal_log` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `dish_id`    INT UNSIGNED NOT NULL,
    `quantity`   INT UNSIGNED NOT NULL DEFAULT 1           COMMENT 'número de raciones',
    `created_at` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_meal_log_user`
        FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_meal_log_dish`
        FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: exercise
-- Ejercicios individuales disponibles.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `exercise` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `intensity`   VARCHAR(255) DEFAULT NULL                COMMENT 'alta | media | baja',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_exercise_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: routine_category
-- Categorías de rutinas (objetivo, nivel, tipo de trabajo…).
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `routine_category` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_category_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: routine_has_exercise  (N:N Routine ↔ Exercise)
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `routine_has_exercise` (
    `routine_id`   INT UNSIGNED NOT NULL,
    `exercise_id`  INT UNSIGNED NOT NULL,
    `sets`         INT UNSIGNED DEFAULT NULL,
    `reps`         INT UNSIGNED DEFAULT NULL,
    `rest_seconds` INT UNSIGNED DEFAULT NULL,
    `order_index`  INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`routine_id`, `exercise_id`),
    CONSTRAINT `fk_rhe_routine`
        FOREIGN KEY (`routine_id`)  REFERENCES `routine`  (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_rhe_exercise`
        FOREIGN KEY (`exercise_id`) REFERENCES `exercise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: routine_has_category  (N:N Routine ↔ RoutineCategory)
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `routine_has_category` (
    `routine_id`  INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`routine_id`, `category_id`),
    CONSTRAINT `fk_rhc_routine`
        FOREIGN KEY (`routine_id`)  REFERENCES `routine`          (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_rhc_category`
        FOREIGN KEY (`category_id`) REFERENCES `routine_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Tabla: training_session
-- Sesiones de entrenamiento registradas por el usuario.
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_session` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`           INT UNSIGNED NOT NULL,
    `routine_id`        INT UNSIGNED NOT NULL,
    `duration_minutes`  INT UNSIGNED DEFAULT NULL,
    `calories_estimated` INT UNSIGNED DEFAULT NULL,
    `created_at`        DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_training_user`
        FOREIGN KEY (`user_id`)    REFERENCES `user`    (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_training_routine`
        FOREIGN KEY (`routine_id`) REFERENCES `routine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
