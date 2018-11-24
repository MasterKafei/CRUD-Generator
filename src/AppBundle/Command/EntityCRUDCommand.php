<?php


namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityCRUDCommand extends ContainerAwareCommand
{

    const SKIP_CREATION_OPTION_KEY = 'skip-creation';
    const SKIP_DELETION_OPTION_KEY = 'skip-deletion';
    const SKIP_EDITION_OPTION_KEY = 'skip-edition';
    const SKIP_LISTING_OPTION_KEY = 'skip-listing';
    const SKIP_SHOWING_OPTION_KEY = 'skip-showing';
    const IS_ADMIN_OPTION_KEY = 'is-admin';

    const ENTITY_NAME_ARGUMENT_KEY = 'entity-name';

    protected function configure()
    {
        $this
            ->setName('dyosis:entity:crud')
            ->setDescription('Create every CRUD action for given entity')
            ->addArgument(self::ENTITY_NAME_ARGUMENT_KEY, InputArgument::REQUIRED, 'The entity whose gross you want to generate')
            ->addOption(self::SKIP_CREATION_OPTION_KEY, 'sc',InputOption::VALUE_NONE, 'Skip creation action')
            ->addOption(self::SKIP_DELETION_OPTION_KEY, 'sd',InputOption::VALUE_NONE, 'Skip deletion action')
            ->addOption(self::SKIP_EDITION_OPTION_KEY, 'se',InputOption::VALUE_NONE, 'Skip edition action')
            ->addOption(self::SKIP_LISTING_OPTION_KEY, 'sl',InputOption::VALUE_NONE, 'Skip listing action')
            ->addOption(self::SKIP_SHOWING_OPTION_KEY, 'ss', InputOption::VALUE_NONE, 'Skip showing action')
            ->addOption(self::IS_ADMIN_OPTION_KEY, 'ia', InputOption::VALUE_NONE, 'Is an administration CRUD');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('entity-name');
        $options = $this->applyOptions($input->getOptions());

        /*$this->getContainer()->get('app.business.routing')->addEntityRoutes($entityName, $options);
        $this->getContainer()->get('app.business.controller')->addEntityController($entityName, $options);*/
        $this->getContainer()->get('app.business.view')->addEntityView($entityName, $options);
    }

    protected function getDefaults(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults(array(
            self::SKIP_CREATION_OPTION_KEY => false,
            self::SKIP_EDITION_OPTION_KEY => false,
            self::SKIP_DELETION_OPTION_KEY => false,
            self::SKIP_LISTING_OPTION_KEY => false,
            self::SKIP_SHOWING_OPTION_KEY => false,
            self::IS_ADMIN_OPTION_KEY => false,
        ));
    }

    protected function applyOptions(array $options)
    {
        $optionResolver = new OptionsResolver();
        $this->getDefaults($optionResolver);
        $finalOptions = array();
        foreach ($options as $key => $option) {
            if ($optionResolver->hasDefault($key)) {
                $finalOptions[$key] = $option;
            }
        }

        return $optionResolver->resolve($finalOptions);
    }
}