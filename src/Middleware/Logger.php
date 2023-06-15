<?php

namespace Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

class Logger implements MiddlewareInterface
{
    const ERR_LOG = 'ERROR: unable to log entry';

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $text = sprintf(
            '%20s : %10s : %16s : %s' . PHP_EOL,
            date('Y-m-d H:i:s'),
            $request->getUri()->getPath(),
            ($request->getHeaders()['accept'][0] ?? 'N/A'),
            ($request->getServerParams()['REMOTE_ADDR']) ?? 'Command Line'
        );
        if (file_put_contents(LOG_FILE, $text, FILE_APPEND)) {
            return $handler->handle($request)->withStatus(202);
        } else {
            $msg = ['status' => 'fail', 'message' => self::ERR_LOG];
            return new JsonResponse($msg, 500);
        }
    }
}
