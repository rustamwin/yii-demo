<?php

use App\Factory\CycleOrmFactory;
use Cycle\ORM\ORMInterface;
use hiqdev\composer\config\Builder;
use Yiisoft\Di\Container;
use Yiisoft\Yii\Web\Application;
use Yiisoft\Yii\Web\ServerRequestFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Don't do it in production, assembling takes it's time
Builder::rebuild();

$container = new Container(require Builder::path('web'));
$container->set(ORMInterface::class, new CycleOrmFactory());

$request = $container->get(ServerRequestFactory::class)->createFromGlobals();
$container->get(Application::class)->handle($request);
