<?php

namespace Madapaja\TwigModule;

use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class EmptyInterceptor implements MethodInterceptor
{
    public function invoke(MethodInvocation $invocation)
    {
        return $invocation->proceed();
    }
}
