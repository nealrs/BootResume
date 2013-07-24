<?php 
// this script takes 1 input, userid, and writes out the full bootres html file. it will pull all the neccesary info from a mysql database, using select only credentials.

// set userid based on passed parameter (manually set for now)
  $userid = 'nealrs';

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
  $db_table = "social_lookup";
  $load_social = $db->prepare("SELECT * FROM social JOIN social_lookup ON social_lookup.index = social.platformindex WHERE userid = '$userid' AND active != '0'");
  $load_social->execute();
  
  $alt=0;
  while ($social_table = $load_social->fetch(PDO::FETCH_ASSOC)){
    $social[$alt][0] = $social_table['platform_name'];
    $social[$alt][1] = $social_table['platformhandle'];
    $social[$alt][2] = $social_table['icon'];
    $social[$alt][3] = $social_table['url'];
        
    $alt++;
  }
  //var_dump ($social);

  // pull category info & create array

    // pull lineitem info & create array

      // pull tag info & create array

    
    
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
        <h2><img src="http://www.gravatar.com/avatar/'.$hash.'?s=200d=mm" height = "60px" width = "60px" class="tip img-circle" data-toggle="tooltip" title = "Hi there!"> '.$name.'</h2>
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
        	  	echo'<a style="text-decoration: none" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a>&nbsp;&nbsp;';
              } else { 
                  echo'<a style="text-decoration: none" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a>'; 
                }
              $alt++;  
            }
          
          echo'</h3>
        </div>
      </div>
      
    </div>';

// whitebox content
  echo'
  <!--- main content block --->  
    <div class="container body_cont">
    <div class="row span7 offset2" style=" background:white; padding:15px;">
        
    <h3><i class="icon-briefcase"></i> Work</h3>
    <ul style="padding-bottom:5px;">
      <li>
        <a href="http://adstruc.com"><strong>ADstruc</strong></a>, 2011 - Present<br>
        <span class="label label-warning">account manager</span>
        <span class="label">marketing</span>
        <!--<span class="label">copywriting</span>-->
        <span class="label">blog</span>
        <span class="label">sales</span>
        <span class="label">strategy</span>
        <span class="label">media planning</span>
        <!--<span class="label">crm</span>-->
        
      </li><br>
      <li>
        <a href="http://arnell.com"><strong>Arnell</strong></a>, 2011<br>
        <span class="label label-warning">intern</span>
        <span class="label">brand strategy</span>
        <span class="label">research</span>
        
      </li><br>
      <li>
        <a href="http://ew.com"><strong>Entertainment Weekly</strong></a>, 2010 - 2011<br>
        <span class="label label-warning">intern</span>
        <span class="label">research</span>
        <span class="label">analytics</span>
        
      </li><br>
      <li>
        <a href="http://figment.com"><strong>Figment</strong></a>, 2009 - 2010<br>
        <span class="label label-warning">intern</span>
        <span class="label">research</span>
        <span class="label">financial modeling</span>
        <span class="label">web design</span>
        
      </li><br>
      <li>
        <a href="http://cat.com"><strong>Caterpillar</strong></a>, 2005 - 2009<br>
        <span class="label label-warning">engineer</span>
        <!--<span class="label label-info">international assignment</span>-->
        <span class="label">risk mitigation</span>
        <span class="label">prototype</span>
        <span class="label">cost reduction</span>
        <span class="label">six sigma</span>
        <span class="label">design</span>
        <!--<span class="label">test</span>-->
        <!--<span class="label">analysis</span>-->
        <!--<span class="label">tier iv</span>-->
        
      </li>
    </ul>
    
    <h3><i class="icon-beaker"></i> Projects</h3>
    <ul style="padding-bottom:5px;">
      <li>
        <a href="http://audioshocker.com"><strong>AudioShocker</strong></a>, pop-culture podcast network<br>
        <span class="label label-warning">cofounder</span>
        <span class="label">podcasting</span>
        <span class="label">editorial</span>
        <span class="label">web design</span>
        
      </li><br>
      <li>
        <a href="http://legalgrep.com"><strong>LegalGrep</strong></a>, document proximity search & legal software<br>
        <span class="label label-warning">founder</span>
        <span class="label">javascript</span>
        <span class="label">css</span>
        <span class="label">regex</span>
        <span class="label">webapp</span>
        
      </li><br>
      <li>
        <a href="http://nealshyam.com/mta"><strong>MTA Traffic Charts</strong></a>, dynamic traffic charts for NYC subway stations<br>
        <span class="label label-info">open source</span>
        <span class="label">data visualization</span>
        <span class="label">geo information</span>
        <span class="label">javascript</span>
        <span class="label">mysql</span>
        <span class="label">d3</span>
        
      </li><br>
      <li>
        <a href="https://github.com/nealrs/BootResume#bootr%C3%A9sum%C3%A9"><strong>BootRésumé</strong></a>, mobile-ready website & résumé template<br>
        <span class="label label-info">open source</span>
        <!--<span class="label">markdown</span>-->
        <span class="label">bootstrap</span>
        <span class="label">css</span>
        <span class="label">html</span>
        <span class="label">mobile</span>
        
      </li><br>
      <li>
        <a href="https://github.com/nealrs/ScrollPop#scrollpop"><strong>ScrollPop</strong></a>, interactive comment-roll ads for WordPress<br>
        <span class="label label-info">open source</span>
        <span class="label">wordpress</span>
        <span class="label">php</span>
        <span class="label">display ads</span>
        <span class="label">user engagement</span>
        <span class="label">jquery</span>
        
      </li><br>
      <li>
        <a href="http://joketi.me"><strong>JokeTime</strong></a>, web & mobile app for stand up comedians [on hold]<br>
        <span class="label label-warning">founder</span>
        <span class="label">php</span>
        <span class="label">web app</span>
        <span class="label">comedy</span>
        <span class="label">mobile</span>
        
      </li>
    </ul>

    <!---<h3><i class="icon-comments"></i> Languages</h3>
    <ul style="padding-bottom:5px;">
      <li>
        <a href=""><strong>english</strong></a><br>
        <span class="label ">native</span>

      </li><br>
      <li>
        <a href=""><strong>spanish</strong></a><br>
        <span class="label ">working proficiency</span>

      </li>
    </ul>-->

    <h3><i class="icon-book"></i> Education</h3>
    <ul style="padding-bottom:5px;">
      <li>
        <a href="http://www.stern.nyu.edu/"><strong>NYU Stern School of Business</strong></a>, MBA Marketing, 2011<br>
        <span class="label">media & entertainment conference</span>

      </li><br>
      <li>
        <a href="http://cmu.edu"><strong>Carnegie Mellon University</strong></a>, B.S. Mechanical Engineering, 2005<br>
        <span class="label">tartan comics editor</span>
        <span class="label">short fiction press</span>

      </li>
    </ul>
    </div>
    
    <!-- contact / social icons for desktop/tablet view-->
    <div class="span1 hidden-phone text-left">';
// desktop/tablet social rail.        
      $alt = -1;
      while($x = $social[$alt+1]){
        echo'<h2><a style="text-decoration: none" data-toggle="tooltip" class = "tip2" href="'.$x[3].$x[1]. '" title="'.$x[0].'" target="_blank"><i class="'.$x[2].'"></i></a></h2>';
        $alt++;  
      }
      echo'
      </div>
    
    </div><br>';

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