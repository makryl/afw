<?php

namespace afw;

require __DIR__ . '/../../afw/AMarkup.php';



class AMarkupTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AMarkup
     */
    protected $object;



    function testConvert()
    {
        $text = <<<EOS
=#h1name Heading 1
==#h2name Heading 2

Multiline paragraph
with www.autolinks.com
and [www.google.com named links]
and [#h1name references]
and abbr(Abbreviation)
and `code`

http://google.com/. Example text
https://google.com, example text
www.google.com? Example text
https://www.google.ru/search?aq=f&oq=example&sugexp=chrome,mod=0&sourceid=chrome&client=ubuntu&channel=cs&ie=UTF-8&q=example
http://www.google.com

quoted "quoted text" text
 - starts with space and dash
long --- dash
middle--dash
<<manual>> quotes and (c) copyright


* unordered list item
multiline
* another one
** sublist item
** one more sublist item

# ordered list item
# another one
## sublist item
## one more sublist item

! definition
- definition text

>multiline
>blockquote

|= table    heading         row
|  any      more than one   space delibiter
|  for      table           marckup

```
preformatted code

* ignores
** any markup

```
EOS;
        $html = <<<EOS
<h1 id="h1name"> Heading 1 </h1>  <h2 id="h2name"> Heading 2 </h2>  <p> Multiline paragraph
with <a rel="nofollow" href="http://www.autolinks.com">www.autolinks.com</a>
and <a rel="nofollow" href="http://www.google.com">named links</a>
and <a href="#h1name">references</a>
and <abbr title="Abbreviation">abbr</abbr>
and <code>code</code>
 <p> <a rel="nofollow" href="http://google.com/">http://google.com/</a>. Example text
<a rel="nofollow" href="https://google.com">https://google.com</a>, example text
<a rel="nofollow" href="http://www.google.com">www.google.com</a>? Example text
<a rel="nofollow" href="https://www.google.ru/search?aq=f&amp;oq=example&amp;sugexp=chrome,mod=0&amp;sourceid=chrome&amp;client=ubuntu&amp;channel=cs&amp;ie=UTF-8&amp;q=example">https://www.google.ru/search?aq=f&amp;oq=example&amp;sugex…</a>
<a rel="nofollow" href="http://www.google.com">http://www.google.com</a>
 <p> quoted «quoted text» text
&nbsp;— starts with space and dash
long&nbsp;— dash
middle–dash
«manual» quotes and © copyright
 <ul><li> unordered list item
multiline
 <li> another one
 <ul><li> sublist item
 <li> one more sublist item
 </ul>  </ul>  <ol><li> ordered list item
 <li> another one
 <ol><li> sublist item
 <li> one more sublist item
 </ol>  </ol>  <dl><dt> definition
 <dd> definition text
 </dl>  <blockquote> multiline
blockquote
 </blockquote>  <table><tr><th>table</th><th>heading</th><th>row</th></tr>
<tr><td>any</td><td>more than one</td><td>space delibiter</td></tr>
<tr><td>for</td><td>table</td><td>marckup</td></tr>
</table>  <pre><code>preformatted code

* ignores
** any markup

</code></pre>
EOS;
        $this->assertEquals($html, (new AMarkup)->convert($text));
    }

}
