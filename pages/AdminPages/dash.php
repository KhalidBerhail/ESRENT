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

// for real statestique
//SELECT * FROM `sts_visiteur` WHERE `date` LIKE "_____04___"
//SELECT COUNT(*),MONTH(date) FROM `sts_visiteur` GROUP BY YEAR(date),MONTH(date)
//pre-statestique
$nbr_vis=0; $nbr_pro=0; $nbr_logement=0; $nbr_message=0; $pack=0; $trans=0;
$thisyear = date("Y");
$thismount = date("m");
//visiteur
$normaltype="normal";
$req="SELECT COUNT(*) as sumres FROM `utilisateur` where MONTH(date) = ? and YEAR(date) = ? and type=?";
$statement=$conn->prepare($req);
$statement->bind_param("sss",$thismount,$thisyear,$normaltype);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$nbr_vis=$row['sumres'];
//proprietaire
$protype="pro";
$req="SELECT COUNT(*) as sumres FROM `utilisateur` where MONTH(date) = ? and YEAR(date) = ? and type=?";
$statement=$conn->prepare($req);
$statement->bind_param("sss",$thismount,$thisyear,$protype);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$nbr_pro=$row['sumres'];
//logement
$req="SELECT COUNT(*) as sumres FROM `logement` where MONTH(date) = ? and YEAR(date) = ?";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$thismount,$thisyear);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$nbr_logement=$row['sumres'];
//messages
$req="SELECT COUNT(*) as sumres FROM `messages` where MONTH(datemsg) = ? and YEAR(datemsg) = ?";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$thismount,$thisyear);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$nbr_message=$row['sumres'];
//pack
$req="SELECT COUNT(*) as sumres FROM `pack` where MONTH(datein) = ? and YEAR(datein) = ?";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$thismount,$thisyear);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$pack=$row['sumres'];
//transaction
$req="SELECT sum(`value`) as sumres FROM `sts_trans` where MONTH(date) = ? and YEAR(date) = ?";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$thismount,$thisyear);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
if($row['sumres'])
$trans=$row['sumres'];


$totpacks = 0;

//char packs
////////////// ultra
$UlVal = array();
$UlVal[1]=0; $UlVal[2]=0; $UlVal[3]=0; $UlVal[4]=0;
$UlVal[5]=0; $UlVal[6]=0; $UlVal[7]=0; $UlVal[8]=0;
$UlVal[9]=0;$UlVal[10]=0;$UlVal[11]=0;$UlVal[12]=0;
$req="SELECT COUNT(*) as sumres,MONTH(datein) as mou FROM `pack` where YEAR(datein) = ?
and `type`='ultra' GROUP BY MONTH(datein)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ($row = mysqli_fetch_array($res))
{
  $UlVal[$row['mou']] = $row['sumres'];
  $totpacks = $totpacks + $row['sumres'];
}
////////////// super
$SuVal = array();
$SuVal[1]=0; $SuVal[2]=0; $SuVal[3]=0; $SuVal[4]=0;
$SuVal[5]=0; $SuVal[6]=0; $SuVal[7]=0; $SuVal[8]=0;
$SuVal[9]=0;$SuVal[10]=0;$SuVal[11]=0;$SuVal[12]=0;
$req="SELECT COUNT(*) as sumres,MONTH(datein) as mou FROM `pack` where YEAR(datein) = ?
and `type`='super' GROUP BY MONTH(datein)";
$statement=$conn->prepare($req);
$statement->bind_param("s",$thisyear);
$statement->execute();
$res=$statement->get_result();
while ($row = mysqli_fetch_array($res))
{
  $SuVal[$row['mou']] = $row['sumres'];
  $totpacks = $totpacks + $row['sumres'];
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
				  	  <a href="../Gestions/Admin/gestionAdmin.php" class="dropdown-item">
                        <i class="mdi mdi-account text-primary"></i>
                        Mon Compte
                      </a>
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
                  <a href="DemandeLoc.php" class="nav-link">
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
						<div class="col-sm-6 mb-4 mb-xl-0">
							<div class="d-lg-flex align-items-center">
								<div>
									<h3 class="text-dark font-weight-bold mb-2">Hi, welcome back!</h3>
									<h6 class="font-weight-normal mb-2">Last login was 23 hours ago. View details</h6>
								</div>
								<div class="ml-lg-5 d-lg-flex d-none">
										<button type="button" class="btn bg-white btn-icon">
											<i class="mdi mdi-view-grid text-success"></i>
									</button>
										<button type="button" class="btn bg-white btn-icon ml-2">
											<i class="mdi mdi-format-list-bulleted font-weight-bold text-primary"></i>
										</button>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="d-flex align-items-center justify-content-md-end">
								<div class="pr-1 mb-3 mb-xl-0">
										
								</div>
								<div class="pr-1 mb-3 mb-xl-0">
										
								</div>
								<div class="pr-1 mb-3 mb-xl-0">
										
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-8 flex-column d-flex stretch-card">
							
							<div class="row">
								<div class="col-sm-12 grid-margin d-flex stretch-card">
									<div class="card">
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-success font-weight-bold"><?=$nbr_vis; ?></h2>
										<i class="mdi mdi-account-outline mdi-18px text-dark"></i>
									</div>
								</div>
								<canvas id="newClient" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">LES VISITEURS</div>
							</div>
						</div>
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-danger font-weight-bold"><?=$nbr_pro; ?></h2>
										<i class="mdi mdi-account-star mdi-18px text-dark"></i>
									</div>
								</div>
								<canvas id="allProducts" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">LES PROPRIETAIRE</div>
							</div>
						</div>
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-info font-weight-bold"><?=$nbr_logement; ?></h2>
										<i class="mdi mdi-home-modern mdi-18px text-dark"></i>
									</div>
								</div>
								<canvas id="invoices" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">LES LOGEMENTS</div>
							</div>
						</div>
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-warning font-weight-bold"><?=$nbr_message; ?></h2>
										<i class="mdi mdi-message-text-outline mdi-18px text-dark"></i>
									</div>
								</div>
								<canvas id="projects" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">LES MESSAGES</div>
							</div>
						</div>
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-secondary font-weight-bold"><?=$pack; ?></h2>
										<i class="mdi mdi-package-variant mdi-18px text-dark"></i>
									</div>
								</div>
								<canvas id="orderRecieved" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">LES PACK</div>
							</div>
						</div>
						<div class="col-lg-2 grid-margin stretch-card">
							<div class="card"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
								<div class="card-body pb-0">
									<div class="d-flex align-items-center justify-content-between">
										<h2 class="text-dark font-weight-bold"><?=$trans; ?> Dh</h2>
										<i class="mdi mdi-cash-multiple text-dark mdi-18px"></i>
									</div>
								</div>
								<canvas id="transactions" width="979" height="489" class="chartjs-render-monitor" style="display: block; height: 326px; width: 653px;"></canvas>
								<div class="line-chart-row-title">TRANSACTIONS</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 grid-margin grid-margin-md-0 stretch-card">
							<div class="card">
								<div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
									<div class="d-flex align-items-center justify-content-between">
										<h4 class="card-title">Pack Tracker</h4>
										<h4 class="text-success font-weight-bold">Packs<span class="text-dark ml-3"><?=$totpacks; ?></span></h4>
									</div>
									<canvas id="supportTracker" width="396" height="198" class="chartjs-render-monitor" style="display: block; height: 132px; width: 264px;"></canvas>
								</div>
							</div>
						</div>
						<div class="col-sm-6 grid-margin grid-margin-md-0 stretch-card">
							<div class="card">
								<div class="card-body">
									<div class="d-lg-flex align-items-center justify-content-between mb-4">
										<h4 class="card-title">Product Orders</h4>
										<p class="text-dark">+5.2% vs last 7 days</p>
									</div>
									<div class="product-order-wrap padding-reduced">
										<div id="productorder-gage" class="gauge productorder-gage"><svg height="100%" version="1.1" width="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.666667px; top: -0.0520833px;" viewBox="0 0 200 150" preserveAspectRatio="xMidYMid meet"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.4</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><filter id="inner-shadow-productorder-gage"><feOffset dx="0" dy="3"></feOffset><feGaussianBlur result="offset-blur" stdDeviation="5"></feGaussianBlur><feComposite operator="out" in="SourceGraphic" in2="offset-blur" result="inverse"></feComposite><feFlood flood-color="black" flood-opacity="0.2" result="color"></feFlood><feComposite operator="in" in="color" in2="inverse" result="shadow"></feComposite><feComposite operator="over" in="shadow" in2="SourceGraphic"></feComposite></filter></defs><path fill="#f0f0f0" stroke="none" d="M33.4375,120L25,120A75,75,0,0,1,175,120L166.5625,120A66.5625,66.5625,0,0,0,33.4375,120Z" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);" filter="url(#inner-shadow-productorder-gage)"></path><path fill="#fcd53b" stroke="none" d="M33.4375,120L25,120A75,75,0,0,1,133.83918133313838,53.067871640721165L130.0322734331603,60.59773608114003A66.5625,66.5625,0,0,0,33.4375,120Z" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);" filter="url(#inner-shadow-productorder-gage)"></path><text x="100" y="23.4375" text-anchor="middle" font-family="sans-serif" font-size="15px" stroke="none" fill="#999999" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 15px; font-weight: bold; fill-opacity: 1;" font-weight="bold" fill-opacity="1"><tspan dy="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></tspan></text><text x="100" y="117.64705882352942" text-anchor="middle" font-family="Arial" font-size="23px" stroke="none" fill="#001737" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 23px; font-weight: bold; fill-opacity: 1;" font-weight="bold" fill-opacity="1"><tspan dy="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">3245K</tspan></text><text x="100" y="134.18552036199097" text-anchor="middle" font-family="Arial" font-size="10px" stroke="none" fill="#001737" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 10px; font-weight: normal; fill-opacity: 1;" font-weight="normal" fill-opacity="1"><tspan dy="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">You have done 57.6% more ordes today</tspan></text><text x="29.21875" y="134.18552036199097" text-anchor="middle" font-family="Arial" font-size="10px" stroke="none" fill="#001737" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 10px; font-weight: normal; fill-opacity: 0;" font-weight="normal" fill-opacity="0"><tspan dy="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan></text><text x="170.78125" y="134.18552036199097" text-anchor="middle" font-family="Arial" font-size="10px" stroke="none" fill="#001737" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 10px; font-weight: normal; fill-opacity: 0;" font-weight="normal" fill-opacity="0"><tspan dy="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">5000</tspan></text></svg><div class="product-order"><div class="icon-inside-circle"><i class="mdi mdi-basket"></i></div></div></div>
									</div>
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
		<script src="../../Resourse/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js"></script>
		<script src="../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    
    <script>
    //////////////////////////////////////////////
      var newClientData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [35, 37, 34, 36, 32],
				backgroundColor: [
						'#f7f7f7',
				],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			},],
		};
		var newClientOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
				point: {
					radius: 0
				},		
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
		};
		if ($("#newClient").length) {
			var lineChartCanvas = $("#newClient").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: newClientData,
				options: newClientOptions
			});
    }
    //////////////////////////////////////////////
		var allProductsData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [37, 36, 37, 35, 36],
				backgroundColor: [
						'#f7f7f7',
				],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			}, ],
		};
		var allProductsOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
				point: {
					radius: 0
				},
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
	
		};
		if ($("#allProducts").length) {
			var lineChartCanvas = $("#allProducts").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: allProductsData,
				options: allProductsOptions
			});
		}

    //////////////////////////////////////////////
    var invoicesData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [35, 37, 34, 36, 32],
				backgroundColor: [
						'#f7f7f7',
				],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			}, ],
		};
		var invoicesOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
					point: {
						radius: 0
					},
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
	
		};
		if ($("#invoices").length) {
			var lineChartCanvas = $("#invoices").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: invoicesData,
				options: invoicesOptions
			});
		}

    
    //////////////////////////////////////////////
		var projectsData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [38, 39, 37, 40, 36],
					backgroundColor: [
							'#f7f7f7',
					],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			}, ],
		};
		var projectsOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
				point: {
					radius: 0
				},
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}					
		};
		if ($("#projects").length) {
			var lineChartCanvas = $("#projects").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: projectsData,
				options: projectsOptions
			});
		}

    //////////////////////////////////////////////
		var orderRecievedData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [35, 37, 34, 36, 32],
				backgroundColor: [
						'#f7f7f7',
				],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			}, ],
		};
		var orderRecievedOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
				point: {
					radius: 0
				},
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
	
		};
		if ($("#orderRecieved").length) {
			var lineChartCanvas = $("#orderRecieved").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: orderRecievedData,
				options: orderRecievedOptions
			});
		}

    //////////////////////////////////////////////
		var transactionsData = {
			labels: ["Jan", "Feb", "Mar", "Apr", "May"],
			datasets: [{
				label: 'Margin',
				data: [38, 35, 36, 38, 34],
				backgroundColor: [
						'#f7f7f7',
				],
				borderColor: [
						'#dcdcdc'
				],
				borderWidth: 2,
				fill: true,
			}, ],
		};
		var transactionsOptions = {
			scales: {
				yAxes: [{
					display: false,
				}],
				xAxes: [{
					display: false,
				}],
			},
			legend: {
				display: false,
			},
			elements: {
				point: {
					radius: 0
				},
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
		};
		if ($("#transactions").length) {
			var lineChartCanvas = $("#transactions").get(0).getContext("2d");
			var saleschart = new Chart(lineChartCanvas, {
				type: 'line',
				data: transactionsData,
				options: transactionsOptions
			});
		}

    //////////////////////////////////////////////

		var supportTrackerData = {
			labels: [ "janv", "fevr", "mars", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", ],
			datasets: [{
				label: 'Super',
        data: [<?=$UlVal[1] ;?>,<?=$UlVal[2] ;?>,<?=$UlVal[3] ;?>,<?=$UlVal[4] ;?>, <?=$UlVal[5] ;?>,
        <?=$UlVal[6] ;?>, <?=$UlVal[7] ;?>, <?=$UlVal[8] ;?>, <?=$UlVal[9] ;?>, <?=$UlVal[10] ;?>,
        <?=$UlVal[11] ;?>, <?=$UlVal[12] ;?>],
				backgroundColor: '#464dee',
				borderColor: '#464dee',//d8d8d8
				borderWidth: 1,
				fill: false
      },
			{
					label: 'Ultra',
					data: [<?=$SuVal[1] ;?>,<?=$SuVal[2] ;?>,<?=$SuVal[3] ;?>,<?=$SuVal[4] ;?>, <?=$SuVal[5] ;?>,
        <?=$SuVal[6] ;?>, <?=$SuVal[7] ;?>, <?=$SuVal[8] ;?>, <?=$SuVal[9] ;?>, <?=$SuVal[10] ;?>,
        <?=$SuVal[11] ;?>, <?=$SuVal[12] ;?>],					
					backgroundColor: '#d8d8d8',
					borderColor: '#d8d8d8',
					borderWidth: 1,
					fill: false
			}
			]
		};
		var supportTrackerOptions = {
			scales: {
				xAxes: [{
				//stacked: true,
				barPercentage: 0.6,
				position: 'bottom',
				display: true,
				gridLines: {
					display: false,
					drawBorder: false,
				},
				ticks: {
					display: true, //this will remove only the label
					stepSize: 300,
				}
				}],
				yAxes: [{
					//stacked: true,
					display: true,
					gridLines: {
						drawBorder: false,
						display: true,
						color: "#f0f3f6",
						borderDash: [8, 4],
					},
					ticks: {
						beginAtZero: true,
						callback: function(value, index, values) {
						return value;//return '$' + value;
						}
					},
				}]
			},
			legend: {
				display: false
			},
			legendCallback: function(chart) {
				var text = [];
				text.push('<ul class="' + chart.id + '-legend">');
				for (var i = 0; i < chart.data.datasets.length; i++) {
					text.push('<li><span class="legend-box" style="background:' + chart.data.datasets[i].backgroundColor[i] + ';"></span><span class="legend-label text-dark">');
					if (chart.data.datasets[i].label) {
							text.push(chart.data.datasets[i].label);
					}
					text.push('</span></li>');
				}
				text.push('</ul>');
				return text.join("");
			},
			tooltips: {
				backgroundColor: 'rgba(0, 0, 0, 1)',
			},
			plugins: {
				datalabels: {
					display: false,
					align: 'center',
					anchor: 'center'
				}
			}				
		};
		if ($("#supportTracker").length) {
			var barChartCanvas = $("#supportTracker").get(0).getContext("2d");
			// This will get the first returned node in the jQuery collection.
			var barChart = new Chart(barChartCanvas, {
				type: 'bar',
				data: supportTrackerData,
				options: supportTrackerOptions
			});
			document.getElementById('support-tracker-legend').innerHTML = barChart.generateLegend();
		}





    </script>



    <!-- End custom js for this page-->
</html>