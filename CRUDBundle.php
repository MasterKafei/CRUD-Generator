<?php

namespace Dyosis\CRUDBundle;

use Dyosis\CRUDBundle\DependencyInjection\CRUDExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Jean Marius
 */
class CRUDBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CRUDExtension();
    }
}
