<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Repository\TripRepository;
use DateTimeImmutable;
use Tests\Support\DatabaseTestCase;

final class TripRepositoryTest extends DatabaseTestCase
{
    private TripRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TripRepository(
            $this->connection,
        );
    }

    public function testTripCanBeCreated(): void
    {
        $authorId = $this->getUserIdByEmail(
            'alice.owner@test.fr'
        );

        $departureAgencyId = $this->getAgencyIdByName(
            'Test Nantes'
        );

        $arrivalAgencyId = $this->getAgencyIdByName(
            'Test Bordeaux'
        );

        $departureDatetime = new DateTimeImmutable(
            '+5 days 09:00'
        );

        $arrivalDatetime = $departureDatetime->modify(
            '+3 hours'
        );

        $initialCount = $this->countRows('trips');

        $tripId = $this->repository->create([
            'departure_datetime' => $departureDatetime->format(
                'Y-m-d H:i:s'
            ),
            'arrival_datetime' => $arrivalDatetime->format(
                'Y-m-d H:i:s'
            ),
            'total_seats' => 5,
            'available_seats' => 4,
            'author_id' => $authorId,
            'departure_agency_id' => $departureAgencyId,
            'arrival_agency_id' => $arrivalAgencyId,
        ]);

        self::assertGreaterThan(0, $tripId);

        self::assertSame(
            $initialCount + 1,
            $this->countRows('trips'),
        );

        $trip = $this->repository->findById($tripId);

        self::assertNotNull($trip);
        self::assertSame($tripId, $trip->getId());
        self::assertSame($authorId, $trip->getAuthorId());

        self::assertSame(
            $departureAgencyId,
            $trip->getDepartureAgencyId(),
        );

        self::assertSame(
            $arrivalAgencyId,
            $trip->getArrivalAgencyId(),
        );

        self::assertSame(5, $trip->getTotalSeats());
        self::assertSame(4, $trip->getAvailableSeats());

        self::assertSame(
            $departureDatetime->format('Y-m-d H:i:s'),
            $trip->getDepartureDatetime()->format(
                'Y-m-d H:i:s'
            ),
        );

        self::assertSame(
            $arrivalDatetime->format('Y-m-d H:i:s'),
            $trip->getArrivalDatetime()->format(
                'Y-m-d H:i:s'
            ),
        );
    }

    public function testTripCanBeUpdated(): void
    {
        $authorId = $this->getUserIdByEmail(
            'alice.owner@test.fr'
        );

        $initialDepartureAgencyId = $this->getAgencyIdByName(
            'Test Paris'
        );

        $initialArrivalAgencyId = $this->getAgencyIdByName(
            'Test Lyon'
        );

        $tripId = $this->repository->create([
            'departure_datetime' => (new DateTimeImmutable(
                '+5 days 08:00'
            ))->format('Y-m-d H:i:s'),
            'arrival_datetime' => (new DateTimeImmutable(
                '+5 days 11:00'
            ))->format('Y-m-d H:i:s'),
            'total_seats' => 4,
            'available_seats' => 3,
            'author_id' => $authorId,
            'departure_agency_id' => $initialDepartureAgencyId,
            'arrival_agency_id' => $initialArrivalAgencyId,
        ]);

        $newDepartureAgencyId = $this->getAgencyIdByName(
            'Test Nantes'
        );

        $newArrivalAgencyId = $this->getAgencyIdByName(
            'Test Bordeaux'
        );

        $newDepartureDatetime = new DateTimeImmutable(
            '+10 days 14:00'
        );

        $newArrivalDatetime = $newDepartureDatetime->modify(
            '+4 hours'
        );

        $this->repository->update($tripId, [
            'departure_datetime' => $newDepartureDatetime->format(
                'Y-m-d H:i:s'
            ),
            'arrival_datetime' => $newArrivalDatetime->format(
                'Y-m-d H:i:s'
            ),
            'total_seats' => 6,
            'available_seats' => 5,
            'departure_agency_id' => $newDepartureAgencyId,
            'arrival_agency_id' => $newArrivalAgencyId,
        ]);

        $updatedTrip = $this->repository->findById($tripId);

        self::assertNotNull($updatedTrip);

        self::assertSame(
            $newDepartureAgencyId,
            $updatedTrip->getDepartureAgencyId(),
        );

        self::assertSame(
            $newArrivalAgencyId,
            $updatedTrip->getArrivalAgencyId(),
        );

        self::assertSame(
            $newDepartureDatetime->format('Y-m-d H:i:s'),
            $updatedTrip->getDepartureDatetime()->format(
                'Y-m-d H:i:s'
            ),
        );

        self::assertSame(
            $newArrivalDatetime->format('Y-m-d H:i:s'),
            $updatedTrip->getArrivalDatetime()->format(
                'Y-m-d H:i:s'
            ),
        );

        self::assertSame(
            6,
            $updatedTrip->getTotalSeats(),
        );

        self::assertSame(
            5,
            $updatedTrip->getAvailableSeats(),
        );

        self::assertSame(
            $authorId,
            $updatedTrip->getAuthorId(),
        );
    }

    public function testTripRecognizesItsOwner(): void
    {
        $ownerId = $this->getUserIdByEmail(
            'alice.owner@test.fr'
        );

        $otherUserId = $this->getUserIdByEmail(
            'bob.other@test.fr'
        );

        $departureAgencyId = $this->getAgencyIdByName(
            'Test Nantes'
        );

        $arrivalAgencyId = $this->getAgencyIdByName(
            'Test Bordeaux'
        );

        $tripId = $this->repository->create([
            'departure_datetime' => (new DateTimeImmutable(
                '+7 days 09:00'
            ))->format('Y-m-d H:i:s'),
            'arrival_datetime' => (new DateTimeImmutable(
                '+7 days 12:00'
            ))->format('Y-m-d H:i:s'),
            'total_seats' => 4,
            'available_seats' => 4,
            'author_id' => $ownerId,
            'departure_agency_id' => $departureAgencyId,
            'arrival_agency_id' => $arrivalAgencyId,
        ]);

        $trip = $this->repository->findById($tripId);

        self::assertNotNull($trip);

        self::assertTrue(
            $trip->isOwnedBy($ownerId),
        );

        self::assertFalse(
            $trip->isOwnedBy($otherUserId),
        );
    }

    public function testTripCanBeDeleted(): void
    {
        $authorId = $this->getUserIdByEmail(
            'alice.owner@test.fr'
        );

        $departureAgencyId = $this->getAgencyIdByName(
            'Test Nantes'
        );

        $arrivalAgencyId = $this->getAgencyIdByName(
            'Test Bordeaux'
        );

        $tripId = $this->repository->create([
            'departure_datetime' => (new DateTimeImmutable(
                '+8 days 10:00'
            ))->format('Y-m-d H:i:s'),
            'arrival_datetime' => (new DateTimeImmutable(
                '+8 days 13:00'
            ))->format('Y-m-d H:i:s'),
            'total_seats' => 3,
            'available_seats' => 2,
            'author_id' => $authorId,
            'departure_agency_id' => $departureAgencyId,
            'arrival_agency_id' => $arrivalAgencyId,
        ]);

        self::assertNotNull(
            $this->repository->findById($tripId),
        );

        $countBeforeDeletion = $this->countRows(
            'trips'
        );

        $this->repository->delete($tripId);

        self::assertNull(
            $this->repository->findById($tripId),
        );

        self::assertSame(
            $countBeforeDeletion - 1,
            $this->countRows('trips'),
        );
    }
}