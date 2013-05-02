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

$uri->addPattern("`^doc(/\w+)$`", function ($m)
{
    return new doc\c\Doc($m[0]);
});



$uri->renderController();
