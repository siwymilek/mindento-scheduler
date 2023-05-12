<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Employee;

use App\Employee\Application\Command\CreateEmployee\CreateEmployeeCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\AbstractController;

final class CreateEmployeeController extends AbstractController
{
    #[Route(path: '/employee', name: 'create_employee', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $uuid = Uuid::uuid4()->toString();
        $commandRequest = new CreateEmployeeCommand($uuid);
        $this->handle($commandRequest);

        return $this->json(['id' => $uuid], 201);
    }
}
