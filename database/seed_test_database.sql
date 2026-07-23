SET NAMES utf8mb4;

USE touche_pas_au_klaxon_test;

START TRANSACTION;

INSERT INTO agencies (name) VALUES
    ('Test Paris'),
    ('Test Lyon'),
    ('Test Nantes'),
    ('Test Bordeaux');

INSERT INTO users (
    last_name,
    first_name,
    phone,
    email,
    password,
    role
) VALUES
    (
        'Propriétaire',
        'Alice',
        '0600000001',
        'alice.owner@test.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Autre',
        'Bob',
        '0600000002',
        'bob.other@test.fr',
        '$2y$12$Fi7I5wDkQNT1eQy30KXiLeRkQFd8ul4OeNLWz2Moh6tjtTVxpxumG',
        'ROLE_USER'
    ),
    (
        'Administrateur',
        'Claire',
        '0600000003',
        'claire.admin@test.fr',
        '$2y$12$QhqRQCZ52Gcb3MCTQFJ1p.qcTGEy9/PvmbBvCECtCvRdH6XYu.Rmu',
        'ROLE_ADMIN'
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
        DATE_ADD(NOW(), INTERVAL 2 DAY),
        DATE_ADD(
            DATE_ADD(NOW(), INTERVAL 2 DAY),
            INTERVAL 3 HOUR
        ),
        4,
        3,
        (
            SELECT id
            FROM users
            WHERE email = 'alice.owner@test.fr'
        ),
        (
            SELECT id
            FROM agencies
            WHERE name = 'Test Paris'
        ),
        (
            SELECT id
            FROM agencies
            WHERE name = 'Test Lyon'
        )
    );

COMMIT;