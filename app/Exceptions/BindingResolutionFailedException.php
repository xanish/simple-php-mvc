<?php

namespace App\Exceptions;

use Psr\Container\ContainerExceptionInterface;

class BindingResolutionFailedException extends \Exception implements ContainerExceptionInterface
{
}
