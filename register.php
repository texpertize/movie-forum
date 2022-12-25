<?php
/**
 * Register page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Register");

if(isLoggedIn()){
    redirect("index.php");
}

//-------------
?>

<form class="border border-light p-5" action="register_process.php" method="post">

    <p class="h4 mb-4 text-center">Sign up</p>

    <input type="text" name="username" class="form-control" placeholder="Username">

    <input type="password" name="password" class="form-control" placeholder="Password">

    <small class="form-text text-muted mb-4">Minimal 8 characters lenght</small>
    
    <input type="email" name="email" class="form-control mb-4" placeholder="E-mail">

    <button class="btn btn-info my-4 btn-block" type="submit" name="submit">Sign up</button>

</form>

<?php
//-------------
stdFoot();
?>