SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

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
    NULL, 'CONFIRMED', '2025-10-19 10:15:00', NULL, '2025-10-19 10:15:00', '2025-10-19 10:15:00'),

  -- 2026-01-01
  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:00:00', NULL, '2025-12-20 10:00:00', '2025-12-20 10:00:00'),

  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:05:00', NULL, '2025-12-20 10:05:00', '2025-12-20 10:05:00'),

  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 09:00:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:10:00', NULL, '2025-12-20 10:10:00', '2025-12-20 10:10:00'),

  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 10:30:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:15:00', NULL, '2025-12-20 10:15:00', '2025-12-20 10:15:00'),

  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 12:15:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:20:00', NULL, '2025-12-20 10:20:00', '2025-12-20 10:20:00'),

  ( (SELECT id FROM user WHERE email='paul@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 13:15:00'),
    NULL, 'CONFIRMED', '2025-12-20 10:25:00', NULL, '2025-12-20 10:25:00', '2025-12-20 10:25:00'),

  ( (SELECT id FROM user WHERE email='sarah@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 18:00:00'),
    NULL, 'CONFIRMED', '2025-12-21 09:00:00', NULL, '2025-12-21 09:00:00', '2025-12-21 09:00:00'),

  ( (SELECT id FROM user WHERE email='kevin@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-01 19:00:00'),
    NULL, 'CONFIRMED', '2025-12-21 09:05:00', NULL, '2025-12-21 09:05:00', '2025-12-21 09:05:00'),

  -- 2026-01-02
  ( (SELECT id FROM user WHERE email='manon@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-02 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-22 11:00:00', NULL, '2025-12-22 11:00:00', '2025-12-22 11:00:00'),

  ( (SELECT id FROM user WHERE email='thomas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-02 09:00:00'),
    NULL, 'CONFIRMED', '2025-12-22 11:05:00', NULL, '2025-12-22 11:05:00', '2025-12-22 11:05:00'),

  ( (SELECT id FROM user WHERE email='camille@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-02 10:30:00'),
    NULL, 'CONFIRMED', '2025-12-22 11:10:00', NULL, '2025-12-22 11:10:00', '2025-12-22 11:10:00'),

  ( (SELECT id FROM user WHERE email='antoine@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-02 12:15:00'),
    NULL, 'CONFIRMED', '2025-12-22 11:15:00', NULL, '2025-12-22 11:15:00', '2025-12-22 11:15:00'),

  -- Annulation par l'élève (session completed, mais réservation annulée)
  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-02 13:15:00'),
    (SELECT id FROM user WHERE email='alice@mail.com'), 'CANCELLED', '2025-12-22 11:20:00', '2025-12-30 08:00:00', '2025-12-22 11:20:00', '2025-12-30 08:00:00'),

  -- 2026-01-03
  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-03 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-23 10:00:00', NULL, '2025-12-23 10:00:00', '2025-12-23 10:00:00'),

  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-03 09:00:00'),
    NULL, 'CONFIRMED', '2025-12-23 10:05:00', NULL, '2025-12-23 10:05:00', '2025-12-23 10:05:00'),

  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-03 18:00:00'),
    NULL, 'CONFIRMED', '2025-12-23 10:10:00', NULL, '2025-12-23 10:10:00', '2025-12-23 10:10:00'),

  -- 2026-01-04
  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-04 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-24 09:00:00', NULL, '2025-12-24 09:00:00', '2025-12-24 09:00:00'),

  ( (SELECT id FROM user WHERE email='paul@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-04 09:00:00'),
    NULL, 'CONFIRMED', '2025-12-24 09:05:00', NULL, '2025-12-24 09:05:00', '2025-12-24 09:05:00'),

  ( (SELECT id FROM user WHERE email='sarah@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-04 13:15:00'),
    NULL, 'CONFIRMED', '2025-12-24 09:10:00', NULL, '2025-12-24 09:10:00', '2025-12-24 09:10:00'),

  -- 2026-01-05
  ( (SELECT id FROM user WHERE email='kevin@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-05 07:30:00'),
    NULL, 'CONFIRMED', '2025-12-26 10:00:00', NULL, '2025-12-26 10:00:00', '2025-12-26 10:00:00'),

  ( (SELECT id FROM user WHERE email='manon@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-05 09:00:00'),
    NULL, 'CONFIRMED', '2025-12-26 10:05:00', NULL, '2025-12-26 10:05:00', '2025-12-26 10:05:00'),

  ( (SELECT id FROM user WHERE email='thomas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-01-05 18:00:00'),
    NULL, 'CONFIRMED', '2025-12-26 10:10:00', NULL, '2025-12-26 10:10:00', '2025-12-26 10:10:00'),

  -- ==========================
  -- FEBRUARY 2026 (scheduled sessions)
  -- ==========================

  -- 2026-02-17
  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-17 07:00:00'),
    NULL, 'CONFIRMED', '2026-02-10 10:00:00', NULL, '2026-02-10 10:00:00', '2026-02-10 10:00:00'),

  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-17 07:30:00'),
    NULL, 'CONFIRMED', '2026-02-10 10:05:00', NULL, '2026-02-10 10:05:00', '2026-02-10 10:05:00'),

  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-17 10:00:00'),
    NULL, 'CONFIRMED', '2026-02-10 10:10:00', NULL, '2026-02-10 10:10:00', '2026-02-10 10:10:00'),

  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-17 18:00:00'),
    NULL, 'CONFIRMED', '2026-02-10 10:15:00', NULL, '2026-02-10 10:15:00', '2026-02-10 10:15:00'),

  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-17 19:00:00'),
    NULL, 'CONFIRMED', '2026-02-10 10:20:00', NULL, '2026-02-10 10:20:00', '2026-02-10 10:20:00'),

  -- 2026-02-18
  ( (SELECT id FROM user WHERE email='paul@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-18 07:30:00'),
    NULL, 'CONFIRMED', '2026-02-11 10:00:00', NULL, '2026-02-11 10:00:00', '2026-02-11 10:00:00'),

  ( (SELECT id FROM user WHERE email='sarah@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-18 09:00:00'),
    NULL, 'CONFIRMED', '2026-02-11 10:05:00', NULL, '2026-02-11 10:05:00', '2026-02-11 10:05:00'),

  ( (SELECT id FROM user WHERE email='kevin@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-18 17:00:00'),
    NULL, 'CONFIRMED', '2026-02-11 10:10:00', NULL, '2026-02-11 10:10:00', '2026-02-11 10:10:00'),

  -- annulation par l'élève sur une session scheduled (créneau 12:15 ambigu -> on fixe Méditation Guidée + Salle Sérénité)
  ( (SELECT id FROM user WHERE email='manon@mail.com'),
    (SELECT id FROM session
      WHERE start_at='2026-02-18 12:15:00'
        AND class_type_id=(SELECT id FROM class_type WHERE title='Méditation Guidée')
        AND room_id=(SELECT id FROM room WHERE name_room='Sérénité')
    ),
    (SELECT id FROM user WHERE email='manon@mail.com'), 'CANCELLED', '2026-02-11 10:15:00', '2026-02-17 09:00:00', '2026-02-11 10:15:00', '2026-02-17 09:00:00'),

  -- 2026-02-19
  ( (SELECT id FROM user WHERE email='thomas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-19 07:00:00'),
    NULL, 'CONFIRMED', '2026-02-12 10:00:00', NULL, '2026-02-12 10:00:00', '2026-02-12 10:00:00'),

  ( (SELECT id FROM user WHERE email='camille@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-19 09:00:00'),
    NULL, 'CONFIRMED', '2026-02-12 10:05:00', NULL, '2026-02-12 10:05:00', '2026-02-12 10:05:00'),

  ( (SELECT id FROM user WHERE email='antoine@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-19 19:00:00'),
    NULL, 'CONFIRMED', '2026-02-12 10:10:00', NULL, '2026-02-12 10:10:00', '2026-02-12 10:10:00'),

  -- 2026-02-20
  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-20 07:30:00'),
    NULL, 'CONFIRMED', '2026-02-13 10:00:00', NULL, '2026-02-13 10:00:00', '2026-02-13 10:00:00'),

  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-20 09:00:00'),
    NULL, 'CONFIRMED', '2026-02-13 10:05:00', NULL, '2026-02-13 10:05:00', '2026-02-13 10:05:00'),

  -- 2026-02-21
  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-21 07:30:00'),
    NULL, 'CONFIRMED', '2026-02-14 10:00:00', NULL, '2026-02-14 10:00:00', '2026-02-14 10:00:00'),

  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-21 17:00:00'),
    NULL, 'CONFIRMED', '2026-02-14 10:05:00', NULL, '2026-02-14 10:05:00', '2026-02-14 10:05:00'),

  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM session WHERE start_at='2026-02-21 19:00:00'),
    NULL, 'CONFIRMED', '2026-02-14 10:10:00', NULL, '2026-02-14 10:10:00', '2026-02-14 10:10:00')
;

COMMIT;