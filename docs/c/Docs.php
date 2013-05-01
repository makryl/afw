<?php

namespace docs\c;



class Docs extends Layout
{

    /**
     * @var \afw\AMarkup
     */
    public $markup;
    public $file;

    public function __construct($uri)
    {
        parent::__construct();
        $this->setTemplate(__CLASS__);

        if (is_dir('afw/' . $uri))
        {
            $uri .= '/index';
        }

        $this->file = 'afw/' . $uri . '.txt';

        if (!file_exists($this->file))
        {
            throw new \afw\HttpException(404);
        }

        $this->head .= '<base href="/' . $uri . '" />';

        $this->markup = new \afw\AMarkup(50, 1);
    }

}
