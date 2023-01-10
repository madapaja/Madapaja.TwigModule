<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
use BEAR\Resource\Exception\BadRequestException as BadRequest;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;
use BEAR\Sunday\Extension\Transfer\TransferInterface;
use Psr\Log\LoggerInterface;

final class TwigErrorHandler implements ErrorInterface
{
    public function __construct(private TwigErrorPage $errorPage, private TransferInterface $transfer, private LoggerInterface $logger)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Exception $e, Request $request)
    {
        unset($request);
        $code = $this->getCode($e);
        $eStr = (string) $e;
        $logRef = \crc32($eStr);
        if ($code >= 500) {
            $this->logger->error(\sprintf('logref:%s %s', $logRef, $eStr));
        }
        $this->errorPage->code = $code;
        $this->errorPage->body = [
            'status' => [
                'code' => $code,
                'message' => (new Code)->statusText[$code]
            ],
            'e' => [
                'code' => $e->getCode(),
                'class' => $e::class,
                'message' => $e->getMessage()
            ],
            'logref' => (string) $logRef
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

    private function getCode(\Exception $e) : int
    {
        if ($e instanceof NotFound || $e instanceof BadRequest) {
            return $e->getCode();
        }

        return 503;
    }
}
