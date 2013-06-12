<?php

namespace afw\doc\c;



class Index extends \afw\c\Layout
{

    /**
     * @var \afw\AMarkup
     */
    public $markup;
    public $file;

    public function __construct($file)
    {
        parent::__construct();
        $this->setView(__CLASS__);

        $this->addCss('afw/res/css/default-light.css');
        $this->addCss('afw/res/css/google-code-prettify-light.css');
        $this->addCss('afw/doc/res/style.css');
        $this->addJs('afw/res/js/APrettyPrint.js');
        $this->addJsCode('window.onload = function() {APrettyPrint();};');

        $this->markup = new \afw\AMarkup(50, 1);
        $this->file = $file;
    }

}
