<?php
/**
 * Movies page
 * Page where all the available movies from our database are displayed
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Movies");
//-------------
if(isset($_POST["submit"])){
	$url = "movies.php?";
	$errors = array();
	
	if(!empty($_POST["keywords"]))
		$url .= "search=" . sanitize($_POST["keywords"]) . "&";
	
	if(!empty($_POST["year"]))
		$url .= "year=" . sanitize($_POST["year"]) . "&";
	
	if(!empty($_POST["rating"]))
		$url .= "rating=" . sanitize($_POST["rating"]) . "&";
	
	if(!empty($_POST["director"]))
		$url .= "director=" . sanitize($_POST["director"]) . "&";
		
	if(!empty($_POST["star"]))
		$url .= "star=" . sanitize($_POST["star"]) . "&";
	
	if(!empty($_POST["genres"])){
		$url .= "genre=";
		foreach($_POST["genres"] as $genre){
			$url .= trim(sanitize($genre)) . ",";
		}
		$url = rtrim($url, ",");
	}		
	
	$url = rtrim($url, "&");
	redirect($url);
}

displayMovieSearch();

if(isset($_GET["search"])){
	
	if(isset($_GET["year"])){
		$year = $_GET["year"];
	}else{
		$year = NULL;
	}
	
	if(isset($_GET["rating"])){
		$rating = $_GET["rating"];
	}else{
		$rating = NULL;
	}
	
	if(isset($_GET["director"])){
		$director = $_GET["director"];
	}else{
		$director = NULL;
	}
	
	if(isset($_GET["star"])){
		$star = $_GET["star"];
	}else{
		$star = NULL;
	}
	
	if(isset($_GET["genre"])){
		$genre = explode(",", $_GET["genre"]);
	}else{
		$genre = NULL;
	}

	searchMovies(sanitize($_GET["search"]), sanitize($year), sanitize($rating), sanitize($director), sanitize($star), $genre);
}else{
	displayAllMovies();
}

function displayMovieSearch(){
?>
	<form class="form-horizontal" action="movies.php" method="post">
		<fieldset>

			<!-- Form Name -->
			<legend>Search</legend>
			
			<div class="form-row">
				<div class="form-group col-md-6">
				  <input id="keywords" name="keywords" type="text" placeholder="keywords" class="form-control" value="<?php if (!empty($_GET["search"])) echo sanitize($_GET["search"]); ?>" required>
				</div>
				
				<div class="form-group col-md-4">
				  <input id="director" name="director" type="text" placeholder="director(s)" class="form-control" value="<?php if (!empty($_GET["director"])) echo sanitize($_GET["director"]); ?>">
				</div>
				
				<!-- Button -->
				<div class="form-group col-md-2">
				  <div class="col-md-2">
					<button id="submit" name="submit" class="btn btn-primary">Search</button>
				  </div>
				</div>
			</div>
			
			<div class="form-row">	
				<div class="form-group col-md-5">
				  <input id="star" name="star" type="text" placeholder="star" class="form-control" value="<?php if (!empty($_GET["star"])) echo sanitize($_GET["star"]); ?>">
				</div>
				
				<div class="col-1">
				  <input id="year" name="year" type="number" placeholder="year" class="form-control" min="1900" max="2020" value="<?php if (!empty($_GET["year"])) echo sanitize($_GET["year"]); ?>">
				</div>
				
				<div class="col-1-2">
				  <input id="rating" name="rating" type="number" placeholder="rating" class="form-control" min="1" max="9" value="<?php if (!empty($_GET["rating"])) echo sanitize($_GET["rating"]); ?>">
				</div>
				
				<!-- Select Multiple -->
				<div class="col-3">
					<select id="genres" name="genres[]" class="custom-select" multiple="multiple">
						<?php 
						$genres = file(FILE_GENRES);
						foreach($genres as $genre){
							echo "<option value='" . strtolower($genre) . "'>$genre</option>";
						}
						?>
					</select>
				</div>
			</div>

		</fieldset>
	</form>
	
	<br>
<?php
}

//-------------
stdFoot();
?>