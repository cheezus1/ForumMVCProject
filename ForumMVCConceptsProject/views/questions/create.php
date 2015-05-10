<div class="custom-form">
    <h1>Add question</h1>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Title" name="title">
            <textarea class="form-control" placeholder="Content" name="content"></textarea>
            <input type="text" class="form-control" placeholder="Tags" name="tags">
        </div>
        <input type="submit" class="btn btn-default" value="Add question">
    </form>
</div>