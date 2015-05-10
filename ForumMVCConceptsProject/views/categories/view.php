
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Category: <?= htmlspecialchars($this->category['name']) ?></h3>
    </div>
    <div class="panel-body">
        <?= htmlspecialchars($this->category['description']) ?>
    </div>
    <div id="questions"></div>
    <script>
        $.ajax({
            url: '/questions/showQuestionsForCategory/' + <?= $this->category['id'] ?>,
            method: 'GET'
        }).success(function(data){
            $('#questions').html(data);
        });
    </script>
    <?php if($this->isLoggedIn) : ?>
        <a href="/questions/create/<?= $this->category['id'] ?>" class="btn btn-success">Add question</a>
    <?php endif; ?>
</div>