<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Repository\AgencyRepository;
use App\Service\TripValidator;
use DateTimeImmutable;
use Tests\Support\DatabaseTestCase;

final class TripValidatorTest extends DatabaseTestCase
{
    private TripValidator $validator;

    private int $departureAgencyId;

    private int $arrivalAgencyId;

    protected function setUp(): void
    {
        parent::setUp();

        $agencyRepository = new AgencyRepository(
            $this->connection,
        );

        $this->validator = new TripValidator(
            $agencyRepository,
        );

        $this->departureAgencyId = $this->getAgencyIdByName(
            'Test Paris'
        );

        $this->arrivalAgencyId = $this->getAgencyIdByName(
            'Test Lyon'
        );
    }

    /**
     * @return array<string, string>
     */
    private function getValidData(): array
    {
        $departureDatetime = new DateTimeImmutable(
            '+5 days 09:00'
        );

        $arrivalDatetime = $departureDatetime->modify(
            '+3 hours'
        );

        return [
            'departure_agency_id' =>
                (string) $this->departureAgencyId,
            'arrival_agency_id' =>
                (string) $this->arrivalAgencyId,
            'departure_datetime' =>
                $departureDatetime->format('Y-m-d\TH:i'),
            'arrival_datetime' =>
                $arrivalDatetime->format('Y-m-d\TH:i'),
            'total_seats' => '4',
            'available_seats' => '3',
        ];
    }

    public function testValidTripDataProducesNoError(): void
    {
        $errors = $this->validator->validate(
            $this->getValidData(),
        );

        self::assertSame([], $errors);
    }

    public function testDepartureAndArrivalAgenciesMustBeDifferent(): void
    {
        $data = $this->getValidData();

        $data['arrival_agency_id'] =
            $data['departure_agency_id'];

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'arrival_agency_id',
            $errors,
        );
    }

    public function testDepartureDatetimeCannotBeInThePast(): void
    {
        $data = $this->getValidData();

        $departureDatetime = new DateTimeImmutable(
            '-1 day'
        );

        $arrivalDatetime = $departureDatetime->modify(
            '+3 hours'
        );

        $data['departure_datetime'] =
            $departureDatetime->format('Y-m-d\TH:i');

        $data['arrival_datetime'] =
            $arrivalDatetime->format('Y-m-d\TH:i');

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'departure_datetime',
            $errors,
        );
    }

    public function testArrivalDatetimeMustBeAfterDepartureDatetime(): void
    {
        $data = $this->getValidData();

        $departureDatetime = new DateTimeImmutable(
            '+5 days 15:00'
        );

        $arrivalDatetime = $departureDatetime->modify(
            '-2 hours'
        );

        $data['departure_datetime'] =
            $departureDatetime->format('Y-m-d\TH:i');

        $data['arrival_datetime'] =
            $arrivalDatetime->format('Y-m-d\TH:i');

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'arrival_datetime',
            $errors,
        );
    }

    public function testTotalSeatsMustBeGreaterThanZero(): void
    {
        $data = $this->getValidData();

        $data['total_seats'] = '0';

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'total_seats',
            $errors,
        );
    }

    public function testAvailableSeatsCannotExceedTotalSeats(): void
    {
        $data = $this->getValidData();

        $data['total_seats'] = '4';
        $data['available_seats'] = '5';

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'available_seats',
            $errors,
        );
    }

    public function testAvailableSeatsCannotBeNegative(): void
    {
        $data = $this->getValidData();

        $data['available_seats'] = '-1';

        $errors = $this->validator->validate($data);

        self::assertArrayHasKey(
            'available_seats',
            $errors,
        );
    }

    public function testInvalidTripDataDoesNotCauseDatabaseWrite(): void
    {
        $initialTripCount = $this->countRows('trips');

        $data = $this->getValidData();

        $data['arrival_agency_id'] =
            $data['departure_agency_id'];

        $data['total_seats'] = '0';

        $errors = $this->validator->validate($data);

        self::assertNotEmpty($errors);

        self::assertSame(
            $initialTripCount,
            $this->countRows('trips'),
        );
    }
}