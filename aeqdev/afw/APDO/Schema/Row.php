<?php

namespace aeqdev\afw\APDO\Schema;

class Row extends \aeqdev\APDO\Schema\Row
{

    /**
     * @var Table
     */
    public $table;

    public function save($columns = null)
    {
        $this->table->dispatchEvent(Table::EVENT_BEFORE_SAVE, $this);
        parent::save($columns);
        $this->table->dispatchEvent(Table::EVENT_SAVE, $this);
    }

    public function delete()
    {
        // load columns
        foreach ($this->table->cols as $name) {
            $this->table->{$name}();
        }
        $this->table->dispatchEvent(Table::EVENT_BEFORE_DELETE, $this);
        parent::delete();
        $this->table->dispatchEvent(Table::EVENT_DELETE, $this);
    }

}
