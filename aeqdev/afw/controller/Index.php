<?php

namespace aeqdev\afw\controller;

use aeqdev\afw\HttpException;

class Index
{

    /** @var self */
    protected static $instance;
    protected $uri;
    protected $patterns = [];
    protected $exceptionCreator;


    /**
     * @return self
     */
    static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    static function currentURI()
    {
        return self::instance()->uri;
    }

    static function variable($class, $method, $args = [], array $allowable = null)
    {
        if (
            !method_exists($class, $method)
            || (
                isset($allowable)
                && !in_array($class, $allowable)
                &&  (
                    !isset($allowable[$class])
                    || !in_array($method, $allowable[$class])
                )
            )
        ) {
            return null;
        }

        $decoded_args = [];
        foreach ((array)$args as $arg) {
            $decoded_args [] = urldecode($arg);
        }

        return call_user_func_array([$class, $method], $decoded_args);
    }

    function __construct($uri = null)
    {
        if (isset($uri)) {
            $_SERVER['REQUEST_URI'] = $uri;
        }
        $this->uri = $_SERVER['REQUEST_URI'];

        $p = strpos($this->uri, '?');
        if ($p !== false) {
            $this->uri = substr($this->uri, 0, $p);
        }

        $this->uri = trim($this->uri, '/');
    }

    function addPattern($pattern, $controllerCreator)
    {
        $this->patterns[$pattern] = $controllerCreator;
    }

    function resetPatterns()
    {
        $this->patterns = [];
    }

    function setException($exceptionControllerCreator)
    {
        $this->exceptionCreator = $exceptionControllerCreator;
    }

    function getException(\Exception $exception)
    {
        if (empty($this->exceptionCreator)) {
            throw $exception;
        }
        $creator = $this->exceptionCreator;
        return $creator($exception);
    }

    function getController($try = false)
    {
        $controller = null;
        foreach ($this->patterns as $pattern => $creator) {
            if (preg_match($pattern, $this->uri, $matches)) {
                array_shift($matches);
                try {
                    $controller = call_user_func_array($creator, $matches);
                } catch (\Exception $exception) {
                    $controller = $this->getException($exception);
                }
                break;
            }
        }
        if (empty($controller) && !$try) {
            $controller = $this->getException(new HttpException(404));
        }
        return $controller;
    }

    function tryGetController()
    {
        return $this->getController(true);
    }

}
