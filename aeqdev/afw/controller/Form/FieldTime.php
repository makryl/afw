<?php

namespace aeqdev\afw\controller\Form;

class FieldTime extends Field
{

    protected $format;

    function __construct($label = null, $name = null, $format = null)
    {
        parent::__construct($label, $name);
        $this->format = $format;
    }

    function render()
    {
        if (isset($this->format)) {
            $this->value = date($this->format, strtotime($this->form->getValue($this->name)));
        }
        parent::render();
    }

}
