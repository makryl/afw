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

$uri->addPattern("`^docs(/.+)?$`", function ($m)
{
    return new docs\c\Docs($m[0]);
});



$uri->renderController();
