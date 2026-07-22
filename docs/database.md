# Database

## Overview

The application uses a MySQL or MariaDB database named:

`touche_pas_au_klaxon`

The schema is defined in:

`database/create_database.sql`

The initial data will be stored separately in:

`database/seed_database.sql`

## Tables

The database contains three tables:

- `users`
- `agencies`
- `trips`

## users

Stores employees imported from the company HR system.

Main columns:

- `id`
- `last_name`
- `first_name`
- `phone`
- `email`
- `password`
- `role`

Main constraints:

- unique email
- required user information
- role limited to `ROLE_USER` or `ROLE_ADMIN`
- password stored as a hash

The administrator interface provides read-only access to employee data.

User creation, modification and deletion are intentionally unavailable because employees originate from the company HR system.

## agencies

Stores company locations.

Main columns:

- `id`
- `name`

The agency name is required and unique.

Only administrators can manage agencies.

## trips

Stores trips proposed by employees.

Main columns:

- `id`
- `departure_datetime`
- `arrival_datetime`
- `total_seats`
- `available_seats`
- `author_id`
- `departure_agency_id`
- `arrival_agency_id`

Main rules:

- one author per trip
- one departure agency
- one arrival agency
- departure and arrival agencies must differ
- arrival must be later than departure
- total seats must be greater than zero
- available seats cannot exceed total seats

The rule requiring a future departure date will be validated in PHP.

## Relationships

- one user can create several trips
- one trip belongs to one user
- one agency can be used by several trips
- one trip has one departure agency and one arrival agency

## Referential Integrity

Foreign keys use:

- `ON UPDATE RESTRICT`
- `ON DELETE RESTRICT`

A user or agency referenced by a trip cannot be deleted in a way that breaks the relationship.

## Indexes

Indexes are created on:

- `departure_datetime`
- `author_id`
- `departure_agency_id`
- `arrival_agency_id`

These indexes support trip filtering, sorting and joins.

## Validation

The database protects essential integrity rules.

The PHP application must also validate user input in order to:

- display clear error messages
- preserve submitted values
- check permissions
- validate rules depending on the current date

## Development Reset

The creation script starts with:

`DROP DATABASE IF EXISTS touche_pas_au_klaxon;`

Running it deletes all existing data.

During development, the expected sequence is:

1. run `create_database.sql`
2. run `seed_database.sql`
3. start the application

## Initial Data

The initial data set is stored in:

`database/seed_database.sql`

It contains:

- the 12 agencies supplied in the assignment annex
- the 20 employees supplied in the assignment annex
- one administrator account
- standard user accounts
- future, past and full test trips

Test credentials are documented in the README and final assignment deliverable.

The seed script must be executed after `create_database.sql`.