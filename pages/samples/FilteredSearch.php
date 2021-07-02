<?php
session_start();

  $servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  $msg="";
   $result="";
   $markers=array();
   /*
   $reqR="SELECT * from logement where (`status`='valide') and (`SL_adr_nom` like '%$rech%') and (`prix` between $Pmin and $Pmax) ";
   if($region!="ALL")
   {
    $reqR.= "and (`region`=$region) ";
   }
   if($province!="ALL")
   {
    $reqR.="and (`province-prefecture`=$province)";
   }*/
// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rech=metaphone($_POST['search']);

$Pmin=$_POST['Pmin'];
$Pmax=$_POST['Pmax'];
$NP=$_POST['NP'];
$NC=$_POST['NC'];
$TL=$_POST['TL'];
$CLC=$_POST['colloc'];
$EPR=$_POST['etu_prch'];
$ETB=$_POST['etab'];
$region=$_POST['region'];

$province=$_POST['province'];
//$result= "Min:$Pmin ,  MAX:$Pmax  , NP:$NP  , NC:$NC ,TL:$TL, CLC:$CLC , EPR:$EPR  , ETB:$ETB , region:$region , province:$province";

$reqR="SELECT * from logement where (`status`='valide') and (`SL_adr_nom` like '%$rech%') and (`prix` between $Pmin and $Pmax) ";
if($region!="ALL")
   {
    $reqR.= "and (`region`='$region') ";
   }
   if($province!="ALL")
   {
    $reqR.="and (`province-prefecture`='$province')";
   }
    if($NP!="All")
    {
      $reqR=$reqR." AND ( (CodeL in (SELECT CodeS from studio where nbrP=$NP )) or (CodeL in (SELECT Codeapp from appartement where nbrP=$NP)))";
    }
    if($TL!="All")
    {
      if($TL=="studio")
        {
          $reqR=$reqR." AND (type='studio')";
        }
      else if($TL=="Appartement")
        {
          $reqR=$reqR." AND (type='Appartement')";
          if($NC=="All")
            {
              $reqR=$reqR." AND (CodeL in (SELECT Codeapp from appartement where nbrC=$NC))";
            }
        } 
    }
    if($NC!="All")
            {
              $reqR=$reqR." AND (CodeL in (SELECT Codeapp from appartement where nbrC=$NC))";
            }
    
    if($CLC!="All")  
    {
      $reqR=$reqR." AND (collocation='$CLC')";
    }
    if($EPR=="oui")  
    {
      $reqR=$reqR." AND (pour_etudiant='oui')";
      if($ETB!="")  
       {
         $reqR=$reqR." AND (etabe_proche='$ETB')";
       } 
     
    }
    else if($EPR=="non")
    {
      $reqR=$reqR." AND (pour_etudiant='non')";
    }
    else if($EPR=="All")
    {
      $reqR=$reqR." AND (pour_etudiant='non' or pour_etudiant='oui' )";
    }


    
     
$statementR=$conn->prepare($reqR);
//$statementR->bind_param("ss",$region,$province);
$statementR->execute();
$resR=$statementR->get_result();
while($rowR = mysqli_fetch_array($resR))
  {
    $LogeType = $rowR['type'];
  $CodeL = $rowR['CodeL'];
  $nom = $rowR['nom'];
  $adress = $rowR['adress'];
  $description = $rowR['description'];
  $description=substr($description,0,150)."...";
  $price=$rowR['prix'];
  $sup=$rowR['superficie'];
  $prix=$rowR['prix'];
  $lat=$rowR['lat'];
  $lng=$rowR['lng'];



  $req = "SELECT * FROM image where CodeL=?";
  $statement=$conn->prepare($req);
  $statement->bind_param("i",$CodeL);
  $statement->execute();
  $res=$statement->get_result();
  $i=0;
  $img="";
  $active="active";
  while ( ($row = mysqli_fetch_array($res)) && ($i < 3) ) 
  {
    $id=$row['CodeImg'];
    $src="genere_image.php?id=$id";
    if($i!=0)
    $active="";

    $img.=
  "
    <div class='carousel-item  $active'>
    <img src='$src' class='d-block w-100'>
    </div>
  ";
    $i = $i + 1;
  }



  if($LogeType=="Appartement")
  {//appartement
    $req = "SELECT * FROM appartement where Codeapp=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("i",$CodeL);
    $statement->execute();
    $res=$statement->get_result();
    $row=$res->fetch_assoc();
    $rooms=$row['nbrC'];
    $nbrP=$row['nbrP'];




    $Aimg="<img id='smrP".$CodeL."' src='$src' class='act_img'>";
    array_push($markers,array($CodeL,$nom,$LogeType,$description,$prix,$lat,$lng,$Aimg,$adress));
    $result.='  <article id="card-'.$CodeL.'" class="displayed-item" >
    <!--Slidshow-->
      <div id="demo'.$CodeL.'" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
          <li data-target="#demo'.$CodeL.'" data-slide-to="0" class="active"></li>
          <li data-target="#demo'.$CodeL.'" data-slide-to="1"></li>
          <li data-target="#demo'.$CodeL.'" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">

        '.$img.'


        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo'.$CodeL.'" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo'.$CodeL.'" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
      </div>

        <!--/slidshow-->
      <div class="card-body">
        <h5 class="card-title">'.$nom.'</h5>
        <p class="card-text"> <i class="fas fa-tags CA"></i>'.$prix.'Dh  &nbsp;<i class="fas fa-bed CA"></i> '.$rooms.'  &nbsp;  <i class="fas fa-male CA"></i> '.$nbrP.'  &nbsp; <i class="fas fa-warehouse CA"></i>'.$sup.'  m²</p>

        <p class="card-text">  <i class="fas fa-map-marker-alt CA"></i> '.$adress.' </p>
          <br>
        <p class="cpara">'.$description.'</p> <br>
          <a href="SeeMore.php?smr='.$CodeL.'" class="btn btn-primary">Voir plus</a>
      </div>
  </article>';

    

  }else if($LogeType=="Studio")
  {//studio
    $req = "SELECT * FROM studio where CodeS=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("i",$CodeL);
    $statement->execute();
    $res=$statement->get_result();
    $row=$res->fetch_assoc();
    $nbrP=$row['nbrP'];
    
    $Aimg="<img id='smrP".$CodeL."' src='$src' class='act_img'>";
    array_push($markers,array($CodeL,$nom,$LogeType,$description,$prix,$lat,$lng,$Aimg,$adress));
   $result.='  <article id="card-'.$CodeL.'" class="displayed-item" >
    <!--Slidshow-->
      <div id="demo'.$CodeL.'" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
          <li data-target="#demo'.$CodeL.'" data-slide-to="0" class="active"></li>
          <li data-target="#demo'.$CodeL.'" data-slide-to="1"></li>
          <li data-target="#demo'.$CodeL.'" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">
        '.$img.'
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo'.$CodeL.'" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo'.$CodeL.'" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
      </div>

        <!--/slidshow-->
 <div class="card-body">
        <h5 class="card-title">'.$nom.'</h5>
        <p class="card-text"> <i class="fas fa-tags CA"></i>'.$prix.'Dh  &nbsp;<i class="fas fa-male CA"></i> '.$nbrP.'  &nbsp; <i class="fas fa-warehouse CA"></i> '.$sup.'m²</p>

        <p class="card-text">  <i class="fas fa-map-marker-alt CA"></i> '.$adress.' </p>
          <br>
        <p class="cpara">'.$description.'</p> <br>
          <a href="SeeMore.php?smr='.$CodeL.'" class="btn btn-primary">Voir plus</a>
      </div>
  </article>';
    
  }
  }
  $response=array('result'=>$result,"markers"=>$markers);
  echo json_encode($response);
?>

