<div class="custom-form">
    <h1>Register</h1>
    <form action="/account/register" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Username" name="username">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <input type="password" class="form-control" placeholder="Confirm password" name="confirmed-password">
            <input type="text" class="form-control" placeholder="Firstname" name="firstname">
            <input type="text" class="form-control" placeholder="Lastname" name="lastname">
            <input type="text" class="form-control" placeholder="Email" name="email">
            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
        </div>
        <input type="submit" value="Register" class="btn btn-default" type="button">
        Already a member? <a href="/account/login">Login</a>
    </form>
</div>