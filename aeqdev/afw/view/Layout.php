<?php /* @var $this \aeqdev\afw\controller\Layout */ ?>
<!doctype html>
<html>
<head>
<title><?= htmlspecialchars($this->title) ?></title>
<meta charset="utf-8">
<?php if (!empty($this->keywords)): ?>
<meta name="keywords" content="<?= htmlspecialchars($this->keywords) ?>">
<?php endif ?>
<?php if (!empty($this->description)): ?>
<meta name="description" content="<?= htmlspecialchars($this->description) ?>">
<?php endif ?>
<?php $this->css() ?>
<?php if (!empty($this->head)) echo $this->head ?>
</head>
<body>
<?php $this->contents() ?>
<?php $this->js() ?>
</body>
</html>
