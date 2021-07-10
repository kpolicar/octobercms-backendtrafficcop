<?php namespace Kpolicar\BackendTrafficCop\Exceptions;


use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ModelHasChangedException extends \Exception implements HttpExceptionInterface
{

    public function getStatusCode()
    {
        return 409;
    }

    public function getHeaders()
    {
    }
}
