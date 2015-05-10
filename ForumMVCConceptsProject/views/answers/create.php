<div class="custom-form">
    <h1>Add answer</h1>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="input-group">
            <?php if(!$this->isLoggedIn) : ?>
                <input type="text" class="form-control" placeholder="Firstname" name="firstname">
                <input type="text" class="form-control" placeholder="Lastname" name="lastname">
                <input type="text" class="form-control" placeholder="Email" name="email">
            <?php endif; ?>
            <textarea class="form-control" placeholder="Content" name="content"></textarea>
        </div>
        <input type="submit" class="btn btn-default" value="Add answer">
    </form>
</div>