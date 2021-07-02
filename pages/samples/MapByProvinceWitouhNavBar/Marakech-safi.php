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
$nbrA_Marakech=0;
$nbrA_Chichaoua=0;
$nbrA_AlHaouz=0;
$nbrA_ELKelaa=0;
$nbrA_Essaouira=0;
$nbrA_Rehamna=0;
$nbrA_Safi=0;
$nbrA_Youssoufia=0;


$req_nbrA="SELECT `province-prefecture`,count(*) as nbrA FROM `logement` GROUP BY `province-prefecture`  ";
$statement_nbrA=$conn->prepare($req_nbrA);
$statement_nbrA->execute();
$res_nbrA=$statement_nbrA->get_result();                    
while(($row_nbrA= mysqli_fetch_array($res_nbrA)))
{
   if($row_nbrA['province-prefecture']=="Préfecture de Marrakech")
   {
    $nbrA_Marakech=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Chichaoua")
   {
    $nbrA_Chichaoua=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'Al Haouz")
   {
    $nbrA_AlHaouz=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'El Kelaâ des Sraghna")
   {
    $nbrA_ELKelaa=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province d'Essaouira")
   {
    $nbrA_Essaouira=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Rehamna")
   {
    $nbrA_Rehamna=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Safi")
   {
    $nbrA_Safi=$row_nbrA['nbrA'];
   }
   else if($row_nbrA['province-prefecture']=="Province de Youssoufia")
   {
    $nbrA_Youssoufia=$row_nbrA['nbrA'];
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
                <a class="nav-link active" id="a01"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Préfecture de Marrakech"         role="tab" aria-controls="a01" aria-selected="true"> Préfecture de Marrakech         </a>
                <a class="nav-link" id="a02"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Chichaoua"           role="tab" aria-controls="a02" aria-selected="false">Province de Chichaoua           </a>
                <a class="nav-link" id="a03"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'Al Haouz"             role="tab" aria-controls="a03" aria-selected="false">Province d'Al Haouz             </a>
                <a class="nav-link" id="a04"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'El Kelaâ des Sraghna" role="tab" aria-controls="a04" aria-selected="false">Province d'El Kelaâ des Sraghna </a>
                <a class="nav-link" id="a05"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'Essaouira"            role="tab" aria-controls="a05" aria-selected="false">Province d'Essaouira            </a>
                <a class="nav-link" id="a06"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Rehamna"             role="tab" aria-controls="a06" aria-selected="false">Province de Rehamna             </a>
                <a class="nav-link" id="a07"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Safi"                role="tab" aria-controls="a07" aria-selected="false">Province de Safi                </a>
                <a class="nav-link" id="a08"  href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Youssoufia"          role="tab" aria-controls="a08" aria-selected="false">Province de Youssoufia          </a>
               
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
                    
                    <a  xlink:title="Préfecture de Marrakech         "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Préfecture de Marrakech"               ><polygon style="fill:#46EEAE" id="REG-01" points="503 386 503 356 525 321 522 316 533 290 572 275 574 260 583 270 637 272 642 286 638 333 647 334 656 347 657 369 651 369 641 388 668 384 673 390 689 392 690 401 731 405 743 413 743 441 731 445 691 436 682 443 686 472 621 451 600 467 610 491 584 501 583 519 562 519 562 500 551 471 512 457 511 449 524 445 513 424 501 425 498 415 523 403 509 386 503 386"/>
	                  <a  xlink:title="Province de Chichaoua           "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Chichaoua"                 ><path    id="REG-02" d="M349.39,590.36,356,632l-25-13-34,28v9h-9l-13,42,21,10-14,26-39,2-7,13,10,10s3,24,6,27l-16,2,2,24-20,3-6,9,10,1,3,12,12,10,6,3,4-4,2,4,30,6,9,16-6.72,22-1,18L314,925h8l2-4s13,1,14,0,7-20,7-20l57-29,19,6-1,6,9,9-6,19,10,10,3-10,32-19,9-1,27-10,14,3,22-11,14-48-6-68,22-8s-28-40,16-16h18V715l-11-29-39-14-1-8,13-4-11-21-12,1-3-10,25-12-14-17h-6l-24-1-14,6-16-1-2-5-8,5-6.58-2.62L475,596l-36-3-10,5h-9l-8-6-10,7Z" transform="translate(-42 -215)"/>
	                  <a  xlink:title="Province d'Al Haouz             "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'Al Haouz"                   ><path    id="REG-03" d="M477,892l27-10,14,3,22-11,14-48-6-68,22-8s-29-40,16-16h39l1-18,26-10-10-24,21-16,65,21-4-29,9-7,40,9,12-4V628l5-5h33l13,18,8,1,21-7,7,4,29-18h42l39,38h28l-10,29-17,23-22,6,1,23-34,15-26-2-19,9-3,8-15,10-10,1-1,10H821l-28,11-19,12-27-3-7,12-32,18-11,36,5,5,1,3-16,11-31-3-15-6-26,5-13,14-40,2-68-7Z" transform="translate(-42 -215)"/>
	                  <a  xlink:title="Province d'El Kelaâ des Sraghna "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'El Kelaâ des Sraghna"       ><path    id="REG-04" d="M844,345l-6,35-41,59-2,14s63,82,65,94l-3,5,5,7-15,15,16,3-20,26,7,12-14-2,14,23-6,6,21-7,7,4,29-18h42l-6-13,3-11,43-9,8,6,49-5V567l14-1-21-18,4-8-2-29-17-14-5,4-6-55,12-18-9-8-1-8,7-13-32-11-11-13s-7,0-9-1a68.18,68.18,0,0,1-7-5l-8-6-18-1-14-20-15,5-12-3-2,4h-9l-2-9-5,7-12,3-6-8Z" transform="translate(-42 -215)"/>
                    <a  xlink:title="Province d'Essaouira            "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province d'Essaouira"                  ><path    id="REG-05" d="M184,527l19,17v8l6-2,11,5,8-13s24,13,29-9l14-4,19,18,8.75-.25L311,541s58-1,12,31l24,12,3,8,6,40-25-13-34,28-1,9h-8l-3,18-10,24,21,10-14,26-39,2-7,13,10,10,2,15,4,12-15,2,1,24-20,3-6,9,10,1,3,12,12,10,6,3,4-4,2,4,30,6,9,16-4,13-3,15v9l-1,8-16,15h-8l-24-20-17,2-9-2h-9s-4,3-5,4-7,3-7,3l-29-9-3-7-1-1-10,5v10H132l-4-9-5,1-11,26-22,6H85l-3-4,1-5L68,887l-18-1-1-16,5-4-3-35-9-7,9-30V776l7-14-1-29-7-9-8-1,4-9,22-24,2-10,31-39V614Z" transform="translate(-42 -215)"/>
                    <a  xlink:title="Province de Rehamna             "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Rehamna"                   ><polygon id="REG-06" points="481.64 176.12 492 168 508 168 513 161 522 160 528 162 533 150 540 145 545 147 564 136 566 130 583 128 626 101 639 69 625 55 629 45 638 28 636 16 643 6 656 0 666 14 675 29 682 33 697 52 719 55 720 64 742 65 785 126 794 123 802 130 796 165 783 185 755 224 753 238 814 322 818 332 815 337 820 344 805 359 821 362 801 388 808 400 794 398 808 421 802 427 794 426 781 408 748 408 743 413 731 405 690 401 689 392 673 390 668 384 641 388 651 370 657 369 656 347 647 334 638 333 642 286 637 272 583 270 574 260 570 255 573 248 562 233 551 233 547 237 537 238 536 237 531 231 512 232 506 210 484 191 486 183 481.64 176.12"/>
                    <a  xlink:title="Province de Safi                "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Safi"                      ><path    id="REG-07" d="M241.5,310.5s68-44,80-62l24,17,4,4-12,10-1,5,1,7,2,3s-1,3,1,4-5-2,2,1,3,3,7,5,4,4,6,6,8,5,9,7-15-9,0,0,21,11,21,11h14v-7l6,2,16-2v4s9,7,14,3a102.67,102.67,0,0,1,10-7s12-5,13-1,13,14,13,14l8,11v16l12,14v6h9v16h-16l-19,13-25-3-2,12s-1,4-4,4h-11l-15-3a3.7,3.7,0,0,0-2,3c0,2-6,11-6,11a81.44,81.44,0,0,1-2,9c-1,3,4,3-2,9s-5,0-6,6-7,1-1,6,12,3,13,9,5,9,1,10-7-2-9,0-2,1,1,5,4,21,4,21l-18,20-14,4,4,15-24,36-24-12s39-26-2-31l-10-1-9,5-6,2h-6l-5-4s-4-5-6-7-8-7-8-7l-14,4-1,6s-6,5-9,6-13,1-13,1l-5-1a59.23,59.23,0,0,0-4,6c-1,2-5,4-5,4l-11-5-6,2v-8l-5-3-14-14,35-36s5-5,5-8,3-16,3-16a44.32,44.32,0,0,1,5-6c3-3,17-19,17-19s5-16-2-26c0,0,17-18-3-31l-6-12s28-23,21-39Z" transform="translate(-42 -215)"/>
	                  <a  xlink:title="Province de Youssoufia          "  xlink:href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi&province=Province de Youssoufia"                ><path    id="REG-08" d="M499.69,396l-16.12.07s-19.09,12.2-20.09,12.2-25.2-2.91-25.2-2.91,1.08,16.14-7,16.68-25.2-3.42-25.2-3.42l-8,14.17-2,9.09v3l1.53,1.76-1.51,3.14-4.46,4.12-2.42.73-.14,2.39s-1.15,3.79-2.08,3.42a10.56,10.56,0,0,0-2.94-.37,11.71,11.71,0,0,0,4.05,5A23.69,23.69,0,0,0,398.68,469l4.86,11.58-2.19,1.66-4.54,0-4.53,0-.88,2.21s3.75,1.36,6.45,23.27l-18.46,21-14.08,4.1L369.4,548l-24,36.44s2,3,2,5,55.45,10.85,55.45,10.85l9-8.11,8.09,6,9.07,0,11.06-5.1,34.26,2.88,0,7.06,7.07,2,7-5.08,2,5,16.13.93,15.08-5.11,23.18.91-.14-30.28,22-35.42-3-5,11-26.29L614,490.35l1.95-15.15-4-5,3-7.08L603.69,448l-11.08,0-4,4.06s-10.07,1.05-10.08.05-5.06-6-5.06-6l-19.14.09L548.18,425,525.93,406l2-8.09-5.08-8.05-5.05-3-5,3.05-6,8.1Z" transform="translate(-42 -215)"/>                                                                                                                                                                                                                                                                                                                                   
	                  
	
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
