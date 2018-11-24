<?php


namespace AppBundle\Service\Business;

use AppBundle\Command\EntityCRUDCommand;
use AppBundle\Service\Util\AbstractContainerAware;
use Symfony\Component\Filesystem\Filesystem;

class ControllerBusiness extends AbstractContainerAware
{
    const EXTENSION_FILE = 'php';

    const CREATION_MODE_KEY = 'Creation';
    const DELETION_MODE_KEY = 'Deletion';
    const EDITION_MODE_KEY = 'Edition';
    const LISTING_MODE_KEY = 'Listing';
    const SHOWING_MODE_KEY = 'Showing';

    private static $optionsMode = [
        self::CREATION_MODE_KEY => EntityCRUDCommand::SKIP_CREATION_OPTION_KEY,
        self::DELETION_MODE_KEY => EntityCRUDCommand::SKIP_DELETION_OPTION_KEY,
        self::EDITION_MODE_KEY => EntityCRUDCommand::SKIP_EDITION_OPTION_KEY,
        self::LISTING_MODE_KEY => EntityCRUDCommand::SKIP_LISTING_OPTION_KEY,
        self::SHOWING_MODE_KEY => EntityCRUDCommand::SKIP_SHOWING_OPTION_KEY,
    ];

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var array
     */
    private $options;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    public function addEntityController($entityName, $options)
    {
        $this->options = $options;

        $this->addCreationController($entityName);
        $this->addDeletionController($entityName);
        $this->addEditionController($entityName);
        $this->addListingController($entityName);
        $this->addShowingController($entityName);
    }

    private function addCreationController($entityName)
    {
        $this->addGenericController($entityName, self::CREATION_MODE_KEY, '@Page/Controller/creation.php.twig');
    }

    private function addDeletionController($entityName)
    {
        $this->addGenericController($entityName, self::DELETION_MODE_KEY, '@Page/Controller/deletion.php.twig');
    }

    private function addEditionController($entityName)
    {
        $this->addGenericController($entityName, self::EDITION_MODE_KEY, '@Page/Controller/edition.php.twig');
    }

    private function addListingController($entityName)
    {
        $this->addGenericController($entityName, self::LISTING_MODE_KEY, '@Page/Controller/listing.php.twig');
    }

    private function addShowingController($entityName)
    {
        $this->addGenericController($entityName, self::SHOWING_MODE_KEY, '@Page/Controller/showing.php.twig');
    }

    private function addGenericController($entityName, $mode, $template)
    {
        $folder = $this->getControllerFolder() . $entityName;
        $content = $this->container->get('twig')->render($template, array(
            'entity_name' => $entityName,
            'is_admin' => $this->options[EntityCRUDCommand::IS_ADMIN_OPTION_KEY],
        ));

        $this->addContent($folder . '/' . $mode . 'Controller.' . self::EXTENSION_FILE, $content);
    }

    private function addContent($path, $content)
    {
        $this->fileSystem->appendToFile($path, $content);
    }

    private function getControllerFolder($bundleName = 'AppBundle')
    {
        $isAdmin = $this->options[EntityCRUDCommand::IS_ADMIN_OPTION_KEY];
        return $folder = $this->container->getParameter('kernel.root_dir') . '/../src/' . $bundleName . '/Controller/' . ($isAdmin ? 'Admin/' : '');
    }
}