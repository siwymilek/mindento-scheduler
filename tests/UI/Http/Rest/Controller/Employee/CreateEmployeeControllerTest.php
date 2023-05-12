<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller\Employee;

use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\ApiTestCase;

class CreateEmployeeControllerTest extends ApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Throwable
     */
    public function testCreateEmployeeShouldReturn201StatusCode(): void
    {
        $this->post('/api/employee', []);
        $this->assertSame(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}
