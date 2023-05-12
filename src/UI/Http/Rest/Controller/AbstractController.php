<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

abstract class AbstractController extends BaseController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    protected function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->container->has('serializer')) {
            $json = $this->container->get('serializer')->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                'groups' => ['default'],
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }

    protected function ask(QueryInterface $query): mixed
    {
        return $this->queryBus->ask($query);
    }

    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
