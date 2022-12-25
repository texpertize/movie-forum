<?php
/**
 * Movie page
 * @author Ankit
 */
include "includes/main.php";
dbconnect();
stdHead("Movie details");
//-------------
if(isset($_GET["imdb"])){
	$imdb = sanitize($_GET["imdb"]);
}else{
	redirect("movies.php"); //redirect the user if imdb is not set
}


getXML($imdb);

$query = "SELECT * FROM `movies` WHERE `imdb` = '{$imdb}'";
$select_query = mysqli_query($connection, $query);

if(!$select_query){
    die("QUERY FAILED" . mysqli_error($connection));
}

if($row = mysqli_fetch_assoc($select_query)){
	$trailer = $row["trailer"];
	$youtube = $row["youtube"];
	$amazon = $row["amazon"];
}else{
	redirect("movies.php"); //redirect the user if the movie doesn't exist
}

?>

<div class="container-fluid">
  <div class="row no-gutters">
    <div class="col-6 col-md-4">
      <img src="<?php echo $xml->movie[0]['poster']; ?>">
    </div>
    <div class="col-12 col-sm-6 col-md-8">
        <div class="d-flex justify-content-between">
              <div>
                 <b><?php echo $xml->movie[0]['title']; ?></b> (<?php echo $xml->movie[0]['year']; ?>)
              </div>
              <div>
                <img src="style/star.png"> <?php echo $xml->movie[0]['imdbRating']; ?>
              </div>
         </div>
         
         <small class="text-muted">
         <?php echo $xml->movie[0]['rated']; ?> | <?php echo $xml->movie[0]['runtime']; ?> | <?php echo $xml->movie[0]['genre']; ?> | <?php echo $xml->movie[0]['released']; ?> (<?php echo $xml->movie[0]['country']; ?>)
         </small>  
                          
          <hr>
          
        <?php echo $xml->movie[0]['plot']; ?> <br><br>
        
         <b>Director: </b> <?php echo $xml->movie[0]['director']; ?> <br>
         <b>Writer: </b> <?php echo $xml->movie[0]['writer']; ?> <br>
         <b>Stars: </b> <?php echo $xml->movie[0]['actors']; ?>
		 
		 <br><br><br><br>
		
		<?php if(isLoggedIn()){ ?>
			<a href="http://youtube.com/watch?v=<?php echo $youtube ?>" target="_blank"><img src="style/youtube.png" alt="Youtube" title="Check youtube link" width="200px" height="40px" ></a> <br><br>
			<a href="http://amazon.co.uk/dp/<?php echo $amazon ?>" target="_blank"><img src="style/amazon.png" alt="Amazon" title="Check amazon link" width="200px" height="40px" ></a>
		<?php }else{ ?>
			<a href="#"><img src="style/youtube.png" alt="Youtube" title="Check youtube link" width="200px" height="40px" ></a> <br><br>
			<a href="#"><img src="style/amazon.png" alt="Amazon" title="Check amazon link" width="200px" height="40px" ></a> <br>
			<p style="font-size:14px"><i>*You must be logged in to be redirected to the movie providers!</i></p>
		<?php } ?>
		

		
    </div>
  </div>
  
  <hr>
  
	<center>
		<iframe width="100%" height="450" src="https://www.youtube-nocookie.com/embed/<?php echo $trailer ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</center>
	
	<hr>
	
	<?php if(isLoggedIn()){ ?>
		<!--DISQUIS COMMENTS-->
		<div id="disqus_thread"></div>
		<script>

		/**
		*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
		*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

		var disqus_config = function () {
		this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
		this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
		};

		(function() { // DON'T EDIT BELOW THIS LINE
		var d = document, s = d.createElement('script');
		s.src = 'https://movie-db.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
		})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>				
		<!--DISQUIS COMMENTS-->
	<?php }else{ ?>
		<p style="font-size:16px"><i>Only registered users can leave comments and reactions!</i></p>
	<?php } ?>
</div>
<?php
//-------------
stdFoot();
?>