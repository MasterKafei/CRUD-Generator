<?php


namespace AppBundle\Service\Business;

use AppBundle\Service\Util\AbstractContainerAware;
use Symfony\Component\Filesystem\Filesystem;

class RoutingBusiness extends AbstractContainerAware
{
    const EXTENSION_FILE = 'yml';
    const CREATION_ROUTE_TYPE = 'creation';
    const DELETION_ROUTE_TYPE = 'deletion';
    const EDITION_ROUTE_TYPE = 'edition';
    const LISTING_ROUTE_TYPE = 'listing';
    const SHOWING_ROUTE_TYPE = 'showing';

    private static $short_actions = array(
        self::CREATION_ROUTE_TYPE => array(
            'short_action' => 'create',
            'id' => false,
        ),
        self::DELETION_ROUTE_TYPE => array(
            'short_action' => 'delete',
            'id' => true,
        ),
        self::EDITION_ROUTE_TYPE => array(
            'short_action' => 'edit',
            'id' => true,
        ),
        self::LISTING_ROUTE_TYPE => array(
            'short_action' => 'list',
            'id' => false,
        ),
        self::SHOWING_ROUTE_TYPE => array(
            'short_action' => 'show',
            'id' => true,
        ),
    );

    private $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    /**
     * Create all entity routes.
     *
     * @param $entityName
     */
    public function addEntityRoutes($entityName)
    {
        $this->addActionsRoute($entityName);
        $this->addAllRoute($entityName);
        $this->addEntityRoute($entityName);
    }

    private function addActionsRoute($entityName)
    {
        $folder = $this->getEntityRouteFolder($entityName);
        foreach (self::$short_actions as $action => $short_action) {
            $content = $this->generateActionRoute($entityName, $action);
            $this->addContent($folder . '/' . $action . '.' . self::EXTENSION_FILE, $content);
        }
    }

    private function addEntityRoute($entityName)
    {
        $folder = $this->getRouteFolder();
        $this->addContent($folder . '/all.yml', $this->generateEntityRoute($entityName));
    }

    private function addAllRoute($entityName)
    {
        $folder = $this->getEntityRouteFolder($entityName);
        $this->addContent($folder . '/all.yml', $this->generateAllRoute($entityName));
    }

    private function generateActionRoute($entityName, $action, $bundleName = 'AppBundle')
    {
        return $this->container->get('twig')->render('@Page/Routing/action.html.twig', array(
            'action' => $action,
            'entity_name' => $entityName,
            'bundle_name' => $bundleName,
            'short_action' => self::$short_actions[$action]['short_action'],
            'idParameter' => self::$short_actions[$action]['id'],
        ));
    }

    private function generateEntityRoute($entityName)
    {
        return $this->container->get('twig')->render('@Page/Routing/entity.html.twig', array(
            'entity_name' => $entityName,
        ));
    }

    private function generateAllRoute($entityName)
    {
        return $this->container->get('twig')->render('@Page/Routing/all.html.twig', array(
            'actions' => array_keys(self::$short_actions),
            'entity_name' => $entityName,
        ));
    }

    private function addContent($path, $content)
    {
        $this->fileSystem->appendToFile($path, $content);
    }

    private function getEntityRouteFolder($entityName, $bundleName = 'AppBundle')
    {
        $folder = $this->container->getParameter('kernel.root_dir') . '/../src/' . $bundleName . '/Resources/routing/';
        $entityNameSnakeCase = $this->container->get('app.util.case_manager')->snake($entityName);

        return $folder . $entityNameSnakeCase;
    }

    private function getRouteFolder($bundleName = 'AppBundle')
    {
        return $folder = $this->container->getParameter('kernel.root_dir') . '/../src/' . $bundleName . '/Resources/routing';
    }
}