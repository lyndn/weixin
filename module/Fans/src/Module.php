<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 16:44
 */
namespace Fans;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface{

    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }

    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\FansTable::class => function($container) {
                    $tableGateway = $container->get(Model\FansTableGateway::class);
                    return new Model\FansTable($tableGateway);
                },
                Model\FansTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Fans());
                    return new TableGateway('fans_main', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    //控制器映射
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(Model\FansTable::class),
                        $container->get(\Mainlayout\Model\MyRole::class),
                        $container->get(\Wechat\Model\WechatTable::class),
                        $container
                    );
                },
            ],
        ];
    }

}