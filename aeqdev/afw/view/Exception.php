<?php /* @var $this \aeqdev\afw\controller\Exception */ ?>
<h2><?= $this->title ?></h2>
<?php if (ini_get('display_errors')): ?>
    <dl>
        <dt>File</dt><dd><code><?= $this->exception->getFile() ?></code></dd>
        <dt>Line</dt><dd><code><?= $this->exception->getLine() ?></code></dd>
        <dt>Code</dt><dd><code><?= $this->exception->getCode() ?></code></dd>
        <dt>Message</dt><dd><?= $this->exception->getMessage() ?></dd>
        <dt>Trace</dt><dd><pre><code><?= $this->exception->getTraceAsString() ?></code></pre></dd>
    </dl>
<?php else: ?>
    <pre><?= $this->exception->getMessage() ?></pre>
<?php endif ?>
