<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\afw\controller\Form\Element;

class Text extends \aeqdev\APDO\Schema\Column\Text
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldTextarea();
    }

    public function formFieldTextarea()
    {
        $this->formFieldCreator(function() {
            return Element::textarea($this->comment, $this->name);
        });
    }

}
