<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Imglibrary;

use Prophecy\Comparator\Factory;
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
                Status\WorkList::class => InvokableFactory::class,
                Status\WorkAdd::class => InvokableFactory::class,
            ],
        ];
    }

    //    Add this method:
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\MaterialController::class => \Imglibrary\Factory\MaterialFactory::class,
            ],
        ];
    }


}