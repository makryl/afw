<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;

class Float extends \aeqdev\APDO\Schema\Column\Float
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldText();
    }

}
