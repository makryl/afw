<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\afw\controller\Form\Element;

class Time extends \aeqdev\APDO\Schema\Column\Time
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldDateTime();
    }

    public function formFieldTime()
    {
        $this->formFieldCreator(function() {
            return Element::time($this->comment, $this->format, $this->name);
        });
    }

    public function formFieldDateTime()
    {
        $this->formFieldCreator(function() {
            return Element::datetime($this->comment, $this->format, $this->name);
        });
    }

}
