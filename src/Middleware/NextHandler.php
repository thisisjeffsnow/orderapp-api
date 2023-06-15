<?php

namespace Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Laminas\Diactoros\Response\JsonResponse;

class NextHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $resp = ['status' => 'next'];
        return (new JsonResponse($resp))->withStatus(202);
    }
}
