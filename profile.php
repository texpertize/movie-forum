<?php
/**
 * Profile page
 * Page where user can view their profile and change password; also users can view other user's profile but with limited information; administrators are able to access users password
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Profile");
//-------------
if(isset($_SESSION["user_id"]) && isset($_GET["id"])){
	$id = sanitize($_GET["id"]);
	$id = filter_var($id, FILTER_VALIDATE_INT);
	$CURUSER = $_SESSION["user_id"];
}else{
	redirect("index.php");
}

$query = "SELECT * FROM `users` WHERE `id` = '{$id}'";
$select_query = mysqli_query($connection, $query);

if(!$select_query){
    die("QUERY FAILED" . mysqli_error($connection));
}

if($row = mysqli_fetch_assoc($select_query)){
	$username = $row["username"];
	$email = $row["email"];
	$class = $row["class"];
	$genres_pref = explode(",", $row["genres"]);
}else{
	redirect("index.php"); //redirect the user if the user doesn't exist
}

if(isset($_POST["submit"])){

	if(!empty($_POST["oldpassword"]) && !empty($_POST["newpassword"]) && !empty($_POST["confirmpassword"])){
		echo "You cannot change your password. You need to contact an administrator!";
		//changeUserPassword($CURUSER, $_POST["oldpassword"], $_POST["newpassword"], $_POST["confirmpassword"]);
	}

	if(getCurClass() > 0 && !empty($_POST["confirmpassword"])){
		updateUserPassword($_GET["id"], $_POST["confirmpassword"]);
	}
	
	if(!empty($_POST["email"])){
		if($_POST["email"] != $email){
			updateEmail($CURUSER, $_POST["email"]);
		}
	}
	
	if(!empty($_POST["genres"])){
		updatePreferences($CURUSER, "genres", $_POST["genres"]);
	}
}

?>
 
<form class="form-horizontal" action="profile.php?id=<?php echo $CURUSER; ?>" method="post">
	<fieldset>

	<!-- Form Name -->
	<legend><?php echo $username; ?>'s Profile</legend>

	<!-- Text input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="username">Username</label>  
	  <div class="col-md-4">
		<input id="username" name="username" type="text" placeholder="<?php echo $username; ?>" class="form-control input-md" readonly>
	  </div>
	</div>

	<!-- Text input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="class">Class</label>  
	  <div class="col-md-4">
		<input id="class" name="class" type="text" placeholder="<?php echo $class; ?>" class="form-control input-md" readonly> 
	  </div>
	</div>

	<?php if($id == $CURUSER || getCurClass() > 0){ ?>
	<!-- Text input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="email">Email</label>  
	  <div class="col-md-4">
		<input id="email" name="email" type="text" placeholder="<?php echo $email; ?>" class="form-control input-md">
	  </div>
	</div>

	<!-- Password input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="oldpassword">Old Password</label>
	  <div class="col-md-4">
		<input id="oldpassword" name="oldpassword" type="password" placeholder="" class="form-control input-md">
	  </div>
	</div>

	<!-- Password input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="newpassword">New Password</label>
	  <div class="col-md-4">
		<input id="newpassword" name="newpassword" type="password" placeholder="" class="form-control input-md">
	  </div>
	</div>

	<!-- Password input-->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="confirmpassword">Confirm Password</label>
	  <div class="col-md-4">
		<input id="confirmpassword" name="confirmpassword" type="password" placeholder="" class="form-control input-md">
	  </div>
	</div>
	
	<hr>
	
	<!-- PREFERENCES: GENRE -->
	<legend>Movie preferences</legend>
	
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="genres">Genres</label>
	  <div class="col-md-4">
		<select id="genres" name="genres[]" class="custom-select" multiple="multiple">
			<?php 
			$genres = file(FILE_GENRES);
			foreach($genres as $genre){
				if(in_array(strtolower($genre), $genres_pref)){ //i don't get why this is not working
					echo "<option value='" . strtolower($genre) . "' selected>$genre</option>";
				}else{
					echo "<option value='" . strtolower($genre) . "'>$genre</option>";
				}
			}
			?>
		</select>
	  </div>
	</div>


	<!-- Button -->
	<div class="form-group form-inline">
	  <label class="col-md-4 control-label" for="submit"></label>
	  <div class="col-md-4">
		<button id="submit" name="submit" class="btn btn-default">Submit</button>
	  </div>
	</div>

	<?php } ?>

	</fieldset>
</form>

<?php
//-------------
stdFoot();
?>