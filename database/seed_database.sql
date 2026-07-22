-- Inserts the initial application test data

SET NAMES utf8mb4;

USE touche_pas_au_klaxon;

START TRANSACTION;


-- Insertion order according to dependencies between tables :
-- 1. Agencies table
-- 2. Users table
-- 3. Trips table

INSERT INTO agencies (name) VALUES
    ('Paris'),
    ('Lyon'),
    ('Marseille'),
    ('Toulouse'),
    ('Nice'),
    ('Nantes'),
    ('Strasbourg'),
    ('Montpellier'),
    ('Bordeaux'),
    ('Lille'),
    ('Rennes'),
    ('Reims');


-- Test credentials:
--
-- Administrator:
-- alexandre.martin@email.fr
-- Admin123!
--
-- Standard users:
-- User123!

INSERT INTO users (
    last_name,
    first_name,
    phone,
    email,
    password,
    role
) VALUES
    (
        'Martin',
        'Alexandre',
        '0612345678',
        'alexandre.martin@email.fr',
        '$2y$12$QhqRQCZ52Gcb3MCTQFJ1p.qcTGEy9/PvmbBvCECtCvRdH6XYu.Rmu',
        'ROLE_ADMIN'
    ),
    (
        'Dubois',
        'Sophie',
        '0698765432',
        'sophie.dubois@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Bernard',
        'Julien',
        '0622446688',
        'julien.bernard@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Moreau',
        'Camille',
        '0611223344',
        'camille.moreau@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Lefèvre',
        'Lucie',
        '0777889900',
        'lucie.lefevre@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Leroy',
        'Thomas',
        '0655443322',
        'thomas.leroy@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Roux',
        'Chloé',
        '0633221199',
        'chloe.roux@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Petit',
        'Maxime',
        '0766778899',
        'maxime.petit@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Garnier',
        'Laura',
        '0688776655',
        'laura.garnier@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Dupuis',
        'Antoine',
        '0744556677',
        'antoine.dupuis@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Lefebvre',
        'Emma',
        '0699887766',
        'emma.lefebvre@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Fontaine',
        'Louis',
        '0655667788',
        'louis.fontaine@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Chevalier',
        'Clara',
        '0788990011',
        'clara.chevalier@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Robin',
        'Nicolas',
        '0644332211',
        'nicolas.robin@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Gauthier',
        'Marine',
        '0677889922',
        'marine.gauthier@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Fournier',
        'Pierre',
        '0722334455',
        'pierre.fournier@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Girard',
        'Sarah',
        '0688665544',
        'sarah.girard@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Lambert',
        'Hugo',
        '0611223366',
        'hugo.lambert@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Masson',
        'Julie',
        '0733445566',
        'julie.masson@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Henry',
        'Arthur',
        '0666554433',
        'arthur.henry@email.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    );

INSERT INTO trips (
    departure_datetime,
    arrival_datetime,
    total_seats,
    available_seats,
    author_id,
    departure_agency_id,
    arrival_agency_id
) VALUES
    (
        DATE_ADD(NOW(), INTERVAL 1 DAY),
        DATE_ADD(DATE_ADD(NOW(), INTERVAL 1 DAY), INTERVAL 3 HOUR),
        4,
        3,
        (SELECT id FROM users WHERE email = 'sophie.dubois@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Paris'),
        (SELECT id FROM agencies WHERE name = 'Lyon')
    ),
    (
        DATE_ADD(NOW(), INTERVAL 2 DAY),
        DATE_ADD(DATE_ADD(NOW(), INTERVAL 2 DAY), INTERVAL 4 HOUR),
        5,
        2,
        (SELECT id FROM users WHERE email = 'julien.bernard@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Nantes'),
        (SELECT id FROM agencies WHERE name = 'Rennes')
    ),
    (
        DATE_ADD(NOW(), INTERVAL 3 DAY),
        DATE_ADD(DATE_ADD(NOW(), INTERVAL 3 DAY), INTERVAL 2 HOUR),
        3,
        1,
        (SELECT id FROM users WHERE email = 'camille.moreau@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Lille'),
        (SELECT id FROM agencies WHERE name = 'Reims')
    ),
    (
        DATE_ADD(NOW(), INTERVAL 4 DAY),
        DATE_ADD(DATE_ADD(NOW(), INTERVAL 4 DAY), INTERVAL 5 HOUR),
        4,
        0,
        (SELECT id FROM users WHERE email = 'lucie.lefevre@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Bordeaux'),
        (SELECT id FROM agencies WHERE name = 'Toulouse')
    ),
    (
        DATE_SUB(NOW(), INTERVAL 2 DAY),
        DATE_ADD(DATE_SUB(NOW(), INTERVAL 2 DAY), INTERVAL 3 HOUR),
        4,
        2,
        (SELECT id FROM users WHERE email = 'thomas.leroy@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Marseille'),
        (SELECT id FROM agencies WHERE name = 'Nice')
    ),
    (
        DATE_ADD(NOW(), INTERVAL 7 DAY),
        DATE_ADD(DATE_ADD(NOW(), INTERVAL 7 DAY), INTERVAL 6 HOUR),
        6,
        5,
        (SELECT id FROM users WHERE email = 'alexandre.martin@email.fr'),
        (SELECT id FROM agencies WHERE name = 'Strasbourg'),
        (SELECT id FROM agencies WHERE name = 'Paris')
    );

COMMIT;