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

//visiteur
$thisyear = date("Y");

$Vis = array();
$Vis[1]=0; $Vis[2]=0; $Vis[3]=0; $Vis[4]=0;
$Vis[5]=0; $Vis[6]=0; $Vis[7]=0; $Vis[8]=0;
$Vis[9]=0;$Vis[10]=0;$Vis[11]=0;$Vis[12]=0;
$normaltype='normal';

$reqvis="SELECT COUNT(*) as sumres,MONTH(date) as mou FROM `utilisateur` where YEAR(date) = ?
and `type`= ? GROUP BY MONTH(date)";
$statementvis=$conn->prepare($reqvis);
$statementvis->bind_param("ss",$thisyear,$normaltype);
$statementvis->execute();
$resvis=$statementvis->get_result();
while ( $rowvis = mysqli_fetch_array($resvis) )
{
      $Vis[$rowvis['mou']] = $rowvis['sumres'];
}

//proprietaire
$Pro = array();
$Pro[1]=0; $Pro[2]=0; $Pro[3]=0; $Pro[4]=0;
$Pro[5]=0; $Pro[6]=0; $Pro[7]=0; $Pro[8]=0;
$Pro[9]=0;$Pro[10]=0;$Pro[11]=0;$Pro[12]=0;

$protype='pro';

$reqpro="SELECT COUNT(*) as sumres,MONTH(date) as mou FROM `utilisateur` where YEAR(date) = ?
and `type`= ? GROUP BY MONTH(date)";
$statementpro=$conn->prepare($reqpro);
$statementpro->bind_param("ss",$thisyear,$protype);
$statementpro->execute();
$respro=$statementpro->get_result();
while ( $rowpro = mysqli_fetch_array($respro) )
{
      $Pro[$rowpro['mou']] = $rowpro['sumres'];
}
//Logement
$Log = array();
$Log[1]=0; $Log[2]=0; $Log[3]=0; $Log[4]=0;
$Log[5]=0; $Log[6]=0; $Log[7]=0; $Log[8]=0;
$Log[9]=0;$Log[10]=0;$Log[11]=0;$Log[12]=0;

$req="SELECT COUNT(*) as sumres,MONTH(date) as mou FROM `logement` where YEAR(date) = ?
GROUP BY MONTH(date)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
      $Log[$row['mou']] = $row['sumres'];
}
//message
$Mes = array();
$Mes[1]=0; $Mes[2]=0; $Mes[3]=0; $Mes[4]=0;
$Mes[5]=0; $Mes[6]=0; $Mes[7]=0; $Mes[8]=0;
$Mes[9]=0;$Mes[10]=0;$Mes[11]=0;$Mes[12]=0;

$req="SELECT COUNT(*) as sumres,MONTH(datemsg) as mou FROM `messages` where YEAR(datemsg) = ?
GROUP BY MONTH(datemsg)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
      $Mes[$row['mou']] = $row['sumres'];
}
//Pack
$Pac = array();
$Pac[1]=0; $Pac[2]=0; $Pac[3]=0; $Pac[4]=0;
$Pac[5]=0; $Pac[6]=0; $Pac[7]=0; $Pac[8]=0;
$Pac[9]=0;$Pac[10]=0;$Pac[11]=0;$Pac[12]=0;

$req="SELECT COUNT(*) as sumres,MONTH(datein) as mou FROM `pack` where YEAR(datein) = ?
GROUP BY MONTH(datein)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
      $Pac[$row['mou']] = $row['sumres'];
}
//Pack
$Tra = array();
$Tra[1]=0; $Tra[2]=0; $Tra[3]=0; $Tra[4]=0;
$Tra[5]=0; $Tra[6]=0; $Tra[7]=0; $Tra[8]=0;
$Tra[9]=0;$Tra[10]=0;$Tra[11]=0;$Tra[12]=0;

$req="SELECT sum(`value`) as sumres,MONTH(date) as mou FROM `sts_trans` where YEAR(date) = ?
GROUP BY MONTH(date)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
      $Tra[$row['mou']] = $row['sumres'];
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
    <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
 

  
  <link rel="stylesheet" href="../../Resourse/css2/stylePro.css">
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
                        <img src="../images/faces/face4.jpg" alt="image" class="profile-pic">
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
                        <img src="../images/faces/face2.jpg" alt="image" class="profile-pic">
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
                  <a href="../pages/charts/chartjs.html" class="nav-link">
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
	
	

				
    <div class="main-panel">
				<div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des visteurs</h4>
                              <canvas id="areaChart1" width="682" height="340" class="chartjs-render-monitor" style="display: block; height: 227px; width: 455px;"></canvas>
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des Proprietaires</h4>
                              <canvas id="barChart1" style="display: block; height: 227px; width: 455px;" width="682" height="340" class="chartjs-render-monitor"></canvas>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des Logements</h4>
                              <canvas id="areaChart2" width="682" height="340" class="chartjs-render-monitor" style="display: block; height: 227px; width: 455px;"></canvas>
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des Messages</h4>
                              <canvas id="barChart2" style="display: block; height: 227px; width: 455px;" width="682" height="340" class="chartjs-render-monitor"></canvas>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des Packs</h4>
                              <canvas id="areaChart3" width="682" height="340" class="chartjs-render-monitor" style="display: block; height: 227px; width: 455px;"></canvas>
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                              <h4 class="card-title">Statestique des Transactions</h4>
                              <canvas id="barChart3" style="display: block; height: 227px; width: 455px;" width="682" height="340" class="chartjs-render-monitor"></canvas>
                              </div>
                          </div>
                        </div>
                    </div>
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
				<footer class="footer">
          <div class="footer-wrap">
              <div class="w-100 clearfix">
                <span class="d-block text-center text-sm-left d-sm-inline-block">Copyright © 2020 ESRENT. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"></span>
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
    <script src="../../Resourse/vendors/chart.js/Chart.min.js"></script>
    <script src="../../Resourse/vendors/progressbar.js/progressbar.min.js"></script>

		<script src="../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    
    <script>
    //////////////////////////////////////////////
    $(function() 
      {
        var data1 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: 'Proprietaires',
            data: [<?=$Pro[1] ;?>,<?=$Pro[2] ;?>,<?=$Pro[3] ;?>,<?=$Pro[4] ;?>, <?=$Pro[5] ;?>,
        <?=$Pro[6] ;?>, <?=$Pro[7] ;?>, <?=$Pro[8] ;?>, <?=$Pro[9] ;?>, <?=$Pro[10] ;?>,
        <?=$Pro[11] ;?>, <?=$Pro[12] ;?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            fill: false
          }]
        };
        var data2 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: 'Messages',
            data: [<?=$Mes[1] ;?>,<?=$Mes[2] ;?>,<?=$Mes[3] ;?>,<?=$Mes[4] ;?>, <?=$Mes[5] ;?>,
        <?=$Mes[6] ;?>, <?=$Mes[7] ;?>, <?=$Mes[8] ;?>, <?=$Mes[9] ;?>, <?=$Mes[10] ;?>,
        <?=$Mes[11] ;?>, <?=$Mes[12] ;?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            fill: false
          }]
        };
        var data3 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: 'Transaction',
            data: [<?=$Tra[1] ;?>,<?=$Tra[2] ;?>,<?=$Tra[3] ;?>,<?=$Tra[4] ;?>, <?=$Tra[5] ;?>,
        <?=$Tra[6] ;?>, <?=$Tra[7] ;?>, <?=$Tra[8] ;?>, <?=$Tra[9] ;?>, <?=$Tra[10] ;?>,
        <?=$Tra[11] ;?>, <?=$Tra[12] ;?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            fill: false
          }]
        };

        var options1 = {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        };
        var options2 = {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        };
        var options3 = {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        };

        ///////////////////////////////barChart1///////////////////////////////
        if ($("#barChart1").length) {
          var barChartCanvas = $("#barChart1").get(0).getContext("2d");
          var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: data1,
            options: options1
          });
        }
        ///////////////////////////////barChart2///////////////////////////////
        if ($("#barChart2").length) {
          var barChartCanvas = $("#barChart2").get(0).getContext("2d");
          var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: data2,
            options: options2
          });
        }
        ////////////////////////////////barChart3///////////////////////////////
        if ($("#barChart3").length) {
          var barChartCanvas = $("#barChart3").get(0).getContext("2d");
          var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: data3,
            options: options3
          });
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        var areaData1 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: 'Visiteur',
            data: [<?=$Vis[1] ;?>,<?=$Vis[2] ;?>,<?=$Vis[3] ;?>,<?=$Vis[4] ;?>, <?=$Vis[5] ;?>,
        <?=$Vis[6] ;?>, <?=$Vis[7] ;?>, <?=$Vis[8] ;?>, <?=$Vis[9] ;?>, <?=$Vis[10] ;?>,
        <?=$Vis[11] ;?>, <?=$Vis[12] ;?>],
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            pointBackgroundColor:[
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],
            pointBorderColor:[
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            fill: true, // 3: no fill
          }]
        };
        //areaoptions
        var areaOptions1 = {
          plugins: {
            filler: {
              propagate: true
            }
          }
        }
        var areaData2 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: ' Logements',
            data: [<?=$Log[1] ;?>,<?=$Log[2] ;?>,<?=$Log[3] ;?>,<?=$Log[4] ;?>, <?=$Log[5] ;?>,
        <?=$Log[6] ;?>, <?=$Log[7] ;?>, <?=$Log[8] ;?>, <?=$Log[9] ;?>, <?=$Log[10] ;?>,
        <?=$Log[11] ;?>, <?=$Log[12] ;?>],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            pointBackgroundColor:[
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],

            pointBorderColor:[
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            fill: true, // 3: no fill
          }]
        };
        //areaoptions
        var areaOptions2 = {
          plugins: {
            filler: {
              propagate: true
            }
          }
        }
        var areaData3 = {
          labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
          datasets: [{
            label: 'Packs',
            data: [<?=$Pac[1] ;?>,<?=$Pac[2] ;?>,<?=$Pac[3] ;?>,<?=$Pac[4] ;?>, <?=$Pac[5] ;?>,
        <?=$Pac[6] ;?>, <?=$Pac[7] ;?>, <?=$Pac[8] ;?>, <?=$Pac[9] ;?>, <?=$Pac[10] ;?>,
        <?=$Pac[11] ;?>, <?=$Pac[12] ;?>],
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            pointBackgroundColor:[
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255,99,132,0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],
            pointBorderColor:[
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1,
            fill: true, // 3: no fill
          }]
        };
        //areaoptions
        var areaOptions3 = {
          plugins: {
            filler: {
              propagate: true
            }
          }
        }
        //////////////////////////////areaChart1//////////////////////////////////
        if ($("#areaChart1").length) {
          var areaChartCanvas = $("#areaChart1").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaData1,
            options: areaOptions1
          });
        }
        //////////////////////////////areaChart2//////////////////////////////////
        if ($("#areaChart2").length) {
          var areaChartCanvas = $("#areaChart2").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaData2,
            options: areaOptions2
          });
        }
        //////////////////////////////areaChart3//////////////////////////////////
        if ($("#areaChart3").length) {
          var areaChartCanvas = $("#areaChart3").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaData3,
            options: areaOptions3
          });
        }
      });

    </script>



    <!-- End custom js for this page-->
</html>