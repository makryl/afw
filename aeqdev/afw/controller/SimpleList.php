<?php

namespace aeqdev\afw\controller;

use aeqdev\ATPL;

class SimpleList extends ATPL
{

    public $items;

    function __construct($__METHOD__, &$items)
    {
        $this->addView($__METHOD__);
        $this->items = & $items;
    }

    function getItems()
    {
        return empty($this->items) ? [] : $this->items;
    }

    function isEmpty()
    {
        return empty($this->items);
    }

}
