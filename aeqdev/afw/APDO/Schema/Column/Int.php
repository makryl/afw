<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;

class Int extends \aeqdev\APDO\Schema\Column\Int
{
    use ColumnFormField;

    public function __construct()
    {
        parent::__construct();
        $this->formFieldText();
    }

}
