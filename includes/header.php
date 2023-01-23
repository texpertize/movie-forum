<?php
include "includes/functions.php";

/**
 * Function to display the standard header for each page
 * @author Adrian Serban, Alexandru Simion, Henrik Hegedus, Iosif Szatmari
 * @param string $title title of the page (eg: movies)
 */
function stdHead($title){
	include "counter.php";
    session_start();
    ?>
	<!doctype html>
	<html lang="en">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo $title ?> - <?php echo SITETITLE ?></title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/style.css">
	   

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		   
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
          <div class="container">
            <a class="navbar-brand" href="index.php">Movieholic - Let's Binge!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="index.php">Home
                        <span class="sr-only">(current)</span>
                      </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="movies.php">Movies</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Profile
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                   <?php if (isLoggedIn()){ 
                        echo "<center>Welcome, " . getUsername() . "</center>";  
                    ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php?id=<?php echo $_SESSION["user_id"]; ?>">Profile</a>
                        <!--<a class="dropdown-item" href="#">Another action</a>-->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Log out</a>
                   <?php }else{ ?>
                        <a class="dropdown-item" href="login.php">Log in</a>
                        <a class="dropdown-item" href="register.php">Register</a>
                   <?php } ?>
                  </div>
                </li>
				<?php if(getCurClass() > 0){ ?>
				<li class="nav-item">
                  <a class="nav-link" href="admin_panel.php">Admin</a>
                </li>
				<?php } ?>
              </ul>
            </div>
          </div>
        </nav>

        <!-- Page Content -->
        <div class="container">
          <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
        <?php
}