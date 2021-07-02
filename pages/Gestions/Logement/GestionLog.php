
<?php
session_start();

if( !isset($_SESSION['username']) || $_SESSION['type'] != "pro" )
{
  header("location:../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
$result="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$codeU=$_SESSION['usercode'];

$reqIU="SELECT * FROM proprietaire WHERE CodeP=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$codeU);
$statementIU->execute();
$resUI=$statementIU->get_result();
$rowUI=$resUI->fetch_assoc();
$nomPro=$rowUI['nom']." ".$rowUI['prenom'];



$reqL = "SELECT * from logement where CodeP=? ";
$statementL=$conn->prepare($reqL);
$statementL->bind_param("s",$codeU);
$statementL->execute();
$resL=$statementL->get_result();
while ($rowL = mysqli_fetch_array($resL)) 
{
  $nom = $rowL['nom'];
  $price=$rowL['prix'];
  $CodeL=$rowL['CodeL'];
  $result.='
  <div class="center-side">
    <div class="masonry-box post-media">
        <img src="genere_image.php?id='.$CodeL.'" alt="" class="img-fluid">
        <div class="shadoweffect">
            <div class="shadow-desc">
                <div class="blog-meta">
                <span class="bg-aqua"><a href="Modifierlog.php?idL='.$CodeL.'" title="">Modifier</a></span>
                    <h4><a title="">'.$nom.'</a></h4>
                    <small><a title="">Prix : '.$price.' dh</a></small>
                    <small><a title="">by '.$nomPro.'</a></small>
                </div><!-- end meta -->
            </div><!-- end shadow-desc -->
        </div><!-- end shadow -->
    </div><!-- end post-media -->
  </div><!-- end left-side -->
  ';

}



?>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion de logement</title>
    <link rel="stylesheet" type="text/css" href="../../../Resourse/CSS/semantic.min.css">
  <link rel="stylesheet" href="../../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../../Resourse/vendors/base/vendor.bundle.base.css">
 
  <link rel="stylesheet" href="../../../Resourse/ForUserPage/styleCardd.css">
  <link rel="stylesheet" href="../../../Resourse/ForUserPage/css/grid2.css">
  <link rel="stylesheet" href="../../../Resourse/ForUserPage/css/colors.css">
  <link rel="stylesheet" href="../../../Resourse/ForUserPage/css/responsive.css">

  <link rel="stylesheet" href="../../../Resourse/css2/styleUser.css">
  <link rel="shortcut icon" href="../../../Resourse/images/favicon.png" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="../../../Resourse/cssSm/font-awesome.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>
  <link rel="stylesheet" href="../../../Resourse/css3/chatbox.css">


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

                  <?=$ntMsg;?>

                </div>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link count-indicator "><i class="mdi mdi-message-reply-text"></i></a>
              </li>
              <li class="nav-item nav-search d-none d-lg-block ml-3">
                <form class="input-group" action="searshResult.php" methode="POST">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="search">
                        <i class="mdi mdi-magnify"></i>
                      </span>
                    </div>
                    <input type="text" name="rech" class="form-control" placeholder="Search a very wide input..." aria-label="search" aria-describedby="search">
                </form>
              </li>	
            </ul>
 
              
   
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand" href="dash.html"><img src="../../../Resourse/images/logo-1.png" alt="logo"/></a>
            </div>
            <ul class="navbar-nav navbar-nav-right">
               
              
             
                <li class="nav-item nav-profile dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <span class="nav-profile-name"></span>
                    <span class="online-status"></span>
               
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <a class="dropdown-item">
                        <i class="mdi mdi-account text-primary"></i>
                        Mon Compte
                      </a>
                      <a class="dropdown-item">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                      </a>
                  </div>
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
		<div class="container-fluid">
    <br>
    <nav aria-label="breadcrumb">
  <ol class="SectionName">
    <p class="breadcrumb-item active" aria-current="page">Gerer mes logement</p>

  </ol>
</nav>
<section class="section first-section">
            <div class="container-fluid">
                <div class="masonry-blog clearfix">
                    <?=$result; ?>
                </div><!-- end masonry -->
            </div>
        </section>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>

</main>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
<br>

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
    <script src="../../../Resourse/vendors/chart.js/Chart.min.js"></script>
    <script src="../../../Resourse/vendors/progressbar.js/progressbar.min.js"></script>
		<script src="../../../Resourse/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js"></script>
		<script src="../../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="../../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->
    <script src="../../../Resourse/js2/Card.js"></script>

      <!-- chat-box -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>

</body>
</html>