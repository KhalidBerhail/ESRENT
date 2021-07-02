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
$nbrA_Assilah=0;
$nbrA_Fahs_Anjra=0;
$nbrA_MdiqFnideq=0;
$nbrA_Tetouan=0;
$nbrA_Larache=0;
$nbrA_Ouezzane=0;
$nbrA_Chefchaouen=0;
$nbrA_Hoceima=0;



$req_nbrA="SELECT `province-prefecture`,count(*) as nbrA FROM `logement` GROUP BY `province-prefecture`  ";
$statement_nbrA=$conn->prepare($req_nbrA);
$statement_nbrA->execute();
$res_nbrA=$statement_nbrA->get_result();                    
while(($row_nbrA= mysqli_fetch_array($res_nbrA)))
{
   if($row_nbrA['province-prefecture']=="Préfecture de Tanger-Assilah")
   {
    $nbrA_Assilah=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Fahs-Anjra")
   {
    $nbrA_Fahs_Anjra=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Préfecture de M'diq-Fnideq")
   {
    $nbrA_MdiqFnideq=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Tétouan")
   {
    $nbrA_Tetouan=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Larache")
   {
    $nbrA_Larache=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'Ouezzane")
   {
    $nbrA_Ouezzane=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Chefchaouen")
   {
    $nbrA_Chefchaouen=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'Al Hoceïma")
   {
    $nbrA_Hoceima=$row_nbrA['nbrA'];
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
                <a class="nav-link" id="a01" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Préfecture de Tanger-Assilah" role="tab" aria-controls="a01" aria-selected="true"> Préfecture de Tanger-Assilah </a>
                <a class="nav-link" id="a02" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Fahs-Anjra"       role="tab" aria-controls="a02" aria-selected="false">Province de Fahs-Anjra       </a>
                <a class="nav-link" id="a03" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Préfecture de M'diq-Fnideq"   role="tab" aria-controls="a03" aria-selected="false">Préfecture de M'diq-Fnideq   </a>
                <a class="nav-link" id="a04" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Tétouan"          role="tab" aria-controls="a04" aria-selected="false">Province de Tétouan          </a>
                <a class="nav-link" id="a05" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Larache"          role="tab" aria-controls="a05" aria-selected="false">Province de Larache          </a>
                <a class="nav-link" id="a06" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province d'Ouezzane"          role="tab" aria-controls="a06" aria-selected="false">Province d'Ouezzane          </a>
                <a class="nav-link" id="a07" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Chefchaouen"      role="tab" aria-controls="a07" aria-selected="false">Province de Chefchaouen      </a>
                <a class="nav-link" id="a08" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province d'Al Hoceïma"        role="tab" aria-controls="a08" aria-selected="false">Province d'Al Hoceïma        </a>
               
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
                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:amcharts="http://amcharts.com/ammap" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewbox="0 0 810 584.58">

	                <g>
                    
                      <a  xlink:title="Préfecture de Tanger-Assilah"  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Préfecture de Tanger-Assilah"><path    id="REG-01" d="M186.54,870.65s48.53-157.47,56.08-197.38l57.16,14-5.39,28L314.88,725,317,783.28v4.32l-4.31,1.08h-4.32c-1.08,0-16.18,2.15-16.18,2.15L276.06,821l-43.15,7.55,19.42,21.57-6.47,32.36-22.65-18.34" transform="translate(-138 -620.42)"/></a> 
	                    <a  xlink:title="Province de Fahs-Anjra      "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Fahs-Anjra"      ><path    id="REG-02" d="M449.7,634.44l-14-14-47.45,8.63-27,35.59-36.67-6.47-24.81,29.12-5.39,28L314.88,725l1.25,33.71,21.4,6.2,34.52-27,19.41,15.1s2.16-17.25,14-28l-5.39-51.77,18.34,5.39-4.32-31.28,35.59-11.86Z" transform="translate(-138 -620.42)"/></a>  
	                    <a  xlink:title="Préfecture de M'diq-Fnideq  "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Préfecture de M'diq-Fnideq"  ><path    id="REG-03" d="M449.7,718.57s-6.45-52.85-6.46-51.77S467,630.13,467,630.13L414.12,646.3l4.31,31.28-18.33-5.39L405.48,724l7.55,8.63,6.47,2.16,30.2-15.1Z" transform="translate(-138 -620.42)"/></a>  
	                    <a  xlink:title="Province de Tétouan         "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Tétouan"         ><path    id="REG-04" d="M414.11,919.18l31.28-11.86,23.73,7.55,14-7.55-1.08-30.2L499.32,848l35.59-27L500.4,783.28l-29.12-37.75L449.7,719.65l-30.2,15.1-5.39-1.8L405.48,725s-12.94,11.86-14,28L372.05,738l-34.52,27L316,758.48,317,787.6l-24.81,3.23L276.06,821l15.1,27,37.75,4.31L363.42,834l34.51,16.18-3.23,17.26,11.86,18.34Z" transform="translate(-138 -620.42)"/></a>  
	                    <a  xlink:title="Province de Larache         "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Larache"         ><polygon id="REG-05" points="244.83 389.36 257.78 373.18 239.44 341.9 240.52 294.45 253.46 287.98 268.79 266.31 256.7 246.99 259.93 229.73 225.42 213.56 190.91 231.89 153.16 227.58 138.06 200.61 94.91 208.16 114.33 229.73 107.86 262.09 85.21 243.75 48.53 250.23 0 379.65 72.26 378.57 105.7 393.68 162.86 407.7 187.67 409.85 182.28 385.05 208.16 386.13 202.77 368.87 244.83 389.36"/></a>  
                      <a  xlink:title="Province d'Ouezzane         "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province d'Ouezzane"         ><path    id="REG-06" d="M414.11,1188.82l28,16.18,46.38-20.49-5.39-42.07,10.79-59.32L490.69,1054l-58.24-60.4H395.78l-12.95,16.18-42.06-20.49,5.39,17.25-25.88-1.07,5.39,24.8v1.08H303s-5.39,28-5.39,31.28,2.15,24.81,2.15,25.89,2.16,17.25,2.16,17.25l1.08,4.32,15.1,9.7,12.94-16.17,1.08-1.08L417.35,1164Z" transform="translate(-138 -620.42)"/></a> 
                      <a  xlink:title="Province de Chefchaouen     "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province de Chefchaouen"     ><polygon id="REG-07" points="257.78 373.18 294.45 373.18 352.69 433.58 355.93 462.7 424.95 465.94 483.2 444.37 530.65 395.83 545.75 412.01 565.17 405.54 599.68 357 587.82 308.47 462.7 264.25 396.91 200.61 361.32 227.58 344.06 256.7 345.14 286.9 331.12 294.45 307.39 286.9 276.11 298.76 268.56 266.4 253.46 287.98 240.52 294.45 239.44 341.9 257.78 373.18"/></a>  
	                    <a  xlink:title="Province d'Al Hoceïma       "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma&province=Province d'Al Hoceïma"       ><polygon id="REG-08" points="484.43 443.1 502.61 496.14 601.84 515.55 638.51 493.98 657.92 456.23 677.34 482.12 701.07 478.88 733.42 445.45 775.49 447.6 810 421.72 810 296.61 769.01 264.25 649.29 312.78 587.82 308.47 599.68 357 565.17 405.54 545.75 412.01 530.65 395.83 484.43 443.1"/></a>  
	                  
	
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
