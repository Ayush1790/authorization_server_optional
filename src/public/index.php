<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Router;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require_once BASE_PATH . '/vendor/autoload.php';

$container = new FactoryDefault();

$container->set(
    'router',
    function () {
        $router = new Router();

        $router->setDefaultModule('frontend');
        $router->add(
            '/admin/:controller/:action/:params',
            [
                'module' => 'admin',
                'controller' => 1,
                'action'     => 2,
                'params'     => 3,
            ]
        );


        return $router;
    }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client('mongodb+srv://myAtlasDBUser:myatlas-001@myatlas' .
            'clusteredu.aocinmp.mongodb.net/?retryWrites=true&w=majority');
        return $mongo->store;
    },
    true
);

$application = new Application($container);

$application->registerModules(
    [
        'admin' => [
            'className' => \Multi\Admin\Module::class,
            'path'      => '../app/admin/Module.php',
        ],
        'frontend'  => [
            'className' => \Multi\Frontend\Module::class,
            'path'      => '../app/frontend/Module.php',
        ]
    ]
);

try {
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo $e->getMessage();
}
