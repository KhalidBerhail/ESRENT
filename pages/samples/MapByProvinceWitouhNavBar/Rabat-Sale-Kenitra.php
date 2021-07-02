<?php

$rech=$_GET['rech'];
$LGTP=$_GET['LGTP'];
$RCMI=$_GET['RCMI'];
$RCMA=$_GET['RCMA'];
$region=$_GET['region'];
$servername = "localhost";
$userservername = "root";
$database = "pfe";


// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$nbrA_Skhirate_Témara=0;
$nbrA_Rabat=0;
$nbrA_Sale=0;
$nbrA_Kenitra=0;
$nbrA_SidiSlimane=0;
$nbrA_SidiKacem=0;
$nbrA_Khmisset=0;



$req_nbrA="SELECT `province-prefecture`,count(*) as nbrA FROM `logement` GROUP BY `province-prefecture`  ";
$statement_nbrA=$conn->prepare($req_nbrA);
$statement_nbrA->execute();
$res_nbrA=$statement_nbrA->get_result();                    
while(($row_nbrA= mysqli_fetch_array($res_nbrA)))
{
   if($row_nbrA['province-prefecture']=="Préfecture de Skhirate-Témara")
   {
    $nbrA_Skhirate_Témara=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Préfecture de Rabat")
   {
    $nbrA_Rabat=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Préfecture de Salé")
   {
    $nbrA_Sale=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Kénitra")
   {
    $nbrA_Kenitra=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Sidi Slimane")
   {
    $nbrA_SidiSlimane=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Sidi Kacem")
   {
    $nbrA_SidiKacem=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Khémisset")
   {
    $nbrA_Khmisset=$row_nbrA['nbrA'];
   }

}




?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kapella Bootstrap Admin Dashboard Template</title>
  
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
 

 
  <link rel="shortcut icon" href="favicon.png" />


  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>

  <link href="../../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="../../../Resourse/Allfooters/Style.css">
  <link rel="stylesheet" href="styleRe.css">
  <!--leaflet css-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
  </head>
  <body>
    




    <div class="container-scroller">
      <!-- partial:partials/_horizontal-navbar.html -->
     
      <div class="main-panel">
		 <div class="content-wrapper">
           <div class="row">

            <div class="col-lg-6 grid-margin stretch-card">

              <div id="map_lst" class="nav flex-column nav-pills map_lst" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                <a class="nav-link" id="a01"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Skhirate-Témara" role="tab" aria-controls="a01" aria-selected="true"> Préfecture de Skhirate-Témara</a>
                <a class="nav-link" id="a02"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Rabat"           role="tab" aria-controls="a02" aria-selected="false">Préfecture de Rabat          </a>
                <a class="nav-link" id="a03"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Salé"            role="tab" aria-controls="a03" aria-selected="false">Préfecture de Salé           </a>
                <a class="nav-link" id="a04"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Kénitra"           role="tab" aria-controls="a04" aria-selected="false">Province de Kénitra          </a>
                <a class="nav-link" id="a05"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Sidi Slimane"      role="tab" aria-controls="a05" aria-selected="false">Province de Sidi Slimane     </a>
                <a class="nav-link" id="a06"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Sidi Kacem"        role="tab" aria-controls="a06" aria-selected="false">Province de Sidi Kacem       </a>
                <a class="nav-link" id="a07"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Khémisset"        role="tab" aria-controls="a07" aria-selected="false">Province de Khémisset        </a>
               
              </div>
              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
              </div>
              <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Choisissez une préfecture/province</li>
              </ol>
              </nav>

            </div>
            <div class="col-lg-6 grid-margin stretch-card">

              <div class="card">
                <div id="map_card" class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                <button type="button" class="btn btn-primary">Voir les logements dans tous les provinces/prefectures</button> &nbsp;

                <div id="map_svg" class="map_svg">
                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:amcharts="http://amcharts.com/ammap" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewbox="0 0 864 895">

	                <g>
                    
                    <a  xlink:title="Préfecture de Skhirate-Témara"  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Skhirate-Témara"       ><polygon    id="REG-01" points="0 569 14 587 29 582 64 683 118 693 132 707 177 707 195 608 188 596 195 596 178 547 173 540 181 530 180 519 158 519 144 540 121 523 113 526 100 507 0 569"/> 
	                  <a  xlink:title="Préfecture de Rabat          "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Rabat"                 ><polygon    id="REG-02" points="100 507 113 526 121 523 144 540 158 519 164 515 175 501 171 493 166 490 160 494 154 492 150 487 152 479 136 472 100 507"/> 
	                  <a  xlink:title="Préfecture de Salé           "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Préfecture de Salé"                 ><path       id="REG-03" d="M255,721s47-41,59-67l12,12,5-6,24,19h27l-2,4,10,4,3,20-22,21,14,2-2,8,4,9,13,16-2,16-8,5,4,2,6-3,14,19v2l3-5,14,3,2,9-4,10-12,1-3-4-4,6,3,5-4,8-14,4-2-5-9,4-7-5-3-12-12-4-9-4,1-5-3-2-2,5-5-5-5,1-17-12-6,3-2-6-12-1-4.48,4.5L292,789l8-10-1-11H277l17-18-4-8-5-3-6,4-6-2-4-5,2-8Z" transform="translate(-119 -249)"/>
	                  <a  xlink:title="Province de Kénitra          "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Kénitra"                 ><path       id="REG-04" d="M314,654l19-40,75-104S545,276,540,259l22,5,26-2,11-13,25,11,7,10,33-9,22,21,34,8,27-5,19,2,3,33-7,12-6-1,1,7-12,6-3-12-15,1-3,8-9,1-9-3-5,4,21,43-15,13,13,13-6,16,20,10-14,3-27-6-23-7-10,7-6-2-6,9,2,3-11-1L507,499l20,6,13,15-31,46v35l27,8-13,51-10,5H492l-7-11-17,16-19-3,4-9-28,22H402l-11.37,11.21L390,687l-10-4,2-4H355l-24-19-5,6Z" transform="translate(-119 -249)"/>
	                  <a  xlink:title="Province de Sidi Slimane     "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Sidi Slimane"       ><path       id="REG-05" d="M564,661l-41-1,13-51-27-8V566l31-46,19-5,17,5,3,9,6,3,13-3,42,31,1,6,37-2,5,15,26-6,3-5,7,1,2,2,2,1,5-11-5-10-7-1s-11-10-11-11-2-10-2-10l-6-2,3-15,12,2,3-8h4l-6,13,13-3-5-4,14-7,9,4-4,7,15,1-6,6,10,4-6,6,6,8,11-4,15,7-14,1,24,11,5-6,9,15-6,11-12,2-4-6-2,6-10-1,3-11-18,8,2,7-4,15-3-2,2,23-2,28-13,2,5,5,1,8-37,16,5,3-15,16H687l-5-6-5-1-1,5-20,5-29-5-31-24Z" transform="translate(-119 -249)"/>
                    <a  xlink:title="Province de Sidi Kacem       "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Sidi Kacem"       ><path       id="REG-06" d="M771,649l-15-17.89L757,617l-2-23,3,2,4-15-2-7,18-8-3,11,10,1,2-6,4,6,12-2,6-11-9-15-5,6-24-11,14-1-15-7-11,4-6-8,6-6-10-4,6-6-15-1,4-7-9-4-14,7,5,4-13,3,6-13h-4l-3,8-12-2-3,15,6,2,2,10,11,11,7,1,5,10-5,11-2-1-2-2-7-1-3,5-26,6-5-15-37,2-1-6-42-31-13,3-6-3-3-9-17-5-19,5-13-15-20-6,132-55,11,1-2-3,6-9,6,2,10-7,23,7,27,6,14-3-20-10,6-16-13-13,15-13-21-43,5-4,9,3,9-1,3-8,15-1,3,12,12-6,6,8-2,6,1,11,6,4,1,5-5,12,2,5h4l3,3,5-2,4,3,3-1,2,2-2,6,18,13,15-4-11-15,5-9,23-1v6l13,3,34,38,38,5,20,21-7,5-1,19,23,1v-4l28,14-11,14,4,2-4,7,3,2-5,6-8,5-7-3-8,22-10-1-33,15-18-3-4,5h7l12,11-5,7,6,7,4-5,4,4,8-4,2,3-2,3,7,6,10,23-6-1-21,22,5,8,16,4-7,11h-8l-1,5-30-6-3,14-21,11s-11,0-9-8H829l-4,3-21-3-4,3s-11,1-4-10l6-15-20-1v-8Z" transform="translate(-119 -249)"/> 
                    <a  xlink:title="Province de Khémisset        "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra&province=Province de Khémisset"       ><path       id="REG-07" d="M737,691l-23-8,2-4-15,16H687l-5-6-5-1-1,5-20,5-29-5-31-24-32-8-41-1-10,5H492l-7-11-17,16-19-3,4-9-28,22H402l-11,11,2,16-22,21,14,2-2,8,4,9,13,16-2,16-8,5,4,2,6-3,14,19v2l3-5,14,3,1.81,8.14L429,821l-12,1-3-4-4,6,3,5-4,8-14,4-2-5-9,4-7-5-3-12-21-8,1-5-3-2-2,5-5-5-5,1-17-12-6,3-2-6-12-1-4.89,3.87L314,845h-7l7,12-18,99-7,5,2,7-16,16-22,13s27,18,27,20-1,10-2,10H266l-2,70,129,33,8,14,13-16,60-26,6-11s-17-26-17-27,9-12,9-12l-9-8,23-24-2-31,44,12,32,2,6,3-7,44,56,8,133,12,28-15-24-10,1-39,13-17-18-9,18-12,5-26-11-16,8-7,1-17-3-10,23-21,12-32-25-15,6-15-29-13,25-28-2-12-36-31,13-4v-5l-14-6Z" transform="translate(-119 -249)"/>                                                                                                                              
	                
	                  
	
                	</g>
                </svg>
                </div>
                  <canvas id="barChart" style="display: block; height: 227px; width: 455px;" width="682" height="340" class="chartjs-render-monitor"></canvas>
                </div>
              </div>
              
            </div>

          </div>
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
       

				<!-- partial -->
			</div>



		  <!-- page-body-wrapper ends -->
    </div>
   
    








    <!-- container-scroller -->
    <!-- base:js -->
    <script src="../../../Resourse/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="../../../Resourse/js2/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
  

		<script src="../../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="../../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->

    <!-- chat-box -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>
<!--MAP JavaScript-->   
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
   
</html>
    <script src="../../Resourse/JS/JSG/jquery-3.4.1.min.js"></script>
<footer id="fh5co-footer" role="contentinfo">
		<div class="container" id="footertext">
			<div class="row row-pb-md">
				<div class="col-md-4 fh5co-widget">
					<h3  style="color:white;">ESRENT</h3>
					<p  style="color:white;">Site web qui permetra aux client de trouver facilement un logement qui respect leurs criteres de qualité et aux propriétaire d'atendre un grand nombre de loueurs potentiels</p>
					<p><a href="#"  style="color:white;">Learn More</a></p>
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
						<small class="block"  style="color:white;">&copy; 2020 ESRENT</small> 
						<small class="block"  style="color:white;"> All Rights Reserved.</small>
					</p>
					<p>
					
					</p>
				</div>
			</div>

		</div>
	</footer>
</body>
</html>



<script>
 // var shape_id="";
  var map=document.querySelector('#map_card');
var shapes=document.querySelectorAll('.map_svg path,polygon');
var links=document.querySelectorAll('.map_lst a');
var link_id="";
  $(document).ready(function(){ 
  
//Polyfill for foreach in browsers that don't suport it
if(NodeList.prototype.forEach === undefined){
  NodeList.prototype.forEach=function(callback){
    [].forEach.call(this, callback);
  }
}


//adding the mouseenter event listener on all svg shapes to activate the corespandent link <a> 
shapes.forEach(function (shape){
  shape.addEventListener('mouseenter', function(f){
    var shape_id=this.id.replace('REG-','a');
      
      document.querySelector("#"+shape_id).classList.add('active');
      shape_id="";
      
  });

  shape.addEventListener('mouseleave', function(f){
    
    document.querySelectorAll('.active').forEach(function (item){
      item.classList.remove('active');
    });

});

 
});


links.forEach(function (link){
  link.addEventListener('mouseenter', function(f1){
    
    
     link_id=this.id.replace('a','REG-');
 
      document.querySelector("#"+link_id).classList.add('shape_active');
      link_id="";
});

link.addEventListener('mouseleave', function(f1){
    
     document.querySelectorAll('.shape_active').forEach(function (item1){
       item1.classList.remove('shape_active');
     });

});

});


});

</script>
