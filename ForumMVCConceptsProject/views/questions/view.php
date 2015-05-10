<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Question: <?= htmlspecialchars($this->question['title']) ?></h3>
        <br>
        From:
        <img src="/<?= $this->question['picture_url'] ?>" width="50" height="50">
        <a href="/account/profile/<?= $this->question['from_user'] ?>"><?= htmlspecialchars($this->question['username']) ?></a>
        <br>
        Number of visits: <?= $this->question['number_of_visits'] ?>
    </div>
    <div class="panel-body">
        <?= htmlspecialchars($this->question['content']) ?>
    </div>
    <div id="answers"></div>
    <script>
        $.ajax({
            url: '/answers/showAnswersForQuestion/' + <?= $this->question['id'] ?>,
            method: 'GET'
        }).success(function(data) {
            $('#answers').html(data);
        });
    </script>
<a href="/answers/create/<?= $this->question['id'] ?>" class="btn btn-success">Add answer</a>
</div>