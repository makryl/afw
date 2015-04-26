<?php

namespace aeqdev\afw\APDO\Schema;

use aeqdev\afw\controller\Form\Element;
use aeqdev\afw\controller\Form\Field;

trait ColumnFormField
{

    /**
     * @var Table
     */
    public $table;
    public $name;
    public $comment;

    /**
     * @var callable
     */
    protected $formFieldCreator;

    /**
     * @return Field
     */
    public function createFormField()
    {
        return call_user_func($this->formFieldCreator);
    }

    /**
     * @param callable $callback
     */
    public function formFieldCreator($callback)
    {
        $this->formFieldCreator = $callback;
    }

    public function formFieldText()
    {
        $this->formFieldCreator(function() {
            return Element::text($this->comment, $this->name);
        });
    }

}
