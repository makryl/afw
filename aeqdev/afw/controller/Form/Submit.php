<?php

namespace aeqdev\afw\controller\Form;

class Submit extends Element
{

    protected static $i = 0;
    public $name;

    function __construct($label = null, $name = null)
    {
        parent::__construct($label);

        if (!isset($name))
        {
            $name = '__submit' . ++self::$i;
        }
        $this->name = $name;
    }

}
