<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/28
 * Time: 16:42
 */
namespace Fans;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'fans' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/fans[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
             __DIR__ . '/../view',
        ],
    ],
];