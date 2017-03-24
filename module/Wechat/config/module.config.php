<?php
namespace Wechat;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'wechat' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/wechat[/:action[/:id]]',
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
            'server' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/server[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ServerController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'function' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/function[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\FunctionController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'wechat' => __DIR__ . '/../view',
        ],
    ],
];