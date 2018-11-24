<?php


namespace AppBundle\Extension\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SnakeFilter extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('snake', array($this, 'snakeFilter')),
        );
    }

    public function snakeFilter($input)
    {
        return $this->container->get('app.util.case_manager')->snake($input);
    }
}