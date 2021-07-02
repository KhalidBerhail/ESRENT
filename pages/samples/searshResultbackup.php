<?php
session_start();
$servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  $msg="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$Srech=metaphone($_GET['rech']);
//$Srech="'%".$Srech."%'";



$result="";
$reqL = "SELECT * from logement where SL_adr_nom like '%$Srech%' ";
$statementL=$conn->prepare($reqL);
//$statement->bind_param("s",$Srech);
$statementL->execute();
$resL=$statementL->get_result();
while ($rowL = mysqli_fetch_array($resL)) 
{
  $LogeType = $rowL['type'];
  $CodeL = $rowL['CodeL'];
  $nom = $rowL['nom'];
  $adress = $rowL['adress'];
  $description = $rowL['description'];
  $price=$rowL['prix'];
  $sup=$rowL['superficie'];
  $prix=$rowL['prix'];



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





    $result.='  <article>
    <!--Slidshow-->
      <div id="demo" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
          <li data-target="#demo" data-slide-to="0" class="active"></li>
          <li data-target="#demo" data-slide-to="1"></li>
          <li data-target="#demo" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">

        '.$img.'


        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
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
          <a href="#" class="btn btn-primary">Voir plus</a>
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
    

    $result.='  <article>
    <!--Slidshow-->
      <div id="demo" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
          <li data-target="#demo" data-slide-to="0" class="active"></li>
          <li data-target="#demo" data-slide-to="1"></li>
          <li data-target="#demo" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">
        '.$img.'
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
      </div>

        <!--/slidshow-->
      <div class="card-body">
        <h5 class="card-title">'.$nom.'</h5>
        <p class="card-text"> <i class="fas fa-tags CA"></i>'.$prix.'Dh  &nbsp;<i class="fas fa-male CA"></i> '.$nbrP.'  &nbsp; <i class="fas fa-warehouse CA"></i>'.$sup.' 100 m²</p>

        <p class="card-text">  <i class="fas fa-map-marker-alt CA"></i> '.$adress.' </p>
          <br>
        <p class="cpara">'.$description.'</p> <br>
          <a href="#" class="btn btn-primary">Voir plus</a>
      </div>
  </article>';
    
  }

}

?>
<!DOCTYPE html>
<html>
<head>


  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Homepage</title>
  <link rel="stylesheet" type="text/css" href="../../Resourse/CSS/semantic.min.css">
  <link href="../../vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
 

  <link rel="stylesheet" type="text/css" href="../../Resourse/CSS/PageSearch.css">
  <link rel="stylesheet" type="text/css" href="../../Resourse/CSS/StyleSearshBar.css">
 

  
  <link rel="stylesheet" href="../../Resourse/css2/styleRe.css">
  
	<link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
</head>
<body>


  <!-- Page Contents -->
   <div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container-fluid">
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
          
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="dash.html"><img src="../../Resourse/images/logo-1.png" alt="logo"/></a>
            </div>
            <ul class="navbar-nav navbar-nav-right">
               
                <li class="nav-item nav-profile dropdown">
                <div class="rightSh">
            <a href="pages/samples/register.php" class="itemXE">Devenez hôte</a>
            <a href="pages/samples/register.php" class="itemXE">Aide</a>
            <a href="pages/samples/register.php" class="itemXE">Inscription</a>
            <a href="pages/samples/login.php"  class="itemXE" >Connexion</a>

            </div>
                 
                </li>
            </ul>
          
          </div>
        </div>
      </nav>
      <nav class="navbar bottom-navbar col-lg-12 col-12 p-0">
      <nav class="bottom-navbar">
      <ul class="nav page-navigation">
              <li class="nav-item">
        <div class="container">
            <ul class="nav page-navigation">
            <div id="SR" class="searshX">
        <input id="search51" name="q" type="text" value="<?=$_GET['rech']?>"  placeholder="Search..." />
        </div> 
         </li>
         &nbsp;  &nbsp;&nbsp;
         <li class="nav-item dropdown d-lg-flex d-none">
                  <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm" id="nreportDropdown" href="#" data-toggle="dropdown">
                  Price
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"  aria-labelledby="nreportDropdown">
                    
                    <p class="mb-0 font-weight-medium float-left dropdown-header">Price</p> <br><br>
                    <div id="PM">
                      <a class="dropdown-item">
                        <input type="text" class="form-control" placeholder="MIN" id="Imin" value="0" aria-label="search" aria-describedby="MIN">
                      </a>
                      <a class="dropdown-item">                      
                        <input type="text" class="form-control" placeholder="MAX" id="Imax" value="5000" aria-label="search" aria-describedby="MAX">
                      </a>
                      <br>
                    </div>
                    <div class="footer">
                      <button type="button" class="btn btn-dark">Done</button>
                    </div>
                  </div>
                 
                </li>
                &nbsp;&nbsp;&nbsp;
          
                <li class="nav-item dropdown d-lg-flex d-none">
                  <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm" id="nreportDropdown" href="#" data-toggle="dropdown">
                  Nombre de personne
                  </a>
                 <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="nreportDropdown">
                      <p class="mb-0 font-weight-medium float-left dropdown-header">Nombre de personne</p> <br><br>

                        <a class="dropdown-item">
                        <div id="NPM" class="switch-field">
                        <input type="radio" id="radio-O" name="switch-two" value="All" checked/>
	                         	<label for="radio-O">All</label>
	                        	<input type="radio" id="radio-one" name="switch-two" value="1" />
	                         	<label for="radio-one">1</label>
	                         	<input type="radio" id="radio-two" name="switch-two" value="2" />
	                          	<label for="radio-two">2</label>
	                         	<input type="radio" id="radio-thre" name="switch-two" value="3" />
                            <label for="radio-thre">3</label>
                            <input type="radio" id="radio-four" name="switch-two" value="4" />
	                          	<label for="radio-four">4</label>
	                         	<input type="radio" id="radio-five" name="switch-two" value="5" />
	                        	<label for="radio-five">5</label>
                     	</div>
                    

                      </a>
                     
                      <br>
                      <div class="footer">
                      <button type="button" class="btn btn-dark">Done</button>
                      </div>
                  </div>
                 
                </li>

                &nbsp;&nbsp;&nbsp;
          
          <li class="nav-item dropdown d-lg-flex d-none">
            <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm" id="nreportDropdown" href="#" data-toggle="dropdown">
            Nombre de Chambres
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="nreportDropdown">
                <p class="mb-0 font-weight-medium float-left dropdown-header">Nombre de Chambres</p> <br><br>

                  <a class="dropdown-item">
                  <div id="NCM" class="switch-field">
                  <input type="radio" id="rad0" name="switchs-two3" value="All" checked/> <label for="rad0">All</label>
                  <input type="radio" id="rad1" name="switchs-two3" value="1" /> <label for="rad1">1</label>
                  <input type="radio" id="rad2" name="switchs-two3" value="2" /> <label for="rad2">2</label>
                  <input type="radio" id="rad3" name="switchs-two3" value="3" /> <label for="rad3">3</label>
                  <input type="radio" id="rad4" name="switchs-two3" value="4" /> <label for="rad4">4</label>
                  <input type="radio" id="rad5" name="switchs-two3" value="5" /> <label for="rad5">5</label>
                 </div>


                </a>
               
                <br>
                <div class="footer">
                <button type="button" class="btn btn-dark">Done</button>
            </div>
            </div>
           
          </li>

          &nbsp;&nbsp;&nbsp;
          
          <li class="nav-item dropdown d-lg-flex d-none">
            <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm" id="nreportDropdown" href="#" data-toggle="dropdown">
            Type de Logemnt
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="nreportDropdown">
                <p class="mb-0 font-weight-medium float-left dropdown-header">Type de Logemnt</p> <br><br>
                  <a class="dropdown-item">
                  <div id="TM" class="switch-field">
                  <input type="radio" id="radio-Oee" name="switch-two4" value="All" checked/>
                       <label for="radio-Oee">All</label>
                  <input type="radio" id="radio-Oe" name="switch-two4" value="studio"/>
                       <label for="radio-Oe">Studio</label>
                      <input type="radio" id="radio-fin" name="switch-two4" value="Apartement" />
                       <label for="radio-fin">Apartement</label>
                      
                 </div>


                </a>
               
                <br>
                <div class="footer">
                <button type="button" class="btn btn-dark">Done</button>
            </div>
            </div>
           
          </li>
          &nbsp;&nbsp;&nbsp;
          
          <li class="nav-item dropdown d-lg-flex d-none">
            <a class="dropdown-toggle show-dropdown-arrow btn btn-inverse-primary btn-sm" id="nreportDropdown" href="#" data-toggle="dropdown">
            Plus
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="nreportDropdown">
                <p class="mb-0 font-weight-medium float-left dropdown-header">Price</p> <br><br>

                  <a class="dropdown-item">
                  <div id="MrM" class="switch-field">
                  <input type="radio" id="radio-O" name="switch-twoM" value="yes"/>
                       <label for="radio-l">Studio</label>
                      <input type="radio" id="radio-three" name="switch-twoM" value="no"  />
                       <label for="radio-l">Apartement</label>
                      
                 </div>


                </a>
               
                <br>
                <div class="footer">
                <button type="button" class="btn btn-dark">Done</button>
            </div>
            </div>
           
          </li>


       </ul>
         <li class="nav-item">
        
           <button  id="rf" class="btn btn-dark">Rechercher</button>

         </li>
        </div>

        
      </nav>
   </nav> 
   </div>

  <div class="grid" id="nC">
    <?=$result ;?>
    </div>


</body>

<script type="text/javascript">
  
          // Prevents menu from closing when clicked inside 
        // Prevents menu from closing when clicked inside 
        document.getElementById("PM").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
        document.getElementById("NPM").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
        document.getElementById("NCM").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
        document.getElementById("TM").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
        document.getElementById("MrM").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
</script>





<script src="../../Resourse/Js/JSG/semantic.min.js"></script>
<script src="../../Resourse/JS/JSG/jquery-3.4.1.min.js"></script>
<script src="../../Resourse/JS/JSG/jquery.min.js"></script>

<script src="../../Resourse/js2/Card.js"></script>


<script src="../../Resourse/Js/searsh.js"></script>

<script src="../../Resourse/js2/template.js"></script>
<script src="../../Resourse/vendors/base/vendor.bundle.base.js"></script>
    <script src="../../Resourse/js2/dashboard.js"></script>

   
</html>



<script>  


 $(document).ready(function(){  
      $('#rf').click(function(){  
           var Max = document.getElementById("Imax").value; 
           var Min = document.getElementById("Imin").value; 
           var Nbr_pr=document.querySelector('#NPM input[name="switch-two"]:checked').value;
           var Nbr_Ch=document.querySelector('#NCM input[name="switchs-two3"]:checked').value;
           var TL_Type=document.querySelector('#TM input[name="switch-two4"]:checked').value; 
           var srch=document.querySelector('#SR input[name="q"]').value;


           $.ajax({  
                url:"FilteredSearch.php?rech=<?=$_GET['rech']?>",  
                method:"POST",  
                data:{Pmax:Max,Pmin:Min,NP:Nbr_pr,NC:Nbr_Ch,TL:TL_Type,search:srch},  
                success:function(data){  
                     $('#nC').html(data);  
                }  
           });  
      });  
 });  
 </script>  
