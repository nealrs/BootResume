<?php 
// this script takes 1 input, userid, and writes out the full bootres html file. it will pull all the neccesary info from a mysql database, using select only credentials.

// set userid based on passed parameter (manually set for now)
  $userid = 'nealrs';
  //$userid = 'example';

// load db parameters, open PDO session, set table name
  require 'db_pdo_v.php';
  
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
    $hash = md5(strtolower(trim($email)));
    $bgcolor = $user_table['bgcolor'];
    $bgtile = $user_table['bgtile'];
    $colorscheme = $user_table['colorscheme'];    
  }  
  
// pull social rail info & create array -- need to join this with a lookup table for social network names/icons/base urls
  $db_table = "social";
  $db_table2 = "social_lookup";
  $load_social = $db->prepare("SELECT * FROM social JOIN social_lookup ON social_lookup.index = social.platformindex WHERE userid = '$userid' AND active != '0' ORDER BY social.order ASC");
  $load_social->execute();
  
  $alt=0;
  while ($social_table = $load_social->fetch(PDO::FETCH_ASSOC)){
    $social[$alt][0] = $social_table['platform_name'];
    $social[$alt][1] = $social_table['platformhandle'];
    $social[$alt][2] = $social_table['icon'];
    $social[$alt][3] = $social_table['url'];
    $alt++;
  }

//// BEGIN HEADER

  echo'<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="utf-8">
    <title>'.$name.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="'.$name.'\'s online résumé, created with BootRésumé">
    <meta name="author" content="'.$name.'">

    <!-- includes -->    
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
//// END HEADER

//// BEGIN BODY (using arrays generated from mysql queries)

// mobile & desktop headers
  echo'
  <body style="background:'.$bgcolor.'; padding-top:20px; padding-bottom:20px; background-image:url(\''.$bgtile.'\'); background-repeat:repeat;">

  <!--- head block --->
    <div class="container head_cont">
      
      <!--- tablet & desktop head --->
      <div class="row span7 offset2 hidden-phone" style="padding-bottom:10px;">
        <h2><img src="http://www.gravatar.com/avatar/'.$hash.'?s=200d=mm" height = "80px" width = "80px" class="tip img-circle" data-toggle="tooltip" title = "Hi there!"> '.$name.'</h2>
      </div>
      
      <!--- mobile / phone head --->
      <div class="row visible-phone" style="padding-bottom:10px;">
        <div>
          <h3><img src="http://www.gravatar.com/avatar/'.$hash.'?s=200d=mm" height = "60px" width = "60px" class="img-circle"> '.$name.'</h3>
          <h3>'; 
           
// mobile social rail.        
            $alt = -1;
            while($x = $social[$alt+1]){
        	  if ($social[$alt+2]){
        	  	echo'<a style="text-decoration: none" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a>
        	  	';
              } else { 
                  echo'<a style="text-decoration: none" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a>
                  '; 
                }
              $alt++;  
            }
          
          echo'</h3>
        </div>
      </div>
      
    </div>';

// resume content
  echo'
  <!--- main content block --->  
    <div class="container body_cont">
    <div class="row span7 offset2" style=" background:white; padding:15px;">';

// categories
  $db_table = "category";
  $db_table2 = "category_lookup";
  $load_cat = $db->prepare("SELECT * FROM category JOIN category_lookup ON category_lookup.index = category.category WHERE userid = '$userid' AND active != '0' ORDER BY category.order ASC");
  $load_cat->execute();
  
  $alt=0;
  while ($cat_table = $load_cat->fetch(PDO::FETCH_ASSOC)){
    $cat[$alt][0] = $cat_table['category'];
    $cat[$alt][1] = $cat_table['icon'];
    
    $categoryindex = $cat_table['catindex'];

	echo'
	<h3><i class="'.$cat[$alt][1].'"></i> '.$cat[$alt][0].'</h3>
        <ul style="padding-bottom:5px;">
    ';
      
// lineitems
  		$db_table = "lineitems";
  		$load_line = $db->prepare("SELECT * FROM lineitems WHERE categoryindex = '$categoryindex' AND active != '0' ORDER BY lineitems.order ASC");
  		$load_line->execute();
  
  		$alt1=0;
  		while ($line_table = $load_line->fetch(PDO::FETCH_ASSOC)){  		
    	  $line[$alt1][0] = $line_table['precomma'];
    	  $line[$alt1][1] = $line_table['postcomma'];
    	  $line[$alt1][2] = $line_table['link'];
    	  
    	  $lineitemindex = $line_table['lineitemindex'];
                  
          echo'
          <li style="padding-bottom:18px;">
           <a href="'.$line[$alt1][2].'"><strong>'.$line[$alt1][0].'</strong></a>, '.$line[$alt1][1].'<br>';

// tags
		   $db_table = 'tags';
		   $load_tags = $db->prepare("SELECT * FROM tags JOIN tag_lookup ON type = tagtype WHERE lineitemindex = '$lineitemindex' AND active != '0' ORDER BY tags.order ASC");
  		   $load_tags->execute(); 
  		 
  		   $alt2 = 0;      
           while ($tag_table = $load_tags->fetch(PDO::FETCH_ASSOC)){  
           
             //var_dump($tag_table);
           		
    	     $tag[$alt2][0] = strtolower($tag_table['tag']);
    	  	 $tag[$alt2][1] = $tag_table['color'];
    	     
    	     echo'<span class="label '.$tag[$alt2][1].'">'.$tag[$alt2][0].'</span>
    	     ';
    	  	 $alt2++;
    	   }	 
          echo'
          </li>'; 
          $alt1++;
        }
    echo'
        </ul>
    '; 
    $alt++;
  }
  echo'
  </div>
    <!-- contact / social icons for desktop/tablet view-->
    <div class="span1 hidden-phone text-left">';
    
// desktop/tablet social rail.        
      $alt = -1;
      while($x = $social[$alt+1]){
        echo'<h2><a style="text-decoration: none" data-toggle="tooltip" class = "tip2" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a></h2>
        ';
        $alt++;  
      }
      echo'
      </div>
    
    </div><br>
    ';

//// END BODY

//// BEGIN FOOTER
  echo'
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
//// END FOOTER

  
// close mysql connection
  $db = null;
?>