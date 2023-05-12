<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Employee;

use App\Delegation\Application\Query\GetEmployeeDelegations\GetEmployeeDelegationsQuery;
use App\Shared\Application\Query\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\AbstractController;

final class GetDelegationsController extends AbstractController
{
    #[Route(path: '/employee/{id}/delegations', name: 'employee_delegations', methods: ['GET'])]
    public function delegations(Request $request): Response
    {
        $query = new GetEmployeeDelegationsQuery($request->get('id'));
        /** @var Collection $collection */
        $collection = $this->ask($query);

        return $this->json($collection->data);
    }
}
