<?php
/**
 * Logout page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Logout");
//-------------

logOut();
redirect("index.php");

//-------------
stdFoot();
?>