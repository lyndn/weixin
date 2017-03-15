<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mainlayout;

use Zend\Json\Server\Smd\Service;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // Add this section:

    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\MainlayoutController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/:action]',
                    'defaults' => [
                        'controller' => Controller\MainlayoutController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'aliases' => [
            Model\PostRepositoryInterface::class => Model\PostRepository::class,
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\MainlayoutController::class => Factory\MainlayoutControllerFactory::class,

            //Controller\MainlayoutController::class => InvokableFactory::class,
        ],

    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'mainlayout/mainlayout/index' => __DIR__ . '/../view/mainlayout/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];