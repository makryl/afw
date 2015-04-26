<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\afw\controller\Form\Element;

class Date extends \aeqdev\APDO\Schema\Column\Date
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldDate();
    }

    public function formFieldDate()
    {
        $this->formFieldCreator(function() {
            return Element::date($this->comment, $this->format, $this->name);
        });
    }

}
