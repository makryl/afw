<?php

namespace aeqdev\afw\controller;

use aeqdev\afw\Session;
use aeqdev\ATPL;

class Form extends ATPL
{

    const SESSION_COMPLETE = 'aeqdev\afw\controller\Form::complete';
    const SESSION_REFERER = 'aeqdev\afw\controller\Form::referer';

    protected $data = [];
    protected $default = [];
    protected $exceptions = [];
    protected $elements = [];

    /**
     * @var callable
     */
    protected $handler;

    protected $sessionComplete = 'complete';
    protected $sessionReferer = 'referer';

    public $action;
    public $method;
    public $maxFileSize;
    public $exception;
    public $complete = false;
    public $completeMessage;

    function __construct($completeMessage = '', $action = '', $method = 'post')
    {
        $this->addView(__CLASS__);
        $this->completeMessage = $completeMessage;
        $this->action = $action;
        $this->method = $method;

        if (empty($_POST) && !empty($_SERVER['HTTP_REFERER'])) {
            Session::set(self::SESSION_REFERER, $_SERVER['HTTP_REFERER']);
        }
    }

    function returnToUrl($url = null)
    {
        Session::set(self::SESSION_REFERER, isset($url) ? $url : $_SERVER['REQUEST_URI']);
    }

    function setData($data)
    {
        $this->data = (array)$data;
    }

    function setDefault($default)
    {
        $this->default = (array)$default;
    }

    function addExceptions($exceptions)
    {
        $this->exceptions = array_merge($this->exceptions, (array)$exceptions);
    }

    function addException($name, $exception)
    {
        $this->exceptions[$name] = $exception;
    }

    function getValue($name)
    {
        return isset($this->data[$name])
            ? $this->data[$name]
            : (
                isset($this->default[$name])
                    ? $this->default[$name]
                    : null
            );
    }

    function setValue($name, $value)
    {
        $this->data[$name] = $value;
    }

    function setDefaultValue($name, $value)
    {
        $this->default[$name] = $value;
    }

    function getException($name)
    {
        return isset($this->exceptions[$name]) ? $this->exceptions[$name] : null;
    }

    static function lastComplete($noClear = false)
    {
        $message = Session::get(self::SESSION_COMPLETE);
        if (!$noClear && !empty($message)) {
            Session::set(self::SESSION_COMPLETE, null);
        }
        return $message;
    }

    function complete($message = null)
    {
        if (empty($message)) {
            $message = $this->completeMessage;
        }
        Session::set(self::SESSION_COMPLETE, $message);
        $referer = Session::get(self::SESSION_REFERER);
        if (!empty($referer)) {
            header('location: ' . $referer);
            exit;
        }
        $this->complete = $message;
    }

    function fail($exception)
    {
        $this->exception = $exception;
    }

    function push(ATPL $controller)
    {
        if ($controller instanceof Form\Element) {
            $controller->setForm($this);
        }
        parent::push($controller);
    }

    /**
     * @param callable $callback
     */
    function setHandler($callback)
    {
        $this->handler = $callback;
    }

    /**
     * @param callable $callback
     */
    function run($callback = null)
    {
        if (!empty($_POST)) {
            if (!isset($callback)) {
                $callback = $this->handler;
            }
            try {
                $this->runInternal($callback);
            } catch (\Exception $exception) {
                $this->exception = $exception;
            }
            if (empty($this->exception) && empty($this->exceptions)) {
                $this->complete();
            }
        }
    }

    protected function runInternal($callback)
    {
        $callback();
    }

}
