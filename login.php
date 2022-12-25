<?php
/**
 * Login page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Login");

if(isLoggedIn()){
    redirect("index.php");
}
   
?>

<form class="border border-light p-5" action="login_process.php" method="post">

    <p class="h4 mb-4 text-center">Sign in</p>

    <input type="text" name="username" class="form-control mb-4" placeholder="Username">

    <input type="password" name="password" class="form-control mb-4" placeholder="Password">

    <button class="btn btn-info btn-block my-4" type="submit" name="submit">Sign in</button>

    <div class="text-center">
        <p>Not a member? <a href="">Register</a></p>
        <p><a href="">Forgot password?</a></p>
    </div>
</form>

<?php
stdFoot();
?>