<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Mainlayout;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\Factory\InvokableFactory;


class Module implements ConfigProviderInterface
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AuthTable::class => function($container) {
                    $tableGateway = $container->get(Model\AuthTableGateway::class);
                    return new Model\AuthTable($tableGateway);
                },

                Model\RoleTable::class => function($container) {
                    $tableGateway = $container->get(Model\RoleTableGateway::class);
                    return new Model\RoleTable($tableGateway);
                },

                Model\ModulesTable::class => function($container) {
                    $tableGateway = $container->get(Model\ModulesTableGateway::class);
                    return new Model\ModulesTable($tableGateway);
                },

                Model\MyRole::class => function($container) {
                    $tableGateway = $container->get(Model\PowergroupTableGateway::class);
                    return new Model\MyRole($tableGateway,$container);
                },


                Model\PowergroupTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Powergroup());
                    return new TableGateway('powergroup', $dbAdapter, null, $resultSetPrototype);
                },

                Model\AuthTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Auth());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },

                Model\RoleTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Role());
                    return new TableGateway('role', $dbAdapter, null, $resultSetPrototype);
                },

                Model\ModulesTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Modules());
                    return new TableGateway('module', $dbAdapter, null, $resultSetPrototype);
                },

            ],
        ];
    }

//    Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\MainlayoutController::class => function($container) {
                    return new Controller\MainlayoutController(
                        $container->get(Model\MyRole::class)
                    );
                },
                Controller\AccountController::class => function($container) {
                    return new Controller\AccountController(
                        $container->get(Model\MyRole::class),
                        $container->get(Model\RoleTable::class),
                        $container->get(Model\AuthTable::class),
                        $container
                    );
                },
            ],
        ];
    }


}
