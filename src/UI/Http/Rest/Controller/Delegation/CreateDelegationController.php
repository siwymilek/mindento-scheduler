<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Delegation;

use App\Delegation\Application\Command\CreateDelegation\CreateDelegationCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\AbstractController;

final class CreateDelegationController extends AbstractController
{
    #[Route(path: '/delegation', name: 'create_delegation', methods: ['post'])]
    public function __invoke(Request $request): Response
    {
        $uuid = Uuid::uuid4()->toString();

        $commandRequest = new CreateDelegationCommand(
            $uuid,
            $request->request->get('employee_uuid'),
            $request->request->get('start_date'),
            $request->request->get('end_date'),
            $request->request->get('country_code'),
        );
        $this->handle($commandRequest);

        return $this->json(['id' => $uuid], 201);
    }
}
