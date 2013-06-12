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
    return new afw\doc\c\Index('afw/' . $m[0] . '.txt');
});



$uri->renderController();
