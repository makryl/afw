<?php

namespace aeqdev\afw;

trait EventTarget
{

    private $eventListeners = [];

    public function addEventListener($type, $callback)
    {
        $this->eventListeners[$type] [] = $callback;
    }

    public function removeEventListeners($type)
    {
        unset($this->eventListeners[$type]);
    }

    public function dispatchEvent($type)
    {
        if (isset($this->eventListeners[$type])) {
            foreach ($this->eventListeners[$type] as $callback) {
                call_user_func_array($callback, array_slice(func_get_args(), 1));
            }
        }
    }

}
