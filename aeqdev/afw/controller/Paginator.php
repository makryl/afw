<?php

namespace aeqdev\afw\controller;

use aeqdev\ATPL;

class Paginator extends ATPL
{

    public $count;
    public $current;
    public $radius = 3;
    public $reverse = false;

    public $htmlBegin = '<nav class="paginator">';
    public $htmlEnd = '</nav>';
    public $htmlLink = '<a href="%s">%d</a>';
    public $htmlLinkPrev = '<a href="%s">&larr;</a>';
    public $htmlLinkNext = '<a href="%s">&rarr;</a>';
    public $htmlCurr = '<b>%d</b>';
    public $htmlCollapse = '<i>...</i>';

    protected $prefix;
    protected $urlStart;
    protected $urlEnd;

    function __construct($count = 0, $current = 1, $prefix = 'page-')
    {
        $this->count = $count;
        $this->current = empty($current) ? 1 : $current;
        $this->prefix = $prefix;
        $this->urlStart = preg_replace(
            '`/' . preg_quote($this->prefix) . '\d+$`',
            '',
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        $this->urlEnd = empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING'];

        $this->addViewCallback(function() {
            $this->renderPages();
        });
    }

    function href($page)
    {
        $href = $this->urlStart;
        if (
            (!$this->reverse && $page != 1)
            || ($this->reverse && $page != $this->count)
        ) {
            $href .= '/' . $this->prefix . $page;
        }
        return $href . $this->urlEnd;
    }

    private function renderPages()
    {
        if ($this->count <= 1) {
            return;
        }

        echo $this->htmlBegin;

        if ($this->current == 1) {
            printf($this->htmlCurr, 1);
        } else {
            printf($this->htmlLinkPrev, $this->href($this->current - 1));
            printf($this->htmlLink, $this->href(1), 1);
        }

        if ($this->current - $this->radius > 2) {
            echo $this->htmlCollapse;
        }

        for ($i = 2; $i < $this->count; $i++) {
            if (abs($i - $this->current) <= $this->radius) {
                if ($this->current == $i) {
                    printf($this->htmlCurr, $i);
                } else {
                    printf($this->htmlLink, $this->href($i), $i);
                }
            }
        }

        if ($this->current + $this->radius < $this->count - 1) {
            echo $this->htmlCollapse;
        }

        if ($this->current == $this->count) {
            printf($this->htmlCurr, $this->count);
        } else {
            printf($this->htmlLink, $this->href($this->count), $this->count);
            printf($this->htmlLinkNext, $this->href($this->current + 1));
        }

        echo $this->htmlEnd;
    }

}
