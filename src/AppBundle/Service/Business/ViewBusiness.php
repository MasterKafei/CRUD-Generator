<?php

namespace AppBundle\Service\Business;

use AppBundle\Command\EntityCRUDCommand;
use AppBundle\Service\Util\AbstractContainerAware;
use Symfony\Component\Filesystem\Filesystem;

class ViewBusiness extends AbstractContainerAware
{
    const FILE_EXTENSION = 'twig';

    const CREATION_MODE_KEY = 'Creation';
    const EDITION_MODE_KEY = 'Edition';
    const LISTING_MODE_KEY = 'Listing';
    const SHOWING_MODE_KEY = 'Showing';

    private static $optionsMode = [
        self::CREATION_MODE_KEY => EntityCRUDCommand::SKIP_CREATION_OPTION_KEY,
        self::EDITION_MODE_KEY => EntityCRUDCommand::SKIP_EDITION_OPTION_KEY,
        self::LISTING_MODE_KEY => EntityCRUDCommand::SKIP_LISTING_OPTION_KEY,
        self::SHOWING_MODE_KEY => EntityCRUDCommand::SKIP_SHOWING_OPTION_KEY,
    ];

    private static $shortActions = [
        self::CREATION_MODE_KEY => 'create',
        self::EDITION_MODE_KEY => 'edit',
        self::LISTING_MODE_KEY => 'list',
        self::SHOWING_MODE_KEY => 'show',
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

    public function addEntityView($entityName, $options)
    {
        $this->options = $options;
        $this->addCreationView($entityName);
        $this->addEditionView($entityName);
        $this->addListingView($entityName);
        $this->addShowingView($entityName);
    }

    public function addCreationView($entityName)
    {
        $this->addGenericView($entityName, self::CREATION_MODE_KEY, '@Page/Views/creation.html.twig.twig');
    }

    public function addEditionView($entityName)
    {
        $this->addGenericView($entityName, self::EDITION_MODE_KEY, '@Page/Views/edition.html.twig.twig');
    }

    public function addListingView($entityName)
    {
        $this->addGenericView($entityName, self::LISTING_MODE_KEY, '@Page/Views/listing.html.twig.twig');
    }

    public function addShowingView($entityName)
    {
        $this->addGenericView($entityName, self::SHOWING_MODE_KEY, '@Page/Views/showing.html.twig.twig');
    }

    public function addGenericView($entityName, $mode, $template)
    {
        if ($this->options[self::$optionsMode[$mode]]) {
            return;
        }

        $file = $this->getViewFolder() . $entityName . '/' . $mode . '/' . self::$shortActions[$mode] . '_' . $this->container->get('app.util.case_manager')->snake($entityName) . '.html.' . self::FILE_EXTENSION;
        $content = $this->container->get('twig')->render($template, array(
            'is_admin' => $this->options[EntityCRUDCommand::IS_ADMIN_OPTION_KEY],
            'entity_name' => $entityName,
        ));
        $this->addContent($file, $content);
    }
    private function addContent($path, $content)
    {
        $this->fileSystem->appendToFile($path, $content);
    }

    private function getViewFolder($bundleName = 'AppBundle')
    {
        $isAdmin = $this->options[EntityCRUDCommand::IS_ADMIN_OPTION_KEY];
        return $folder = $this->container->getParameter('kernel.root_dir') . '/../src/' . $bundleName . '/Resources/views/Page/' . ($isAdmin ? 'Admin/' : '');
    }
}
