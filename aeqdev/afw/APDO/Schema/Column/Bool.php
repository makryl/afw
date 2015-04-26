<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\afw\controller\Form\Element;

class Bool extends \aeqdev\APDO\Schema\Column\Bool
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldCheckbox();
    }

    public function formFieldCheckbox()
    {
        $this->formFieldCreator(function() {
            return Element::checkbox($this->comment, $this->name);
        });
    }

    public function formFieldYesNoNull($labelYes = null, $labelNo = null, $labelNull = null)
    {
        $this->formFieldCreator(function() use ($labelYes, $labelNo, $labelNull) {
            return Element::selectYesNoNull($this->comment, $this->name, $labelYes, $labelNo, $labelNull);
        });
    }

}
