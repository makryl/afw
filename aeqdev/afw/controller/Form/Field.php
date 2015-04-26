<?php

namespace aeqdev\afw\controller\Form;

class Field extends Element
{

    public $name;
    public $value;
    public $exception;

    function __construct($label = null, $name = null, $value = null)
    {
        parent::__construct($label);
        $this->addView(__CLASS__);
        $this->name = $name;
        $this->value = $value;
    }

    function render()
    {
        if (isset($this->form)) {
            if (!isset($this->value)) {
                $this->value = $this->form->getValue($this->name);
            }
            if (!isset($this->exception)) {
                $this->exception = $this->form->getException($this->name);
            }
        }
        parent::render();
    }

}
