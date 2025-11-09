-- ------------------------------------------------------------
-- Table : user (comptes élèves / professeurs / admin)
-- ------------------------------------------------------------
CREATE TABLE `user` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `email`         VARCHAR(180)  NOT NULL,
  `roles`         JSON          NOT NULL,
  `password`      VARCHAR(255)  NOT NULL,
  `first_name`    VARCHAR(255)  NOT NULL,
  `last_name`     VARCHAR(255)  NOT NULL,
  `avatar_url`    VARCHAR(255)  NULL,
  `is_active`     TINYINT(1)    NOT NULL DEFAULT 1,
  `bio`           LONGTEXT      NULL,
  `specialties`   LONGTEXT      NULL,
  `created_at`    DATETIME      NOT NULL,
  `updated_at`    DATETIME      NOT NULL,
  CONSTRAINT `UNIQ_user_email` UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : class_type (typologies de cours)
-- ------------------------------------------------------------
CREATE TABLE `class_type` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(255)  NOT NULL,
  `style`       VARCHAR(255)  NOT NULL,
  `level`       VARCHAR(255)  NOT NULL,
  `description` LONGTEXT      NULL,
  `created_at`  DATETIME      NOT NULL,
  `updated_at`  DATETIME      NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : room (salles de cours)
-- ------------------------------------------------------------
CREATE TABLE `room` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `name_room`  VARCHAR(255) NOT NULL,
  `note_room`  LONGTEXT     NULL,
  `created_at` DATETIME     NOT NULL,
  `updated_at` DATETIME     NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : session (cours planifiés)
-- ------------------------------------------------------------
CREATE TABLE `session` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `teacher_id`     INT         NOT NULL,
  `cancelled_by_id` INT        NULL,
  `class_type_id`  INT         NOT NULL,
  `room_id`        INT         NULL,
  `start_at`       DATETIME    NOT NULL,
  `end_at`         DATETIME    NOT NULL,
  `capacity`       INT         NOT NULL,
  `price`          DECIMAL(7,2) NULL,
  `details`        LONGTEXT    NULL,
  `status`         VARCHAR(50) NOT NULL,
  `cancelled_at`   DATETIME    NULL,
  `cancel_reason`  VARCHAR(255) NULL,
  `created_at`     DATETIME    NOT NULL,
  `updated_at`     DATETIME    NOT NULL,
  KEY `IDX_session_teacher`      (`teacher_id`),
  KEY `IDX_session_cancelled_by` (`cancelled_by_id`),
  KEY `IDX_session_class_type`   (`class_type_id`),
  KEY `IDX_session_room`         (`room_id`),
  CONSTRAINT `FK_session_teacher`
    FOREIGN KEY (`teacher_id`) REFERENCES `user`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_session_cancelled_by`
    FOREIGN KEY (`cancelled_by_id`) REFERENCES `user`(`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_session_class_type`
    FOREIGN KEY (`class_type_id`) REFERENCES `class_type`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_session_room`
    FOREIGN KEY (`room_id`) REFERENCES `room`(`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : reservation (inscriptions aux sessions)
-- ------------------------------------------------------------
CREATE TABLE `reservation` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `student_id`     INT          NOT NULL,
  `session_id`     INT          NOT NULL,
  `cancelled_by_id` INT         NULL,
  `statut`         VARCHAR(255) NOT NULL,
  `booked_at`      DATETIME     NOT NULL,
  `cancelled_at`   DATETIME     NULL,
  `created_at`     DATETIME     NOT NULL,
  `updated_at`     DATETIME     NOT NULL,
  CONSTRAINT `UNIQ_reservation_student_session`
    UNIQUE (`student_id`, `session_id`),
  KEY `IDX_reservation_student`     (`student_id`),
  KEY `IDX_reservation_session`     (`session_id`),
  KEY `IDX_reservation_cancelled_by`(`cancelled_by_id`),
  CONSTRAINT `FK_reservation_student`
    FOREIGN KEY (`student_id`) REFERENCES `user`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_reservation_session`
    FOREIGN KEY (`session_id`) REFERENCES `session`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_reservation_cancelled_by`
    FOREIGN KEY (`cancelled_by_id`) REFERENCES `user`(`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : review (avis des élèves sur un type de cours)
-- ------------------------------------------------------------
CREATE TABLE `review` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `student_id`   INT         NOT NULL,
  `class_type_id` INT        NOT NULL,
  `rating`       INT         NOT NULL,
  `comment`      LONGTEXT    NULL,
  `statut`       VARCHAR(20) NOT NULL DEFAULT 'PENDING',
  `created_at`   DATETIME    NOT NULL,
  `updated_at`   DATETIME    NOT NULL,
  KEY `IDX_review_student`    (`student_id`),
  KEY `IDX_review_class_type` (`class_type_id`),
  CONSTRAINT `FK_review_student`
    FOREIGN KEY (`student_id`) REFERENCES `user`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_review_class_type`
    FOREIGN KEY (`class_type_id`) REFERENCES `class_type`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `CHK_review_rating`
    CHECK (`rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : suspension (suspensions décidées par l’admin)
-- ------------------------------------------------------------
CREATE TABLE `suspension` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `student_id`  INT          NOT NULL,
  `admin_res_id` INT         NOT NULL,
  `reason`      VARCHAR(255) NOT NULL,
  `start_at`    DATETIME     NOT NULL,
  `end_at`      DATETIME     NULL,
  `status`      VARCHAR(255) NOT NULL,
  `created_at`  DATETIME     NOT NULL,
  `updated_at`  DATETIME     NOT NULL,
  KEY `IDX_suspension_student` (`student_id`),
  KEY `IDX_suspension_admin`   (`admin_res_id`),
  CONSTRAINT `FK_suspension_student`
    FOREIGN KEY (`student_id`) REFERENCES `user`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `FK_suspension_admin`
    FOREIGN KEY (`admin_res_id`) REFERENCES `user`(`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


