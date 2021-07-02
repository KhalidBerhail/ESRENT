<?php 
session_start();
if( !isset($_SESSION['username']) || $_SESSION['type'] != "admin" )
{
  header("location:../../indexx.php");
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
$CodeL=$_GET['idL'];
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






//remplicage des images
$images="";
$req = "SELECT * FROM image where CodeL=?";
$statement=$conn->prepare($req);
$statement->bind_param("s",$CodeL);
$statement->execute();
$res=$statement->get_result();
$i=1;
while ($row = mysqli_fetch_array($res)) 
{
      $images.="
      <img src='genere_image_log.php?id=".$row['CodeImg']."' alt='image' class='imagepage mySlides' style='width: 430px; border-radius: 10px; display: block;'>
      ";
}

//remplicage des champ
$reqIU="SELECT * FROM `logement` WHERE CodeL=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$CodeL);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();
$codeP=$rowIU['CodeP'];

$nom = $rowIU['nom'];
$adress = $rowIU['adress'];
$description = $rowIU['description'];
$sup=$rowIU['superficie'];
$prix=$rowIU['prix'];

$reqIU="SELECT * FROM `proprietaire` WHERE CodeP=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$codeP);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();
$nomP = $rowIU['nom'];
$prenomP = $rowIU['prenom'];
$CIN = $rowIU['CIN'];
$Tel=$rowIU['tel'];


if(isset($_POST['refus']))
  {
    $motif=$_POST['motif'];
    echo $motif;
    $req = "INSERT INTO `demande`(`codeP`, `motiv`, `CodeL`) VALUES (?,?,?)";
    $statement=$conn->prepare($req);
    $statement->bind_param("sss",$codeP,$motif,$CodeL);
    $statement->execute();
    header("location:dash.php");
  }
  if(isset($_POST['accept']))
  {
    echo 6;
    $req = "DELETE FROM `demande` WHERE `CodeL`=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("s",$CodeL);
    $statement->execute();
    $sta="valide";
    $req = "UPDATE `logement` SET `status`=? WHERE `CodeL`=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("ss",$sta,$CodeL);
    $statement->execute();

    header("location:dash.php");
  }



$reqIU="SELECT * FROM utilisateur WHERE CodeU=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$codeP);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();
if($rowIU['imageP']!=NULL)
{
  $src="../Samples/profilpic.php?UN=$USN";
}
else
{
	$src="../../Resourse/imgs/ProfileHolder.jpg";
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kapella Bootstrap Admin Dashboard Template</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../Resourse/requestdetails/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/requestdetails/vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../../Resourse/requestdetails/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../Resourse/requestdetails/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../Resourse/requestdetails/css/style.css">
  <!-- endinject -->


  <link rel="stylesheet" href="../../Resourse/requestdetails/css/styleSCROling.css">



  <link rel="shortcut icon" href="../../Resourse/requestdetails/images/favicon.png" />

</head>

<body>
<div class="container-scroller">
		<!-- partial:partials/_horizontal-navbar.html -->
		<div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container-fluid">
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
          
          <ul class="navbar-nav navbar-nav-left">
              <li class="nav-item ml-0 mr-5 d-lg-flex d-none">
                <a href="#" class="nav-link horizontal-nav-left-menu"><i class="mdi mdi-format-list-bulleted"></i></a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                  <i class="mdi mdi-bell mx-0"></i>
                  <span class="count bg-success">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                  <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-success">
                          <i class="mdi mdi-information mx-0"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal">Application Error</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                          Just now
                        </p>
                    </div>
                  </a>
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
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                  <i class="mdi mdi-email mx-0"></i>
                  <span class="count bg-primary">4</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                  <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="images/faces/face4.jpg" alt="image" class="profile-pic">
                    </div>
                    <div class="preview-item-content flex-grow">
                        <h6 class="preview-subject ellipsis font-weight-normal">David Grey
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                          The meeting is cancelled
                        </p>
                    </div>
                  </a>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="images/faces/face2.jpg" alt="image" class="profile-pic">
                    </div>
                    <div class="preview-item-content flex-grow">
                        <h6 class="preview-subject ellipsis font-weight-normal">Tim Cook
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                          New product launch
                        </p>
                    </div>
                  </a>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <img src="images/faces/face3.jpg" alt="image" class="profile-pic">
                    </div>
                    <div class="preview-item-content flex-grow">
                        <h6 class="preview-subject ellipsis font-weight-normal"> Johnson
                        </h6>
                        <p class="font-weight-light small-text text-muted mb-0">
                          Upcoming board meeting
                        </p>
                    </div>
                  </a>
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
      <nav class="bottom-navbar">
        <div class="container">
            <ul class="nav page-navigation">
              <li class="nav-item active">
                <a class="nav-link" href="dash.php">
                  <i class="mdi mdi-file-document-box menu-icon"></i>
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
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
                    <i class="mdi mdi-settings-box menu-icon"></i>
                    <span class="menu-title">Gestion</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="submenu">
                      <ul>
                          <li class="nav-item"><a class="nav-link" href="#">Comptes</a></li>
                          <li class="nav-item"><a class="nav-link" href="DemandeLoc.php">Location</a></li>
                      </ul>
                  </div>
              </li>
              <li class="nav-item">
                  <a href="statestique.php" class="nav-link">
                    <i class="mdi mdi-chart-areaspline menu-icon"></i>
                    <span class="menu-title">Statestique</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="../pages/tables/basic-table.html" class="nav-link">
                    <i class="mdi mdi-checkbox-multiple-marked menu-icon"></i>
                    <span class="menu-title">Demandes</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="pages/icons/mdi.html" class="nav-link">
                    <i class="mdi mdi-help-circle menu-icon"></i>
                    <span class="menu-title">Support</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="mdi mdi-finance menu-icon"></i>
                    <span class="menu-title">traffic</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
            </ul>
        </div>
      </nav>
    </div>
	
        <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            
           
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Information de logement</h4>
                 
				  <div class="grid-container">

  <div class="image">	
    <div class="w3-content w3-display-container" style="max-width:800px">
      
      <?=$images;?>
      <div class="w3-center w3-container w3-section w3-large w3-text-white w3-display-bottommiddle" style="width:100%">
        <div class="w3-left w3-hover-text-khaki" onclick="plusDivs(-1)">&#10094;</div>
        <div class="w3-right w3-hover-text-khaki" onclick="plusDivs(1)">&#10095;</div>
      </div>
    </div>
  </div>
  <div class="form"> <div class="forms-sample">

<div class="form-group row">
  <label for="exampleInputUsername2" class="col-sm-3 col-form-label">prix</label>
  <div class="col-sm-9">
	<input type="text" class="form-control" disabled id="exampleInputUsername2" value="<?=$prix;?>" placeholder="prix">
  </div>
</div>
<div class="form-group row">
  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Superficie</label>
  <div class="col-sm-9">
	<input type="text" class="form-control" disabled id="exampleInputMobile" value="<?=$sup;?>" placeholder="Superficie">
  </div>
</div>
<div class="form-group row">
  <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Description</label>
  <div class="col-sm-9">
  <textarea class="form-control" disabled id="exampleTextarea1" rows="4"><?=$description;?></textarea></div>
</div>
<div class="form-group row">
  <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">Adresse</label>
  <div class="col-sm-9">
	<input type="text" disabled class="form-control" id="exampleInputConfirmPassword2" value="<?=$adress;?>" placeholder="Adresse">
  </div>
</div>


</div>
<div class="form-check form-check-flat form-check-primary">
  <label class="consulter">
	Pice de légalité : <a href="" onclick="openpap()">Consulter</a>
  </label>
  <script>
           function openpap() { 
            window.open("genere_file.php?id=<?=$CodeL;?>", "_blank");
         }
  </script>
</div>
</div>

</div>
               
				</div>		
			  </div>	  
            </div>
		  </div>
		  <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Information de propriétaire</h4>
                 
				  <div class="grid-container2">
<div class="pic">		  <img src="<?=$src;?>"  alt="image" class="imagepage" style="width: 120px;border-radius: 10px;" >
</div>
  <div class="form2"> <div class="forms-sample">


<div class="form-group row">
  <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Nom</label>
  <div class="col-sm-9">
	<input type="text" disabled class="form-control" id="exampleInputEmail2" value="<?=$nomP;?>" placeholder="Nom">
  </div>
</div>
<div class="form-group row">
  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Prenom</label>
  <div class="col-sm-9">
	<input type="text" disabled class="form-control" id="exampleInputMobile" value="<?=$prenomP;?>" placeholder="Prenom">
  </div>
</div>
<div class="form-group row">
  <label for="exampleInputMobile" class="col-sm-3 col-form-label">CIN</label>
  <div class="col-sm-9">
	<input type="text" disabled class="form-control" id="exampleInputMobile" value="<?=$CIN;?>" placeholder="CIN">
  </div>
</div>
<div class="form-group row">
  <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Tel</label>
  <div class="col-sm-9">
  <input type="text" disabled class="form-control" id="exampleInputMobile" value="<?=$Tel;?>" placeholder="Email">
</div>


</div>
                  <form method="post" class="buttnsss">
  	                <button class="btn btn-primary mr-2" name="accept" >Accepter</button>
                  </form>
                  <br>
                  <form method="post">
                  <div class="form-group row">
                    <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Motife de refus</label>
                    <div class="col-sm-9">
                    <textarea class="form-control" name="motif" required id="exampleTextarea1" rows="4"></textarea></div>
                  </div>

                  <div class="buttnsss">
                    <button class="btn btn-light mr-2" name="refus">Refuser</button>
                  </div>
                  </form>
</div>

</div>
               
				</div>
		</div>
		
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="footer-wrap">
              <div class="w-100 clearfix">
                <span class="d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="https://www.templatewatch.com/" target="_blank">templatewatch</a>. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart-outline"></i></span>
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
  <script src="../../Resourse/requestdetails/vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../Resourse/requestdetails/js/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="../../Resourse/requestdetails/vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="../../Resourse/requestdetails/vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="../../Resourse/requestdetails/js/file-upload.js"></script>
  <script src="../../Resourse/requestdetails/js/typeahead.js"></script>
  <script src="../../Resourse/requestdetails/js/select2.js"></script>




  <!-- End custom js for this page-->



  <script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" w3-white", "");
  }
  x[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " w3-white";
}
</script>
</body>

</html>


