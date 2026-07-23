<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Repository\AgencyRepository;
use Tests\Support\DatabaseTestCase;

final class AgencyRepositoryTest extends DatabaseTestCase
{
    private AgencyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new AgencyRepository(
            $this->connection,
        );
    }

    public function testAgencyCanBeCreated(): void
    {
        $initialCount = $this->countRows('agencies');

        $agencyId = $this->repository->create(
            'Test Strasbourg'
        );

        self::assertGreaterThan(
            0,
            $agencyId,
        );

        self::assertSame(
            $initialCount + 1,
            $this->countRows('agencies'),
        );

        $agency = $this->repository->findById(
            $agencyId,
        );

        self::assertNotNull($agency);

        self::assertSame(
            'Test Strasbourg',
            $agency->getName(),
        );
    }

    public function testAgencyCanBeUpdated(): void
    {
        $agencyId = $this->repository->create(
            'Temporary Agency'
        );

        $this->repository->update(
            $agencyId,
            'Updated Agency',
        );

        $agency = $this->repository->findById(
            $agencyId,
        );

        self::assertNotNull($agency);

        self::assertSame(
            'Updated Agency',
            $agency->getName(),
        );
    }

    public function testUnusedAgencyCanBeDeleted(): void
    {
        $agencyId = $this->repository->create(
            'Agency To Delete'
        );

        self::assertNotNull(
            $this->repository->findById(
                $agencyId,
            ),
        );

        $countBeforeDeletion = $this->countRows(
            'agencies'
        );

        $this->repository->delete(
            $agencyId,
        );

        self::assertNull(
            $this->repository->findById(
                $agencyId,
            ),
        );

        self::assertSame(
            $countBeforeDeletion - 1,
            $this->countRows('agencies'),
        );
    }

    public function testAgencyUsedByTripsCannotBeDeleted(): void
    {
        $agencyId = $this->getAgencyIdByName(
            'Test Paris',
        );

        self::assertTrue(
            $this->repository->isUsedByTrips(
                $agencyId,
            ),
        );
    }

    public function testUnusedAgencyIsNotReferencedByTrips(): void
    {
        $agencyId = $this->repository->create(
            'Unused Agency',
        );

        self::assertFalse(
            $this->repository->isUsedByTrips(
                $agencyId,
            ),
        );
    }
}