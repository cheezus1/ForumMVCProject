
<?php if(isset($_SESSION['messages'])) : ?>
    <div class="messages">
        <?php foreach($_SESSION['messages'] as $msg) : ?>
            <div class="alert alert-<?= $msg['type'] ?>" role="alert"><?= $msg['text'] ?></div>
        <?php
        endforeach;
        unset($_SESSION['messages']);
        ?>
    </div>
<?php endif; ?>
