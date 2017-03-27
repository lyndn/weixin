<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            //大小写敏感
            'article' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/article[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ArticleController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\HomeController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        // 默认情况下，MVC 注册一个 "异常策略"
        // 当一个请求的 action 引发一个异常时触发
        // 它创建一个自定义的视图模型（view model）来包裹住异常并且选择一个模板
        // 我们将设定它到 "error/index" 目录下
        //
        // 此外，我们告诉它我们要显示一个异常跟踪
        // 你很有可能默认想要关闭这个工能
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        // 另一个 MVC 默认的策略是"路由没有找到"（route not found）
        // 基本上要触发这个策略（a）没有路由能够匹配当前的请求，（b）控制器（controller）指定的路由在服务定位器（service locator）中无法找到，（c）控制器（controller）指定的路由在 DispatchableInterface 接口中无效，（d）从控制器（controller）返回的响应状态是 404
        //
        // 在这种情况下，默认使用的是 "error" 模板，就像异常策略。
        // 这里，我们将使用 "error/404" 模板（我们通过上面提到的模板映射解析器（TemplateMapResolver）来映射。）
        //
        // 你可以有选择性的注入 404 状况的原因；具体见各种各样的 `Application\:\:ERROR_*`_ 常量列表。
        // 此外，许多 404 状况来自于路由和调度过程中的异常。
        // 你可以有选择性的设定打开或关闭
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        // 模板映射解析器允许你直接地映射模板名称到一个特殊的模板。
        // 以下映射将提供首页模板（"application/index/index"）位置
        // 以及布局（"layout/layout"），错误页面（"error/index"）和
        // 404 page ("error/404"), 决定他们对应的视图（view）代码
        'template_map' => [
            // 设定站点布局模板名称
            //
            // 默认情况下，MVC 默认的渲染策略使用 "layout/layout" 来作为站点布局模板名称。
            // 这里，我们使用 "site/layout"。
            // 我们通过上面提到的模板映射解析器（TemplateMapResolver）来映射。
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'blog/article/index' => __DIR__ . '/../view/blog/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],

        // 模板路径堆栈（TemplatePathStack）是一个目录数组。
        // 这些目录根据请求的视图（view）代码以 LIFO 方式（它是一个堆栈）进行搜索
        // 这是一个进行快速开发应用程序非常好的解决方案，但是由于在产品中有很多必要的静态调用而导致潜在的声明影响了性能。
        //
        // 下面添加了一个当前模块的视图（view）目录的入口
        // 确保你的关键字在各个模块中是不同的，确保关键字不会相互覆盖 -- 或者简单的忽略关键字！

        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
