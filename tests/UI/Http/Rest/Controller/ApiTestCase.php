<?php

declare(strict_types=1);

namespace Tests\UI\Http\Rest\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class ApiTestCase extends WebTestCase
{
    protected ?KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_HOST', 'localhost:8080');
    }

    protected function post(string $uri, array $params): void
    {
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            $this->headers(),
            (string) \json_encode($params, JSON_THROW_ON_ERROR)
        );
    }

    protected function get(string $uri, array $parameters = []): void
    {
        $this->client->request(
            'GET',
            $uri,
            $parameters,
            [],
            $this->headers()
        );
    }

    private function headers(): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
        ];
    }

    protected function fireTerminateEvent(): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->client->getContainer()->get('event_dispatcher');

        $dispatcher->dispatch(
            new TerminateEvent(
                static::$kernel,
                Request::create('/'),
                new Response()
            ),
            KernelEvents::TERMINATE
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
