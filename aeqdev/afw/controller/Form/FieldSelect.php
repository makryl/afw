<?php

namespace aeqdev\afw\controller\Form;

class FieldSelect extends Field
{

    public $options;
    public $padding;

    /**
     * @param string $label
     * @param string $name
     * @param array|callable $options
     * @param string $padding
     */
    function __construct($label = null, $name = null, $options = null, $padding = null)
    {
        parent::__construct($label, $name);
        $this->options = $options;
        $this->padding = isset($padding) ? $padding : ' ·  ';
    }

    function render()
    {
        if (isset($this->options) && is_callable($this->options)) {
            $this->options = call_user_func($this->options);
        }
        $this->options = (array)$this->options;
        parent::render();
    }

    function renderOption($i, $v, $deep = 0)
    {
        if (is_array($v)) {
            echo '<option disabled="disabled">',
                str_repeat($this->padding, $deep),
                $i, '</option>';
            foreach ($v as $ci => $cv) {
                $this->renderOption($ci, $cv, $deep + 1);
            }
        } else {
            echo '<option value="', htmlspecialchars($i), '"',
                (string)$i == (string)$this->value ? ' selected="selected"' : ''
                , '>',
                str_repeat($this->padding, $deep),
                $v, '</option>';
        }
    }

}
