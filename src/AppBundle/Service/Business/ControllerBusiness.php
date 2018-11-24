<?php


namespace AppBundle\Service\Business;


use AppBundle\Service\Util\AbstractContainerAware;
use Symfony\Component\Filesystem\Filesystem;

class ControllerBusiness extends AbstractContainerAware
{
    private $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    public function addEntityController($entityName)
    {
        $this->addCreationController($entityName);
        $this->addDeletionController($entityName);
        $this->addEditionController($entityName);
        $this->addListingController($entityName);
        $this->addShowingController($entityName);
    }

    private function addCreationController($entityName)
    {
        $this->addGenericController($entityName, 'Creation', '@Page/Controller/creation.php.twig');
    }

    private function addDeletionController($entityName)
    {
        $this->addGenericController($entityName, 'Deletion', '@Page/Controller/deletion.php.twig');
    }

    private function addEditionController($entityName)
    {
        $this->addGenericController($entityName, 'Edition', '@Page/Controller/edition.php.twig');
    }

    private function addListingController($entityName)
    {
        $this->addGenericController($entityName, 'Listing', '@Page/Controller/listing.php.twig');
    }

    private function addShowingController($entityName)
    {
        $this->addGenericController($entityName, 'Showing', '@Page/Controller/showing.php.twig');
    }

    private function addGenericController($entityName, $mode, $template)
    {
        $folder = $this->getControllerFolder() . $entityName;
        $content = $this->container->get('twig')->render($template, array(
            'entity_name' => $entityName,
        ));
        $this->addContent($folder . '/' . $mode . 'Controller.php', $content);
    }

    private function addContent($path, $content)
    {
        $this->fileSystem->appendToFile($path, $content);
    }

    private function getControllerFolder($bundleName = 'AppBundle')
    {
        return $folder = $this->container->getParameter('kernel.root_dir') . '/../src/' . $bundleName . '/Controller/';
    }
}