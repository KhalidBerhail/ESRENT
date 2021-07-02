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
$nbrA_Settat=0;
$nbrA_ElJadida=0;
$nbrA_SidiBennour=0;
$nbrA_Berrechid=0;
$nbrA_Mohammédia=0;
$nbrA_Benslimane=0;
$nbrA_Casablanca=0;



$req_nbrA="SELECT `province-prefecture`,count(*) as nbrA FROM `logement` GROUP BY `province-prefecture`  ";
$statement_nbrA=$conn->prepare($req_nbrA);
$statement_nbrA->execute();
$res_nbrA=$statement_nbrA->get_result();                    
while(($row_nbrA= mysqli_fetch_array($res_nbrA)))
{
   if($row_nbrA['province-prefecture']=="Province de Settat")
   {
    $nbrA_Settat=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'El Jadida")
   {
    $nbrA_ElJadida=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Sidi Bennour")
   {
    $nbrA_SidiBennour=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Berrechid")
   {
    $nbrA_Berrechid=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Préfecture de Mohammédia")
   {
    $nbrA_Mohammédia=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Benslimane")
   {
    $nbrA_Benslimane=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Préfecture de Casablanca")
   {
    $nbrA_Casablanca=$row_nbrA['nbrA'];
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
                <a class="nav-link" id="a01" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Settat"       role="tab" aria-controls="a01" aria-selected="true"> Province de Settat         </a>
                <a class="nav-link" id="a02" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province d'El Jadida"     role="tab" aria-controls="a02" aria-selected="false">Province d'El Jadida          </a>
                <a class="nav-link" id="a03" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Sidi Bennour" role="tab" aria-controls="a03" aria-selected="false">Province de Sidi Bennour             </a>
                <a class="nav-link" id="a04" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Berrechid"    role="tab" aria-controls="a04" aria-selected="false">Province de Berrechid    </a>
                <a class="nav-link" id="a05" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Préfecture de Casablanca" role="tab" aria-controls="a05" aria-selected="false">Préfecture de Casablanca        </a>
                <a class="nav-link" id="a06" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Préfecture de Mohammédia" role="tab" aria-controls="a06" aria-selected="false">Préfecture de Mohammédia          </a>
                <a class="nav-link" id="a07" href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Benslimane"   role="tab" aria-controls="a07" aria-selected="false">Province de Benslimane            </a>

               
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
                <svg  xmlns="http://www.w3.org/2000/svg" xmlns:amcharts="http://amcharts.com/ammap" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewbox="0 0 1012 726">

	                <g>
                    
                      <a  xlink:title="Préfecture de Marrakech         "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Settat"             ><path    id="REG-01" d="M466,701v48l64,118,54,11,30,63,30,30,87-16,11,37s139,118,124-116l-19-27,32-127,17,1,13-99,13-7-23-23-9,6-37,5s-29,27-63,23l-25-16-14,6v34l-50,12-3,14-11,14-6-7V669l-11-3-2-14H656l-2-3v-9l-57-2-3,18,12,7-21,21,5,20-22,4-18-18-7,9-24,2-2,10-13-12Z" transform="translate(-86 -349)"/>                                                                                                 
	                    <a  xlink:title="Province de Chichaoua           "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province d'El Jadida"           ><path    id="REG-02" d="M462,808l20.1-29.32L466,749V679l-13-13V648l-15-11,15-9,4-15,25-3V587l15,12,9-3,5-30-4-7-10-47-10-6-43,26-60,6-66,60-37-16s-47,48-45,95L119,780l31,56,27-15,18,15h19V817l25,12,26-8V804l14-7,41,1,11-29,34-10,8,8,27-1v25l28,3,8,11Z" transform="translate(-86 -349)"/>
	                    <a  xlink:title="Province d'Al Haouz             "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Sidi Bennour"       ><polygon id="REG-03" points="33 431 0 461 30 507 11 522 10 542 52 573 144 568 168 599 168 633 187 669 252 655 261 626 301 616 380 556 352 512 375 496 376 459 350 456 342 445 314 442 314 417 287 417 279 410 245 420 234 449 193 448 179 455 179 472 153 480 128 468 128 487 109 487 91 472 64 487 33 431"/> 
	                    <a  xlink:title="Province d'El Kelaâ des Sraghna "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Berrechid"          ><path    id="REG-04" d="M531,483l-44,23,10,6,10,47,4,7-5,30-9,3-15-12v23l-25,3-4,15-15,9,15,11v18l13,13v22l38-2,13,12,2-10,24-2,7-9,18,18,22-4-5-20,21-21-12-7,3-18,57,2v9l2,3h12l2,14,11,3v16l6,7,11-14,3-14,50-12V618l14-6,10.53,6.74L787,594l-37-25,7-11,2-21-34-3-6-21-49,10,1,29-19,4,3,30h-7l-49-27-2-27,13-14-23-17S524,497,531,483Z" transform="translate(-86 -349)"/>
                      <a  xlink:title="Province d'Essaouira            "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Préfecture de Casablanca"       ><path    id="REG-05" d="M587,449l-56,34s-13,13,56,18l23,17-13,14,2,27,49,27h7l-3-30,19-4V523l48-10V496l26-1,2-38,21-34-26-29-27,17,1,21-43,31-3,11-18,1-9,12-28,3S619,454,587,449Z" transform="translate(-86 -349)"/>
                      <a  xlink:title="Province de Rehamna             "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Préfecture de Mohammédia"       ><path    id="REG-06" d="M611,437l-24,12s29,1,28,41l28-3,9-12,18-1,3-11,43-31-1-21-52,26Z" transform="translate(-86 -349)"/>
                      <a  xlink:title="Province de Safi                "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat&province=Province de Benslimane"         ><path    id="REG-07" d="M944,562v44l-22,11-23-23-9,6-37,5s-33,29-63,23l-14-9h0l11-25-37-25,7-11,2-21-34-3-6-21V496l26-1,2-38,21-34-26-29,39-45h56l11,6,22,99,14,3,26-11,18,17,38,2,4-2-24,43S974,531,944,562Z" transform="translate(-86 -349)"/>
	                                                                                                                                                          
	                
	
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
