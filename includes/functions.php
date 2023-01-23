<?php
include "includes/config.php";

//====DATABASE FUNCTIONS===
/**
 * Connect to database
 * @author Ankit
 */
function dbconnect(){
    global $connection;
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    
    $query = "SET NAMES utf8";
    mysqli_query($connection, $query);
}


//===GENERAL FUNCTIONS===
/**
 * Redirect the user to another page
 * @param string $location page name (eg: index.php)
 */
function redirect($location){
    header("Location:" . $location);
    exit;
}

/**
 * Sanitize data to enhance website security
 * @param string $data the data that needs to be sanitized
 */
function sanitize($data){
	$data = htmlspecialchars($data);
	$data = strip_tags($data);
	return $data;
}

/**
 * Prepare the string before database insert
 * @param  string $string string to prepare
 * @return string the string after preparation
 */
function escape($string){
    global $connection;
    
    return mysqli_real_escape_string($connection, trim($string));
}


//===MOVIES FUNCTIONS===
/**
 * Load the xml file
 * @param string $imdb unique identifier (eg: tt5463162)
 */
function getXML($imdb){
    global $xml;
    
    $xml_file = "http://www.omdbapi.com/?apikey=" . OMDB_API . "&i=" . $imdb . "&r=xml";
    $xml = simplexml_load_file($xml_file) or die("Error: Cannot create object");
}

/**
 * Check if a movie exists in the database
 * @param  string $imdb imdbID of the movie
 * @return boolean  true if the movie exists, false otherwise
 */
function movieExists($imdb){
	global $connection;
    
    $query = "SELECT `imdb` FROM `movies` WHERE `imdb` = '{$imdb}' ";
    $select_query = mysqli_query($connection, $query);
    
    if(!$select_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    while ($row = mysqli_fetch_array($select_query)) {
        if($imdb === $row["imdb"]){
            return true;
        }
    }
    return false;
}

/**
 * Add a movie in the database
 * @param string $imdb    imdbID (eg: tt5463162)
 * @param string $trailer movie trailer; value of v paramater in the youtube link (full link: https://www.youtube.com/watch?v=D86RtevtfrA; what to insert: D86RtevtfrA)
 * @param string $youtube youtube movie link; value of v paramater in the youtube link (full link: https://www.youtube.com/watch?v=GsmPaN8HYpQ; what to insert: GsmPaN8HYpQ)
 * @param string $amazon  amazon movie link; value after the last slash (full link: https://www.amazon.co.uk/gp/video/detail/B07CYZY5GS; what to insert: B07CYZY5GS)
 */
function addMovie($imdb, $trailer, $youtube, $amazon){
	global $connection;
	
	if(!movieExists($imdb)){
		$query = "INSERT INTO `movies` (`imdb`, `trailer`, `youtube`, `amazon`) VALUES ('$imdb', '$trailer', '$youtube', '$amazon')";
		$insert = mysqli_query($connection, $query);
		
		if(!$insert){
			die("QUERY FAILED" . mysqli_error($connection));
		}
		
		redirect("movie.php?imdb=".$imdb);
	}else{
		die("Movie already in the database!");
	}
}

/**
 * Update movie in the database
 * @param string $imdb    imdbID (eg: tt5463162)
 * @param string $trailer youtube movie trailer (eg: D86RtevtfrA)
 * @param string $youtube youtube movie link (eg: GsmPaN8HYpQ)
 * @param string $amazon  amazon movie link (eg: B07CYZY5GS)
 */
function updateMovie($imdb, $trailer, $youtube, $amazon){
	global $connection;
	
	if(movieExists($imdb)){
		$query = "UPDATE `movies` SET `trailer` = '{$trailer}', `youtube` = '{$youtube}', `amazon` = '{$amazon}' WHERE `imdb` = '{$imdb}'";
		$update = mysqli_query($connection, $query);
		
		if($update){
			redirect("movie.php?imdb=".$imdb);
		}else{
			die("QUERY FAILED" . mysqli_error($connection));
		}
		
	}else{
		die("Movie not in the database!");
	}
}

/**
 * Delete movie from database
 * @param string $imdb imdbID (eg: tt5463162)
 */
function deleteMovie($imdb){
	global $connection;
	
	if(movieExists($imdb)){
		$query = "DELETE FROM `movies` WHERE `imdb` = '{$imdb}'";
		$delete = mysqli_query($connection, $query);
		
		if($delete){
			echo "Movie deleted!";
		}else{
			die("QUERY FAILED" . mysqli_error($connection));
		}
	}else{
		die("Movie not in the database!");
	}
}

/**
 * Search movies by criteria
 * @param string $keywords keywords to search for (eg: deadpool)
 * @param integer $year     year of the movie (eg: 2018)
 * @param integer $rating   minimum movie rating (eg: 7)
 * @param string $director director of the movie (eg: David Leitch)
 * @param string $star     stars from the movie (eg: Ryan Reynolds)
 * @param string $genre    genre of the movie (eg: comedy)
 */
function searchMovies($keywords, $year, $rating, $director, $star, $genre){
	global $connection, $xml;
	
	$query = "SELECT * FROM `movies`";
	$select_movie_query = mysqli_query($connection, $query);
	$movies = array();

	if(!$select_movie_query){
		die("QUERY FAILED" . mysqli_error($connection));
	}

	while($row = mysqli_fetch_assoc($select_movie_query)){
		getXML($row["imdb"]);
		
		if(!empty($keywords)){
			$words = explode(" ", $keywords);
			
			foreach($words as $word){
				if (stripos($xml->movie[0]['title'], $word) !== false) {
					$movies[] = $xml->movie[0]['imdbID'];
				}
			}
		}else{
			echo "You have to type at least one keyword!";
			break;
		}
	}
	
	if(!empty($year) && !empty($movies)){
		foreach($movies as $key => $movie){
			getXML($movie);
			
			if($xml->movie[0]['year'] != $year){
				unset($movies[$key]);
			}
		}
	}
	
	if(!empty($rating) && !empty($movies)){
		foreach($movies as $key => $movie){
			getXML($movie);
			
			if(round($xml->movie[0]['imdbRating']) < $rating){
				unset($movies[$key]);
			}
		}
	}
	
	if(!empty($director) && !empty($movies)){
		$all_directors = explode(" ", $director);
		$movie_match_criteria = array();
		
		foreach($movies as $key => $movie){
			getXML($movie);
			
			foreach($all_directors as $directorKey => $directorName){
				if(stripos($xml->movie[0]['director'], $directorName) !== false){
					$movie_match_criteria[] = $xml->movie[0]['imdbID'];
				}
			}
		}
		
		if(!empty($movie_match_criteria)){
			$movies = $movie_match_criteria;
		}
	}
	
	if(!empty($star) && !empty($movies)){
		$all_stars = explode(" ", $star);
		$movie_match_criteria = array();
		
		foreach($movies as $key => $movie){
			getXML($movie);
			
			foreach($all_stars as $starKey => $starName){
				if(stripos($xml->movie[0]['actors'], $starName) !== false){
					$movie_match_criteria[] = $xml->movie[0]['imdbID'];
				}
			}
		}
		
		if(!empty($movie_match_criteria)){
			$movies = $movie_match_criteria;
		}
	}
	
	if(!empty($genre) && !empty($movies)){
		foreach($movies as $key => $movie){
			getXML($movie);
			
			foreach($genre as $gen){
				if(stripos($xml->movie[0]['genre'], $gen) === false){
					unset($movies[$key]);
				}
			}
		}
	}
	
	if(!empty($movies)){
		if(isLoggedIn()){
			displayMovies($movies, 1);
		}else{
			echo "<center>You must be logged in to access this feature! :(</center><br>";
		}
	}else{
		echo "No movie found using your criteria!";
	}
}

/**
 * Display movie recommendations
 * @param integer $userid      id of the logged in user
 */
function movieRecommendations($userid){
	global $connection, $xml;
	$selected_movies = array();
	$limit = 4;
	
	$query = "SELECT * FROM `users` WHERE `id` = '{$userid}' ";
	$user_query = mysqli_query($connection, $query);
	
	if(!$user_query){
		die("QUERY FAILED: " . mysqli_error($connection) . $query);
	}
	
	if($userRow = mysqli_fetch_assoc($user_query)){
		$userGenres = explode(",", $userRow["genres"]);
		
		$query = "SELECT * FROM `movies`";
		$movie_query = mysqli_query($connection, $query);
		
		if(!$movie_query){
			die("QUERY FAILED: " . mysqli_error($connection) . $query);
		}
		
		while($row = mysqli_fetch_assoc($movie_query)){
			getXML($row["imdb"]);
			$movieGenres = explode(", ", $xml->movie[0]['genre']);
			
			foreach($movieGenres as $mgenre){
				if(in_array(strtolower($mgenre), $userGenres)){
					$selected_movies[] = $xml->movie[0]['imdbID'];
				}
			}
		}
	}
	
	if(!empty($selected_movies)){
		$random = array_rand($selected_movies, $limit);
		$movies = array();
		
		foreach($random as $key => $index){
			$movies[] = $selected_movies[$index];
		}
		
		displayMovies($movies, 1);
	}else{
		echo "No recommendation could be made! Edit your preferences accessing your <a href='profile.php?id={$_SESSION['user_id']}'>Profile</a> page.";
	}	
}

/**
 * Display best rated movies
 * @param integer $year      year of the movie (or first year if the second parameter is defined)
 * @param integer [$to=NULL] to this year
 */
function bestMoviesFromYear($year, $to=NULL){
	global $connection, $xml;
	$selected_movies = array();
	$limit = 4;
	
	$query = "SELECT * FROM `movies`";
	$filter_movie_query = mysqli_query($connection, $query);
	
	if(!$filter_movie_query){
		die("QUERY FAILED: " . mysqli_error($connection) . $query);
	}
	
	while($row = mysqli_fetch_assoc($filter_movie_query)){
		getXML($row["imdb"]);
		$movieFromDatabaseYear = $xml->movie[0]['year'];
		
		if(!empty($to)){
			if($movieFromDatabaseYear >= $year && $movieFromDatabaseYear <= $to){
				if(!empty($selected_movies) && count($selected_movies) == $limit){
					$movieFromDatabaseRating = $xml->movie[0]['imdbRating'];
					$lowest_rating = 10.0;
					
					for($i = 0; $i < $limit; $i++){
						getXML($selected_movies[$i]);
						
						if(bccomp($xml->movie[0]['imdbRating'], $lowest_rating, 1) == -1){
							$lowest_rating = $xml->movie[0]['imdbRating'];
							$lowest_rating_index = $i;
						}
					}
					
					getXML($selected_movies[$lowest_rating_index]);
					
					if(bccomp($xml->movie[0]['imdbRating'], $movieFromDatabaseRating, 1) == -1)
						$selected_movies[$lowest_rating_index] = $row["imdb"];
					
				}else{		
					$selected_movies[] = $xml->movie[0]['imdbID'];
				}
			}
		}else{
			if($movieFromDatabaseYear == $year){		
				if(!empty($selected_movies) && count($selected_movies) == $limit){
					$movieFromDatabaseRating = $xml->movie[0]['imdbRating'];
					$lowest_rating = 10.0;
					
					for($i = 0; $i < $limit; $i++){
						getXML($selected_movies[$i]);
						
						if(bccomp($xml->movie[0]['imdbRating'], $lowest_rating, 1) == -1){
							$lowest_rating = $xml->movie[0]['imdbRating'];
							$lowest_rating_index = $i;
						}
					}
					
					getXML($selected_movies[$lowest_rating_index]);
					
					if(bccomp($xml->movie[0]['imdbRating'], $movieFromDatabaseRating, 1) == -1)
						$selected_movies[$lowest_rating_index] = $row["imdb"];
					
				}else{		
					$selected_movies[] = $xml->movie[0]['imdbID'];
				}
			}
		}
	}
	
	if(!empty($selected_movies)){
		displayMovies($selected_movies, 1);
	}else{
		echo "No movie found using your criteria";
	}
}

/**
 * Sort the movies by rating
 * @param  array $movies list of the movies to be sorted by rating
 * @return array movie list sorted by rating
 */
function sortByRating($movies){
	global $xml;
	$ratings = array();
	$movies_sorted = array();
	
	foreach($movies as $index => $title){
		getXML($title);
		
		$ratings[(string) $title] = (float) $xml->movie[0]['imdbRating'];
	}
	
	arsort($ratings);
	
	foreach($ratings as $title => $rating)	
		$movies_sorted[] = $title;
	
	return $movies_sorted;
}

/**
 * Display movies from a specific list
 * @param array $movies       list of movies to be displayed
 * @param integer $ratingsorted 1 if you want to display the movies sorted by rating
 */
function displayMovies($movies, $ratingsorted){
	
	if(!empty($movies) && !empty($ratingsorted)){
		$movie_count = 1;
		
		if($ratingsorted == 1)
			$movies = sortByRating($movies);
		
		foreach($movies as $movie){
			getXML($movie);
			
			switch($movie_count){
				case 1:
					echo "<div class='card-deck'>";
					movieCard();
					$movie_count++;
					break;
				case 4:
					movieCard();
					echo "  </div>  <!-- Card -->";
					$movie_count = 1;
					break;
				default:
					movieCard();
					$movie_count++;
					break;
			}
		}	
	}else{
		echo "No movies found!";
	}
}

/**
 * Display all movies available in the database
 */
function displayAllMovies(){
	global $connection;
	
	$query = "SELECT * FROM `movies` ";
		
	$select_movie_query = mysqli_query($connection, $query);

	if(!$select_movie_query){
		die("QUERY FAILED" . mysqli_error($connection) . $query);
	}

	$movie_count = 1;
	
	echo "<div class='container mt-3'>    <div class='row'>";
	
	while($row = mysqli_fetch_assoc($select_movie_query)){
		
		getXML($row["imdb"]);
		
		switch($movie_count){
			case 1:
				echo "<div class='card-deck'>";
				movieCard();
				$movie_count++;
				break;
			case 4:
				movieCard();
				echo "  </div>  <!-- Card -->";
				$movie_count = 1;
				break;
			default:
				movieCard();
				$movie_count++;
				break;
		}      
	}
	
	echo "</div></div>";
}

/**
 * Movie card
 */
function movieCard(){
    global $xml;
    
    ?>
  <!-- Card -->
  <div class="card mb-4" style="min-width: 14rem; max-width: 14rem;">

    <!--Card image-->
    <div class="view overlay">
      <img class="card-img-top" src="<?php echo $xml->movie[0]['poster'] ?>" alt="Card image cap" width="229" height="325">
    </div>

    <!--Card content-->
    <div class="card-body">

      <!--Title-->
      <center><a href="movie.php?imdb=<?php echo $xml->movie[0]['imdbID'] ?>"><h5 class="card-title"><?php echo $xml->movie[0]['title'] ?> (<?php echo $xml->movie[0]['year'] ?>)</h5></a></center>

	  <?php if (getCurClass() > 0){ ?>
	  <div class="float-right">
		  <a href="admin_edit_movie.php?imdb=<?php echo $xml->movie[0]['imdbID'] ?>"><img src="style/edit.png" width="14px" height="14px"></a> 
		  <a href="admin_delete_movie.php?imdb=<?php echo $xml->movie[0]['imdbID'] ?>"><img src="style/del.png" width="14px" height="14px"></a>
	  </div>
	  <?php } ?>
    </div>
	
  </div>
  <!-- Card -->
    <?php
}

//====USER FUNCTIONS===
/**
 * Login function to enable user authentication
 * @param string $username username to be logged with
 * @param string $password password for that username
 */
function logIn($username, $password){
    global $connection;
    
    $username = escape($username);
    $password = escape($password);
    
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    
    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    while ($row = mysqli_fetch_array($select_user_query)) {
        if(password_verify($password, $row["password"])){
            $_SESSION['user_id'] = $row["id"];
            redirect("index.php");
        }else{
            echo "Password is wrong!";
        }
    }
}

/**
 * Register function to enable user registration
 * @param string $username desired username
 * @param string $password desired password
 * @param string $email    desired email
 */
function registerUser($username, $password, $email){
    global $connection;
    
    $username = escape($username);
    $password = escape($password);
    $email = escape($email);
    
    $password = encryptPassword($password); //encrypt the password

    $query = "INSERT INTO users(username, password, email) VALUES ('$username','$password','$email')";
    mysqli_query($connection, $query);
    
}

/**
 * Change user password function; called by the user
 * @param integer $id   id of the user
 * @param string $old  old password
 * @param string $new  new password
 * @param string $conf confirm new password
 */
function changeUserPassword($id, $old, $new, $conf){
	global $connection;
	
	$newPassword = escape($new);
	$encrypted = encryptPassword($newPassword);

    $query = "SELECT * FROM users WHERE id = '{$id}' ";
    $select_query = mysqli_query($connection, $query);
    
    if (!$select_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
	
	if($row = mysqli_fetch_assoc($select_query)){
		if(password_verify($old, $row["password"])){
			if($new === $conf){
				$query = "UPDATE `users` SET `password` = '" . $encrypted . "' WHERE `id` = '" . $id . "'";
				$update_query = mysqli_query($connection, $query);
				if(!$update_query){
					die("QUERY FAILED" . mysqli_error($connection));
				}else{
					echo "Password updated!";
				}
			}else{
				die("'New Password' needs to match 'Confirm Password'");
			}
		}else{
			die("Old Password doesn't match");
		}
	}
}

/**
 * Update user password function; called by administrator
 * @param integer $id   id of the user subjected to password change
 * @param string $pass new password
 */
function updateUserPassword($id, $pass){
	global $connection;
	
	$password = escape($pass);
	$encrypted = encryptPassword($password);
	
    $query = "SELECT * FROM users WHERE id = '{$id}' ";
    $select_query = mysqli_query($connection, $query);
    
    if (!$select_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
	
	if($row = mysqli_fetch_assoc($select_query)){
		$query = "UPDATE `users` SET `password` = '" . $encrypted . "' WHERE `id` = '" . $id . "'";
		$update_query = mysqli_query($connection, $query);
		
		if(!$update_query){
			die("QUERY FAILED" . mysqli_error($connection));
		}else{
			echo "Password updated!";
		}
	}
}

/**
 * Update user's email; called by administrator
 * @param integer $id    id of the user subjected to email change
 * @param string $email new email
 */
function updateEmail($id, $email){
    global $connection;
    
    $query = "SELECT * FROM users WHERE id = '{$id}' ";
    $select_query = mysqli_query($connection, $query);
    
    if(!$select_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    if ($row = mysqli_fetch_array($select_query)) {
		$query = "UPDATE `users` SET `email` = '" . $email . "' WHERE `id` = '" . $id . "'";
		$update_query = mysqli_query($connection, $query);
		
		if(!$update_query){
			die("QUERY FAILED" . mysqli_error($connection));
		}else{
			echo "Email updated!";
		}
    }
}

/**
 * Update user's preferences regarding movie tastes
 * @param integer $userid    id of the user subjected to preference change
 * @param string $preference the preference that needs to be changed eg: genres
 * @param array $list the list of new preferences eg: action, animation, comedy
 */
function updatePreferences($userid, $preference, $list){
    global $connection;
    
    $query = "SELECT * FROM `users` WHERE `id` = '{$userid}' ";
    $select_query = mysqli_query($connection, $query);
    
    if(!$select_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    if ($row = mysqli_fetch_array($select_query)) {
		$items = NULL;
		
		foreach($list as $item){
			$items .= trim(sanitize($item)) . ",";
		}
		
		$items = rtrim($items, ",");
		
		$query = "UPDATE `users` SET `{$preference}` = '" . $items . "' WHERE `id` = '" . $userid . "'";
		$update_query = mysqli_query($connection, $query);
		
		if(!$update_query){
			die("QUERY FAILED" . mysqli_error($connection));
		}else{
			echo "Preferences updated!";
		}
    }	

}

/**
 * Encrypt the password for user registration
 * @param  string $password password to be encrypted
 * @return string the encrypted password
 */
function encryptPassword($password){
    $hashFormat = "$2y$10$";
    $salt = "syvwvpolgdjqsjnpgbacoq";
    $hash_and_salt = $hashFormat . $salt;
    $encrypted_password = crypt($password, $hash_and_salt);
    
    return $encrypted_password;
}

/**
 * Check if the user is logged in
 * @return boolean true if the user is logged, false otherwise
 */
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    }
    return false;
}

/**
 * Logout the user
 */
function logOut(){
    $_SESSION['user_id'] = null;
}

/**
 * Get username of the logged in user
 * @return username of the logged in user, false otherwise
 */
function getUsername(){
    global $connection;
    
    $query = "SELECT username FROM users WHERE id = '{$_SESSION['user_id']}' ";
    $select_query = mysqli_query($connection, $query);
    
    if(!$select_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    if ($row = mysqli_fetch_array($select_query)) {
        return $row["username"];
    }
    return false;
   
}

/**
 * Get class of the logged in user
 * @return integer/string 0 for user, 1 for administrator, Visitor if the user is not logged in
 */
function getCurClass(){
	global $connection;
	
	if(isLoggedIn()){
		$query = "SELECT class FROM users WHERE id = '{$_SESSION['user_id']}' ";
		$select_query = mysqli_query($connection, $query);
		
		if(!$select_query){
			die("QUERY FAILED" . mysqli_error($connection));
		}
		
		if ($row = mysqli_fetch_array($select_query)) {
			return $row["class"];
		}
	}else{
		return "Visitor";
	}
}

/**
 * Check if the username already exists in the database
 * @param  string $username username to check for
 * @return boolean  true if exists, false otherwise
 */
function usernameExists($username){
    global $connection;
    
    $username = escape($username);
    
    $query = "SELECT username FROM users WHERE username = '{$username}' ";
    $select_query = mysqli_query($connection, $query);
    
    if(!$select_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
    while ($row = mysqli_fetch_array($select_query)) {
        if($username === $row["username"]){
            return true;
        }
    }
    return false;
}

?>
