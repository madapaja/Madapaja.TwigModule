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
use BEAR\Resource\Exception\ServerErrorException as ServerError;
use BEAR\Resource\TransferInterface;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;
use Psr\Log\LoggerInterface;

final class TwigErrorPageHandler implements ErrorInterface
{
    /**
     * @var TransferInterface
     */
    private $transfer;
    /**
     * @var TwigErrorPage
     */
    private $errorPage;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(TwigErrorPage $errorPage, TransferInterface $transfer, LoggerInterface $logger)
    {
        $this->transfer = $transfer;
        $this->errorPage = $errorPage;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Exception $e, Request $request)
    {
        unset($request);
        $code = $this->isCodeExists($e) ? $e->getCode() : 503;
        if ($code >= 500) {
            $eStr = (string) $e;
            $this->logger->error(\sprintf('logref:%s %s', \crc32($eStr), $eStr));
        }
        $this->errorPage->code = $code;
        $this->errorPage->body = [
            'status' => [
                'code' => $code,
                'message' => (new Code)->statusText[$code]
            ],
            'e' => [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]
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

    private function isCodeExists(\Exception $e) : bool
    {
        if (! ($e instanceof NotFound) && ! ($e instanceof BadRequest) && ! ($e instanceof ServerError)) {
            return false;
        }

        return \array_key_exists($e->getCode(), (new Code)->statusText);
    }
}
