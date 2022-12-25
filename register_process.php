<?php
/**
 * Register process page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Register");
//-------------
if(isLoggedIn()){
    redirect("index.php");
}

if(isset($_POST['submit'])){
	$username = sanitize($_POST['username']);
	$password = sanitize($_POST['password']);
	$email = sanitize($_POST['email']);
	
    registerUser($username, $password, $email);
    echo "You successfully registered on our website!" . " " . "Click <a href='login.php'>HERE</a> to log in.";
}else{
    echo "You are not allowed to access this page!";
}

//-------------
stdFoot();
?>