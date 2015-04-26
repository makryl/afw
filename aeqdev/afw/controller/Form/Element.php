<?php

namespace aeqdev\afw\controller\Form;

use aeqdev\afw\controller\Form;
use aeqdev\ATPL;

class Element extends ATPL
{

    /**
     * @var \aeqdev\afw\controller\Form
     */
    public $form;
    public $label;
    public $description;

    function __construct($label = null)
    {
        $this->label = $label;
    }

    function setForm(Form $form)
    {
        $this->form = $form;
    }

    function description($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $__FUNCTION__
     * @param ATPL $controller
     * @return ATPL
     */
    protected static function simpleElement($__FUNCTION__, ATPL $controller)
    {
        $controller->addView(get_called_class() . '::' . $__FUNCTION__);
        return $controller;
    }

    /**
     * @param string $label
     * @return Element
     */
    static function label($label = null)
    {
        return static::simpleElement(__FUNCTION__, new self($label));
    }

    /**
     * @param string $label
     * @return Element
     */
    static function header($label = null)
    {
        return static::simpleElement(__FUNCTION__, new self($label));
    }

    /**
     * @param string $name
     * @param string $value
     * @return Field
     */
    static function hidden($name = null, $value = null)
    {
        return static::simpleElement(__FUNCTION__, new Field(null, $name, $value));
    }

    /**
     * @param string $label
     * @param string $name
     * @param string $value
     * @return Field
     */
    static function value($label = null, $name = null, $value = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name, $value));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function text($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function color($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function number($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function tel($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function url($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $format
     * @param string $name
     * @return FieldTime
     */
    static function date($label = null, $format = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldTime($label, $name, $format));
    }

    /**
     * @param string $label
     * @param string $format
     * @param string $name
     * @return FieldTime
     */
    static function time($label = null, $format = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldTime($label, $name, $format));
    }

    /**
     * @param string $label
     * @param string $format
     * @param string $name
     * @return FieldTime
     */
    static function datetime($label = null, $format = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldTime($label, $name, $format));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function password($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function textarea($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Field
     */
    static function checkbox($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Field($label, $name));
    }

    public static $selectYesNoNull_yes = 'Yes';
    public static $selectYesNoNull_no = 'No';
    public static $selectYesNoNull_null = '';

    /**
     * @param string $label
     * @param string $name
     * @param string $labelYes
     * @param string $labelNo
     * @param string $labelNull
     * @return Field
     */
    static function selectYesNoNull($label = null, $name = null, $labelYes = null, $labelNo = null, $labelNull = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldSelect($label, $name, [
            '' => isset($labelNull) ? $labelNull : self::$selectYesNoNull_null,
            '1' => isset($labelYes) ? $labelYes : self::$selectYesNoNull_yes,
            '0' => isset($labelNo) ? $labelNo : self::$selectYesNoNull_no,
        ]));
    }

    /**
     * @param string $label
     * @param string $name
     * @return Submit
     */
    static function submit($label = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new Submit($label, $name));
    }

    /**
     * @param string $label
     * @param array|callable $options
     * @param string $name
     * @param string $padding
     * @return FieldSelect
     */
    static function select($label = null, $options = null, $name = null, $padding = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldSelect($label, $name, $options, $padding));
    }

    /**
     * @param string $label
     * @param string $labelDelete
     * @param int $maxFileSize
     * @param string $name
     * @return FieldFile
     */
    static function file($label = null, $labelDelete = null, $maxFileSize = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldFile($label, $name, $labelDelete, $maxFileSize));
    }

    /**
     * @param string $label
     * @param string $labelDelete
     * @param int $maxFileSize
     * @param string $name
     * @return FieldFile
     */
    static function image($label = null, $labelDelete = null, $maxFileSize = null, $name = null)
    {
        return static::simpleElement(__FUNCTION__, new FieldFile($label, $name, $labelDelete, $maxFileSize));
    }

}
