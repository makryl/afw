<?php

namespace aeqdev\afw\APDO\Schema;

use aeqdev\afw\EventTarget;

class Table extends \aeqdev\APDO\Schema\Table
{
    use EventTarget;

    const EVENT_BEFORE_SAVE = 1;
    const EVENT_SAVE = 2;
    const EVENT_BEFORE_DELETE = 3;
    const EVENT_DELETE = 4;
}
