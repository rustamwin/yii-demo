<?php
/**
 * @var Goridge\RelayInterface $relay
 */

use App\Factory\CycleOrmFactory;
use Cycle\ORM\ORMInterface;
use Spiral\Goridge;
use Spiral\RoadRunner;
use Yiisoft\Di\Container;
use Yiisoft\Yii\Web\Application;
use hiqdev\composer\config\Builder;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(\STDIN, \STDOUT));
$psr7 = new RoadRunner\PSR7Client($worker);

// Don't do it in production, assembling takes it's time
Builder::rebuild();

$container = new Container(require Builder::path('web'));

$container->set(Spiral\RoadRunner\PSR7Client::class, $psr7);
$container->set(\Yiisoft\Yii\Web\Emitter\EmitterInterface::class, \App\Emitter\RoadrunnerEmitter::class);
//$container->set(ORMInterface::class, new CycleOrmFactory());
$container->set(ORMInterface::class, (new CycleOrmFactory)->__invoke($container));
error_log("asfsas");
while ($request = $psr7->acceptRequest()) {
    try {
        $container->get(Application::class)->handle($request);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
