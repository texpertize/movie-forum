<?php
/**
 * Administrators can delete a movie
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Delete a movie");
//-------------
if(getCurClass() == 0){
	die("Unauthorized access!");
}

if(isset($_POST["delete"])){
	deleteMovie(sanitize($_POST["imdb"]));
}

if(!isset($_GET["imdb"])){
	?>
<form class="form-horizontal" action="admin_delete_movie.php" method="post">
	<fieldset>

	<div class="input-group">
		<label class="col-md-4 control-label" for="imdb">IMDB</label>  
		<input id="imdb" name="imdb" type="text" placeholder="eg: tt0111161" class="form-control input-md" required=""> &nbsp
		<button id="delete" name="delete" class="btn btn-primary">Delete</button>
	</div>
	
	</fieldset>
</form>
	<?php
}

if(isset($_GET["imdb"]) && !isset($_POST["delete"])){
	deleteMovie(sanitize($_GET["imdb"]));
}

//-------------
stdFoot();
?>