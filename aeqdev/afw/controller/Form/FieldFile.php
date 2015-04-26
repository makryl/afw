<?php

namespace aeqdev\afw\controller\Form;

use aeqdev\afw\controller\Form;

class FieldFile extends Field
{

    public $labelDelete;
    public $maxFileSize;
    public $src;

    function __construct($label = null, $name = null, $labelDelete = null, $maxFileSize = null)
    {
        parent::__construct($label, $name);
        $this->labelDelete = $labelDelete;
        $this->maxFileSize = $maxFileSize;
    }

    function setForm(Form $form)
    {
        parent::setForm($form);

        if ($form->maxFileSize < $this->maxFileSize) {
            $form->maxFileSize = $this->maxFileSize;
        }
    }

}
