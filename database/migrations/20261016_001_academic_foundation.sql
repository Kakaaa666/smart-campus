-- Academic foundation for Smart Campus (MySQL / InnoDB).
-- Back up the development database before running. This migration only creates
-- new tables and does not alter or delete existing academic records.
-- Prerequisite: the application has initialized akun_mahasiswa, akun_biro,
-- penawaran_matakuliah, krs, and krs_detail.

CREATE TABLE IF NOT EXISTS `academic_programs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(32) NULL DEFAULT NULL,
    `name` VARCHAR(150) NOT NULL,
    `faculty` VARCHAR(150) NULL DEFAULT NULL,
    `degree_level` VARCHAR(50) NULL DEFAULT NULL,
    `status` ENUM('PENDING_REVIEW','ACTIVE','INACTIVE') NOT NULL DEFAULT 'PENDING_REVIEW',
    `valid_from` DATE NULL DEFAULT NULL,
    `valid_until` DATE NULL DEFAULT NULL,
    `created_by` INT(11) NULL DEFAULT NULL,
    `updated_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_program_code` (`code`),
    KEY `idx_academic_program_name` (`name`),
    KEY `idx_academic_program_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_lecturer_programs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lecturer_id` INT(11) NOT NULL,
    `program_id` INT(11) NOT NULL,
    `assignment_type` VARCHAR(50) NOT NULL,
    `status` ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
    `valid_from` DATE NULL DEFAULT NULL,
    `valid_until` DATE NULL DEFAULT NULL,
    `assigned_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_academic_lecturer_program_active` (`lecturer_id`,`program_id`,`status`),
    KEY `idx_academic_lecturer_program_dates` (`valid_from`,`valid_until`),
    CONSTRAINT `fk_academic_lecturer_program_lecturer`
        FOREIGN KEY (`lecturer_id`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_lecturer_program_program`
        FOREIGN KEY (`program_id`) REFERENCES `academic_programs` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_lecturer_program_assigned_by`
        FOREIGN KEY (`assigned_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_curricula` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `program_id` INT(11) NOT NULL,
    `code` VARCHAR(32) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `cohort_start_year` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    `cohort_end_year` SMALLINT(5) UNSIGNED NULL DEFAULT NULL,
    `valid_from` DATE NULL DEFAULT NULL,
    `valid_until` DATE NULL DEFAULT NULL,
    `status` ENUM('DRAFT','ACTIVE','ARCHIVED') NOT NULL DEFAULT 'DRAFT',
    `created_by` INT(11) NULL DEFAULT NULL,
    `updated_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_curriculum_program_code` (`program_id`,`code`),
    KEY `idx_academic_curriculum_status` (`program_id`,`status`,`cohort_start_year`,`cohort_end_year`),
    CONSTRAINT `fk_academic_curriculum_program`
        FOREIGN KEY (`program_id`) REFERENCES `academic_programs` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_curriculum_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_academic_curriculum_updated_by`
        FOREIGN KEY (`updated_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_courses` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(32) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `status` ENUM('PENDING_REVIEW','ACTIVE','INACTIVE') NOT NULL DEFAULT 'PENDING_REVIEW',
    `created_by` INT(11) NULL DEFAULT NULL,
    `updated_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_course_code` (`code`),
    KEY `idx_academic_course_name` (`name`),
    CONSTRAINT `fk_academic_course_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_academic_course_updated_by`
        FOREIGN KEY (`updated_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_curriculum_courses` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `curriculum_id` INT(11) NOT NULL,
    `course_id` INT(11) NOT NULL,
    `recommended_semester` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
    `credits` TINYINT(2) UNSIGNED NOT NULL,
    `course_group` VARCHAR(50) NULL DEFAULT NULL,
    `is_required` TINYINT(1) NOT NULL DEFAULT 1,
    `status` ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_curriculum_course` (`curriculum_id`,`course_id`),
    KEY `idx_academic_curriculum_course_semester` (`curriculum_id`,`recommended_semester`,`status`),
    CONSTRAINT `fk_academic_curriculum_course_curriculum`
        FOREIGN KEY (`curriculum_id`) REFERENCES `academic_curricula` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_curriculum_course_course`
        FOREIGN KEY (`course_id`) REFERENCES `academic_courses` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_course_prerequisites` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `curriculum_course_id` INT(11) NOT NULL,
    `prerequisite_course_id` INT(11) NOT NULL,
    `alternative_group` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 1,
    `minimum_grade` DECIMAL(3,2) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_course_prerequisite` (`curriculum_course_id`,`prerequisite_course_id`),
    KEY `idx_academic_prerequisite_course` (`prerequisite_course_id`),
    CONSTRAINT `fk_academic_prerequisite_target`
        FOREIGN KEY (`curriculum_course_id`) REFERENCES `academic_curriculum_courses` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_prerequisite_source`
        FOREIGN KEY (`prerequisite_course_id`) REFERENCES `academic_curriculum_courses` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_student_curricula` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `student_id` INT(11) NOT NULL,
    `curriculum_id` INT(11) NOT NULL,
    `effective_from` DATE NOT NULL,
    `effective_until` DATE NULL DEFAULT NULL,
    `change_reason` VARCHAR(500) NULL DEFAULT NULL,
    `changed_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_academic_student_curriculum_effective` (`student_id`,`effective_from`,`effective_until`),
    KEY `idx_academic_student_curriculum_curriculum` (`curriculum_id`),
    CONSTRAINT `fk_academic_student_curriculum_student`
        FOREIGN KEY (`student_id`) REFERENCES `akun_mahasiswa` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_student_curriculum_curriculum`
        FOREIGN KEY (`curriculum_id`) REFERENCES `academic_curricula` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_student_curriculum_changed_by`
        FOREIGN KEY (`changed_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_offering_courses` (
    `offering_id` INT(11) NOT NULL,
    `course_id` INT(11) NOT NULL,
    `created_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`offering_id`),
    KEY `idx_academic_offering_course_course` (`course_id`),
    CONSTRAINT `fk_academic_offering_course_offering`
        FOREIGN KEY (`offering_id`) REFERENCES `penawaran_matakuliah` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_offering_course_course`
        FOREIGN KEY (`course_id`) REFERENCES `academic_courses` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_offering_course_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_rules` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(64) NOT NULL,
    `version` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `scope_type` ENUM('GLOBAL','PROGRAM','CURRICULUM','COURSE','PERIOD') NOT NULL DEFAULT 'GLOBAL',
    `scope_id` INT(11) NULL DEFAULT NULL,
    `parameters_json` LONGTEXT NOT NULL,
    `effective_from` DATE NULL DEFAULT NULL,
    `effective_until` DATE NULL DEFAULT NULL,
    `status` ENUM('DRAFT','ACTIVE','INACTIVE','NEEDS_REVIEW') NOT NULL DEFAULT 'DRAFT',
    `change_reason` VARCHAR(500) NULL DEFAULT NULL,
    `created_by` INT(11) NULL DEFAULT NULL,
    `updated_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_rule_code_version` (`code`,`version`),
    KEY `idx_academic_rule_effective` (`status`,`category`,`scope_type`,`scope_id`,`effective_from`,`effective_until`),
    CONSTRAINT `fk_academic_rule_created_by`
        FOREIGN KEY (`created_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_academic_rule_updated_by`
        FOREIGN KEY (`updated_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_rule_history` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `rule_id` INT(11) NOT NULL,
    `version` INT(11) UNSIGNED NOT NULL,
    `before_json` LONGTEXT NULL DEFAULT NULL,
    `after_json` LONGTEXT NOT NULL,
    `change_reason` VARCHAR(500) NOT NULL,
    `changed_by` INT(11) NULL DEFAULT NULL,
    `changed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_rule_history_version` (`rule_id`,`version`),
    KEY `idx_academic_rule_history_changed_at` (`changed_at`),
    CONSTRAINT `fk_academic_rule_history_rule`
        FOREIGN KEY (`rule_id`) REFERENCES `academic_rules` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_rule_history_changed_by`
        FOREIGN KEY (`changed_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_catalog_history` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `entity_type` ENUM('PROGRAM','COURSE','CURRICULUM','CURRICULUM_COURSE','LECTURER_ASSIGNMENT','STUDENT_CURRICULUM') NOT NULL,
    `entity_id` INT(11) NOT NULL,
    `action` ENUM('CREATE','UPDATE','STATUS_CHANGE') NOT NULL,
    `before_json` LONGTEXT NULL DEFAULT NULL,
    `after_json` LONGTEXT NOT NULL,
    `change_reason` VARCHAR(500) NOT NULL,
    `performed_by` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_academic_catalog_history_entity` (`entity_type`,`entity_id`,`created_at`),
    CONSTRAINT `fk_academic_catalog_history_performed_by`
        FOREIGN KEY (`performed_by`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_krs_validation_history` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `krs_id` INT(11) NOT NULL,
    `action` ENUM('DRAFT','SUBMIT','APPROVE','REJECT','REVALIDATE') NOT NULL,
    `result_json` LONGTEXT NOT NULL,
    `rule_versions_json` LONGTEXT NOT NULL,
    `performed_by_staff_id` INT(11) NULL DEFAULT NULL,
    `performed_by_student_id` INT(11) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_academic_krs_validation_history` (`krs_id`,`created_at`),
    CONSTRAINT `fk_academic_krs_validation_krs`
        FOREIGN KEY (`krs_id`) REFERENCES `krs` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_academic_krs_validation_staff`
        FOREIGN KEY (`performed_by_staff_id`) REFERENCES `akun_biro` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT `fk_academic_krs_validation_student`
        FOREIGN KEY (`performed_by_student_id`) REFERENCES `akun_mahasiswa` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `academic_krs_detail_snapshots` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `krs_detail_id` INT(11) NOT NULL,
    `course_code` VARCHAR(32) NOT NULL,
    `course_name` VARCHAR(150) NOT NULL,
    `credits` TINYINT(2) UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_academic_krs_detail_snapshot` (`krs_detail_id`),
    CONSTRAINT `fk_academic_krs_detail_snapshot_detail`
        FOREIGN KEY (`krs_detail_id`) REFERENCES `krs_detail` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
