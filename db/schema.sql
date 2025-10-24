SET NAMES utf8mb4;
SET time_zone = '+00:00';

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reservation;
DROP TABLE IF EXISTS session;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS suspension;
DROP TABLE IF EXISTS room;
DROP TABLE IF EXISTS class_type;
DROP TABLE IF EXISTS user;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE user (
  id_user        INT AUTO_INCREMENT PRIMARY KEY,
  email          VARCHAR(255) NOT NULL UNIQUE,
  password_hash  VARCHAR(255) NOT NULL,
  first_name     VARCHAR(255) NOT NULL,
  last_name      VARCHAR(255) NOT NULL,
  avatar_url     VARCHAR(255) NULL,
  role           VARCHAR(50)  NOT NULL,       
  is_active      BOOLEAN      NOT NULL DEFAULT TRUE,
  bio            TEXT NULL,
  specialties    TEXT NULL,
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_user_role (role),
  INDEX idx_user_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE class_type (
  id_class_type    INT AUTO_INCREMENT PRIMARY KEY,
  title            VARCHAR(255) NOT NULL,
  style            VARCHAR(255) NOT NULL,
  level            VARCHAR(255) NOT NULL,
  description_cla  TEXT NULL,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_class_type (title, style, level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE room (
  id_room     INT AUTO_INCREMENT PRIMARY KEY,
  name_room   VARCHAR(255) NOT NULL,
  note_room   TEXT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_room_name (name_room)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE session (
  id_session         INT AUTO_INCREMENT PRIMARY KEY,
  start_at_ses       DATETIME NOT NULL,
  end_at_ses         DATETIME NOT NULL,
  capacity_session   INT NOT NULL,
  price_ses          DECIMAL(7,2) NULL,
  details_ses        TEXT NULL,
  status_ses         VARCHAR(50) NOT NULL,
  cancelled_at_ses   DATETIME NULL,
  cancel_reason_ses  VARCHAR(255) NULL,
  created_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  user_id_teacher  INT NOT NULL,
  class_type_id    INT NOT NULL,
  room_id          INT NULL,
  cancelled_by     INT NULL,

  CONSTRAINT fk_session_teacher
    FOREIGN KEY (user_id_teacher) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_session_class_type
    FOREIGN KEY (class_type_id) REFERENCES class_type(id_class_type)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_session_room
    FOREIGN KEY (room_id) REFERENCES room(id_room)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_session_cancelled_by
    FOREIGN KEY (cancelled_by) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE SET NULL,

  INDEX idx_session_dates (start_at_ses, end_at_ses),
  INDEX idx_session_status (status_ses),
  INDEX idx_session_teacher (user_id_teacher),
  INDEX idx_session_class_type (class_type_id),
  INDEX idx_session_room (room_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE reservation (
  id_reservation    INT AUTO_INCREMENT PRIMARY KEY,
  status_res        VARCHAR(255) NOT NULL,
  booked_at_res     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  cancelled_at_res  DATETIME NULL,
  cancel_reason_res VARCHAR(255) NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  user_id_student INT NOT NULL,
  session_id      INT NOT NULL,
  cancelled_by    INT NULL,

  CONSTRAINT fk_reservation_student
    FOREIGN KEY (user_id_student) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reservation_session
    FOREIGN KEY (session_id) REFERENCES session(id_session)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reservation_cancelled_by
    FOREIGN KEY (cancelled_by) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE SET NULL,

  UNIQUE KEY uq_reservation_unique (user_id_student, session_id),
  INDEX idx_reservation_status (status_res),
  INDEX idx_reservation_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE review (
  id_review      INT AUTO_INCREMENT PRIMARY KEY,
  rating         TINYINT NOT NULL,
  comment        TEXT NULL,
  status_rev     VARCHAR(255) NOT NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  user_id_student INT NOT NULL,
  class_type_id   INT NOT NULL,

  CONSTRAINT fk_review_student
    FOREIGN KEY (user_id_student) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_review_class_type
    FOREIGN KEY (class_type_id) REFERENCES class_type(id_class_type)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  INDEX idx_review_status (status_rev),
  INDEX idx_review_class_type (class_type_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE suspension (
  id_suspension  INT AUTO_INCREMENT PRIMARY KEY,
  reason_sus     VARCHAR(255) NOT NULL,
  start_at_sus   DATETIME NOT NULL,
  end_at_sus     DATETIME NOT NULL,
  status_sus     VARCHAR(255) NOT NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  user_id_student INT NOT NULL,
  user_id_admin   INT NOT NULL,

  CONSTRAINT fk_suspension_student
    FOREIGN KEY (user_id_student) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_suspension_admin
    FOREIGN KEY (user_id_admin) REFERENCES user(id_user)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  INDEX idx_suspension_dates (start_at_sus, end_at_sus),
  INDEX idx_suspension_status (status_sus)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

SET FOREIGN_KEY_CHECKS = 1;
