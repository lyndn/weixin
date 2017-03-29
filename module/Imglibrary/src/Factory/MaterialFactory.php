<?php

/**
 *
 * PHP Version ～7.1
 * @package   MaterialFactory.php
 * @author    yanchao <yanchao563@yahoo.com>
 * @time      2017/03/29 10:29
 * @copyright 2017
 * @license   www.guanlunsm.com license
 * @link      yanchao563@yahoo.com
 */
namespace Imglibrary\Factory;
use Imglibrary\Status\Work;
use Imglibrary\Controller\MaterialController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\Adapter;

class MaterialFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // TODO: Implement __invoke() method.
        $controllerPluginManager = $container;
        $serviceManager = $controllerPluginManager->get('ServiceManager');
        $work = $serviceManager->get(Work::class);
        return new MaterialController($work);
    }
}