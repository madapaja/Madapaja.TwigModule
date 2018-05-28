<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
use BEAR\Resource\RenderInterface;
use BEAR\Resource\TransferInterface;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use Madapaja\TwigModule\Annotation\TwigLoader;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

use BEAR\Resource\Exception\BadRequestException as BadRequest;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use BEAR\Resource\Exception\ServerErrorException as ServerError;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;


final class TwigErrorPageHandler implements ErrorInterface
{
    /**
     * @var string
     */
    const CONTENT_TYPE = 'application/vnd.error+json';

    /**
     * @var TransferInterface
     */
    private $transfer;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var int
     */
    private $code = 500;

    /**
     * @var array
     */
    private $status = ['message' => '500 Server Error'];

    /**
     * @var string
     */
    private $templateDir;

    public function __construct(string $templateDir, Twig_Environment $twig, TransferInterface $transfer)
    {
        $this->transfer = $transfer;
        $this->twig = $twig;
        $this->templateDir = $templateDir;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Exception $e, Request $request)
    {
        if ($this->isCodeExists($e)) {
            $this->code = $e->getCode();
            $this->status =  ['message' => (new Code)->statusText[$this->code]];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function transfer()
    {
        $templateFile = sprintf('%s/error.%s.html.twg', $this->code, $this->templateDir);

        $templateFile = file_exists($templateFile) ? $templateFile : $this->templateDir . '/error.html.twig';
        $this->twig->load($templateFile);
        $this->errorPage->headers['content-type'] = self::CONTENT_TYPE;
        $this->transfer->__invoke($this->errorPage, []);
    }

    private function isCodeExists(\Exception $e) : bool
    {
        if (! ($e instanceof NotFound) && ! ($e instanceof BadRequest) && ! ($e instanceof ServerError)) {
            return false;
        }

        return \array_key_exists($e->getCode(), (new Code)->statusText);
    }
}
