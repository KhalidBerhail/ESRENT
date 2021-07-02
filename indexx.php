
<?php
$servername = "localhost";
$userservername = "root";
$database = "pfe";


// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$avis='';
$reqAvi="SELECT * FROM `avis_clients` WHERE showCM='oui' LIMIT 3 ";
$statementAvi=$conn->prepare($reqAvi);
$statementAvi->execute();
$resAvi=$statementAvi->get_result();
while(($rowAvi= mysqli_fetch_array($resAvi)))
{
	$CodeU=$rowAvi['CodeU'];
	$comment=$rowAvi['commentaire'];
	$src='';
	$ProfileP='';

	$reqAvii="SELECT * FROM utilisateur WHERE CodeU=?";
	$statementAvii=$conn->prepare($reqAvii);
	$statementAvii->bind_param("i",$CodeU);
    $statementAvii->execute();
	$resAvii=$statementAvii->get_result();
	$rowAvii=$resAvii->fetch_assoc();
	$user_n=$rowAvii['username'];
	if($rowAvii['imageP']!=NULL)
     {
      $src="pages/samples/profilpic.php?UN=$user_n";
	  //$ProfileP="<img src='".$src."' />";
     }
    else
     {
	  $src="Resourse/imgs/ProfileHolder.jpg";
	 // $ProfileP="<img src='".$src."' />";
     }


	

	$avis.="<div class='row'>
	            <div class='col-md-10 col-md-offset-1'>
		            <div class='row'>
		 	            <div class='col-md-12 animate-box'>
		                 	<div class='testimony'>
								<div class='inner text-center'>
								<img src='".$src."' />
								 </div>
				             	<blockquote>
				         		    <p>&ldquo; ".$comment." &rdquo;</p>
					             	<p class='author'><cite>&mdash; ".$user_n."</cite></p>
				            	</blockquote>
				            </div>
			            </div>
		            </div>
	            </div>
            </div>";
}

$ultra1=0;
$ultra2=0;
$ultra3=0;
$line_cnt=0;
$ultra_rec='';
//$reqR1="SELECT * from logement where CodeL is in(SELECT CodeL from pack where type='Ultra')";
$reqR1="SELECT idL,COUNT(*) AS total_rcm FROM log_recomm where idL in(SELECT CodeL from pack where type='ultra') GROUP BY idL ORDER BY total_rcm  limit 3";
$statementR1=$conn->prepare($reqR1);
$statementR1->execute();
$resR1=$statementR1->get_result();

if(mysqli_num_rows($resR1)==0)
{

$reqRS="SELECT * FROM logement where CodeL in(SELECT CodeL from pack where type='ultra') limit 3";
$statementRS=$conn->prepare($reqRS);
$statementRS->execute();
$resRS=$statementRS->get_result();
while($rowRS= mysqli_fetch_array($resRS))
{
   $line_cnt=$line_cnt+1;
   $ultra_CodeL=$rowRS['CodeL'];
 
   //info du logement courant
   $reqIL="SELECT * FROM logement where CodeL=?";
   $statementIL=$conn->prepare($reqIL);
   $statementIL->bind_param('i',$ultra_CodeL);
   $statementIL->execute();
   $resIL=$statementIL->get_result();
   $rowIL=$resIL->fetch_assoc();
   $ultra_titre=$rowIL['nom'];
   $ultra_type=$rowIL['type'];
   $ultra_prix=$rowIL['prix'];
   $ultra_CodeP=$rowIL['CodeP'];
   //info prop
   $reqN="SELECT * from utilisateur where CodeU=?";
   $statementN=$conn->prepare($reqN);
   $statementN->bind_param("i",$ultra_CodeP);
   $statementN->execute();
   $resN=$statementN->get_result();
   $rowN=$resN->fetch_assoc();
   $ultra_nomP=$rowN['username'];

   //image du logement
   $reqI="SELECT * FROM image where CodeL=? Limit 1";
   $statementI=$conn->prepare($reqI);
   $statementI->bind_param("i",$ultra_CodeL);
   $statementI->execute();
   $resI=$statementI->get_result();
   $rowI=$resI->fetch_assoc();
   $ultra_IdI=$rowI['CodeImg'];
   $image="genere_image.php?id=$ultra_IdI";

   
   
   if($line_cnt==1)
	{ 
	 $ultra_rec.="  <div class='col-half'>
	                    <div id='Ultr_Img".$line_cnt."' class='project animate-box' style='background-image:url(". $image.");'>
		                    <div class='desc'>
			                        <span>".$ultra_nomP."</span>
			                        <h3>".$ultra_titre."</h3>
			                        <span>Prix : ".$ultra_prix." dh</h3>
		                     </div>
	                    </div>
                    </div>";
	 $ultra1=$ultra_CodeL;              
	}
   else if($line_cnt==2)
	{
	 $ultra_rec.="  <div class='col-half'>
	                    <div id='Ultr_Img".$line_cnt."' class='project-grid animate-box' style='background-image:url(". $image.");'>
		                    <div class='desc'>
		                        <span>".$ultra_nomP."</span>
			                    <h3>".$ultra_titre."</h3>
		                        <span>Prix : ".$ultra_prix." dh</h3>
	                        </div>
	                    </div>";
	 $ultra2=$ultra_CodeL;                 
	} 
   else if($line_cnt==3)
	{
	 $ultra_rec.="  <div id='Ultr_Img".$line_cnt."' class='project-grid animate-box' style='background-image:url(". $image.");'>
	                    <div class='desc'>
	                       <span>".$ultra_nomP."</span>
		                   <h3>".$ultra_titre."</h3>
		                   <span>Prix : ".$ultra_prix." dh</h3>
	                    </div>
                    </div>
                  </div>"; 
   $ultra3=$ultra_CodeL;   
	} 

	$datenow = new DateTime(date('Y-m-d'));
   $dateNow = $datenow->format('Y-m-d');

   $reqV = "INSERT INTO `log_recomm`(`idL`, `date`) VALUES (?,?)";
   $statementV=$conn->prepare($reqV);
   $statementV->bind_param("ss",$ultra_CodeL,$dateNow);
   $statementV->execute(); 

 }

}

else
{
$line_cnt=0;
while(($rowR1= mysqli_fetch_array($resR1)))
{
   $line_cnt=$line_cnt+1;
   $ultra_CodeL=$rowR1['idL'];
   
   //info du logement courant
   $reqIL="SELECT * FROM logement where CodeL=?";
   $statementIL=$conn->prepare($reqIL);
   $statementIL->bind_param('i',$ultra_CodeL);
   $statementIL->execute();
   $resIL=$statementIL->get_result();
   $rowIL=$resIL->fetch_assoc();
   $ultra_titre=$rowIL['nom'];
   $ultra_type=$rowIL['type'];
   $ultra_prix=$rowIL['prix'];
   $ultra_CodeP=$rowIL['CodeP'];
   //info prop
   $reqN="SELECT * from utilisateur where CodeU=?";
   $statementN=$conn->prepare($reqN);
   $statementN->bind_param("i",$ultra_CodeP);
   $statementN->execute();
   $resN=$statementN->get_result();
   $rowN=$resN->fetch_assoc();
   $ultra_nomP=$rowN['username'];

   //image du logement
   $reqI="SELECT * FROM image where CodeL=? Limit 1";
   $statementI=$conn->prepare($reqI);
   $statementI->bind_param("i",$ultra_CodeL);
   $statementI->execute();
   $resI=$statementI->get_result();
   $rowI=$resI->fetch_assoc();
   $num_rowI=$resI->num_rows;
   
   if($num_rowI>0)
   {
	 $ultra_IdI=$rowI['CodeImg'];
	 $image="genere_image.php?id=$ultra_IdI";
   }
   else
   {
	 $image="../../Resourse/imgs/userimgs/home-holder.jpg";
   }

   
   
   
   if($line_cnt==1)
	{ 
		$ultra_rec.="  <div class='col-half'>
		<div id='Ultr_Img".$line_cnt."' class='project animate-box' style='background-image:url(". $image.");'>
			<div class='desc'>
					<span>".$ultra_nomP."</span>
					<h3>".$ultra_titre."</h3>
					<span>Prix : ".$ultra_prix." dh</h3>
			 </div>
		</div>
	</div>";
	 $ultra1=$ultra_CodeL;                 
	}
   else if($line_cnt==2)
	{
		$ultra_rec.="  <div class='col-half'>
		<div id='Ultr_Img".$line_cnt."' class='project-grid animate-box' style='background-image:url(". $image.");'>
			<div class='desc'>
				<span>".$ultra_nomP."</span>
				<h3>".$ultra_titre."</h3>
				<span>Prix : ".$ultra_prix." dh</h3>
			</div>
		</div>";
	 $ultra2=$ultra_CodeL;                 
	} 
   else if($line_cnt==3)
	{
		$ultra_rec.="  <div id='Ultr_Img".$line_cnt."' class='project-grid animate-box' style='background-image:url(". $image.");'>
		<div  class='desc'>
		   <span>".$ultra_nomP."</span>
		   <h3>".$ultra_titre."</h3>
		   <span>Prix : ".$ultra_prix." dh</h3>
		</div>
	</div>
  </div>"; 
   $ultra3=$ultra_CodeL;
	} 

	$datenow = new DateTime(date('Y-m-d'));
   $dateNow = $datenow->format('Y-m-d');

   $reqV = "INSERT INTO `log_recomm`(`idL`, `date`) VALUES (?,?)";
   $statementV=$conn->prepare($reqV);
   $statementV->bind_param("ss",$ultra_CodeL,$dateNow);
   $statementV->execute(); 

}
}

?>
<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>ESRENT &mdash; </title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free HTML5 Website Template by gettemplates.co" />
	<meta name="keywords" content="free website templates, free html5, free template, free bootstrap, free website template, html5, css3, mobile first, responsive" />
	<meta name="author" content="gettemplates.co" />



  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- <link href='https://fonts.googleapis.com/css?family=Work+Sans:400,300,600,400italic,700' rel='stylesheet' type='text/css'> -->
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="Resourse/newhome/css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="Resourse/newhome/css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="Resourse/newhome/css/bootstrap.css">
	<!-- Theme style  -->
	<link rel="stylesheet" href="Resourse/newhome/css/style.css">

	<!-- Modernizr JS -->
	<script src="Resourse/newhome/js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->

	<link rel='stylesheet' href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'>
	
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
		
	<div class="fh5co-loader"></div>
	
	<div id="page">
	<nav class="fh5co-nav" role="navigation">
		<div class="container">
			<div class="row">
				<div class="col-xs-2">
					<div id="fh5co-logo"><a href="indexx.php">ESRENT</a></div>
				</div>
				<div class="col-xs-10 text-right menu-1">
					<ul>
						
						<li><a href="pages/Hote/LoginHote.php">Devenez hôte</a></li>
						<li><a href="#">Aide</a></li>			
						<li><a href="#">Contact</a></li>
						<li class="btn-cta"><a href="pages/samples/register.php"><span>Inscription</span></a></li>
						<li class="btn-cta"><a href="pages/samples/login.php"><span>Connexion</span></a></li>
					</ul>
				</div>
			</div>
			
		</div>
	</nav>

	<header id="fh5co-header" class="fh5co-cover" role="banner" style="background-image:url(Resourse/images/back2.jpg);">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
			
				<div class="col-md-8 col-md-offset-2 text-center">

					<div class="display-t">
						<div class="display-tc animate-box" data-animate-effect="fadeIn">
							
							<h1 style="line-height: 1.1;font-size: 31px;letter-spacing: -0.076em;font-weight: 500;">Nous vous aiderons à trouver un endroit que vous aimerez.</h1> <br>
							<div class="row">
								<form class="form-inline" id="fh5co-header-subscribe"  action="pages\samples\MapByProvinceWitouhNavBar\RegionSelection.php" methode="POST">
									<div class="col-md-8 col-md-offset-2">
										<div class="form-group">
											<input  class="form-control" id="email" type="text" name="rech" placeholder="Rechercher une location" required>
											<button type="submit" class="btn btn-default"  methode="POST">Rechercher</button>
										
										</div>
									</div>
									
								
                                       <div class="dropdown" id="price_drop_div">
                                         <button class="btn btn-secondary dropdown-toggle" type="button" id="price_drop_button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           Prix
                                         </button>
                                         <div id="price_drop"class="dropdown-menu" aria-labelledby="price_drop_button">
                                           
                                           <div class="selector">
                                               <div class="price-slider">
                                                   <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content">
                                                       <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                                        <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                                       </div>
													   <span id="min-price" data-currency="DH"  class="slider-price">0</span> 
													   <span class="seperator">-</span> 
													   <span id="max-price" data-currency="DH" data-max="3500"  class="slider-price">3500 +</span>
                                                   </div> 
                                               </div>
                                       	
                                           </div>
                                       </div>
									   <div class="dropdown" id="Type_drop_div">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="Type_drop_button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Type
                                        </button>
                                          <div id="Type_drop"class="dropdown-menu" aria-labelledby="price_drop_button">
                                      	    <div class="cage">
                                                 <label class="radio">Tous
                                                  <input type="radio" checked="checked" name="LGTP" value="OptT">
                                                  <span class="checkround"></span>
                                                 </label>
                                                 <label class="radio">Appartement
                                                   <input type="radio" name="LGTP" value="OptA">
                                                   <span class="checkround"></span>
                                                 </label>
                                                  <label class="radio">Studio
                                                   <input type="radio" name="LGTP" value="OptS">
                                                   <span class="checkround"></span>
                                                 </label>
                                      
                                              <div>
                                          </div>
									  </div>
									   <input id="hidden_min" type="text" name="RCMI" style="display:none;" value="0"/>
									   <input id="hidden_max" type="text" name="RCMA" style="display:none;" value="3500+"/>
									   
								
								
									</form>
                           
                                      
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</header>

	<div id="fh5co-project">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center fh5co-heading">
					
					<h2>ESRENT</h2>
					<p>Nous avons le plus d'annonces et de mises à jour constantes.
Vous ne manquerez donc jamais rien..</p>
				</div>
			</div>
		</div>
		<div class="project-content">
			<?=$ultra_rec;?>
		</div>
	</div>
	<div id="fh5co-testimonial" class="fh5co-bg-section">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center fh5co-heading">
					<h2>Clients satisfaits</h2>
				</div>
			</div>
			<?=$avis;?>
		</div>
	</div>


	

	<footer id="fh5co-footer" role="contentinfo">
		<div class="container" id="footertext">
			<div class="row row-pb-md">
				<div class="col-md-4 fh5co-widget">
					<h3>ESRENT</h3>
					<p>Site web qui permetra aux client de trouver facilement un logement qui respect leurs criteres de qualité et aux propriétaire d'atendre un grand nombre de loueurs potentiels</p>
					<p><a href="#">Learn More</a></p>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-6 col-md-push-1">
					<ul class="fh5co-footer-links">
						<li><a href="#">À PROPOS</a></li>
						<li><a href="#">Contact</a></li>
						<li><a href="#">termes</a></li>
						<li><a href="#">Aidez-moi</a></li>
				
					</ul>
				</div>

				<div class="col-md-2 col-sm-4 col-xs-6 col-md-push-1">
					<ul class="fh5co-footer-links">
						<li><a href="#">PAGES</a></li>
						<li><a href="#">Accueil</a></li>
						<li><a href="#">recherche avancée</a></li>
						<li><a href="#">Handbook</a></li>
						<li><a href="#">voir plus</a></li>
					</ul>
				</div>

				
			</div>

			<div class="row copyright">
				<div class="col-md-12 text-center">
					<p>
						<small class="block">&copy; 2020 ESRENT</small> 
						<small class="block"> All Rights Reserved.</small>
					</p>
					<p>
						<ul class="fh5co-social-icons">
							<li><a href="#"><i class="icon-twitter"></i></a></li>
							<li><a href="#"><i class="icon-facebook"></i></a></li>
							<li><a href="#"><i class="icon-linkedin"></i></a></li>
							<li><a href="#"><i class="icon-dribbble"></i></a></li>
						</ul>
					</p>
				</div>
			</div>

		</div>
	</footer>


	

	<div class="gototop js-top">
		<a href="#" class="js-gotop"><i class="icon-arrow-up"></i></a>
	</div>


   
	<!-- jQuery -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
	<!-- jQuery Easing -->
	<script src="Resourse/newhome/js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="Resourse/newhome/js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="Resourse/newhome/js/jquery.waypoints.min.js"></script>
	<!-- Main -->
	<script src="Resourse/newhome/js/main.js"></script>


	

<script>

var ultra1=<?=$ultra1;?>;
var ultra2=<?=$ultra2;?>;
var ultra3=<?=$ultra3;?>;
$(document).ready(function(){ 
      $('#Ultr_Img1').click(function(){

          window.location.href = "pages/samples/SeeMore.php?smr="+ultra1;
         
      });


      $('#Ultr_Img2').click(function(){

          
          window.location.href = "pages/samples/SeeMore.php?smr="+ultra2;
         
      });  

      $('#Ultr_Img3').click(function(){

          window.location.href = "pages/samples/SeeMore.php?smr="+ultra3;
        
      });    
    });    
</script>

<script>
$(document).ready(function(){ 


	document.getElementById("Type_drop").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
		 });

	document.getElementById("price_drop").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
		 });
		 


$("#slider-range").slider({
    range: true, 
    min: 0,
    max: 3500,
    step: 50,
    slide: function( event, ui ) {
	  $( "#min-price").html(ui.values[ 0 ]);
	  $("#hidden_min").val(ui.values[ 0 ]);
	  
      
      console.log(ui.values[0])
      
      suffix = '';
      if (ui.values[ 1 ] == $( "#max-price").data('max') ){
         suffix = ' +';
      }
	  $( "#max-price").html(ui.values[ 1 ] + suffix); 
	  $("#hidden_max").val(ui.values[ 1 ] + suffix);
	       
    }
  });
});

//document.getElementById("max-price").textContent prix max
//document.getElementById("min-price").textContent prix min
</script>

	</body>
</html>

