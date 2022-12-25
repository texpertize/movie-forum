<?php
/**
 * Administrators can edit a movie
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Edit a movie");
//-------------
if(getCurClass() == 0){
	die("Unauthorized access!");
}

if(isset($_POST["find"])){
	if(movieExists(sanitize($_POST["imdb"]))){
		redirect("admin_edit_movie.php?imdb=".$_POST["imdb"]);
	}else{
		echo "Movie not in the database";
	}
}

if(isset($_POST["submit"])){
	if(empty($_POST["imdb"]) || empty($_POST["trailer"])){
		die("IMDB and Trailer are mandatory");
	}
	
	if(empty($_POST["youtube"]) && empty($_POST["amazon"])){
		die("Youtube and Amazon cannot be both empty!");
	}
	
	updateMovie(sanitize($_POST["imdb"]), sanitize($_POST["trailer"]), sanitize($_POST["youtube"]), sanitize($_POST["amazon"]));
}
	
if(!isset($_GET["imdb"])){
	?>
<form class="form-horizontal" action="admin_edit_movie.php" method="post">
	<fieldset>

	<div class="input-group">
		<label class="col-md-4 control-label" for="imdb">IMDB</label>  
		<input id="imdb" name="imdb" type="text" placeholder="eg: tt0111161" class="form-control input-md" required=""> &nbsp
		<button id="find" name="find" class="btn btn-primary">Find</button>
	</div>
	
	</fieldset>
</form>
	<?php
}else{
	$getIMDB = sanitize($_GET["imdb"]);
	if(movieExists($getIMDB)){
		$query = "SELECT * FROM `movies` WHERE `imdb` = '{$getIMDB}'";
		$select_query = mysqli_query($connection, $query);
		
		if(!$select_query){
			die("QUERY FAILED" . mysqli_error($connection));
		}
		
		$row = mysqli_fetch_array($select_query);
		
		$imdb = sanitize($row["imdb"]);
		$trailer = sanitize($row["trailer"]);
		$youtube = sanitize($row["youtube"]);
		$amazon = sanitize($row["amazon"]);
	}else{
		redirect("admin_edit_movie.php");
	}
	?>
<form class="form-horizontal" action="admin_edit_movie.php" method="post">
	<fieldset>

		<!-- Form Name -->
		<legend>Edit a movie</legend>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="imdb">IMDB</label>  
		  <div class="col-md-4">
		  <input id="imdb" name="imdb" type="text" value="<?php echo $imdb; ?>" class="form-control input-md" readonly>
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="trailer">YT Trailer</label>  
		  <div class="col-md-4">
		  <input id="trailer" name="trailer" type="text" value="<?php echo $trailer; ?>" class="form-control input-md" required="">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="youtube">Youtube</label>  
		  <div class="col-md-4">
		  <input id="youtube" name="youtube" type="text" value="<?php echo $youtube; ?>" class="form-control input-md">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="amazon">Amazon</label>  
		  <div class="col-md-4">
		  <input id="amazon" name="amazon" type="text" value="<?php echo $amazon; ?>" class="form-control input-md">
			
		  </div>
		</div>

		<!-- Button -->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="submit"></label>
		  <div class="col-md-4">
			<button id="submit" name="submit" class="btn btn-primary">Submit</button>
		  </div>
		</div>

	</fieldset>
</form>
	
	<?php
}
//-------------
stdFoot();
?>