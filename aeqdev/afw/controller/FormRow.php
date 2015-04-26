<?php

namespace aeqdev\afw\controller;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\APDO\Schema;
use aeqdev\APDO\Schema\Row;
use aeqdev\APDO\Schema\RowValidateException;
use aeqdev\APDO\Schema\Table;
use aeqdev\ATPL;

class FormRow extends Form
{

    /**
     * @var Row
     */
    public $row;
    public $defaultHeader = '%s %s';
    public $defaultSubmit = null;

    function __construct(Row $row, $completeMessage = '', $action = '', $method = 'post')
    {
        parent::__construct($completeMessage, $action, $method);
        $this->row = $row;
    }

    function getValue($name)
    {
        $value = parent::getValue($name);
        return isset($value)
            ? $value
            : (
                isset($this->row->{$name})
                    ? $this->row->{$name}
                    : null
            );
    }

    protected function runInternal($callback)
    {
        try {
            $callback();
        } catch (RowValidateException $e) {
            $this->addExceptions($e->exceptions);
        }
    }

    function pushElements($headLabel = null, $pushFields = null, $buttonLabels = null)
    {
        $this->push(
            Form\Element::header(
                isset($headLabel)
                    ? $headLabel
                    : sprintf(
                        $this->defaultHeader,
                        $this->row->table->comment,
                        $this->row->pkey()
                    )
            )
        );

        $this->pushFields(
            isset($pushFields)
                ? $pushFields
                : $this->row->table->cols
        );

        if (isset($buttonLabels)) {
            if (is_array($buttonLabels)) {
                foreach ($buttonLabels as $name => $label) {
                    $this->push(Form\Element::submit($label, $name));
                }
            } else {
                $this->push(Form\Element::submit($buttonLabels));
            }
        } else {
            $this->push(Form\Element::submit($this->defaultSubmit));
        }
    }

    function pushFields($names)
    {
        foreach ((array)$names as $cname) {
            $column = $this->row->table->{$cname}();
            if ($column instanceof ColumnFormField) {
                $field = $column->createFormField();
                if ($field instanceof ATPL) {
                    $this->push($field);
                }
            }
        }
    }

    /**
     * @param Table $table
     * @param string|int $pkey
     * @param string $completeMessage
     * @param array|string $buttonLabel
     * @param array $pushFields
     * @param string $headLabel
     * @param string $action
     * @param string $method
     * @return self
     */
    static function save(Table $table, $pkey,
                         $completeMessage = '', $buttonLabel = null, $pushFields = null, $headLabel = null,
                         $action = '', $method = 'post')
    {
        $form = new self(isset($pkey) ? $table->get($pkey) : $table->create(), $completeMessage, $action, $method);
        $form->run(function() use ($form)
        {
            $form->row->saveData($_POST + $_FILES);
        });
        $form->setData($_POST);
        $form->pushElements($headLabel, $pushFields, $buttonLabel);
        return $form;
    }

    /**
     * @param Table $table
     * @param string|int $pkey
     * @param string $completeMessage
     * @param array|string $buttonLabel
     * @param array $pushFields
     * @param string $headLabel
     * @param string $action
     * @param string $method
     * @return FormRow
     */
    static function delete(Table $table, $pkey,
                           $completeMessage = '', $buttonLabel = null, $pushFields = null, $headLabel = null,
                           $action = '', $method = 'post')
    {
        $form = new self($table->get($pkey), $completeMessage, $action, $method);
        $form->run(function() use ($form)
        {
            $form->row->delete();
        });
        $form->pushElements($headLabel, $pushFields, $buttonLabel);
        return $form;
    }


    /**
     * @param Schema $schema
     * @param $tableName
     * @param $methodName
     * @param $pkey
     * @param array $allowable
     * @return FormRow
     */
    static function variable(Schema $schema, $tableName, $methodName, $pkey, array $allowable = null)
    {
        if (
            !method_exists(static::class, $methodName)
            || (
                isset($allowable)
                && !in_array($tableName, $allowable)
                && (
                    !isset($allowable[$tableName])
                    || !in_array($methodName, $allowable[$tableName])
                )
            )
        ) {
            return null;
        }

        $table = $schema->{$tableName};

        if (!($table instanceof \aeqdev\afw\APDO\Schema\Table)) {
            return null;
        }

        return call_user_func(
            [static::class, $methodName],
            $table,
            $pkey,
            '',
            null,
            null,
            $table->comment
        );
    }

}
