<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mainlayout;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

//    public function getServiceConfig()
//    {
//
//        return [
//            'factories' => [
//                Service\CurrencyConverter::class => function($container) {
//                    return new Service\CurrencyConverter();
//                },
//
//
//            ]
//        ];
//    }
//
//    public function getControllerConfig()
//    {
//        return [
//            'factories' => [
//                Controller\MainlayoutController::class => function($container) {
//                    return new Controller\MainlayoutController(
//                        $container->get(Service\CurrencyConverter::class)
//                    );
//                },
//            ]
//        ];
//    }


}
