<?php

namespace aeqdev\afw\controller;

use aeqdev\ATPL;

class Layout extends ATPL
{

    public $htmlCss = "<link rel=\"stylesheet\" href=\"%s\" />\n";
    public $htmlJs = "<script src=\"%s\"></script>\n";
    public $htmlJsCode = "<script>%s</script>\n";

    public $title;
    public $titleSeparator = ' | ';
    public $keywords;
    public $description;
    public $head;
    public $resourcesBase = '/';
    public $resourcesDir = '';

    protected $css = [];
    protected $js = [];

    function __construct()
    {
        $this->addView(__CLASS__);
    }

    function addTitle($title)
    {
        foreach ((array)$title as $v) {
            $this->title = $v . (isset($this->title) ? $this->titleSeparator . $this->title : '');
        }
    }

    function addCss($css)
    {
        $this->css[$css] = true;
    }

    function addJs($js)
    {
        $this->js[$js] = null;
    }

    function addJsCode($code)
    {
        $this->js [] = $code;
    }

    function css()
    {
        foreach ($this->css as $css => $true) {
            printf($this->htmlCss, $this->resourceUrl($css));
        }
    }

    function js()
    {
        foreach ($this->js as $js => $code) {
            if (isset($code)) {
                printf($this->htmlJsCode, $code);
            } else {
                printf($this->htmlJs, $this->resourceUrl($js));
            }
        }
    }

    protected function resourceUrl($fname)
    {
        if (parse_url($fname, PHP_URL_HOST)) {
            return $fname;
        } else {
            return $this->resourcesBase . $fname;
        }
    }

    function setKeywordsFromStr($str)
    {
        $str = preg_replace('`\W+`u', ' ', $str);
        $words = [];
        foreach (preg_split('`\s+`', $str) as $word) {
            if (mb_strlen($word) > 2) {
                $words [] = $word;
            }
        }
        $this->keywords = implode(', ', $words);
    }

    function setKeywordsFromTitle()
    {
        $this->setKeywordsFromStr($this->title);
    }

}
