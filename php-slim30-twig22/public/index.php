<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../lib/db_connect.php';

$app = new \Slim\App();

// Fetch DI Container
$container = $app->getContainer();

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views', [
        'cache' => false //__DIR__.'/../resources/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};



#$loader = new Twig_Loader_Filesystem(__DIR__.'/../resources/views');
#$twig = new Twig_Environment($loader, array(
#    'cache' => __DIR__.'/../resources/cache',
#));

// Render Twig template in route
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'hello.html', [
       'name' => $args['name']
    ]);
})->setName('profile');



//$app->get('/', function (Request $request, Response $response) {
//    $name = $request->getAttribute('name');
//    $response->getBody()->write("DBService Initiated");
//    return $response;
//});



//$app->get('/write', function (Request $request, Response $response) {
    /*
    $mongodb = \DBService\connect('mongo');
    $bulk = new MongoDB\Driver\BulkWrite();
    $document = [
        'UserID' => 101,
        'NodeID' => 102,
        'CounterID' => 103,
        'AuthToken' => '3e474b3f864840ea741ab574eae21b26',
        'DeviceID' => 104,
        'ExpirationDate' => '2100-01-01 12:34:56',
        'AuthKey' => 's0mRIdlKvI',
        'Signature' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
        'ErrorCode' => 135,
    ];
    $bulk->insert($document);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $mongodb->executeBulkWrite('dbservice.nosql_tbl1', $bulk, $writeConcern);
    */

//    $response->getBody()->write('write successful');
//    return $response;
//});

//$app->get('/read', function (Request $request, Response $response) {
//    $mysqldb = \DBService\connect('mysql');
//    $result = $mysqldb->query('select * from my_tbl1');
//    $data = [];
//    while ($row = $result->fetch_assoc()) {
//        $data[] = $row;
//    }
//    $response->getBody()->write(print_r($data, 1));
//    return $response;
//});

$app->run();