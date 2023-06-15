<?php

namespace Middleware;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Laminas\Diactoros\Response\JsonResponse;

class InsertHandler implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $method = $request->getMethod();
        if (strtoupper($method) !== 'POST') return $handler->handle($request);
        $data = $request->getParsedBody();
        $msg = [];
        if (DbService::insert($data, $msg)) {
            $code = 201;
            $resp = ['status' => 'success', 'message' => $msg];
        } else {
            $code = 400;
            $resp = ['status' => 'failed', 'message' => $msg];
        }
        return (new JsonResponse($resp))->withStatus($code);
    }
}
