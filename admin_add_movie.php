<?php
/**
 * Administrators can add a movie
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Add a movie");
//-------------
if(getCurClass() == 0){
	die("Unauthorized access!");
}

if(isset($_POST["submit"])){
	
	$imdb = sanitize($_POST["imdb"]);
	$trailer = sanitize($_POST["trailer"]);
	$youtube = sanitize($_POST["youtube"]);
	$amazon = sanitize($_POST["amazon"]);
	
	if(empty($imdb) || empty($trailer)){
		die("IMDB and Trailer are mandatory");
	}
	
	if(empty($youtube) && empty($amazon)){
		die("Youtube and Amazon cannot be both empty!");
	}
	
	addMovie($imdb, $trailer, $youtube, $amazon);
}

?>
<form class="form-horizontal" action="admin_add_movie.php" method="post">
	<fieldset>

		<!-- Form Name -->
		<legend>Add a movie</legend>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="imdb">IMDB</label>  
		  <div class="col-md-4">
		  <input id="imdb" name="imdb" type="text" placeholder="eg: tt0111161" class="form-control input-md" required="">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="trailer">YT Trailer</label>  
		  <div class="col-md-4">
		  <input id="trailer" name="trailer" type="text" placeholder="eg: 6hB3S9bIaco" class="form-control input-md" required="">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="youtube">Youtube</label>  
		  <div class="col-md-4">
		  <input id="youtube" name="youtube" type="text" placeholder="" class="form-control input-md">
			
		  </div>
		</div>

		<!-- Text input-->
		<div class="form-group">
		  <label class="col-md-4 control-label" for="amazon">Amazon</label>  
		  <div class="col-md-4">
		  <input id="amazon" name="amazon" type="text" placeholder="" class="form-control input-md">
			
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
//-------------
stdFoot();
?>