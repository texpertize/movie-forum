<?php
/**
 * Index/main page of the website
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Home");

if(isLoggedIn()){
	echo "<center><legend>RECOMMENDATIONS BASED ON YOUR PREFERENCES</legend></center>";
	movieRecommendations($_SESSION['user_id']);
}

echo "<center><legend>TOP RATED MOVIES </legend></center>";
bestMoviesFromYear(2018);

echo "<br><br>";
echo "<center><legend>TOP RATED MOVIES till 2010</legend></center>";
bestMoviesFromYear(2000, 2009);

echo "<br><br>";
echo "<center><legend>TOP RATED MOVIES Current</legend></center>";
bestMoviesFromYear(2010, 2018);

stdFoot();
?>