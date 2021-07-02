<?php
session_start();
if( !isset($_SESSION['username']) || $_SESSION['type'] != "pro" )
{
  header("location:../../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
$result="";
$equi="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$CodeL=$_GET['idL'];
//remplicage des input
$req = "SELECT * FROM logement where CodeL=?";
$statement=$conn->prepare($req);
$statement->bind_param("s",$CodeL);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$nomL = $row['nom'];
$desc = $row['description'];
$prix = $row['prix'];
$sprf = $row['superficie'];
$Adress = $row['adress'];
$Reglement = $row['reglement'];
$logtype=$row['type'];
$nbrL="";
$nbrP="";
$locinput="";
if($logtype=='Studio')
{
   $req = "SELECT * FROM studio where CodeS=?";
   $statement=$conn->prepare($req);
   $statement->bind_param("s",$CodeL);
   $statement->execute();
   $res=$statement->get_result();
   $row=$res->fetch_assoc();
   $nbrP = $row['nbrP'];
}else if($logtype=='Appartement')
{
   $req = "SELECT * FROM appartement where Codeapp=?";
   $statement=$conn->prepare($req);
   $statement->bind_param("s",$CodeL);
   $statement->execute();
   $res=$statement->get_result();
   $row=$res->fetch_assoc();
   $nbrP = $row['nbrP'];
   $nbrL = $row['nbrC'];
   $locinput='
      <div class="col-12 col-sm-6">
         <input class="form-control" type="text" value="'.$nbrL.'" name="nbrL" required />
      </div>';

}


//selection equipment
   $req = "SELECT * FROM equipement";
   $statement=$conn->prepare($req);
   $statement->execute();
   $res=$statement->get_result();
   while ($row = mysqli_fetch_array($res)) 
   {
      $nomE=$row['nom'];
      $codeEq=$row['CodeE'];
      $reqL = "SELECT * from eqlo where CodeL=? and CodeE=? ";
      $statementL=$conn->prepare($reqL);
      $statementL->bind_param("ss",$CodeL,$codeEq);
      $statementL->execute();
      $resL=$statementL->get_result();
      if($resL->num_rows==1)
      {
         $equi .= 
         "
         <hr>
         <label class='container'>".$nomE."
            <input type='checkbox' onclick='updateEq(".$CodeL.",".$codeEq.")' checked='true'>
            <span class='checkmark'></span>
         </label>
         ";
      }else
      {
         $equi .= 
         "
         <hr>
         <label class='container'>".$nomE."
            <input type='checkbox' onclick='updateEq(".$CodeL.",".$codeEq.")' >
            <span class='checkmark'></span>
         </label>
         ";
      }
   }
   $equi.="<hr>";
   
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
      if($i==1)
      {
         $images.="
         <div class='row'>
          <div class='col-sm-4'>
            <div class='bloc-text-image'>
              <img src='genere_image_log.php?id=".$row['CodeImg']."'>
              <a onclick='delImg(".$row['CodeImg'].");location.reload();'><i class='delete far fa-times-circle'></i></a>
            </div>
          </div>
         ";
         $i=2;
      }else if($i==2)
      {
         $images.="
          <div class='col-sm-4'>
            <div class='bloc-text-image'>
              <img src='genere_image_log.php?id=".$row['CodeImg']."'>
              <a onclick='delImg(".$row['CodeImg'].");location.reload();'><i class='delete far fa-times-circle'></i></a>
            </div>
          </div>
         </div>
         ";
         $i=1;
      }
   }
   if($i==1)
   {
      $images.="
      <div class='row'>
         <div class='col-sm-4'>
         <script class='jsbin' src='https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
         <div class='file-upload'>
            <div class='image-upload-wrap'>
               <input class='file-upload-input' type='file' onchange='readURL(this);' accept='image/*' />
               <div class='drag-text'>
                        <h3>Ajouter image</h3>
               </div>
            </div>
            <div class='file-upload-content'>
               <img class='file-upload-image'  alt='your image' />
               <div class='image-title-wrap'>
                  <a onclick='removeUpload()'><i style='color:#FF0000;' class='far fa-times-circle'></i></a>
                  <a onclick='valideUpload(".$CodeL.")'><i style='color:#32CD32;' class='far fa-check-circle'></i></i></a>
               </div>
            </div>
         </div>
      </div>
      ";
      $i=2;
   }else if($i==2)
   {
      $images.="
         <div class='col-sm-4'>
         <script class='jsbin' src='https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
         <div class='file-upload'>
            <div class='image-upload-wrap'>
               <input class='file-upload-input' type='file' onchange='readURL(this);' accept='image/*' />
               <div class='drag-text'>
                        <h3>Ajouter image</h3>
               </div>
            </div>
            <div class='file-upload-content'>
               <img class='file-upload-image'  alt='your image' />
               <div class='image-title-wrap'>
               <a onclick='removeUpload()'><i style='color:#FF0000;' class='far fa-times-circle'></i></a>
               <a onclick='valideUpload(".$CodeL.")'><i style='color:#32CD32;' class='far fa-check-circle'></i></i></a>
               </div>

            </div>
         </div>
      </div>
      ";
      $i=1;
   }


//modifier information 
   if(isset($_POST['modif']))
   {
      $nomLinp = $_POST['nomL'];
      $descinp = $_POST['desc'];
      $prixinp = $_POST['prix'];
      $Adressinp = $_POST['Adress'];
      $Reglementinp = $_POST['Reg'];
      $sprfinp = $_POST['sprfc'];

      $nbrPinp = $_POST['nbrP'];

      if($logtype=='Studio'){
         $req = "UPDATE `studio` SET `nbrP`=? where CodeS=?";
         $statement=$conn->prepare($req);
         $statement->bind_param("ii",$nbrPinp,$CodeL);
         $statement->execute();							
      }else if($logtype=='Appartement'){
         $nbrLinp = $_POST['nbrL'];
         $req = "UPDATE `appartement` SET `nbrP`=?,`nbrC`=? where Codeapp=?";
         $statement=$conn->prepare($req);
         $statement->bind_param("iii",$nbrPinp,$nbrLinp,$CodeL);
         $statement->execute();
      }
      $sta="modification";
      $req = "UPDATE `logement` SET `status`=?,`nom`=?,`description`=?,`prix`=?,`superficie`=?,`adress`=?,`reglement`=? where CodeL=?";
      $statement=$conn->prepare($req);
      $statement->bind_param("ssssssss",$sta,$nomLinp,$descinp,$prixinp,$sprfinp,$Adressinp,$Reglementinp,$CodeL);
      $statement->execute();
      header('location: ModifierLog.php?idL='.$CodeL);
   }
   

?>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Modifier Info logement</title>

      <!--enable mobile device-->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--fontawesome css-->
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/font-awesome.min.css">
      <!--bootstrap css-->
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/bootstrap.min.css">
      <!--animate css-->
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/animate-wow.css">
      <!--main css-->
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/style.css">
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/bootstrap-select.min.css">
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/select2.min.css">
      <!--responsive css-->
      <link rel="stylesheet" href="../../../Resourse/CssModiferLog/responsive.css">
      <link href="../../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
      <!--ChatBox-->
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

      <link rel="stylesheet" href="../../../Resourse/css3/chatbox.css">
      <style>
      .container {
         display: block;
         position: relative;
         padding-left: 35px;
         margin-bottom: 12px;
         cursor: pointer;
         font-size: 22px;
         -webkit-user-select: none;
         -moz-user-select: none;
         -ms-user-select: none;
         user-select: none;
         }

         /* Hide the browser's default checkbox */
         .container .inputchec {
         position: absolute;
         opacity: 0;
         cursor: pointer;
         height: 0;
         width: 0;
         }

         /* Create a custom checkbox */
         .checkmark {
         position: absolute;
         top: 0;
         left: 0;
         height: 25px;
         width: 25px;
         background-color: #eee;
         }

         /* On mouse-over, add a grey background color */
         .container:hover input ~ .checkmark {
         background-color: #ccc;
         }

         /* When the checkbox is checked, add a blue background */
         .container input:checked ~ .checkmark {
         background-color: #2196F3;
         }

         /* Create the checkmark/indicator (hidden when not checked) */
         .checkmark:after {
         content: "";
         position: absolute;
         display: none;
         }

         /* Show the checkmark when checked */
         .container input:checked ~ .checkmark:after {
         display: block;
         }

         /* Style the checkmark/indicator */
         .container .checkmark:after {
         left: 9px;
         top: 5px;
         width: 5px;
         height: 10px;
         border: solid white;
         border-width: 0 3px 3px 0;
         -webkit-transform: rotate(45deg);
         -ms-transform: rotate(45deg);
         transform: rotate(45deg);
         }
      </style>

   </head>
   <body >
      <header id="header" class="top-head">
         <!-- Static navbar -->
         <nav class="navbar navbar-default">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-4 col-sm-12 left-rs">
                     <div class="navbar-header">
                        <button type="button" id="top-menu" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"> 
                        <span class="sr-only">Toggle navigation</span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        </button>
                        <a href="index.html" class="navbar-brand"><img src="../../../Resourse/images/logo-1.png" alt="" /></a>
                     </div>
                     <form class="navbar-form navbar-left web-sh">
                        <div class="form">
                           <input type="text" class="form-control" placeholder="Rechercher">
                        </div>
                     </form>
                  </div>
                  <div class="col-md-8 col-sm-12">
                     <div class="right-nav">
                        <!--right nav menu - top menu -->
                     </div>
                  </div>
               </div>
            </div>
            <!--/.container-fluid --> 
         </nav>
      </header>
      <!-- Modal -->
     
    
      <div class="product-page-main" >
         <div class="container" >
            <div class="row" >
               <div class="col-md-12">
                  <div class="prod-page-title">
                     <h2 class="titreOne"></h2>
                     <p>By <span></span></p>
                  </div>
               </div>
            </div>
            <div class="row" >
              
               <div class="col-md-7 col-sm-8">
                  <div class="md-prod-page">
                     
                   
                     <div class="description-box">
                   
                     <h4>Images</h4>


                     <?=$images;?>

   </div>
</div>

                  <form method="POST">
                     <hr>  
                        <div class="dex-a">
                           <h4>nom</h4>
                           <div class="form-row mt-4">
                              <input class="form-control" type="text" value="<?=$nomL?>" name="nomL" required/>
                           </div>
                        </div>            
                        <div class="dex-a">
                           <h4>Description</h4>
                           <div class="form-row mt-4">
                              <textarea class="form-control" rows="3" name="desc"required><?=$desc?></textarea>
                           </div>
                        </div>
                        <div class="dex-a">
                           <h4>Prix & superficie</h4>
                           <div class="form-row mt-4">
                              <div class="col-12 col-sm-6">
                                 <input class="form-control" type="text" value="<?=$prix?>" name="prix" required/>
                              </div>
                              <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                                 <input type="text" class="form-control" value="<?=$sprf?>" name="sprfc"  placeholder="sprfc"required>
                              </div>
                           </div>
                        </div>
                        <div class="dex-a">
                           <br>
                           <h4>nombre locataire & piece</h4>
                           <div class="form-row mt-4">
                              <?=$locinput?>
                              <div class="col-12 col-sm-6 mt-4 mt-sm-0">
                                 <input type="text" class="form-control" value="<?=$nbrP?>" name="nbrP" placeholder="sprfc" required>
                              </div>
                           </div>
                        </div>
                        <div class="dex-a">
                           <br>
                           <h4>Adress</h4>
                           <div class="form-row mt-4">
                              <input class="form-control" type="text" value="<?=$Adress?>" name="Adress" required />
                           </div>
                        </div>            
                        <div class="dex-a">
                           <h4>Reglement</h4>
                           <div class="form-row mt-4">
                              <textarea class="form-control" rows="3" name="Reg" required><?=$Reglement?></textarea>
                           </div>
                        </div>
                        <div class="dex-a">
                           <button class="btn btn-primary btn-block" name="modif">Modifier</button>
                        </div>
                  </form>

                        <hr>
                        <div class="spe-a">
                           <h4>Équipements</h4>
                              <div class="form-row mt-4">
                                    <a type="button" data-toggle="modal" data-target="#modalEquip"><i class="fas fa-pen"></i></a>
                                    <a type="button" aria-busy="false" class="equipment" data-toggle="modal" data-target="#modalEquip" >Modifer les équipements</a>
                              </div>
                        </div>
                        <hr>
                        <div class="spe-a">
                              <h4>Legalité</h4>
                              <div class="form-row mt-4">
                                 <a type="button" onclick="openpap()" ><i class="fas fa-eye"></i></a>
                                 <a type="button" aria-busy="false" class="equipment" onclick="openpap()" >Voir les papier de legalité</a><br>
                                 <input class='file-upload-input' type='file' onchange='readfile(this);' accept='application/pdf' />
                                 <div class=''>
                                    <h3>
                                       <a type="button"><i class="fas fa-pen"></i></a>
                                       <a type="button" aria-busy="false" class="equipment" >Modifier les papier de legalité</a>
                                       <br>
                                       <a type="button" onclick='valideUploadFile(<?=$CodeL;?>); ' style="display:">Accepté <i class="far fa-check-circle"></i></a>
                                    </h3>
                                 </div>
                              </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

<!--Modal-->

<div class="modal fade" id="modalEquip"  tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title _26piifo">Équipements</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgb(118, 118, 118);"><path d="m23.25 24c-.19 0-.38-.07-.53-.22l-10.72-10.72-10.72 10.72c-.29.29-.77.29-1.06 0s-.29-.77 0-1.06l10.72-10.72-10.72-10.72c-.29-.29-.29-.77 0-1.06s.77-.29 1.06 0l10.72 10.72 10.72-10.72c.29-.29.77-.29 1.06 0s .29.77 0 1.06l-10.72 10.72 10.72 10.72c.29.29.29.77 0 1.06-.15.15-.34.22-.53.22" fill-rule="evenodd"></path></svg></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="_1lhxpmp">
           <div style="margin-top: 8px;">
            <?=$equi ;?>
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <!--just for the color -->
      </div>
    </div>
  </div>
</div>
   </body>
   

   


   
      <!--bootstrap js--> 
      <script src="../../../Resourse/js2/uploadimg.js"></script>
      <script src="../../../Resourse/js4/bootstrap.min.js"></script> 

      <script src="../../../Resourse/js4/slick.min.js"></script> 
      <script src="../../../Resourse/js4/select2.full.min.js"></script> 
      <script src="../../../Resourse/js4/wow.min.js"></script> 
      <!--custom js-->
      <script>
      //ouvrir le fichier legalité
         function openpap() { 
            window.open("genere_file.php?id=<?=$CodeL;?>", "_blank");
         }
      //update les equipement
         function updateEq(L,E) { 
            $.ajax({  
                  url:"UpdateEq.php",  
                  method:"GET",  
                  data:{codeL:L,codeE:E}
            });
         }
      //ouvrir le fichier legalité
         function delImg(codeImg) { 
            $.ajax({  
                  url:"delete_image.php",  
                  method:"GET",  
                  data:{id:codeImg}
            });
         }
      //lire l'image
      var imageToval,File;
         function readURL(input) {
            if (input.files && input.files[0]) {
         
               var reader = new FileReader();
         
               reader.onload = function(e) {
               $('.image-upload-wrap').hide();
               imageToval=e.target.result;
               $('.file-upload-image').attr('src', e.target.result);
               $('.file-upload-content').show();
         
               $('.image-title').html(input.files[0].name);
               };
         
               reader.readAsDataURL(input.files[0]);
         
            } else {
               removeUpload();
            }
         }
         function readfile(input) {
            if (input.files && input.files[0]) {
               var reader = new FileReader();
               reader.onload = function(e) {
                  File=e.target.result;
               };
               reader.readAsDataURL(input.files[0]);
            } else {
               removeUpload();
            }
         }
         
         function valideUpload(Log) {
            $.ajax({  
                  url:"Add_image.php",  
                  method:"POST",  
                  data:{img:imageToval,codeL:Log},
          success:function(data){
            location.reload();
          }
            });
         }
         function valideUploadFile(Log) {
            $.ajax({  
                  url:"update_pdf.php",  
                  method:"POST",  
                  data:{file:File,codeL:Log},
                  success:function(data){
                     console.log(data);
                     location.reload();
                  }
               });
         }

         function removeUpload() {
            location.reload();
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
            
         }
         $('.image-upload-wrap').bind('dragover', function () {
                  $('.image-upload-wrap').addClass('image-dropping');
               });
               $('.image-upload-wrap').bind('dragleave', function () {
                  $('.image-upload-wrap').removeClass('image-dropping');
         });

         
         
      </script>
</html>