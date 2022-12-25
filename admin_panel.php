<?php
/**
 * Display all the functions available for administrators
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Admin Panel");
//-------------
if(getCurClass() > 0){
	?>
	<center>
	<a href="admin_stats.php" class="btn btn-primary">View site statistics</a> &nbsp
	<a href="admin_add_movie.php" class="btn btn-primary">Add a movie</a> &nbsp
	<a href="admin_edit_movie.php" class="btn btn-primary">Edit a movie</a> &nbsp
	<a href="admin_delete_movie.php" class="btn btn-primary">Delete a movie</a>
	</center>
	<?php
}else{
	die("Unauthorized access!");
}
//-------------
stdFoot();
?>