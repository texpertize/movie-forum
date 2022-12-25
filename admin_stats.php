<?php
/**
 * Administrators can view site statistics
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Visitor Statistics");
//-------------
if(getCurClass() > 0){
	echo "<center>$displayStats</center>";
}else{
	die("Unauthorized access!");
}
//-------------
stdFoot();
?>