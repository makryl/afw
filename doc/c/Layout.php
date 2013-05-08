<?php

namespace doc\c;



class Layout extends \afw\c\Layout
{
    public function __construct()
    {
        parent::__construct();
        $this->setView(__CLASS__);

        $this->addCss('afw/res/css/default-light.css');
        $this->addCss('afw/res/css/google-code-prettify-light.css');
        $this->addCss('doc/res/style.css');
        $this->addJs('afw/res/js/APrettyPrint.js');
        $this->addJsCode('window.onload = function() {APrettyPrint();};');
    }

}
