<?php
/* @var $this \app\base\View */
/* @var $exception \Exception */

$this->title = get_class($exception);
?>
<div><?= $exception->getCode() ?></div>
<div><?= $exception->getMessage() ?></div>
<?php foreach ($exception->getTrace() as $trace): ?>
<div><?= var_export($trace, true) ?></div>
<?php endforeach; ?>