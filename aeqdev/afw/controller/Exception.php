<?php

namespace aeqdev\afw\controller;

use aeqdev\ATPL;

class Exception extends ATPL
{

    /**
     * @var \Exception
     */
    public $exception;
    public $title;

    function __construct(\Exception $exception)
    {
        $this->addView(__CLASS__);
        $this->exception = $exception;
        $this->title = sprintf(_('Error %s'), http_response_code());
        error_log($exception);
    }

    function wrap(ATPL $controller)
    {
        if ($controller instanceof Layout) {
            $controller->addTitle($this->title);
        }
        return parent::wrap($controller);
    }

}
