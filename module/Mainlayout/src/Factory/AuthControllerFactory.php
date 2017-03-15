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

namespace Mainlayout\Factory;

use Mainlayout\Controller\AuthController;
use Mainlayout\Model\AuthInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class AuthControllerFactory implements FactoryInterface
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
        $auth = $serviceManager->get(AuthInterface::class);
        return new AuthController($auth);
    }

}