<?php

namespace docs\c;



class Layout extends \afw\c\Layout
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(__CLASS__);

        $this->addCss('afw/res/css/default-light.css');
    }

}
