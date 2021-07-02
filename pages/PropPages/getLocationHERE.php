<?php
session_start();
if(isset($_POST['logoutbtn'])) 
{
	unset($_SESSION['type']);
	unset($_SESSION['username']);
}
if( !isset($_SESSION['username']) || $_SESSION['type'] != "pro" )
{
  header("location:../../homeP.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//remplisage des données d'utilisateur courrant
$src="";
$ProfileP="";
$USN=$_SESSION['username'];
$reqIU="SELECT * FROM utilisateur WHERE username=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$USN);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();
if($rowIU['imageP']!=NULL)
{
  	$src="../Samples/profilpic.php?UN=$USN";
	$ProfileP="<img src='".$src."' alt='profile'/>";
}
else
{
	$src="../../Resourse/imgs/ProfileHolder.jpg";
	$ProfileP="<img src='".$src."' alt='profile'/>";
}

$codeU = $_SESSION['usercode'];

//----------------- ----------Notifications------------
$nbr_nts=0;
// notifications des packs
$notif="";
$reqN="SELECT * FROM logement WHERE CodeP=? and (CodeL NOT IN (SELECT CodeL FROM pack where CodeU=?))";
$statementN=$conn->prepare($reqN);
$statementN->bind_param("ii",$codeU,$codeU);
$statementN->execute();
$resN=$statementN->get_result();

if($resN->num_rows!=0)
  {
    $notif='
    <a class="dropdown-item preview-item" href="ToSuperLog.php">
    <div class="preview-thumbnail">
        <div class="preview-icon bg-success">
          <i class="mdi mdi-information mx-0"></i>
        </div>
    </div>
    <div class="preview-item-content">
        <h6 class="preview-subject font-weight-normal">Logement en mode normal</h6>
        <p class="font-weight-light small-text mb-0 text-muted">
          click to go update
        </p>
    </div>
  </a>
    ';
    $nbr_nts=$nbr_nts+1;
  }
//Notifications de localisation de logement:
  $reqN="SELECT * from logement where CodeP=?";
  $statementN=$conn->prepare($reqN);
  $statementN->bind_param("i",$codeU);
  $statementN->execute();
  $resN=$statementN->get_result();
  $notifs="";
  $cnt=1;
  $CodeL1;
  $CodeL2;
  while(($rowN = mysqli_fetch_array($resN)))
  {
    
    if($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=1)
     { 
       $CodeL1=$rowN['CodeL'];
       $notifs=" <a id='LLoc1' class='dropdown-item preview-item'>
             <div class='preview-thumbnail'>
               <div class='preview-icon bg-success'>
                <i class='fas fa-map-marked-alt'></i>
               </div>
             </div>
             <div class='preview-item-content'>
               <h6 class='preview-subject font-weight-normal'>Localiser votre ".$rowN['type']." '".$rowN['nom']."'</h6>
               <p class='font-weight-light small-text mb-0 text-muted'>
                 Just now
               </p>
             </div>
           </a>";
           $cnt=$cnt+1;
           $nbr_nts=$nbr_nts+1;
     }  
    else if ($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=2)
     {
      if($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=1)
      { 
        $CodeL2=$rowN['CodeL'];
        $notifs=" <a id='LLoc2' class='dropdown-item preview-item'>
              <div class='preview-thumbnail'>
                <div class='preview-icon bg-success'>
                 <i class='fas fa-map-marked-alt'></i>
                </div>
              </div>
              <div class='preview-item-content'>
                <h6 class='preview-subject font-weight-normal'>Localiser votre ".$rowN['type']." '".$rowN['nom']."'</h6>
                <p class='font-weight-light small-text mb-0 text-muted'>
                  Just now
                </p>
              </div>
            </a>";
            $cnt=$cnt+1;
            $nbr_nts=$nbr_nts+1;
      } 
     }    
  }
//user notifications(saves/ratings)
  $user_notis="";
  $reqN="SELECT * from user_notis where CodeP=? and status='old'";
  $statementN=$conn->prepare($reqN);
  $statementN->bind_param("i",$codeU);
  $statementN->execute();
  $resN=$statementN->get_result();
  while(($rowN = mysqli_fetch_array($resN)))
  {
    $user=$rowN['CodeU'];
    $action=$rowN['action'];
    $logement=$rowN['CodeL'];
    $nt_code=$rowN['idN'];

    $reqU="SELECT * from utilisateur where CodeU=?";
    $statementU=$conn->prepare($reqU);
    $statementU->bind_param("i",$user);
    $statementU->execute();
    $resU=$statementU->get_result();
    $rowU=$resU->fetch_assoc();

    $nt_usern=$rowU['username'];

    $reqU="SELECT * from logement where CodeL=?";
    $statementU=$conn->prepare($reqU);
    $statementU->bind_param("i",$logement);
    $statementU->execute();
    $resU=$statementU->get_result();
    $rowU=$resU->fetch_assoc();
    $nt_loge=$rowU['nom'];
    

    if($action=='saved')
     {
       $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                        <div class='preview-thumbnail'>
                            <div class='preview-icon bg-success'>
                             <i class='mdi mdi-heart text-normal'></i>
                            </div>
                        </div>
                        <div class='preview-item-content'>
                          <h6 class='preview-subject font-weight-normal'>".$nt_usern." a enregistré votre logement '".$nt_loge."'</h6>
                          <p class='font-weight-light small-text mb-0 text-muted'>
                           Just now
                          </p>
                        </div>
                      </a>";
                      //$nbr_nts=$nbr_nts+1;
     }
    else if($action=='rated')
     {
        $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                        <div class='preview-thumbnail'>
                            <div class='preview-icon bg-success'>
                             <i class='fas fa-star'>
                            </div>
                        </div>
                        <div class='preview-item-content'>
                          <h6 class='preview-subject font-weight-normal'>".$nt_usern." a évalué votre logement '".$nt_loge."'</h6>
                          <p class='font-weight-light small-text mb-0 text-muted'>
                           Just now
                          </p>
                        </div>
                      </a>";
                     // $nbr_nts=$nbr_nts+1;
     }
    else if($action=='commented') 
     {
       $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                        <div class='preview-thumbnail'>
                            <div class='preview-icon bg-success'>
                              <i class='far fa-comment'></i> 
                            </div>
                        </div>
                        <div class='preview-item-content'>
                          <h6 class='preview-subject font-weight-normal'>".$nt_usern." a commenté sur votre logement '".$nt_loge."'</h6>
                          <p class='font-weight-light small-text mb-0 text-muted'>
                           Just now
                          </p>
                        </div>
                      </a>";
                    //  $nbr_nts=$nbr_nts+1;
     }
    /* $reqUp="UPDATE `user_notis` SET status='loaded'  where idN=?";
     $statementUp=$conn->prepare($reqUp);
     $statementUp->bind_param("i",$nt_code);
     $statementUp->execute();*/

  }

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <!-- base:css -->
    <link rel="stylesheet" href="../../Resourse/LocationPage/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../Resourse/LocationPage/vendors/base/vendor.bundle.base.css">
  
    <!-- inject:css -->
    <link rel="stylesheet" href="../../Resourse/LocationPage/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../../Resourse/LocationPage/images/favicon.png" />

    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
  
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
  <script type="text/javascript" >window.ENV_VARIABLE = 'https://developer.here.com'</script><script src='https://developer.here.com/javascript/src/iframeheight.js'></script>
  </head>
  <body>
    <div class="container-scroller">
		<!-- partial:partials/_horizontal-navbar.html -->
    <div id="mn" class="horizontal-menu">
    <nav class="navbar top-navbar col-lg-12 col-12 p-0">
          <div class="container-fluid">
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
            
            <ul class="navbar-nav navbar-nav-left">
                <li class="nav-item ml-0 mr-5 d-lg-flex d-none">
                  <a href="#" class="nav-link horizontal-nav-left-menu"><i class="mdi mdi-format-list-bulleted"></i></a>
                </li>
                <li class="nav-item dropdown">
                  <a  id="noti_open" class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell mx-0"></i>
                    <span id="nbrnts" class="count bg-success"><?php if($nbr_nts>0) echo $nbr_nts?></span>
                  </a>
                  <div id="notifs" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p> <br>
                    <hr>
                    <a id="clr_all">Clear all</a>
                    
                    <div id="stsx">
                      
                      </div>
                    <div id="new_user_notis">
                      
                    </div>

                    <div id="loaded_user_notis">
                      
                    </div>

                    <div id="old_user_notis">
                    <?=$user_notis?>   
                    </div>
                    <?=$notif; ?>
                    <a class="dropdown-item preview-item">
                      <div class="preview-thumbnail">
                          <div class="preview-icon bg-warning">
                            <i class="mdi mdi-settings mx-0"></i>
                          </div>
                      </div>
                      <div class="preview-item-content">
                          <h6 class="preview-subject font-weight-normal">Settings</h6>
                          <p class="font-weight-light small-text mb-0 text-muted">
                            Private message
                          </p>
                      </div>
                    </a>
                    <a class="dropdown-item preview-item">
                      <div class="preview-thumbnail">
                          <div class="preview-icon bg-info">
                            <i class="mdi mdi-account-box mx-0"></i>
                          </div>
                      </div>
                      <div class="preview-item-content">
                          <h6 class="preview-subject font-weight-normal">New user registration</h6>
                          <p class="font-weight-light small-text mb-0 text-muted">
                            2 days ago
                          </p>
                      </div>
                    </a>
                    <?=$notifs?>

                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-email mx-0"></i>
                    <span class="count bg-primary">4</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>

                    <?=$ntMsg;?>
                    

                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link count-indicator "><i class="mdi mdi-message-reply-text"></i></a>
                </li>
                <li class="nav-item nav-search d-none d-lg-block ml-3">
                  <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                          <i class="mdi mdi-magnify"></i>
                        </span>
                      </div>
                      <input type="text" class="form-control" placeholder="search" aria-label="search" aria-describedby="search">
                  </div>
                </li>	
              </ul>
  
                
    
              <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                  <a class="navbar-brand" href="dash.html"><img src="../../Resourse/images/logo-1.png" alt="logo"/></a>
              </div>
              <ul class="navbar-nav navbar-nav-right">
                
                
              
                  <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                      <span class="nav-profile-name"><?=$USN?></span>
                      <span class="online-status"></span>
                      <?=$ProfileP?>
                    </a>
                    <form method="post" class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item">
                          <i class="mdi mdi-account text-primary"></i>
                          Mon Compte
                        </a>
                        <a class="dropdown-item">
                          <i class="mdi mdi-home-modern text-primary"></i>
                          Les Logement
                        </a>
                        <button name="logoutbtn" class="dropdown-item">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                        </button>
                    </form>
                  </li>
              </ul>
              <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <span class="mdi mdi-menu"></span>
              </button>
            </div>
          </div>
        </nav>
    </div>
    <!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<div class="main-panel">
				<div class="content-wrapper">
          <div id="map">
                   
          </div>
					<div id="sss1" class="row mt-4">
						<div id="sss2" class="col-lg-8 grid-margin stretch-card">
							<div class="card">
								<div class="card-body">
									
                <h4 class="card-title">Localiser mon logement</h4>
                <hr>
											<div class="row">
                      <button id="ntm"  type="button" class="btn btn-primary centredZero">Naviguer sur la carte</button>
                      </br>     
                      <button id="cl"  type="button" class="btn btn-primary centredOne">Me localiser</button>
                      </div>
</br>
                      <h4  class="card-title">Merci de confirmer la localisation choisie</h4>
                <hr>

                <button id="cnfrm" type="button" class="btn btn-success" >Confirmer</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					
					
        </div>
        
     
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
				
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
   
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
   
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
  
    <!-- Custom js for this page-->
  
    <!-- End custom js for this page-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>


    <script type="text/javascript">
       // var map;
        var clck=0;
       // var marker;
        var cnfrm_state='Y';
        


        function initMap() {                            
          /* var latitude = 34.0531 ; // latitude
            var longitude =-6.79846; //longtitude
            
            var myLatLng = {lat: latitude, lng: longitude};
            
            map = new google.maps.Map(document.getElementById('map'), {
              center: myLatLng,
              zoom: 8,
              disableDoubleClickZoom: true, 
              gestureHandling: 'none',
              zoomControl: false
            });
         // obtenire la positoin et placement d'un marker
            google.maps.event.addListener(map,'dblclick',function(event) {
              document.getElementById("cl").disabled = false;
              document.getElementById("cnfrm").disabled = false;
              var cnfrm_state='N';
              
              lat= event.latLng.lat();
              lng =  event.latLng.lng();
              var MLatLng={lat: lat, lng: lng};
               if(clck==0)
             {   marker = new google.maps.Marker({
                  position: event.latLng, 
                  map: map, 
                }); clck=1;}
                else
                marker.setPosition(MLatLng);
                    
            });    
        
            
           */
        }
        </script>

<script >
       $(document).ready(function(){
           
            $('#ntm').click(function () {

                behavior.enable(H.mapevents.Behavior.WHEELZOOM);
                behavior.enable(H.mapevents.Behavior.DRAGGING);

            /*  map.setOptions({
                disableDoubleClickZoom: true, 
              gestureHandling: 'greedy',
              zoomControl: true
              });*/
            });

        });
        </script>

      <!--  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWv8pHQtbrov613r_RMqCjZ_nOrz2y7HM&callback=initMap"
        async defer></script> -->
        <script>

/**
 * Moves the map to display over Berlin
 *
 * @param  {H.Map} map      A HERE Map instance within the application
 */
function moveMapTohouse(map){
  map.setCenter({lat:34.0531, lng:-6.79846});
  map.setZoom(14);
  behavior.disable(H.mapevents.Behavior.WHEELZOOM);
  behavior.disable(H.mapevents.Behavior.DBLTAPZOOM);
  behavior.disable(H.mapevents.Behavior.DRAGGING);
   

}

/**
 * Boilerplate map initialization code starts below:
 */

//Step 1: initialize communication with the platform
// In your own code, replace variable window.apikey with your own apikey
var platform = new H.service.Platform({
  apikey:'gNAS-hI7AKsqytfacNxMU-WZqMQa_Zn-nunnoU2p6s4'
});
var defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Europe
var map = new H.Map(document.getElementById('map'),
  defaultLayers.vector.normal.map,{
  center: {lat:33.589886, lng:-7.603869},
  zoom: 8,
  pixelRatio: window.devicePixelRatio || 1
});

// add a resize listener to make sure that the map occupies the whole container
window.addEventListener('resize', () => map.getViewPort().resize());

//Step 3: make the map interactive
// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Create the default UI components
var ui = H.ui.UI.createDefault(map, defaultLayers);

// Now use the map as required...
window.onload = function () {
  moveMapTohouse(map);
}

var coord; 
var homeMarker;
//var homeIcon = new H.map.Icon("../../Resourse/imgs/adresse.png");

        function setUpClickListener(map) {
           // Attach an event listener to map display
           // obtain the coordinates and display in an alert box.
                map.addEventListener('tap', function (evt) {
                    coord = map.screenToGeo(evt.currentPointer.viewportX,
                    evt.currentPointer.viewportY);
                    lat=coord.lat;
                    lng=coord.lng.toFixed(4);
                    document.getElementById("cl").disabled = false;
                    document.getElementById("cnfrm").disabled = false;
                    cnfrm_state='N';
                    
                    
                  
                   if(clck==0)
                    {
                        clck=clck+1;
                        homeMarker =new H.map.Marker({lat:lat, lng:lng}) ;
                        map.addObject(homeMarker);
                        
                    }
                   else 
                  { 
                    homeMarker.setGeometry({lat:lat, lng:lng});
                    clck=clck+1;
                   // map.removeObject(homeMarker);
                  }

                });
        }

        setUpClickListener(map);
    
  </script>
        
        <script>
document.getElementById("notifs").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
</script>


<script>
var CodePst=<?=$codeU?>;
var nbrnts=<?=$nbr_nts?>;
var nbrnts2=0;
var audioNT = new Audio('../sound/time-is-now.mp3');




  setInterval(function(){ 
  
//new notis

    $.ajax({  
                          url:"User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:nbrnts,act:'get'},  
                          dataType : 'json',
                          success:function(response){
                           
                            nbrnts=response.result;
                            nbrnts2=response.result;

                            if(nbrnts ><?=$nbr_nts?>)
                            {$('#nbrnts').empty().append(nbrnts);
                              audioNT.play();
                            }
                           nbrnts=<?=$nbr_nts?>;
                           
                             
                          }  
                    });
//loaded notis
                    $.ajax({  
                          url:"Loaded_User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response2){

                            $('#loaded_user_notis').html(response2.echo);
                            nbrnts=response2.result2;
                            if(nbrnts ><?=$nbr_nts?>)
                            {$('#nbrnts').empty().append(nbrnts);}
                            nbrnts=<?=$nbr_nts?>;

                             
                          }  
                    });

//old notis       

$.ajax({  
                          url:"Old_User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response3){

                            $('#old_user_notis').html(response3.echo);
                            
                            
                             
                          }  
                    });
    }, 1000);

   


   


   
</script>

<script>
var mns=0;
$(document).ready(function(){  

nt_clsd="Y";

   $('#noti_open').click(function(){  
          if(nt_clsd=="Y")
           {
            $.ajax({  
                          url:"noti_is_old.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response4){
                            mns=response4.result4;
                            if(mns>0)
                            $('#nbrnts').empty().append(mns);
                          // arr=response3.tab;
                           //$('#old_user_notis').insertAdjacentHTML('afterbegin',response3.echo);
                          //  $('#old_user_notis').html();
                            

                             
                          }  
                    });

             nt_clsd="N";
           }
          else if(nt_clsd=="N")
           {
            

            nt_clsd="Y";
           }
          
       });
        
       
  
 
 }); 
</script>

<script>
   var CL=<?=$_GET['target']?>;
   var CU=<?=$_GET['owner']?>;
   var lng;
   var lat;
   document.getElementById("cnfrm").disabled = true;
    $('#cl').click(function(){
        behavior.disable(H.mapevents.Behavior.WHEELZOOM);
  behavior.disable(H.mapevents.Behavior.DBLTAPZOOM);
  behavior.disable(H.mapevents.Behavior.DRAGGING);
      if(navigator.geolocation)
        {
          navigator.geolocation.getCurrentPosition(function(position){
           console.log(position);
           lat=position.coords.latitude;
            lng=position.coords.longitude;
            document.getElementById("cl").disabled = true;
            document.getElementById("cnfrm").disabled = false;
            cnfrm_state='N';
            
          /* $.ajax({  
                url:"isertLngLat.php?",  
                method:"POST",  
                data:{CodeL:CL,CodeU:CU,lng:lng,lat:lat},  
           });*/

          });
        }
      else
        {
          console.log("geolocation is not supported");  
        }  

    });
   </script>

        <script>   
    $('#cnfrm').click(function(){
     
           $.ajax({  
                url:"isertLngLat.php?",  
                method:"POST",  
                data:{CodeL:CL,CodeU:CU,lng:lng,lat:lat},  
           });

     
    });
   </script>

<script>
  //button disabled hover
  $(document).ready(function(){
           
           $('#cnfrm').hover(function () {

           });
  });
</script>

<style>
#map{
	height:100%;
  width:100%;
  position:absolute;
  top: 0;
  left: 0;
}

</style>
  </body>
</html>