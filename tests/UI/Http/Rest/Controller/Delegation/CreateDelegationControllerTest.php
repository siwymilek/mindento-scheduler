<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller\Delegation;

use App\Employee\Application\Command\CreateEmployee\CreateEmployeeCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\ApiTestCase;

class CreateDelegationControllerTest extends ApiTestCase
{
    private string $employeeUuid;

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Throwable
     */
    public function testCreateDelegationShouldReturn201StatusCode(): void
    {
        $this->post('/api/delegation', [
            'employee_uuid' => $this->employeeUuid,
            'start_date' => '2023-01-15 16:00:00',
            'end_date' => '2023-01-20 16:00:00',
            'country_code' => 'DE',
        ]);
        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Throwable
     */
    public function testCreateDelegationWithInvalidDates(): void
    {
        $this->post('/api/delegation', [
            'employee_uuid' => $this->employeeUuid,
            'start_date' => '2023-01-20 16:00:00',
            'end_date' => '2023-01-15 16:00:00',
            'country_code' => 'DE',
        ]);

        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $json);
        $this->assertEquals('App.Delegation.Domain.Exception.InvalidPeriodException', $json['error']['title']);
    }

    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Throwable
     */
    public function testCreateDelegationWithOverlappingDates(): void
    {
        $this->post('/api/delegation', [
            'employee_uuid' => $this->employeeUuid,
            'start_date' => '2023-01-15 16:00:00',
            'end_date' => '2023-01-20 16:00:00',
            'country_code' => 'DE',
        ]);

        $this->post('/api/delegation', [
            'employee_uuid' => $this->employeeUuid,
            'start_date' => '2023-01-16 16:00:00',
            'end_date' => '2023-01-21 16:00:00',
            'country_code' => 'DE',
        ]);

        $json = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $json);
        $this->assertEquals('App.Delegation.Domain.Exception.DelegationOverlappingWithAnotherException', $json['error']['title']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->employeeUuid = Uuid::uuid4()->toString();

        /** @var CommandBusInterface $bus */
        $bus = $this->getContainer()->get(CommandBusInterface::class);
        $bus->handle(new CreateEmployeeCommand($this->employeeUuid));
    }
}
