<?php
/**
 *
 * PHP Version ～7.1
 * @package   LazyControllerFactory.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/15 13:21
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

// In module/YourModule/src/Controller/YourControllerFactory.php:
namespace Mainlayout\Factory;

use Interop\Container\ContainerInterface;

use Mainlayout\Model\PostRepositoryInterface;

use Mainlayout\Model\AuthInterface;

use Zend\ServiceManager\Factory\FactoryInterface;

use Mainlayout\Controller\MainlayoutController;


class MainlayoutControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return MainlayoutController
     */
//    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
//    {
//
//          $controllerPluginManager = $container;
//          $serviceManager          = $controllerPluginManager->get('ServiceManager');
////
////        // Requires zf-campus/zf-oauth2
////
//echo 1;die;
//        $server   = $serviceManager->get('Mainlayout\Form\loginForm');
//
//
//        return new MainlayoutController($server);
//
//        // Create an instance of the class.
//
//    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controllerPluginManager = $container;

        $serviceManager = $controllerPluginManager->get('ServiceManager');

        $server   = $serviceManager->get(PostRepositoryInterface::class);

        $auth = $serviceManager->get(AuthInterface::class);

        return new MainlayoutController($server,$auth);
    }



}