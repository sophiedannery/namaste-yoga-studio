SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

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
    2, 'Cours annulé, déçu…', 'PUBLISHED', '2025-10-25 20:00:00', '2025-10-25 20:00:00'),

  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    5, 'Très bon cours, explications claires et ambiance rassurante.', 'PUBLISHED', '2026-01-02 09:15:00', '2026-01-02 09:30:00'),

  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    4, 'Bon rythme pour débuter, j’ai aimé les ajustements.', 'PUBLISHED', '2026-01-03 10:20:00', '2026-01-03 10:20:00'),

  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    3, 'Bien mais j’aurais aimé un peu plus de posture debout.', 'PENDING', '2026-01-04 12:00:00', '2026-01-04 12:00:00'),

  -- Vinyasa Flow
  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    5, 'Flow top, très dynamique et bien construit.', 'PUBLISHED', '2026-01-02 08:45:00', '2026-01-02 09:00:00'),

  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    4, 'Énergisant ! Bonne musique et transitions fluides.', 'PUBLISHED', '2026-01-03 09:00:00', '2026-01-03 09:05:00'),

  ( (SELECT id FROM user WHERE email='paul@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    2, 'Un peu trop intense pour moi, mais prof sympa.', 'PUBLISHED', '2026-01-05 11:00:00', '2026-01-05 11:00:00'),

  -- Yin Relax
  ( (SELECT id FROM user WHERE email='sarah@mail.com'),
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    5, 'Parfait pour relâcher, je suis sorti(e) détendu(e).', 'PUBLISHED', '2026-01-01 19:10:00', '2026-01-01 19:20:00'),

  ( (SELECT id FROM user WHERE email='kevin@mail.com'),
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    4, 'Très relaxant, j’ai mieux dormi après.', 'PENDING', '2026-01-04 19:30:00', '2026-01-04 19:30:00'),

  -- Prénatal Doux
  ( (SELECT id FROM user WHERE email='manon@mail.com'),
    (SELECT id FROM class_type WHERE title='Prénatal Doux'),
    5, 'Cours doux et adapté, je me suis sentie en confiance.', 'PUBLISHED', '2026-01-02 12:05:00', '2026-01-02 12:10:00'),

  ( (SELECT id FROM user WHERE email='camille@mail.com'),
    (SELECT id FROM class_type WHERE title='Prénatal Doux'),
    4, 'Très bien, bonnes respirations et étirements.', 'PUBLISHED', '2026-01-03 12:05:00', '2026-01-03 12:15:00'),

  -- Power Vinyasa
  ( (SELECT id FROM user WHERE email='antoine@mail.com'),
    (SELECT id FROM class_type WHERE title='Power Vinyasa'),
    5, 'Intense mais super ! On sent le travail.', 'PUBLISHED', '2026-01-03 15:05:00', '2026-01-03 15:10:00'),

  ( (SELECT id FROM user WHERE email='thomas@mail.com'),
    (SELECT id FROM class_type WHERE title='Power Vinyasa'),
    4, 'Bonne séance, cardio + renforcement, j’ai transpiré !', 'PUBLISHED', '2026-01-04 15:10:00', '2026-01-04 15:10:00'),

  ( (SELECT id FROM user WHERE email='maddie@mail.com'),
    (SELECT id FROM class_type WHERE title='Power Vinyasa'),
    3, 'Challenging, j’ai suivi mais difficile par moments.', 'PENDING', '2026-01-05 18:05:00', '2026-01-05 18:05:00'),

  -- Méditation Guidée
  ( (SELECT id FROM user WHERE email='emma@mail.com'),
    (SELECT id FROM class_type WHERE title='Méditation Guidée'),
    5, 'Très apaisant, la voix guide bien et on ressort calme.', 'PUBLISHED', '2026-01-02 13:20:00', '2026-01-02 13:25:00'),

  ( (SELECT id FROM user WHERE email='martin@mail.com'),
    (SELECT id FROM class_type WHERE title='Méditation Guidée'),
    4, 'Bonne découverte, j’ai apprécié la partie respiration.', 'PUBLISHED', '2026-01-03 13:20:00', '2026-01-03 13:20:00'),

  -- Yoga Nidra
  ( (SELECT id FROM user WHERE email='alice@mail.com'),
    (SELECT id FROM class_type WHERE title='Yoga Nidra'),
    5, 'Incroyable, j’ai vraiment décroché. À refaire.', 'PUBLISHED', '2026-01-01 20:05:00', '2026-01-01 20:05:00'),

  ( (SELECT id FROM user WHERE email='julien@mail.com'),
    (SELECT id FROM class_type WHERE title='Yoga Nidra'),
    4, 'Très reposant, parfait en fin de journée.', 'PUBLISHED', '2026-01-05 20:05:00', '2026-01-05 20:05:00'),

  -- Reviews sur dates de février (pour montrer que ça continue)
  ( (SELECT id FROM user WHERE email='clara@mail.com'),
    (SELECT id FROM class_type WHERE title='Vinyasa Flow'),
    5, 'Super flow, idéal pour se remettre en forme.', 'PUBLISHED', '2026-02-17 09:20:00', '2026-02-17 09:30:00'),

  ( (SELECT id FROM user WHERE email='nicolas@mail.com'),
    (SELECT id FROM class_type WHERE title='Hatha Découverte'),
    4, 'Très bon rappel des bases, prof pédagogue.', 'PENDING', '2026-02-18 10:30:00', '2026-02-18 10:30:00'),

  ( (SELECT id FROM user WHERE email='lea@mail.com'),
    (SELECT id FROM class_type WHERE title='Yin Relax'),
    5, 'Détente totale, exactement ce qu’il me fallait.', 'PUBLISHED', '2026-02-19 20:05:00', '2026-02-19 20:05:00')
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