-- Tabla: nota_descargos ("Descargo de Aplazados")
-- Base de datos: tiqui0_tiquisaat26 (DBGroup 'tiquipaya' en app/Config/Database.php)
--
-- Equivalente en SQL puro a la migración CI4
-- app/Database/Migrations/2026-08-18-000001_NotaDescargos.php
-- Úsala solo si no vas a correr `php spark migrate`; si corres la migración,
-- NO ejecutes este archivo (se crearía la tabla dos veces).

CREATE TABLE IF NOT EXISTS `nota_descargos` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `teacher_id` INT(11) UNSIGNED NOT NULL,
  `section_id` INT(11) UNSIGNED NOT NULL,
  `phase_id` INT(11) UNSIGNED NOT NULL,
  `phase_name` VARCHAR(50) DEFAULT NULL,
  `gestion` VARCHAR(20) DEFAULT NULL,
  `nota_final` DECIMAL(5,2) DEFAULT NULL,
  `reunion_padres` TINYINT(1) NOT NULL DEFAULT 0,
  `estrategias_aplicadas` TEXT,
  `motivo_aplazo` TEXT,
  `archivo_firmado` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_descargo_student_subject_phase` (`student_id`, `subject_id`, `phase_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
