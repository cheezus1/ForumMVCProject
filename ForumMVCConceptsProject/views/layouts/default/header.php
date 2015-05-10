<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/content/styles.css">
    <title>
        <?php if(isset($this->title))
            echo htmlspecialchars($this->title) ?>
    </title>
</head>
<body>
    <div class="container"></div>
        <header>
            <a href="/"><img src="/content/images/forum-banner.png" width="200" height="150"></a>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="/">Home</a></li>
                            <li><a href="/categories">Categories</a></li>
                            <li><a href="/questions">Questions</a></li>
                        </ul>
                        <form class="navbar-form navbar-left" role="search" action="/questions/showQuestionsByTags">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search by tags" name="search-input">
                            </div>
                            <button type="submit" class="btn btn-default">Search</button>
                        </form>
                    </div>
                </div>

            </nav>
            <?php if($this->isLoggedIn) : ?>
                <div id="logged-in-info">
                    <img src="/<?= htmlspecialchars($_SESSION['picture_url']) ?>" width="50" height="50">
                    <br>
                    <span>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    <br>
                    <a href="/account/profile/<?= $_SESSION['user_id'] ?>">Profile</a>
                    <br>
                    <form action="/account/logout">
                        <input type="submit" class="btn btn-default" value="Logout">
                    </form>
                </div>
            <?php endif; ?>
        </header>

        <?php include '/views/layouts/messages.php'; ?>