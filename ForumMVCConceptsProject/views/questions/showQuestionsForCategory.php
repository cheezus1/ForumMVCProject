<?php foreach($this->questions as $question) : ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Q: <a href="/questions/view/<?= $question['id'] ?>"><?= htmlspecialchars($question['title']) ?></a></h3>
        <br>
        From:
        <img src="/<?= $question['picture_url'] ?>" width="50" height="50">
        <a href="/account/profile/<?= $question['from_user'] ?>"><?= htmlspecialchars($question['username']) ?></a>
        <br>
        Number of visits: <?= $question['number_of_visits'] ?>
    </div>
    <div class="panel-body">
        <?= htmlspecialchars($question['content']) ?>
    </div>
    <?php if($this->isLoggedIn) : ?>
        <a href="/questions/delete/<?=$question['id']?>" class="btn btn-danger btn-sm">Delete</a>
    <?php endif; ?>
</div>
<?php endforeach; ?>
