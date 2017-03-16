<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mainlayout;

// Add these import statements:
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

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
                Model\AuthTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Auth());
                    return new TableGateway('auth', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    // Add this method:
//    public function getControllerConfig()
//    {
//        return [
//            'factories' => [
//                Controller\AuthController::class => function($container) {
//                    return new Controller\AlbumController(
//                        $container->get(Model\AuthTable::class)
//                    );
//                },
//            ],
//        ];
//    }




}
