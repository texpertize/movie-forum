<?php
/**
 * Login process page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Login");
//-------------
if(isLoggedIn()){
    redirect("index.php");
}

if(isset($_POST['submit'])){
	$username = sanitize($_POST['username']);
	$password = sanitize($_POST['password']);
	
    if(strlen($username) > 0 && strlen($password) > 0){
        logIn($username, $password);
    }else{
        echo "Username and/or password is empty!" . " " . "Try again: <a href='login.php'>Log in</a>";
    }
}

//-------------
stdFoot();
?>