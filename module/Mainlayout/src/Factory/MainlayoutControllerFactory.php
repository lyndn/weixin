<?php
/**
 *
 * PHP Version ～7.1
 * @package   MainlayoutControllerFactory.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/21 15:05
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */

namespace Mainlayout\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\Adapter;
use Mainlayout\Controller\AuthController;
use Mainlayout\Model\MyRole;


class MainlayoutControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return AuthController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controllerPluginManager = $container;
        $serviceManager = $controllerPluginManager->get('ServiceManager');
        $adapter = $serviceManager->get(Adapter::class);
        $myrole = $serviceManager->get(MyRole::class);
        return new MainlayoutController($myrole);
    }
}