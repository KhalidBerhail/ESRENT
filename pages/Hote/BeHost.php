<?php
session_start();

if( !isset($_SESSION['username']) || $_SESSION['type'] != "pro" )
{
  header("location:../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
$msg="";
$alert="";
$script="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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
"	
  <label class='Xa'>".$row['nom']."
    <input type='checkbox' name='check_list[]' value=".$row['CodeE'].">
    <span class='checkmark'></span>
  </label>
";
}

if(isset($_POST['EnrFrm']))
{
  //remplicage des data
    $LogeType=$_POST['logetype'];
    $PdfFile=file_get_contents($_FILES["pdf"]["tmp_name"]);
    $imgfilescount = count($_FILES['upload_imgs']['name']);
    $images=array();
    // Looping all files
    for($i=0;$i<$imgfilescount;$i++){
      array_push($images,file_get_contents($_FILES['upload_imgs']["tmp_name"][$i]));
    }
    $nomL=$_POST['nomL'];
    $Desc=$_POST['Desc'];
    $nbrloc=$_POST['nbrloc'];
    $adresseL=$_POST['AdrLo'];
    $prix=$_POST['prixL'];
    $sprfc=$_POST['sprfc'];
    $reglement=$_POST['Reg'];
    $checkedItems=$_POST['check_list'];
    $nbr_orEqui=sizeof($checkedItems);
    $CodeU=$_SESSION['usercode'];
    
  //creation logement [creation Studio || Apparetement()],creation images,creation file
    $datenow = new DateTime(date('Y-m-d'));
    $datetoreq = $datenow->format('Y-m-d');
    $Forseatch=metaphone($nomL).' '.metaphone($Desc).' '.metaphone($adresseL);
    $req = "INSERT INTO `logement`(`CodeP`, `nom`, `adress`, `description`, `reglement`,`prix`,`superficie`,`SL_adr_nom`, `type`, `status`, `date`) VALUES (?,?,?,?,?,?,?,?,?,'Ajoute',?)";
    $statement=$conn->prepare($req);
    $statement->bind_param("issssdisss",$CodeU,$nomL,$adresseL,$Desc,$reglement,$prix,$sprfc,$Forseatch,$LogeType,$datetoreq);
    $statement->execute();
    $CodeL=$conn->insert_id;
  //files et images
    foreach ($images as $data) {
      $req = "INSERT INTO `image`(`CodeL`, `image`) values(?,?)";
      $statement=$conn->prepare($req);
      $statement->bind_param("is",$CodeL,$data);
      $statement->execute();
      }
    $req = "INSERT INTO `files`(`CodeL`, `file`) values(?,?)";
    $statement=$conn->prepare($req);
    $statement->bind_param("is",$CodeL,$PdfFile);
    $statement->execute();
  //creation equipement
    if($nbr_orEqui>0){
      foreach ($checkedItems as $CodeEqu) {
      $req = "INSERT INTO `eqlo`(`CodeE`, `CodeL`) VALUES (?,?)";
          $statement=$conn->prepare($req);
          $statement->bind_param("si",$CodeEqu,$CodeL);
          $statement->execute();													
      }
    }
  //insertion apartement ou studio
    if($LogeType=="Appartement"){
      $nbr_piece=$_POST['nbrP'];
      $req = "INSERT INTO `appartement`(`Codeapp`,`nbrC`, `nbrP`) VALUES (?,?,?)";
      $statement=$conn->prepare($req);
      $statement->bind_param("iii",$CodeL,$nbr_piece,$nbrloc);
      $statement->execute();
    }else if($LogeType=="Studio"){
      $req = "INSERT INTO `studio`(`CodeS`, `nbrP`) VALUES (?,?)";
      $statement=$conn->prepare($req);
      $statement->bind_param("ii",$CodeL,$nbrloc);
      $statement->execute();							
    }
    $alert='<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Succes:</strong> Logement ajouté avec succes.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>';
    $script="
    setTimeout(function(){
       location.replace('../PropPages/Prop.php');
      }, 2500);
    ";


}
?>
<!--Code By Webdevtrick ( https://webdevtrick.com )-->
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Multi Step Form | Webdevtrick.com</title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
  <link href='../../Resourse/Behost/style.css' rel='stylesheet'>
  <link rel="stylesheet" href="../../Resourse/Behost/toggles.css">

  <link href='../../Resourse/Behost/images.css' rel='stylesheet'>
  <link href='../../Resourse/Behost/file.css' rel='stylesheet'>

  <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
</head>
<body>
 
<header class="header">
  <h1 class="header__title">Ajoute Du logement</h1>
</header>
<div class="content">
  <div class="content__inner">
    <div class="container">
     
      <h2 class="content__title">veullier remplire tout les input</h2>
    </div>
    <div class="container overflow-hidden">
      <div class="multisteps-form">
        <div class="row">
          <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
            <div class="multisteps-form__progress">
              
              <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">Informations de logement</button>
              <button class="multisteps-form__progress-btn" type="button" title="Address">Images</button>
              <button class="multisteps-form__progress-btn" type="button" title="Order Info">Legalite</button>
              <button class="multisteps-form__progress-btn" type="button" title="Message">Fin </button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-8 m-auto">
            <form class="multisteps-form__form" method="POST" enctype="multipart/form-data">
              <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
                <div class="multisteps-form__content">

            <?=$alert;?>
			<div class="form-row mt-4">
				<div class="rowR">
            <div class="col-6">
              <div class="form-check rad1">
                <label class="form-check-label Xa">
                <input type="radio" onclick="showPiece(1)" name="logetype" value="Studio" class="form-check-input">
                Studio
                <span class="checkmark"></span>
                </label>
              </div>
            </div>				
            <div class="col-6">	
              <div class="form-check rad2">
              <label class="form-check-label Xa">
                <input type="radio" onclick="showPiece(0)" name="logetype" value="Appartement" class="form-check-input" checked>
                Appartement
                <span class="checkmark"></span>
                </label>
              </div>
            </div>
        </div>
      </div>
                <div class="form-row mt-4">
                    <div class="col">
                      <input class="multisteps-form__input form-control" pattern="[A-Za-z0-9 ]+" name="nomL" type="text" placeholder="Nom Logement"/>
                    </div>
                  </div>
                  <div class="form-row mt-4">
				  <textarea class="form-control" id="exampleFormControlTextarea1" pattern="[A-Za-z0-9 ]+" name="Desc" rows="3" placeholder="Description"></textarea>
                  </div>
                  <div class="form-row mt-4">
                    <div class="col-12 col-sm-6">
					<input type="text" class="form-control" name="prixL" pattern="[0-9]+" id="exampleInputEmail2" placeholder="Prix">
                    </div>
                    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
					<input type="text" class="form-control" name="sprfc" pattern="[0-9]+" id="exampleInputEmail2" placeholder="sprfc">
                    </div>
                  </div>

				  <div class="form-row mt-4">
				  <div class="form-group row">
								<div class="col-sm-9">
									<label for="exampleInputPassword2" class="col-sm-3 col-form-label">Equipements </label>
									<div class="dropdown">
										<button class="dropbtn form-control btn btn-default btn-sm dropdown-toggle ">Dropdown</button>
										<div class="dropdown-content force-scroll">
                      <?=$equi ;?>
										</div>
									</div> 
								</div>
							</div>
				  </div>

				   <div class="form-row mt-4">
				    <div class="col-12 col-sm-6 mt-4 mt-sm-0">
					   <input class="form-control" name="nbrloc" type="number" pattern="[0-9]+"  placeholder="Nombre de locataire" />
					  </div>
            <div class="col-12 col-sm-6 " id="PieceInput">
					   <input class="form-control" name="nbrP" type="number" pattern="[0-9]+" placeholder="Nombre de piece" />
				    </div> 
          </div>
				
				   <div class="form-row mt-4">
					  <input class="form-control" type="text" name="AdrLo" pattern="[A-Za-z0-9 ]+" placeholder="Adresse" />
				   </div>

				   <div class="form-row mt-4">
				    <textarea class="form-control" id="exampleFormControlTextarea1" pattern="[A-Za-z0-9 ]+" name="Reg" rows="3" placeholder="Reglement"></textarea>
           </div>                  
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div> 
              </div>

              
 
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Importer vos images</h3>
                <div class="multisteps-form__content">
                
                 
                  <div class="form-row mt-4">
				  <div class="grid-x grid-padding-x">
  <div class="small-10 small-offset-1 medium-8 medium-offset-2 cell">
   
    <div id="img-upload-form" >
      <p>
        <label for="upload_imgs" class="btn btn-outline-info">Select Your Images +</label>
        <input class="show-for-sr" type="file" id="upload_imgs" name="upload_imgs[]" multiple/>
      </p>
      <div class="quote-imgs-thumbs quote-imgs-thumbs--hidden" id="img_preview" aria-live="polite"></div>
    </div>
  </div>
</div>
                     </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                    <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                  </div>
                </div>
              </div>
 
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title"> Ajouter un document ( Certificat de propriété )</h3>
                <div class="multisteps-form__content">
                  <div class="row">
                    <div class="col-12 col-md-6 mt-4">
					<div class="form-group row">
					<div id="preview-container">
					<label for="exampleInputPassword2" class="col-sm-3 col-form-label"><i class="fas fa-file-upload"></i> </label>
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
                  <div class="row">
                    <div class="button-row d-flex mt-4 col-12">
                      <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                      <button class="btn btn-primary ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                  </div>
                </div>
              </div>
 
              <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                <h3 class="multisteps-form__title">Fin</h3>
                <div class="multisteps-form__content">
                  <div class="form-row mt-4">
                       merci
                  </div>
                  <div class="button-row d-flex mt-4">
                    <button class="btn btn-primary js-btn-prev" type="button" title="Prev">Prev</button>
                    <button class="btn btn-success ml-auto" name="EnrFrm" title="Send">Send</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>
 

<script type="text/javascript">

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
    <?=$script;?>
</script>
<script  src="../../Resourse/Behost/js.js"></script>
<script src="../../Resourse/Behost/uploadimg.js"></script>
<script src="../../Resourse/js2/fileUpload.js"></script>
	<script src="../../Resourse/js2/pdf.js"></script>
	<script src="../../Resourse/js2/pdf.worker.js"></script>
</body>
</html>