<h1>Categories</h1>
<?php foreach($this->categories as $category) : ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><a href="/categories/view/<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a></h3>
        </div>
        <div class="panel-body">
            <?= htmlspecialchars($category['description']) ?>
        </div>
        <?php if($this->isLoggedIn) : ?>
        <a href="/categories/delete/<?=$category['id']?>" class="btn btn-danger btn-sm">Delete</a>
        <?php endif; ?>
    </div>
<?php endforeach ?>

<?php if($this->isLoggedIn) : ?>
<a href="/categories/create" class="btn btn-success">Create</a>
<?php endif; ?>