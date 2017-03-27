<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\InvokableFactory;

class Module
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
                Model\ArticleTable::class => function($container) {
                    $tableGateway = $container->get(Model\ArticleTableGateway::class);
                    return new Model\ArticleTable($tableGateway);
                },
                Model\ArticleTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Article());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
                Status\Work::class => InvokableFactory::class,
            ],
        ];
    }

    // Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ArticleController::class => function($container) {
                    return new Controller\ArticleController(
                        $container->get(Model\ArticleTable::class),
                        $container->get(Status\Work::class)
                    );
                },
            ],
        ];
    }
}
