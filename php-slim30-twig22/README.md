# PHP, Slim 3.0, Twig 2.2

Several hours of frustration mulling over Slim and Twig's mediocre documentation has led to this README.  I hope it saves somebody out there some frustration.

Anyways, what it available in Slim's [documentation](https://www.slimframework.com/docs/features/templates.html) is mostly right but there have been so many iteration's with so many examples out there that just won't work.

For me, the example pulled up a blank page.  For hours.  Days even!

In a nutshell, the code from the example:

```
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__.'/../vendor/autoload.php';

$app = new \Slim\App();

$container = $app->getContainer();
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views', [
        'cache' => __DIR__.'/../resources/cache'
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'hello.html', [
       'name' => $args['name']
    ]);
})->setName('profile');

$app->run();
```

Simple.  All well and good.  After several years break from PHP, I don't really understand why this simple stub needs to be so cryptic.  `$container` is what...?  A property of app now?  Is this a pointer?  And referencing `$this-\>view` in my '/hello/{name}' route... seriously now?

Anyways, this example returned nothing forever.  I sent the output of `$this-\>view-\>render` to the error log and only got:
```
Content-Type: text/html; charset=UTF-8
```

...forever, annoyingly.  I changed file paths, tried out a million different examples for older version of twig and the only stupid change that needed to be made was this:

```
'cache' => false \/\/__DIR__.'/../resources/cache'
```

I thought these frameworks was supposed to be smart?  What is the cache dir used for if not to cache front-end components?  It's being defined with the view... wth!?

Slim docs provide this caveat (discovered after the fact):
Note : “cache” could be set to false to disable it, see also ‘auto_reload’ option, useful in development environment.

They should really rewrite this to: "not setting cache to false will result of renderring blank templates".

\\End rant
