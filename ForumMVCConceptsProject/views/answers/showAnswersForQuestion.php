<?php foreach($this->answers as $answer) : ?>
<div class="panel panel-default">
    <div class="panel-heading">
        From:
        <img src="/<?= $answer['picture_url'] ?>" width="50" height="50">
        <?php if($answer['is_registered'] == 0) : ?>
            <?= htmlspecialchars($answer['author_firstname']) . ' ' . htmlspecialchars($answer['author_lastname']) ?>
        <?php endif; ?>
        <?php if($answer['is_registered'] == 1) : ?>
            <a href="/account/profile/<?= $answer['author_id'] ?>"><?= htmlspecialchars($answer['username']) ?></a>
        <?php endif; ?>
        <br>
        <?php if($answer['email'] != null) : ?>
        Email: <?= htmlspecialchars($answer['email']) ?>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <?= htmlspecialchars($answer['content']) ?>
    </div>
    <?php if($this->isLoggedIn) : ?>
        <a href="/answers/delete/<?=$answer['id']?>" class="btn btn-danger btn-sm">Delete</a>
    <?php endif; ?>
</div>
<?php endforeach; ?>