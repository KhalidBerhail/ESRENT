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
  $CodeL="";
  $titre='';
  $desc='';
  $nbr_equi='';
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

///////////////remplicage des equipment///////////////
$equi="";
$req = "SELECT * FROM equipement";
$statement=$conn->prepare($req);
$statement->execute();
$res=$statement->get_result();
while ($row = mysqli_fetch_array($res)) 
{
	$equi .= 
"	<div class='form-check'>
	<label class='form-check-label'>
	<input type='checkbox' name='check_list[]' value=".$row['CodeE']." class='form-check-input' >
	".$row['nom']."
	</label>
	</div>
";
}
//////////////////////////////////////////////////////


////////////////////////Ajout/////////////////////////

$img=array();

if(isset($_POST['EnrFrm']))
{
	$region=$_POST['Select1'];
	$province=$_POST['Select2'];
	$AccType=$_POST['rad1'];
	$colloc=$_POST['radColloc'];
	$LogeType=$_POST['logetype'];
	$PdfFile=file_get_contents($_FILES["pdf"]["tmp_name"]);
	$i=$_POST['i_var'];
	$count=0;
	$filename="";
	for ($v=0 ; $v < $i ; $v++) 
	 {
	   $filename=$_FILES[$v]['name'];
	   if($filename!="")
	    {
	      $img[$count]=file_get_contents($_FILES[$v]["tmp_name"]);
	      $count = $count + 1;
	    }
	   $filename="";
	 }

	$nomL=$_POST['nomL'];
	$Desc=$_POST['Desc'];
	$nbrloc=$_POST['nbrloc'];
	$adresseL=$_POST['AdrLo'];
	$prix=$_POST['prixL'];
	$sprfc=$_POST['sprfc'];
	$reglement=$_POST['Reg'];
	$pour_etu=$_POST['radEtu'];
	$etab=$_POST['etab'];

	$checkedItems=$_POST['check_list'];
    $nbr_orEqui=sizeof($checkedItems);




			$Accval=null;
			$CodeU=null;


			if($AccType=="New")
			{
					$nom=$_POST['nomP'];
					$prenom=$_POST['PrenomP'];
					$CIN=$_POST['CIN'];
					$Tel=$_POST['Tel'];
					$AdressP=$_POST['Adr'];
					$Email=$_POST['Email'];

					$reqC = "SELECT * from proprietaire where CIN=? ";
					$statementC=$conn->prepare($reqC);
					$statementC->bind_param("s",$CIN);
					$statementC->execute();
					$resC=$statementC->get_result();
					$reqT = "SELECT * from proprietaire where Tel=?";
					$statementT=$conn->prepare($reqT);
					$statementT->bind_param("s",$Tel);
					$statementT->execute();
					$resT=$statementT->get_result();
					$reqE = "SELECT * from utilisateur where Email=?";
					$statementE=$conn->prepare($reqE);
					$statementE->bind_param("s",$Email);
					$statementE->execute();
					$resE=$statementE->get_result();


					if ($resC->num_rows==0 && $resT->num_rows==0 && $resE->num_rows==0)
					 {

						//creation utilisateur , select Code , creation Proprietaire

				          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				          $pa = '';
				          for ($j = 0; $j < 8; $j++) {
				              $pa = $pa.$characters[rand(0, strlen($characters))];
				          }
						  $pa=sha1($pa);
						  $datenow = new DateTime(date('Y-m-d'));
						  $datetoreq2 = $datenow->format('Y-m-d');
							$type="pro";
					        $reqI = "INSERT INTO `utilisateur`(`username`, `email`, `pass`, `type`,`date`) VALUES (?,?,?,?,?)";
					        $statementI=$conn->prepare($reqI);
					        $statementI->bind_param("sssss",$CIN,$Email,$pa,$type,$datetoreq2);
					        $statementI->execute();

							$reqI = "SELECT CodeU FROM utilisateur where username=? ";
					        $statementI=$conn->prepare($reqI);
					        $statementI->bind_param("s",$CIN);
					        $statementI->execute();
						    $resI=$statementI->get_result();
						    $rowI=$resI->fetch_assoc();
						    $CodeU=$rowI['CodeU'];

					        $reqP = "INSERT INTO `proprietaire`(`CodeP`, `CIN`, `adress`, `nom`, `prenom`, `tel`) VALUES (?,?,?,?,?,?)";
					        $statementP=$conn->prepare($reqP);
					        $statementP->bind_param("isssss",$CodeU,$CIN,$AdressP,$nom,$prenom,$Tel);
					        $statementP->execute();

					        $Accval="Ok";
					}
					else if($resC->num_rows!=0 && $resT->num_rows!=0)
					{
					   $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>CIN et numero de telephone existent déjà:</strong> entrez des nouvelles valeures.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
					}
					else if($resT->num_rows!=0 && $resE->num_rows!=0)
					{
						$alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>Email et numero de telephone existent déjà:</strong> entrez des nouvelles valeures.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
					}
					else if($resE->num_rows!=0 && $resC->num_rows!=0)
					{
						$alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>CIN et Email existent déjà :</strong> entrez des nouvelles valeures.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
					}
					else if($resC->num_rows!=0 && $resT->num_rows!=0 && $resE->num_rows!=0)
					{
					   $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>CIN,Email et numero de telephone existent déjà :</strong> entrez des nouvelles valeures.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
					}
					else if ($resC->num_rows!=0)
					{
					  $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			          <strong>CIN existe déjà :</strong> entrez une nouvelle valeure.
			          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			          </button>
			          </div>';
					}
					else if ($resT->num_rows!=0)
					{
					  $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			          <strong>Numero de Tele existe déjà:</strong> entrez une nouvelle valeure.
			          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			          </button>
			          </div>';
					}
					else if ($resE->num_rows!=0)
					{
					   $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>Email existe déjà :</strong> entrez une nouvelle valeure.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
					}
			}
			else if($AccType="EXST")
			{

					$Username=$_POST['Username'];

					$reqU = "SELECT * from utilisateur where Username=? ";
					$statementU=$conn->prepare($reqU);
					$statementU->bind_param("s",$Username);
					$statementU->execute();
					$resU=$statementU->get_result();
					$rowU=$resU->fetch_assoc();

					if ($resU->num_rows==1)
					{
						$Utype=$rowU['type'];
						if($Utype=="normal")
						{
						$CodeU=$rowU['CodeU'];
						$req = "INSERT INTO `proprietaire`(`CodeP`) values() ";
						$statement=$conn->prepare($req);
						$statement->bind_param("i",$CodeU);
						$statement->execute();
						$Accval="Ok";				
						}
						else if ($Utype=="pro"){
						$Accval="Ok";
						$CodeU=$rowU['CodeU'];
						}else
						{
						$alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>ce username appartien à un admin :</strong> tapez un autre username.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
						}
					}else if($resU->num_rows!=1)
						{
						$alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
			           <strong>Username non existant :</strong> entrez un unsername valide.
			           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			           <span aria-hidden="true">&times;</span>
			           </button>
			           </div>';
						}
			}

			if ($Accval=="Ok" && $CodeU!=null)
			{
				$datenow = new DateTime(date('Y-m-d'));
				$datetoreq = $datenow->format('Y-m-d');
				//creation logement [creation Studio || Apparetement()],creation images,creation file
				$Forseatch=metaphone($nomL).' '.metaphone($Desc).' '.metaphone($adresseL);
				$req = "INSERT INTO `logement`(`CodeP`, `nom`, `adress`, `description`, `reglement`,`prix`,`superficie`,`collocation`,`pour_etudiant`,`etabe_proche`,`SL_adr_nom`, `type`, `status`, `date`,`region`,`province-prefecture`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'valide',?,?,?)";
			    $statement=$conn->prepare($req);
			    $statement->bind_param("issssdissssssss",$CodeU,$nomL,$adresseL,$Desc,$reglement,$prix,$sprfc,$colloc,$pour_etu,$etab,$Forseatch,$LogeType,$datetoreq,$region,$province);
			    $statement->execute();
				$reqI = "SELECT CodeL FROM logement where nom=? ";
			    $statementI=$conn->prepare($reqI);
			    $statementI->bind_param("s",$nomL);
			    $statementI->execute();
			    $resI=$statementI->get_result();
			    $rowI=$resI->fetch_assoc();
			    $CodeL=$rowI['CodeL'];

			    //files et images
					foreach ($img as $data) {
					$req = "INSERT INTO `image`(`CodeL`, `image`) values(?,?)";
					$statement=$conn->prepare($req);
					$statement->bind_param("is",$CodeL,$data);
					$statement->execute();
					}
					$req = "INSERT INTO `files`(`CodeL`, `file`) values(?,?)";
					$statement=$conn->prepare($req);
					$statement->bind_param("is",$CodeL,$PdfFile);
					$statement->execute();			    	
			    
				//insertion apartement ou studio
			    if($LogeType=="Appartement")
			    {
					$nbr_piece=$_POST['nbrP'];

					$req = "INSERT INTO `appartement`(`Codeapp`,`nbrC`, `nbrP`) VALUES (?,?,?)";
			        $statement=$conn->prepare($req);
			        $statement->bind_param("iii",$CodeL,$nbr_piece,$nbrloc);
			        $statement->execute();

			    }else if($LogeType=="Studio")
				{
					$req = "INSERT INTO `studio`(`CodeS`, `nbrP`) VALUES (?,?)";
			        $statement=$conn->prepare($req);
			        $statement->bind_param("ii",$CodeL,$nbrloc);
			        $statement->execute();							
				}
				//creation equipement
				if($nbr_orEqui>0)
				
				{
					foreach ($checkedItems as $CodeEqu) {
					$req = "INSERT INTO `eqlo`(`CodeE`, `CodeL`) VALUES (?,?)";
			        $statement=$conn->prepare($req);
			        $statement->bind_param("si",$CodeEqu,$CodeL);
			        $statement->execute();													
				    }
				}

			   $alert='<div class="alert alert-success alert-dismissible fade show" role="alert">
			   <strong>Succes:</strong> Logement ajouté avec succes.
			   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			   <span aria-hidden="true">&times;</span>
			   </button>
			   </div>';

			    $titres = $_POST['titre'];
				$descs = $_POST['desc'];

				$nbr_equi=sizeof($titres);

				$iT=0;
			if($nbr_equi>0)
			  {
			   for($iT=0;$iT<$nbr_equi;$iT++)
                {
                   $titre=$titres[$iT];
				   $desc=$descs[$iT];
				   if($titre!='')
                   {
					$rT="INSERT INTO `autre_equi`(`CodeL`,`titre`,`description`) VALUES (?,?,?)";
                    $sT=$conn->prepare($rT);
                    $sT->bind_param("iss",$CodeL,$titre,$desc);
				    $sT->execute();
				   }
                }
			  }
			
			}

}
$province_options="<option selected disabled>choisissez une région d'abbord</option>";
$region_options="<option selected disabled>Choisissez votre région</option>";
$RSK="";
$CS="";
$MS="";
$TTA="";
$region_codes=array();
$reqRO = "SELECT * FROM regions ";
$statementRO=$conn->prepare($reqRO);
$statementRO->execute();
$resRO=$statementRO->get_result();
while ( ($rowRO = mysqli_fetch_array($resRO))) 
{
  
  //$region_options.="<option value='".$rowRO['Nom_Reg']."'>".$rowRO['Nom_Reg']."</option>";
  $region_options.='<option value="'.$rowRO['Nom_Reg'].'">'.$rowRO['Nom_Reg'].'</option>';
  array_push($region_codes,array($rowRO['id_Reg'],$rowRO['Nom_Reg']));
}

for($i=0;$i<sizeof($region_codes);$i++)
{
  $id_Reg=$region_codes[$i][0];
  $Nom_Reg=$region_codes[$i][1];
  $reqRO = "SELECT * FROM provinces where id_Reg='$id_Reg'";
  $statementRO=$conn->prepare($reqRO);
  $statementRO->execute();
  $resRO=$statementRO->get_result();
  while ( ($rowRO = mysqli_fetch_array($resRO))) 
   {

    
      if($Nom_Reg=="Rabat-Salé-Kénitra")
       {
        $RSK.="<option value='".$rowRO['Nom_Pro']."'>".$rowRO['Nom_Pro']."</option>";
       }
      else if($Nom_Reg=="Casablanca-Settat")
       {
        $CS.="<option value='".$rowRO['Nom_Pro']."'>".$rowRO['Nom_Pro']."</option>";
       }  
      else if($Nom_Reg=="Marrakech-Safi")
       {
        $MS.="<option value='".$rowRO['Nom_Pro']."'>".$rowRO['Nom_Pro']."</option>";
       }  
      else if($Nom_Reg=="Tanger-Tétouan-Al Hoceïma")
       {
        
        $TTA.="<option value='".$rowRO['Nom_Pro']."'>".$rowRO['Nom_Pro']."</option>";
       } 
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
    <!-- base:css -->
    <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
	 
	<link rel="stylesheet" type="text/css" href="../../Resourse/CSS/semantic.min.css">
    <link href="../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../Resourse/css2/style.css">
    <!-- endinject -->
	<link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
	
	<script src="../../Resourse/js2/pdf.js"></script>
   <script src="../../Resourse/js2/pdf.worker.js"></script>



  </head>
  <body>
    <div class="container-scroller">
		
		<!-- partial:partials/_horizontal-navbar.html -->
    <div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container-fluid">
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
          
             
              
   
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="dash.html"><img src="../../Resourse/images/logo.svg" alt="logo"/></a>
                <a class="navbar-brand brand-logo-mini" href="dash.html"><img src="../../Resourse/images/logo-mini.svg" alt="logo"/></a>
            </div>
            <ul class="navbar-nav navbar-nav-right">             
                <li class="nav-item nav-profile dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <span class="nav-profile-name"><?=$USN?></span>
                    <span class="online-status"></span>
                       <?=$ProfileP?>
                  </a>
                  <form method='post' class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
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
              <li class="nav-item">
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
                          <li class="nav-item"><a class="nav-link" href="Addform.php">Comptes</a></li>
                          <li class="nav-item"><a class="nav-link" href="#">Location</a></li>
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
	
	<!-- partial -->
<?=$alert;?>
	<br>
	<br>
     <!--Accordion -->
<form  method="POST" enctype="multipart/form-data" >			
	<div class="accordion"><i class="fas fa-info-circle"></i>  INFORMATION PROPRITAIRE</div>
	<div class="panel">
				<div class="card-body form-brdr">
						<div class="radio">
						<div class="rowR">
                           <div class="col-6">
							   <div class="form-check rad1">
								<label class="form-check-label">
								<input type="radio" onclick="showform(0)" name="rad1" value="New" class="form-check-input" checked>
								Nouveau
								</label>
							   </div>
						   </div>
						
                           <div class="col-6">	
							   <div class="form-check rad2">
								  <label class="form-check-label">
								  <input type="radio" onclick="showform(1)" name="rad1" value="EXST" class="form-check-input" >
								   Existe
								  </label>
								</div>
					       </div>
                        </div>
						</div>

						<div class="forms-sample">

						<div id="formNew">

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputUsername2" class="col-sm-3 col-form-label"><i class="fas fa-user"></i>  Nom</label>
								<input type="text" class="form-control" name="nomP" id="exampleInputUsername2"  placeholder="Nom">
							</div>
							</div>
						
							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputEmail2" class="col-sm-3 col-form-label"> <i class="fas fa-user"></i>  Prenom</label>
								<input type="text" class="form-control" name="PrenomP" id="exampleInputEmail2"  placeholder="Prenom">
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputMobile" class="col-sm-3 col-form-label"><i class="fas fa-fingerprint"></i>  CIN</label>

								<input  type="text" class="form-control" name="CIN"  id="exampleInputMobile" placeholder="CIN">
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-phone"></i>  Tel</label>

								<input type="text" class="form-control" name="Tel"  id="exampleInputPassword2" placeholder="Tel">
							</div>
							</div>

							<div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-map-marker-alt"></i>  Adresse</label>

								<input type="text" class="form-control"  name="Adr" id="exampleInputPassword2" placeholder="Adresse">
								</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-envelope"></i>  Email</label>

								<input type="email" class="form-control" name="Email" id="exampleInputPassword2" placeholder="Email" required>
							</div>
							</div>

						</div>
						<div id="formExst" style="display:none;" >																																											
							<div class="form-group row" >
								<div class="col-sm-9">
								<label for="exampleInputUsername2" class="col-sm-3 col-form-label"><i class="fas fa-user"></i>  Username</label>
								<input type="text" class="form-control" name="Username"  id="exampleInputUsername2" placeholder="Username">
								</div>
							</div>
						</div>

					</div>
						</div>
					
	</div>
	
	<div class="accordion"><i class="fas fa-info-circle"></i>  INFORMATIONS DE LOGEMENT</div>
	<div class="panel">
		<div class="card-body">
		<div class="radio">
						<div class="rowR">
                           <div class="col-6">
						   		<div class="form-check rad1">
									<label class="form-check-label">
									<input type="radio" onclick="showPiece(1)" name="logetype" value="Studio" class="form-check-input">
									Studio
									</label>
								</div>
						   </div>
						
				
                           <div class="col-6">	
						   		<div class="form-check rad2">
									<label class="form-check-label">
									<input type="radio" onclick="showPiece(0)" name="logetype" value="Appartement" class="form-check-input" checked>
									Appartement
									</label>
								</div>
                        	</div>
						</div>

						<div class="forms-sample">
							
							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputEmail2" class="col-sm-3 col-form-label"><i class="fas fa-user"></i>  Nom</label><br>
								<input type="text" class="form-control" name="nomL" id="exampleInputEmail2"  placeholder="Nom">
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleTextarea1" class="col-sm-3 col-form-label"><i class="fas fa-comment"></i>  Description</label><br>
								<textarea class="form-control" name="Desc"  id="exampleTextarea1" rows="4"></textarea>
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputEmail2" class="col-sm-3 col-form-label"><i class="fas fa-tag"></i> Prix</label><br>
								<input type="text" class="form-control" name="prixL"  id="exampleInputEmail2" placeholder="Prix">
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleInputEmail2" class="col-sm-3 col-form-label"><i class="fas fa-user"></i>  Superficie</label><br>
								<input type="text" class="form-control" name="sprfc"  id="exampleInputEmail2" placeholder="sprfc">
							</div>
							</div>

							<div class="form-group row">
							<div class="col-sm-9">
							<label for="exampleTextarea1" class="col-sm-3 col-form-label"><i class="fas fa-images"></i>  Importer des images</label><br>

							<br>
								<div class="container">
									<div class="row">
										<div class="col-sm-2 imgUp">
											<div class="imagePreview"></div>
											<label class="btn btn-primary">
												Upload<input type="file" name="0" class="uploadFile img"  style="width: 0px;height: 0px;overflow: hidden;">
											</label>
											</div><!-- col-2 -->
												<i class="fa fa-plus imgAdd"></i>
											</div><!-- row -->
										</div><!-- container -->
									</div>
								</div>
							<div class="form-group row">
								<div class="col-sm-9">
									<label for="exampleInputPassword2" class="col-sm-3 col-form-label">Equipements </label>
									<div class="dropdown">
										<button class="dropbtn form-control btn btn-default btn-sm dropdown-toggle ">Dropdown</button>
										<div class="dropdown-content force-scroll">
											<div class="radioEq">
												<?=$equi ;?>
												<div class='form-check'>
													<label class='form-check-label'>
													<a data-toggle="modal" id='openEqM' data-target="#exampleModal"><i class="far fa-plus-square"> Autre équipements</i></a>
													</label>
												</div>
											</div>	
										</div>
									</div> 
								</div>
							</div> 
							<div class="form-group row" >
						     	
									<div class="col-sm-9">
									    <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Collocation</label>
									    <div class="radioC">
						                    <div class="rowR">
                                                <div class="col-6 rtyy">
							                        <div class="form-check">
								                        <label class="form-check-label rtyy">
								                           <input type="radio"  name="radColloc" value="non" class="form-check-input" checked>
							                             	Non
							                        	</label>
							                        </div>
						                        </div>
						
                                                <div class="col-6 rtyy">	
							                        <div class="form-check">
								                        <label class="form-check-label rtyy">
								                           <input type="radio"  name="radColloc" value="oui" class="form-check-input" >
								                           Oui
								                        </label>
							                     	</div>
					                            </div>
                                            </div>
						                </div>
									</div>
							</div>
							<div class="form-group row" >
						     	
									<div class="col-sm-9">
									    <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Logemment pour étudiants</label>
									    <div class="radioE">
						                    <div class="rowR">
                                                <div class="col-6 rtyy2">
							                        <div class="form-check">
								                        <label class="form-check-label rtyy2">
								                           <input type="radio" id="hideI"  name="radEtu" value="non" class="form-check-input" checked>
							                             	Non
														</label>
														
														
							                        </div>
						                        </div>
						
                                                <div class="col-6 rtyy3">	
							                        <div class="form-check">
								                        <label class="form-check-label rtyy3">
								                           <input type="radio" id="showI" name="radEtu" value="oui" class="form-check-input" >
								                           Oui
								                        </label>
							                     	</div>
					                            </div>
                                            </div>
						                </div>
									</div>
							</div>
							<div id='dv_etab' class="form-group row" >
									<div class="col-sm-9">
									<label for="exampleInputPassword2" class="col-sm-3 col-form-label">Proche de quel établisement?</label>
								    	<input type="text" class="form-control" name="etab"  id="etab_proche " placeholder="établisement ">
									</div>
								</div>
							<div id="PieceInput">
								<div class="form-group row" >
									<div class="col-sm-9">
									<label for="exampleInputPassword2" class="col-sm-3 col-form-label">Nombre de piece</label>
										<input type="number" class="form-control" name="nbrP"  id="nbr_piece" placeholder="nbr_piece">
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label">Nombre de locataire </label>
								<input type="number" class="form-control" name="nbrloc" id="nbr_locataire " placeholder="nbr_locataire ">
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-map-marker-alt"></i>  Adresse </label>
									<input type="text" class="form-control" name="AdrLo" id="adresse "  placeholder="adresse ">
								</div>
							</div>


                            <div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-map-marker-alt"></i>  Région </label>
							     	<select class="form-control" name="Select1" id="Select_Region">
                                       <?=$region_options?>
                                    </select>
								</div>
							</div>


							<div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-map-marker-alt"></i>  Province/Prefecture </label>
								 <select class="form-control"  name="Select2" id="Select_Province">
                                    <?=$province_options?>
                                 </select>
								</div>
							</div>


							<div class="form-group row">
								<div class="col-sm-9">
								<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-file-prescription"></i>  Règlement </label>

									<input type="text" class="form-control" name="Reg"  id="nbr_locataire " placeholder="règlement ">
								</div>
							</div>
									
						</div>
					</div>
	</div>
</div>	
	<div class="accordion"><i class="fas fa-file-alt"></i>  PIECE DE LEGALITE</div>
	<div class="panel">
		<div class="card-body">
			<div class="forms-sample">
				<div class="form-group row">
				
					<div id="preview-container">
					<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-file-upload"></i>  Ajouter un document ( Certificat de propriété )</label>
						<div id="upload-dialog"><i class="fas fa-upload"></i>  Choose PDF</div>
						<input type="file" id="pdf-file" name="pdf" accept="application/pdf" required />
						<div id="pdf-loader">Loading Preview ..</div>
						<canvas id="pdf-preview" width="150"></canvas>
						<span id="pdf-name"></span>
						<button hidden id="upload-button">Upload</button>
						<button id="cancel-pdf">Cancel</button>
					</div>
				</div>		
			</div>
		</div>
	</div> 

	<div class="btns">
		<button class="btn btn X" name="CancelFrm">Annuler</button>
		<button id="addlog"class="btn btn X" name="EnrFrm" >Ajouter</button>
	</div>

	<input type="text" id="i_varable" name="i_var" hidden>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter d'autres equipements</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	    <ul class="equiLst">
		  
		    <div id="originalEL">	
			<li class='equiEl'> <h5>Equipement1</h5><input type='text' name='titre[]' id='ORtitre' placeholder='Titre'>&nbsp;<div class='dropdown dropdownE'><a class='dropbtn dropbtnE'>ajouter description</a><div  class='dropdown-content dropdown-contentE'><h5>Description</h5><textarea name='desc[]' id='ORdesc'></textarea></div></div> <span class='closeE'>x</span></li> 
			</div> 	
			<div id="addedEL">
            </div>				
			<hr class="hrEqui">
			<i class="fas fa-plus-circle addEqui" id="addEqui"></i>
			
        </ul> 
      </div>
      <div class="modal-footer">
        <button id="clsEqui" type="button" class="btn btn-secondary" data-dismiss="modal" >Annuler</button>
        <button id="cnfrm" type="button" class="btn btn-primary">Confirmer</button>
      </div>
    </div>
  </div>
</div>
</form>
		<footer class="footer">
			<div class="footer-wrap">
				<div class="w-100 clearfix">
				  <span class="d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="https://www.templatewatch.com/" target="_blank">templatewatch</a>. All rights reserved.</span>
				  <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart-outline"></i></span>
				</div>
			</div>
		</footer>
		
    

 
 
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
	
	<script src="../../Resourse/Js/JSG/semantic.min.js"></script>

	<script src="../../Resourse/js2/accordion.js"></script>
	<script src="../../Resourse/js2/uploadimg.js"></script>
	<script src="../../Resourse/js2/fileUpload.js"></script>
	<script src="../../Resourse/js2/pdf.js"></script>
	<script src="../../Resourse/js2/pdf.worker.js"></script>


	<script type="text/javascript">

		function showform(x)
		{
			if(x==0)
			  {
				document.getElementById('formExst').style.display='none';
				document.getElementById('formNew').style.display='block';
			  }
			  else
			  {
				document.getElementById('formNew').style.display='none';
				document.getElementById('formExst').style.display='block';
			  }
		}

				function showPiece(x)
		{
			if(x==0)
			  {
				document.getElementById('PieceInput').style.display='block';
			  }
			  else
			  {
				document.getElementById('PieceInput').style.display='none';
			  }
		}

	</script>

<script>
var options = [];

$( '.dropdown-menu a' ).on( 'click', function( event ) {

   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
      
   console.log( options );
   return false;
});

	</script>


<script>
var idhr='';
var idEquiEL=2;
$(document).ready(function(){

	$(document).on('click','.closeE',function(){
		
		idhr='hr'+$(this).attr('id') ;
		this.parentElement.remove(); //style.display = 'none';
		document.getElementById(idhr).remove(); //style.display = 'none';
		idEquiEL=idEquiEL-1;
		
		
		
    });
});
</script>

<script>
	
	
	$(document).ready(function(){ 
		$('#addEqui').click(function(){
			document.getElementById('addedEL').insertAdjacentHTML("beforeend","<hr id='hr"+idEquiEL+"' ><li class='equiEl'> <h5>Equipement"+idEquiEL+"</h5><input name='titre[]' id='titre"+idEquiEL+"' type='text' placeholder='Titre'>&nbsp;<div class='dropdown dropdownE'><a class='dropbtn dropbtnE'>ajouter description</a><div  class='dropdown-content dropdown-contentE'><h5>Description</h5><textarea name='desc[]' id='desc"+idEquiEL+"'></textarea></div> </div><span id='"+idEquiEL+"' class='closeE'>x</span></li>");
			idEquiEL=idEquiEL+1;
		});
});	

</script>

<script>
$(document).ready(function(){ 
	document.getElementById('dv_etab').style.display='none';
		$('#clsEqui').click(function(){
			
			$("#addedEL").empty(); 
			$("#originalEL").empty();
			idEquiEL=2;
			
		});

		$('#showI').click(function(){
			
			document.getElementById('dv_etab').style.display='block';
			
		});
		$('#hideI').click(function(){
			
			document.getElementById('dv_etab').style.display='none';
			
		});
});	

</script>

<script>
/*$(document).ready(function(){ 
		$('#openEqM').click(function(){
			
			document.getElementById('originalEL').insertAdjacentHTML("beforeend","<li class='equiEl'> <h5>Equipement1</h5><input type='text' name='titre[]' id='ORtitre' placeholder='Titre'>&nbsp;<div class='dropdown dropdownE'><a class='dropbtn dropbtnE'>ajouter description</a><div  class='dropdown-content dropdown-contentE'><h5>Description</h5><textarea name='desc[]' id='ORdesc'></textarea></div></div> <span class='closeE'>x</span></li>"); 
			
		});
});	*/
</script>

<script>
  var CS="<?php echo "<option selected disabled>choisissez une province ou prefecture</option>".$CS; ?>";
  var MS="<?php echo "<option selected disabled>choisissez une province ou prefecture</option>".$MS; ?>";
  var RSK="<?php echo "<option selected disabled>choisissez une province ou prefecture</option>".$RSK; ?>";
  var TTA="<?php echo "<option selected disabled>choisissez une province ou prefecture</option>".$TTA; ?>";
  
  var valSelect;
  var region;
$(document).ready(function(){
/*
 if(region=="Rabat-Salé-Kénitra")
  {       
    $('#Select_Province').html(RSK);  
  }
  else if(region=="Casablanca-Settat")
  {
   $('#Select_Province').html(CS); 
  }  
  else if(region=="Marrakech-Safi")
  {
    $('#Select_Province').html(MS); 
  }  
  else if(region=="Tanger-Tétouan-Al Hoceïma")
  {
    $('#Select_Province').html(TTA); 
  }*/

  

  
  
  
  
  $('#Select_Region').change(function(){
	
    valSelect=$(this).val();

      
      if(valSelect=="Rabat-Salé-Kénitra")
       {
         $('#Select_Province').html(RSK);  
       }
      else if(valSelect=="Casablanca-Settat")
       {
        $('#Select_Province').html(CS); 
       }  
      else if(valSelect=="Marrakech-Safi")
       {
        $('#Select_Province').html(MS); 
       }  
      else if(valSelect=="Tanger-Tétouan-Al Hoceïma")
       {
        $('#Select_Province').html(TTA); 
       }    
      else
      {
        $('#Select_Province').html("<option>Provinces non disponibles!!</option>");
      }
   

    
      
   });
});
</script>


  </body>
</html>
