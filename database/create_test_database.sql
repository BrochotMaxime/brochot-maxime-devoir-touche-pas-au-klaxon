-- Create database "touche_pas_au_klaxon_test"

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS touche_pas_au_klaxon_test;

CREATE DATABASE touche_pas_au_klaxon_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE touche_pas_au_klaxon_test;

-- Create tables

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'ROLE_USER',

    CONSTRAINT pk_users
        PRIMARY KEY (id),

    CONSTRAINT uq_users_email
        UNIQUE (email),

    CONSTRAINT chk_users_last_name
        CHECK (CHAR_LENGTH(TRIM(last_name)) > 0),

    CONSTRAINT chk_users_first_name
        CHECK (CHAR_LENGTH(TRIM(first_name)) > 0),

    CONSTRAINT chk_users_phone
        CHECK (CHAR_LENGTH(TRIM(phone)) > 0),

    CONSTRAINT chk_users_email
        CHECK (CHAR_LENGTH(TRIM(email)) > 0),

    CONSTRAINT chk_users_password
        CHECK (CHAR_LENGTH(password) > 0),

    CONSTRAINT chk_users_role
        CHECK (role IN ('ROLE_USER', 'ROLE_ADMIN'))
)
ENGINE = InnoDB;

CREATE TABLE agencies (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,

    CONSTRAINT pk_agencies
        PRIMARY KEY (id),

    CONSTRAINT uq_agencies_name
        UNIQUE (name),

    CONSTRAINT chk_agencies_name
        CHECK (CHAR_LENGTH(TRIM(name)) > 0)
)
ENGINE = InnoDB;

CREATE TABLE trips (
    id INT UNSIGNED AUTO_INCREMENT,
    departure_datetime DATETIME NOT NULL,
    arrival_datetime DATETIME NOT NULL,
    total_seats SMALLINT UNSIGNED NOT NULL,
    available_seats SMALLINT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    departure_agency_id INT UNSIGNED NOT NULL,
    arrival_agency_id INT UNSIGNED NOT NULL,

    CONSTRAINT pk_trips
        PRIMARY KEY (id),

    CONSTRAINT fk_trips_author
        FOREIGN KEY (author_id)
        REFERENCES users (id)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_trips_departure_agency
        FOREIGN KEY (departure_agency_id)
        REFERENCES agencies (id)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_trips_arrival_agency
        FOREIGN KEY (arrival_agency_id)
        REFERENCES agencies (id)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT chk_trips_different_agencies
        CHECK (departure_agency_id <> arrival_agency_id),

    CONSTRAINT chk_trips_datetime_order
        CHECK (arrival_datetime > departure_datetime),

    CONSTRAINT chk_trips_total_seats
        CHECK (total_seats > 0),

    CONSTRAINT chk_trips_available_seats
        CHECK (available_seats <= total_seats)
)
ENGINE = InnoDB;


-- Create indexes

CREATE INDEX idx_trips_departure_datetime
    ON trips (departure_datetime);

CREATE INDEX idx_trips_author_id
    ON trips (author_id);

CREATE INDEX idx_trips_departure_agency_id
    ON trips (departure_agency_id);

CREATE INDEX idx_trips_arrival_agency_id
    ON trips (arrival_agency_id);