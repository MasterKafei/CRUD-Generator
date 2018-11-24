<?php


namespace AppBundle\Service\Util;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AbstractContainerAware implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}