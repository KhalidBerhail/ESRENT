<?php 

session_start();
if(isset($_POST['logoutbtn'])) 
{
	unset($_SESSION['type']);
	unset($_SESSION['username']);
}
if( !isset($_SESSION['username']) || $_SESSION['type'] != "admin" )
{
  header("location:../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
$msg="";
$alert="";


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

$logements="";
$stat="valide";
$reqL="SELECT * FROM `logement` where status!=?";
$statementL=$conn->prepare($reqL);
$statementL->bind_param("s",$stat);
$statementL->execute();
$resL=$statementL->get_result();
$i=1;

while ($rowL = mysqli_fetch_array($resL))
{
  $codeL=$rowL['CodeL'];
  $nom = $rowL['nom'];
  $adress = $rowL['adress'];
  $description = $rowL['description'];
  $sup=$rowL['superficie'];
  $prix=$rowL['prix'];
  $LogeType = $rowL['type'];
  $status=$rowL['status'];

    if($i==1)
      $logements.='<div class="row">';  
    $i++;
    
  if($LogeType=="Appartement")
  {//appartement
    $req = "SELECT * FROM appartement where Codeapp=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("i",$codeL);
    $statement->execute();
    $res=$statement->get_result();
    $row=$res->fetch_assoc();
    $rooms=$row['nbrC'];
    $nbrP=$row['nbrP'];
    $logements.='
    <div class="card">
      <div class="set-image">
        <img id="imagetkherbiqa" src="genere_image.php?id='.$codeL.'" style="margin-left: -1px;" alt="Cinque Terre" width="398" height="249">
  </div>
      <h5 class="card-title">'.$nom.'</h5>
          <p class="card-text"> <i class="fas fa-tags CA"></i>'.$prix.' Dh  &nbsp;<i class="fas fa-male CA"></i> '.$nbrP.'  &nbsp; <i class="fas fa-bed CA"></i> '.$rooms.' &nbsp; <i class="fas fa-warehouse CA"></i> '.$sup.' m²</p>
  
          <p class="card-text">  <i class="fas fa-map-marker-alt CA"></i> '.$adress.' </p>
            <br>
          <p class="cpara">'.$description.'</p> <br>
             
          <a href="Requestdetails.php?idL='.$codeL.'" class="btn btn-primary btn_cnslt">Consulter</a>
  
      <div class="ribbon-wrapper-1">
        <div class="ribbon-1">'.$status.'</div>
      </div>
    </div>
    ';
  }else if($LogeType=="Studio")
  {//studio
    $req = "SELECT * FROM studio where CodeS=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("i",$codeL);
    $statement->execute();
    $res=$statement->get_result();
    $row=$res->fetch_assoc();
    $nbrP=$row['nbrP'];
    $logements.='
    <div class="card">
      <div class="set-image">
        <img id="imagetkherbiqa" src="genere_image.php?id='.$codeL.'" style="margin-left: -1px;" alt="Cinque Terre" width="398" height="249">
  </div>
      <h5 class="card-title">'.$nom.'</h5>
          <p class="card-text"> <i class="fas fa-tags CA"></i>'.$prix.' Dh  &nbsp;<i class="fas fa-male CA"></i> '.$nbrP.'  &nbsp; <i class="fas fa-warehouse CA"></i> '.$sup.' m²</p>
  
          <p class="card-text">  <i class="fas fa-map-marker-alt CA"></i> '.$adress.' </p>
            <br>
          <p class="cpara">'.$description.'</p> <br>
             
          <a href="Requestdetails.php?idL='.$codeL.'" class="btn btn-primary btn_cnslt">Consulter</a>
  
      <div class="ribbon-wrapper-1">
        <div class="ribbon-1">'.$status.'</div>
      </div>
    </div>
    ';
  }
  if($i==3)
  {
    $logements.='</div>';  
    $i=1;
  }  



}
/////


 ?>



 <!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kapella Bootstrap Admin Dashboard Template</title>
    <!-- base:css -->
    <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../Resourse/vendors/fontawesome-free/css/all.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
   
    <link rel="stylesheet" href="../../Resourse/css2Dma/style.css">
    <link rel="stylesheet" href="../../Resourse/css2Dma/DL.css">
    <link rel="stylesheet" href="../../Resourse/css2Dma/bootstrap.min.css">

    <!-- endinject -->
    <link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
  </head>

  
  <style type="text/css">
       .lblack{
      width: 70px;
	  height: 16px;
	  margin-bottom:4px
      
    }
</style>
  <body>
    <div class="container-scroller">
		<!-- partial:partials/_horizontal-navbar.html -->
		<div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container-fluid">
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
          
             
              
   
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="dash.html"><img src="../../Resourse/images/logo-1.png" alt="logo"/></a>
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
                        <i class="mdi mdi-settings text-primary"></i>
                        Settings
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
      <nav class="bottom-navbar navL">
        <div class="container">
            <ul class="nav page-navigation">

              <li class="nav-item">
                <a class="nav-link" href="dash.php">
                  <i class="mdi mdi-file-document-box menu-icon"></i>
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link" href="#" >
                    <i class="mdi mdi-cube-outline menu-icon"></i>
                    <span class="menu-title">Ajouter</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="submenu">
                      <ul>
                          <li class="nav-item"><a class="nav-link" href="Addform.php">Une location</a></li>
                          <li class="nav-item"><a class="nav-link" href="#">Un user</a></li>
                      </ul>
                  </div>
              </li>
            
				  <li class="nav-item">
                  <a href="../pages/forms/basic_elements.html" class="nav-link">
                    <i class="mdi mdi-chart-areaspline menu-icon"></i>
                    <span class="menu-title">Form Elements</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="../pages/charts/chartjs.html" class="nav-link">
                    <i class="mdi mdi-finance menu-icon"></i>
                    <span class="menu-title">Charts</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="../pages/tables/basic-table.html" class="nav-link">
                    <i class="mdi mdi-grid menu-icon"></i>
                    <span class="menu-title">Tables</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="pages/icons/mdi.html" class="nav-link">
                    <i class="mdi mdi-emoticon menu-icon"></i>
                    <span class="menu-title">Icons</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item ">
                  <a href="#" class="nav-link">
                    <i class="mdi mdi-codepen menu-icon"></i>
                    <span class="menu-title">Sample Pages</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="submenu">
                      <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
                        
                      </ul>
                  </div>
              </li>
              <li class="nav-item ">
                  <a href="DemandeLoc.php" class="nav-link ">
                 <i class="fas fa-hand-pointer menu-icon"></i>   
          <!--   <i class="mdi mdi-file-document-box-outline menu-icon"></i>-->
                    <span class="menu-title">Demandes</span></a>
              </li>
	
            </ul>
        </div>
      </nav>
    </div>
	
	

           <!--PAGE content-->
           <br>
          <div class='card-wrap'>
          <?=$logements; ?>
          </div>

				<!-- partial:partials/_footer.html -->
				<footer class="footer">
          <div class="footer-wrap">
              <div class="w-100 clearfix">
                <span class="d-block text-center text-sm-left d-sm-inline-block"> &nbsp; <span> <img src="../../Resourse/images/logoBlack.png" class="lblack" /></span> &nbsp;Copyright © ESRENT. All rights reserved</span>
                
              </div>
          </div>
        </footer>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="../../Resourse/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="../../Resourse/js2/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
 
    <!-- Custom js for this page-->
    <script src="../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->
  </body>
</html>