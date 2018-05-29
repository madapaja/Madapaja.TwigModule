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

    public function __construct(TwigErrorPage $errorPage, TransferInterface $transfer)
    {
        $this->transfer = $transfer;
        $this->errorPage = $errorPage;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Exception $e, Request $request)
    {
        unset($request);
        $code = $e->getCode();
        if ($this->isCodeExists($e)) {
            $this->errorPage->code = $code;
            $this->errorPage->body = [
                'status' => [
                    'code' => $code,
                    'message' => (new Code)->statusText[$code]
                ]
            ];
        }

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
