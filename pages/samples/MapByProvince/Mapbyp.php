

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kapella Bootstrap Admin Dashboard Template</title>
  
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
 

  <link rel="stylesheet" href="styleRe.css">
  <link rel="shortcut icon" href="favicon.png" />


  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>

  <link href="../../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="../../../Resourse/Allfooters/Style.css">

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
                  <a  id="noti_open" class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell mx-0"></i>
                    <span id="nbrnts" class="count bg-success">></span>
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
                   
                    </div>
                   

                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-email mx-0"></i>
                    <span class="count bg-primary">4</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>

                   
                    

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
                  <a class="navbar-brand" href="dash.html"><img src="../../../Resourse/images/logo-1.png" alt="logo"/></a>
              </div>
              <ul class="navbar-nav navbar-nav-right">
                
                
              
                  <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                      <span class="nav-profile-name"></span>
                      <span class="online-status"></span>
                    
                    </a>
                    <form method="post" class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                      <a href="../Gestions/prop/gestionProp.php" class="dropdown-item">
                        <i class="mdi mdi-account text-primary"></i>
                        Mon Compte
                      </a>
                      <a href="../Gestions/Logement/GestionLog.php" class="dropdown-item">
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
      <div class="main-panel">
				<div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
  <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Khemisset</a>
  <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">salé</a>
  <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">rabat</a>
  <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">sala el jadida</a>
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
                            <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <h4 class="card-title">Map</h4>
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