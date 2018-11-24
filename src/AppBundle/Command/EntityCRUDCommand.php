<?php


namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCRUDCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dyosis:entity:crud')
            ->setDescription('Create every CRUD action for given entity')
            ->addArgument('entity-name', InputArgument::REQUIRED, 'The entity whose gross you want to generate')
            ->addArgument('skip-creation', InputArgument::OPTIONAL, 'Skip creation action')
            ->addArgument('skip-deletion',InputArgument::OPTIONAL,'Skip deletion action')
            ->addArgument('skip-edition', InputArgument::OPTIONAL, 'Skip edition action')
            ->addArgument('skip-listing', InputArgument::OPTIONAL, 'Skip listing action')
            ->addArgument('skip-showing', InputArgument::OPTIONAL, 'Skip showing action')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('entity-name');

        $this->getContainer()->get('app.business.routing')->addEntityRoutes($entityName);
        $this->getContainer()->get('app.business.controller')->addEntityController($entityName);
    }
}