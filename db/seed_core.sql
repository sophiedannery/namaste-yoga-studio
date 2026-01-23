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
  ('martin@mail.com',     JSON_ARRAY('ROLE_USER'),   '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Martin','Morel',  NULL, 1, NULL, NULL, '2025-10-01 09:35:00', '2025-10-01 09:35:00'),
  ('laura@namaste.com',   JSON_ARRAY('ROLE_TEACHER','ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Laura', 'Martin', NULL, 1, 'Hatha & Prénatal.', 'Hatha,Prénatal', '2025-10-01 09:26:00', '2025-10-01 09:26:00'),
  ('ines@namaste.com',    JSON_ARRAY('ROLE_TEACHER','ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Inès',  'Petit',  NULL, 1, 'Yin & Méditation.', 'Yin,Méditation', '2025-10-01 09:27:00', '2025-10-01 09:27:00'),
  ('tom@namaste.com',     JSON_ARRAY('ROLE_TEACHER','ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Tom',   'Roux',   NULL, 1, 'Vinyasa dynamique.', 'Vinyasa', '2025-10-01 09:28:00', '2025-10-01 09:28:00'),
  ('alice@mail.com',   JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Alice',   'Dupont',  NULL, 1, NULL, NULL, '2025-10-02 09:00:00', '2025-10-02 09:00:00'),
  ('julien@mail.com',  JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Julien',  'Moreau',  NULL, 1, NULL, NULL, '2025-10-02 09:05:00', '2025-10-02 09:05:00'),
  ('clara@mail.com',   JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Clara',   'Lefevre', NULL, 1, NULL, NULL, '2025-10-02 09:10:00', '2025-10-02 09:10:00'),
  ('nicolas@mail.com', JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Nicolas', 'Girard',  NULL, 1, NULL, NULL, '2025-10-02 09:15:00', '2025-10-02 09:15:00'),
  ('lea@mail.com',     JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Léa',     'Fontaine',NULL, 1, NULL, NULL, '2025-10-02 09:20:00', '2025-10-02 09:20:00'),
  ('paul@mail.com',    JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Paul',    'Renaud',  NULL, 1, NULL, NULL, '2025-10-02 09:25:00', '2025-10-02 09:25:00'),
  ('sarah@mail.com',   JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Sarah',   'Marchand',NULL, 1, NULL, NULL, '2025-10-02 09:30:00', '2025-10-02 09:30:00'),
  ('kevin@mail.com',   JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Kevin',   'Blanc',   NULL, 1, NULL, NULL, '2025-10-02 09:35:00', '2025-10-02 09:35:00'),
  ('manon@mail.com',   JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Manon',   'Gauthier',NULL, 1, NULL, NULL, '2025-10-02 09:40:00', '2025-10-02 09:40:00'),
  ('thomas@mail.com',  JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Thomas',  'Perrin',  NULL, 1, NULL, NULL, '2025-10-02 09:45:00', '2025-10-02 09:45:00'),
  ('camille@mail.com', JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Camille', 'Chevalier',NULL,1, NULL, NULL, '2025-10-02 09:50:00', '2025-10-02 09:50:00'),
  ('antoine@mail.com', JSON_ARRAY('ROLE_USER'), '$2y$13$06JScrgLVpYLL3UxjKPja.9wW1rcHxFcxMbAVT.6Ysp5i34Lv5bdG', 'Antoine', 'Boyer',   NULL, 1, NULL, NULL, '2025-10-02 09:55:00', '2025-10-02 09:55:00');


-- ---------- CLASS TYPES ----------
INSERT INTO class_type (title, style, level, description, created_at, updated_at)
VALUES
  ('Hatha Découverte',  'Hatha',   'Débutant',      'Bases, respiration, postures clés.',        '2025-10-01 10:00:00', '2025-10-01 10:00:00'),
  ('Vinyasa Flow',      'Vinyasa', 'Intermédiaire', 'Séquences dynamiques en musique.',          '2025-10-01 10:05:00', '2025-10-01 10:05:00'),
  ('Yin Relax',         'Yin',     'Tous niveaux',  'Étirements tenus longtemps, relaxation.',   '2025-10-01 10:10:00', '2025-10-01 10:10:00'),
  ('Prénatal Doux',     'Prénatal',  'Débutant',     'Mobilité, respiration, détente.',         '2025-10-01 10:11:00', '2025-10-01 10:11:00'),
  ('Power Vinyasa',     'Vinyasa',   'Avancé',       'Flow intense, renforcement.',            '2025-10-01 10:12:00', '2025-10-01 10:12:00'),
  ('Méditation Guidée', 'Méditation','Tous niveaux', 'Méditation + pranayama.',                '2025-10-01 10:13:00', '2025-10-01 10:13:00'),
  ('Yoga Nidra',        'Nidra',     'Tous niveaux', 'Relaxation profonde, scan corporel.',   '2025-10-01 10:14:00', '2025-10-01 10:14:00');


-- ---------- ROOMS ----------
INSERT INTO room (name_room, note_room, created_at, updated_at)
VALUES
  ('Lotus',      'Salle lumineuse, 15 tapis.',                         '2025-10-01 10:15:00', '2025-10-01 10:15:00'),
  ('Bamboo',     'Salle cosy, 10 tapis.',                              '2025-10-01 10:16:00', '2025-10-01 10:16:00'),
  ('Sérénité',   'Salle calme dédiée au Yin et à la méditation.',      '2025-10-01 10:17:00', '2025-10-01 10:17:00'),
  ('Énergie',    'Grande salle pour flows dynamiques, 20 tapis.',     '2025-10-01 10:18:00', '2025-10-01 10:18:00'),
  ('Harmonie',   'Salle modulable, idéale pour ateliers et prénatal.', '2025-10-01 10:19:00', '2025-10-01 10:19:00');

COMMIT;