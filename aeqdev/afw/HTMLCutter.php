<?php

/**
 * @link http://code.google.com/p/htmlcutting/
 */

namespace aeqdev\afw;

class HTMLCutter
{
    private static $fakeSymb = "\r";
    private static $tags = [];
    private static $tagCounter = 0;
    private static $openTags = [];
    private static $closeTags = [];
    private static $exTags = ['br'];

    private static function tagOut($tag)
    {
        self::$tagCounter++;
        self::$tags[self::$tagCounter] = $tag;
        return self::$fakeSymb;
    }

    private static function tagIn($fake = '')
    {
        self::$tagCounter++;
        $tag = self::$tags[self::$tagCounter];
        preg_match('/^<(\/?)(\w+)[^>]*>/i', $tag, $mathes);
        if (!in_array($mathes[2], self::$exTags)) {
            if ($mathes[1] != '/') {
                self::$openTags[] = $mathes[2];
            } else {
                self::$closeTags[] = $mathes[2];
            }
        }
        return $tag;
    }

    static function cut($text, $length)
    {
        $text = html_entity_decode($text);
        $text = preg_replace('/' . self::$fakeSymb . '/', '', $text);
        // move all tags to array tags
        $text = preg_replace_callback('/(<\/?)(\w+)([^>]*>)/', function($matches) {
                self::tagOut($matches[0]);
            }, $text);
        // check how many tags in cutter text to fix cut length
        $preCut = mb_substr($text, 0, $length);
        $fakeCount = mb_substr_count($preCut, self::$fakeSymb);
        // cut string
        $text = mb_substr($text, 0, $length + ($fakeCount * mb_strlen(self::$fakeSymb)));
        // remove last word
        $text = preg_replace('/\S*$/', '', $text);
        // return tags back
        self::$tagCounter = 0;
        $text = preg_replace_callback('/' . self::$fakeSymb . '/', function() {
                self::tagIn();
            }, $text);
        // get count not closed tags
        $closeCount = count(self::$openTags) - count(self::$closeTags);
        // close opened tags
        for ($i = 0; $i < $closeCount; $i++) {
            $tagName = array_pop(self::$openTags);
            $text .= '</' . $tagName . '>';
        }
        return $text;
    }
}
