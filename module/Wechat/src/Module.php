<?php
namespace Wechat;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Mainlayout\Model\MyRole;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    // Add this method:
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\WechatTable::class => function($container) {
                    $tableGateway = $container->get(Model\WechatTableGateway::class);
                    return new Model\WechatTable($tableGateway);
                },
                Model\WxmenuTable::class => function($container) {
                    $tableGateway = $container->get(Model\WxmenuTableGateway::class);
                    return new Model\WxmenuTable($tableGateway);
                },


                Model\WechatTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Wechat());
                    return new TableGateway('wxuser', $dbAdapter, null, $resultSetPrototype);
                },

                Model\WxmenuTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Wxmenu());
                    return new TableGateway('wxmenu', $dbAdapter, null, $resultSetPrototype);
                },

            ],
        ];
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(Model\WechatTable::class),
                        $container->get(\Mainlayout\Model\MyRole::class)
                    );
                },
                Controller\ServerController::class=>function($container){
                    return new Controller\ServerController(
                        $container->get(Model\WechatTable::class)
                    );
                },
                Controller\FunctionController::class=>function($container){
                    return new Controller\FunctionController(
                        $container->get(Model\WxmenuTable::class),
                        $container->get(Model\WechatTable::class),
                        $container->get(\Mainlayout\Model\MyRole::class),
                        $container
                    );
                }
            ],
        ];
    }


}