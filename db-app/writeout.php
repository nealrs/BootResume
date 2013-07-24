<?php 
// this script takes 1 input, userid, and writes out the full bootres html file. it will pull all the neccesary info from a mysql database, using select only credentials.

// load db parameters, open PDO session, set table name
	require 'db_pdo_v.php';

// set userid based on passed in parameter (just manually set for now)
	$userid = 'nealrs';
	
// Pull basic user info (name/avatar)
	$db_table = "users";
	$load_user = $db->prepare("SELECT * FROM $db_table WHERE userid = '$userid' AND active != '0'");
	$load_user->execute();
	
// if there is no user data, this should really throw an error, but whatevs for now.
	// blerp blerp
		
// loop through user table and set basic params
	while ($user_table = $load_user->fetch(PDO::FETCH_ASSOC)){
		$name = $user_table['name'];
		$email = $user_table['email'];
		$bgcolor = $user_table['bgcolor'];
		$bgtile = $user_table['bgtile'];
		$colorscheme = $user_table['colorscheme'];
		
		//echo $name; echo $email; echo $bgcolor; echo $bgtile; echo $colorscheme;
	}	
	
// pull social rail info & create array
	$db_table = "social";
	$load_social = $db->prepare("SELECT * FROM $db_table WHERE userid = '$userid' AND active != '0'");
	$load_social->execute();
	
	
	// pull category info & create array

		// pull lineitem info & create array

			// pull tag info & create array

		
// write out header

	echo'<!DOCTYPE html>
	<html lang="en">
	  <head>
		<meta charset="utf-8">
		<title>'.$name.'</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="'.$name.'\'s online résumé, created with BootRésumé">
		<meta name="author" content="'.$name.'">

		<!-- Le styles -->    
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="../assets/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
						<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
									   <link rel="shortcut icon" href="../assets/ico/favicon.png">

<!-- Might Scrap GA, unless I do all the hosting myself -->
	<!----- Google Analytics ---->
	  <script>
	  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');

	  ga(\'create\', \''.$analytics_id.'\', \''.$analytics_domain.'\');
	  ga(\'send\', \'pageview\');
	  </script>
	<!----- Google Analytics ---->
  
	</head>';

// write out body (using arrays generated from mysql pull)


// write out footer
	echo'
		<!-- footer div-->
		<div class="container">
			<div class= "row">
				<span class="span8 offset2 text-center">
					<p><small>&copy; 2013 <a href="https://github.com/nealrs/BootResume">BootRésumé</a></small></p>
				</span>
			</div>
		</div>
	
		<script>$(".tip").tooltip({placement:"bottom"})</script>
		<script>$(".tip2").tooltip({placement:"right"})</script> 
	  </body>
	</html>';
	
// close mysql connection
	$db = null;
?>