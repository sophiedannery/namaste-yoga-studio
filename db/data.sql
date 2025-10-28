SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE reservation;
TRUNCATE TABLE review;
TRUNCATE TABLE suspension;
TRUNCATE TABLE session;
TRUNCATE TABLE room;
TRUNCATE TABLE class_type;
TRUNCATE TABLE user;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

-- ---------- USERS ----------
INSERT INTO user (email, roles, password, first_name, last_name, avatar_url, is_active, bio, specialties, created_at, updated_at)
VALUES
  ('admin@namaste.com',  JSON_ARRAY('ROLE_ADMIN'),  '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Aline',  'Admin',   NULL, 1, NULL, NULL, '2025-10-01 09:00:00', '2025-10-01 09:00:00'),
  ('maddie@mail.com',     JSON_ARRAY('ROLE_USER'),   '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Maddie','Luna',   NULL, 1, NULL, NULL, '2025-10-01 09:10:00', '2025-10-01 09:10:00'),
  ('sophie@namaste.com',  JSON_ARRAY('ROLE_TEACHER','ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Sophie','Durand', NULL, 1, 'Enseigne Vinyasa & Yin.', 'Vinyasa,Yin', '2025-10-01 09:20:00', '2025-10-01 09:20:00'),
  ('lucas@namaste.com',   JSON_ARRAY('ROLE_TEACHER','ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Lucas', 'Bernard', NULL, 1, 'Spécialiste Hatha.', 'Hatha', '2025-10-01 09:25:00', '2025-10-01 09:25:00'),
  ('emma@mail.com',       JSON_ARRAY('ROLE_USER'),   '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Emma',  'Leroy',  NULL, 1, NULL, NULL, '2025-10-01 09:30:00', '2025-10-01 09:30:00'),
  ('martin@mail.com',     JSON_ARRAY('ROLE_USER'),   '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Martin','Morel',  NULL, 1, NULL, NULL, '2025-10-01 09:35:00', '2025-10-01 09:35:00');

-- ---------- CLASS TYPES ----------
INSERT INTO class_type (title, style, level, description, created_at, updated_at)
VALUES
  ('Hatha Découverte',  'Hatha',   'Débutant',      'Bases, respiration, postures clés.',        '2025-10-01 10:00:00', '2025-10-01 10:00:00'),
  ('Vinyasa Flow',      'Vinyasa', 'Intermédiaire', 'Séquences dynamiques en musique.',          '2025-10-01 10:05:00', '2025-10-01 10:05:00'),
  ('Yin Relax',         'Yin',     'Tous niveaux',  'Étirements tenus longtemps, relaxation.',   '2025-10-01 10:10:00', '2025-10-01 10:10:00');

-- ---------- ROOMS ----------
INSERT INTO room (name_room, note_room, created_at, updated_at)
VALUES
  ('Lotus',  'Salle lumineuse, 15 tapis.', '2025-10-01 10:15:00', '2025-10-01 10:15:00'),
  ('Bamboo', 'Salle cosy, 10 tapis.',      '2025-10-01 10:16:00', '2025-10-01 10:16:00');

-- ---------- SESSIONS ----------
INSERT INTO session
(teacher_id, cancelled_by_id, class_type_id, room_id, start_at, end_at, capacity, price, details, status, cancelled_at, cancel_reason, created_at, updated_at)
VALUES
  ( (SELECT id FROM user WHERE email='lucas@namaste.com'),
    NULL,
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    (SELECT id FROM room WHERE name_room='Lotus'),
    '2025-11-03 18:00:00', '2025-11-03 19:15:00', 15, 18.00, 'Cours du soir Hatha.', 'SCHEDULED', NULL, NULL, '2025-10-10 09:00:00', '2025-10-10 09:00:00'),

  ( (SELECT id FROM user WHERE email='sophie@namaste.com'),
    NULL,
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    (SELECT id FROM room WHERE name_room='Bamboo'),
    '2025-11-05 07:30:00', '2025-11-05 08:30:00', 10, 20.00, 'Morning flow.', 'SCHEDULED', NULL, NULL, '2025-10-10 09:05:00', '2025-10-10 09:05:00'),

  ( (SELECT id FROM user WHERE email='sophie@namaste.com'),
    NULL,
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    (SELECT id FROM room WHERE name_room='Lotus'),
    '2025-10-20 20:00:00', '2025-10-20 21:15:00', 15, 16.00, 'Session relax du lundi.', 'COMPLETED', NULL, NULL, '2025-10-05 09:10:00', '2025-10-21 22:00:00'),

  ( (SELECT id FROM user WHERE email='lucas@namaste.com'),
    (SELECT id FROM user WHERE email='admin@namaste.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    (SELECT id FROM room WHERE name_room='Bamboo'),
    '2025-10-25 18:00:00', '2025-10-25 19:15:00', 10, 20.00, 'Annulé pour maintenance.', 'CANCELLED', '2025-10-24 12:00:00', 'Plafond à réparer', '2025-10-05 09:15:00', '2025-10-24 12:00:00'),

  ( (SELECT id FROM user WHERE email='lucas@namaste.com'),
    NULL,
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    (SELECT id FROM room WHERE name_room='Bamboo'),
    '2025-11-10 12:15:00', '2025-11-10 13:15:00', 10, 18.00, 'Hatha lunch break.', 'SCHEDULED', NULL, NULL, '2025-10-12 10:00:00', '2025-10-12 10:00:00'),

  ( (SELECT id FROM user WHERE email='sophie@namaste.com'),
    NULL,
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    (SELECT id FROM room WHERE name_room='Lotus'),
    '2025-11-12 19:00:00', '2025-11-12 20:15:00', 15, 16.00, 'Yin en fin de journée.', 'SCHEDULED', NULL, NULL, '2025-10-12 10:05:00', '2025-10-12 10:05:00')
;

-- ---------- RESERVATIONS ----------
INSERT INTO reservation
(student_id, session_id, cancelled_by_id, statut, booked_at, cancelled_at, created_at, updated_at)
VALUES
  ( (SELECT id FROM user WHERE email='maddie@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-11-03 18:00:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Hatha Découverte')),
    NULL, 'CONFIRMED', '2025-10-15 10:00:00', NULL, '2025-10-15 10:00:00', '2025-10-15 10:00:00'),

  ( (SELECT id FROM user WHERE email='emma@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-11-03 18:00:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Hatha Découverte')),
    NULL, 'CONFIRMED', '2025-10-16 11:30:00', NULL, '2025-10-16 11:30:00', '2025-10-16 11:30:00'),

  ( (SELECT id FROM user WHERE email='martin@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-11-05 07:30:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Vinyasa Flow')),
    NULL, 'CONFIRMED', '2025-10-16 12:00:00', NULL, '2025-10-16 12:00:00', '2025-10-16 12:00:00'),

  ( (SELECT id FROM user WHERE email='maddie@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-10-25 18:00:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Vinyasa Flow')),
    (SELECT id FROM user WHERE email='admin@namaste.com'), 'CANCELLED', '2025-10-20 09:00:00', '2025-10-24 12:05:00', '2025-10-20 09:00:00', '2025-10-24 12:05:00'),

  ( (SELECT id FROM user WHERE email='emma@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-10-20 20:00:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Yin Relax')),
    NULL, 'CONFIRMED', '2025-10-18 14:00:00', NULL, '2025-10-18 14:00:00', '2025-10-18 14:00:00'),

  ( (SELECT id FROM user WHERE email='martin@mail.com'),
    (SELECT id FROM session WHERE start_at='2025-11-12 19:00:00' AND class_type_id=(SELECT id FROM class_type WHERE title='Yin Relax')),
    NULL, 'CONFIRMED', '2025-10-19 10:15:00', NULL, '2025-10-19 10:15:00', '2025-10-19 10:15:00')
;

-- ---------- REVIEWS ----------
INSERT INTO review
(student_id, class_type_id, rating, comment, statut, created_at, updated_at)
VALUES
  ( (SELECT id FROM user WHERE email='maddie@mail.com'),
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    5, 'Super pour débuter !', 'PUBLISHED', '2025-10-21 09:00:00', '2025-10-21 09:30:00'),

  ( (SELECT id FROM user WHERE email='emma@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    4, 'Dynamique et ludique.', 'PUBLISHED', '2025-10-21 10:00:00', '2025-10-21 10:00:00'),

  ( (SELECT id FROM user WHERE email='martin@mail.com'),
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    5, 'Très relaxant, parfait le soir.', 'PENDING', '2025-10-22 18:00:00', '2025-10-22 18:00:00'),

  ( (SELECT id FROM user WHERE email='maddie@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    2, 'Cours annulé, déçu…', 'PUBLISHED', '2025-10-25 20:00:00', '2025-10-25 20:00:00')
;

-- ---------- SUSPENSIONS ----------
INSERT INTO suspension
(student_id, admin_res_id, reason, start_at, end_at, status, created_at, updated_at)
VALUES
  ( (SELECT id FROM user WHERE email='martin@mail.com'),
    (SELECT id FROM user WHERE email='admin@namaste.com'),
    'No-show répétés', '2025-10-26 00:00:00', '2025-11-02 23:59:59', 'ACTIVE', '2025-10-26 08:00:00', '2025-10-26 08:00:00')
;

COMMIT;
