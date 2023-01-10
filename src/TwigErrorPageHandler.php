<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
use BEAR\Resource\Exception\BadRequestException as BadRequest;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use BEAR\Resource\Exception\ServerErrorException as ServerError;
use BEAR\Resource\TransferInterface;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_key_exists;
use function crc32;
use function sprintf;

final class TwigErrorPageHandler implements ErrorInterface
{
    public function __construct(
        private TwigErrorPage $errorPage,
        private TransferInterface $transfer,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Throwable $e, Request $request)
    {
        unset($request);
        $code = $this->isCodeExists($e) ? $e->getCode() : 503;
        if ($code >= 500) {
            $eStr = (string) $e;
            $this->logger->error(sprintf('logref:%s %s', crc32($eStr), $eStr));
        }

        $this->errorPage->code = $code;
        $this->errorPage->body = [
            'status' => [
                'code' => $code,
                'message' => (new Code())->statusText[$code],
            ],
            'e' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ],
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function transfer()
    {
        ($this->transfer)($this->errorPage, []);
    }

    private function isCodeExists(Throwable $e): bool
    {
        if (! ($e instanceof NotFound) && ! ($e instanceof BadRequest) && ! ($e instanceof ServerError)) {
            return false;
        }

        return array_key_exists($e->getCode(), (new Code())->statusText);
    }
}
