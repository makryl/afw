<?php



function __autoload($class)
{
    require str_replace('\\', '/', $class) . '.php';
}



$uri = afw\c\Uri::instance();

$uri->setException(function($e)
{
    return (new afw\c\Exception($e))
        ->wrap(new afw\c\Layout());
});

$uri->addPattern('`^doc/(en|ru)$`', function ($m)
{
    return afw\doc\c\Main::doc(new afw\c\Layout(), 'afw/' . $m[0] . '.txt');
});

$uri->addPattern('`^doc/example/css/(light|dark)$`', function ($m)
{
    return afw\doc\c\Main::exampleCSS(new afw\c\Layout(), $m[1] == 'dark');
});



$uri->renderController();
