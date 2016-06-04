<?php

require_once 'vendor/autoload.php';
require_once 'board/Board.php';
require_once 'board/BoardGenerator.php';
require_once 'board/Encoder.php';
require_once 'helpers/HelperBoard.php';
require_once 'ship/Ship.php';
require_once 'gateways/BoardTableGateway.php';

use board\Board;
use board\BoardGenerator;
use board\Encoder;
use gateways\BoardTableGateway;

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

ini_set('display_errors', 1);
error_reporting(-1);
ErrorHandler::register();

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new \Euskadi31\Silex\Provider\ConfigServiceProvider(
    __DIR__ . '/config/config.yml'
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_pgsql',
        'host'      => $app['pgsql']['host'],
        'dbname'    => $app['pgsql']['schema'],
        'user'      => $app['pgsql']['user'],
        'password'  => $app['pgsql']['password'],
        'charset'   => 'utf8',
    ],
]);

$encoder = new Encoder();
$gateway = new BoardTableGateway($app['db'], $encoder);

$app->get('/fetch', function(Silex\Application $app) use ($gateway, $encoder) {
	$board = new Board();
	$generator = new BoardGenerator();

	$generator->generate($board);
	$boardId = $gateway->insert($board);

	return json_encode([
     	'id' => $boardId,
     	'board' => json_encode($encoder->asMatrix($board)),
     	'width' => $board->getWidth(),
     	'height' => $board->getHeight()
    ]);
});

$app->get('/fetch/{id}', function(Silex\Application $app, $id) use ($gateway, $encoder) {
	$board = $gateway->findById($id);

	if (!$board) {
		return json_encode(null);
	}

	return json_encode([
     	'id' => $board->getId(),
     	'board' => json_encode($encoder->asMatrix($board)),
     	'width' => $board->getWidth(),
     	'height' => $board->getHeight()
    ]);
});

$app->get('/', function(Silex\Application $app) {
	return $app['twig']->render('index.twig', [
     	'id' => null,
    ]);
});

$app->get('/{id}', function(Silex\Application $app, $id) use ($gateway) {
	$board = $gateway->findById($id);

	if (!$board) {
		throw new NotFoundHttpException('Board with given ID was not found');
	}

	return $app['twig']->render('index.twig', [
     	'id' => $board->getId(),
    ]);
});

$app->run();