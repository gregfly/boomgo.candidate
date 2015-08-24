<?php
/* @var $this \app\base\View */
/* @var $model \app\models\GameResult */
?>
<form action="" method="POST" onsubmit="return new SimpleForm(this).submit();">
    <div class="row" id="name-element">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="<?= $model->name ?>">
        <ul class="errors">
            <?php foreach ($model->getErrors('name') as $msg): ?>
            <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="row" id="score-element">
        <label for="score">Score</label>
        <input id="score" name="score" type="number" value="<?= $model->score ?>">
        <ul class="errors">
            <?php foreach ($model->getErrors('score') as $msg): ?>
            <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="row" id="date-element">
        <label for="date">Date</label>
        <input id="date" name="date" type="date" value="<?= $model->date? date('Y-m-d', $model->date) : '' ?>">
        <ul class="errors">
            <?php foreach ($model->getErrors('date') as $msg): ?>
            <li><?= $msg ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <p>
        <input type="submit" value="Submit">
    </p>
</form>