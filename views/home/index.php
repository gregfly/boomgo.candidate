<?php
/* @var $data array */
/* @var $page integer */
/* @var $group integer */

use app\models\GameResult;
?>
<div class="group-switch">
    <a class="<?= $group == GameResult::GROUP_DAY? 'active' : '' ?>" href="/home/index?page=<?= $page ?>&group=<?= GameResult::GROUP_DAY ?>" onclick="return grid.ui().activate(this, 'active').switchGroup(<?= GameResult::GROUP_DAY ?>);">Daily</a>
    <a class="<?= $group == GameResult::GROUP_WEEK? 'active' : '' ?>" href="/home/index?page=<?= $page ?>&group=<?= GameResult::GROUP_WEEK ?>" onclick="return grid.ui().activate(this, 'active').switchGroup(<?= GameResult::GROUP_WEEK ?>);">Weekly</a>
    <a class="<?= $group == GameResult::GROUP_MONTH? 'active' : '' ?>" href="/home/index?page=<?= $page ?>&group=<?= GameResult::GROUP_MONTH ?>" onclick="return grid.ui().activate(this, 'active').switchGroup(<?= GameResult::GROUP_MONTH ?>);">Monthly</a>
    <a class="<?= $group == GameResult::GROUP_ALL? 'active' : '' ?>" href="/home/index?page=<?= $page ?>&group=<?= GameResult::GROUP_ALL ?>" onclick="return grid.ui().activate(this, 'active').switchGroup(<?= GameResult::GROUP_ALL ?>);">All Time</a>
</div>
<table id="table" border="1">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['rank'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['score'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination">
    <a href="/home/index?page=<?= $page - 1 ?>&group=<?= $group ?>" onclick="return grid.prevPage();">&larrb;</a>
    <a href="/home/index?page=<?= $page + 1 ?>&group=<?= $group ?>" onclick="return grid.nextPage();">&rarrb;</a>
</div>
<script type="text/javascript">
var grid = new GridView(<?= json_encode(['page' => $page, 'group' => $group]) ?>);
setInterval(grid.load.bind(grid), 10000); // 10 sec
</script>