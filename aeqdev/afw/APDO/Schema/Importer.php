<?php

namespace aeqdev\afw\APDO\Schema;

class Importer extends \aeqdev\APDO\Schema\Importer
{

    public $classTable  = Table::class;
    public $classRow    = Row::class;
    public $classColumn = Column::class;
    public $classColumnByType = [
        'int'    => Column\Int::class,
        'float'  => Column\Float::class,
        'bool'   => Column\Bool::class,
        'string' => Column\String::class,
        'text'   => Column\Text::class,
        'time'   => Column\Time::class,
        'date'   => Column\Date::class,
    ];

}
