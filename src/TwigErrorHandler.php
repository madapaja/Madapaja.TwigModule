<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Package\Provide\Error\LogRef;
use BEAR\Resource\Code;
use BEAR\Resource\Exception\BadRequestException as BadRequest;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;
use BEAR\Sunday\Extension\Transfer\TransferInterface;

final class TwigErrorHandler implements ErrorInterface
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
     * @var AbstractAppMeta
     */
    private $appMeta;

    public function __construct(TwigErrorPage $errorPage, TransferInterface $transfer, AbstractAppMeta $appMeta)
    {
        $this->transfer = $transfer;
        $this->errorPage = $errorPage;
        $this->appMeta = $appMeta;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Exception $e, Request $request)
    {
        unset($request);
        $code = $this->getCode($e);
        $logRef = \hash('crc32b', (string) $e);
        if ($code >= 500) {
            \error_log($logRef . ':' . (string) $e);
        }
        $this->errorPage->code = $code;
        $this->errorPage->body = [
            'status' => [
                'code' => $code,
                'message' => (new Code)->statusText[$code]
            ],
            'e' => [
                'code' => $e->getCode(),
                'class' => get_class($e),
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
