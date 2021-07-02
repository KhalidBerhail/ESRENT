<?php

session_start();
if( !isset($_SESSION['username']) || $_SESSION['type'] != "normal" )
{
  header("location:../../homeP.php");
}
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
//recuperation du code du logement
$act="no";
$CodeL=$_GET["smr"];
if(isset($_GET["act"]))
$act=$_GET["act"];
////////////////System de vues////////////
$datenow = new DateTime();
$dateNow=$datenow->getTimestamp();
/*$datenow = new DateTime(date('Y-m-d'));
$dateNow = $datenow->format('Y-m-d');*/

$reqV = "INSERT INTO `log_vues`(`idL`, `date`) VALUES (?,?)";
$statementV=$conn->prepare($reqV);
$statementV->bind_param("ss",$CodeL,$dateNow);
$statementV->execute();

//////////////////////////////////////////
//recuperation des données du logement a travers son code
$reqL = "SELECT * from logement where CodeL=?";
$statementL=$conn->prepare($reqL);
$statementL->bind_param("i",$CodeL);
$statementL->execute();
$resL=$statementL->get_result();
$rowL=$resL->fetch_assoc();
$Titre=$rowL["nom"];
$adresse=$rowL["adress"];
$desc=$rowL["description"];
$regl=$rowL["reglement"];
$prix=$rowL["prix"];
$sup=$rowL["superficie"];
$Codepro=$rowL["CodeP"];
$type=$rowL["type"];
$lat=$rowL["lat"];
$lng=$rowL["lng"];
$lat_lng_empty="";
$collocation=$rowL["collocation"];
$pour_etudiant=$rowL["pour_etudiant"];
$etabe_proche=$rowL["etabe_proche"];

$etud_info='';
$colloc_info='';
if($lat==NULL ||$lng==NULL)
 {
   $lat_lng_empty="empty";
 }
else
{
   $lat_lng_empty="not_empty";
} 

if($type=="Appartement")
{
   $reqAp="SELECT * from appartement where Codeapp=?";
   $statementAp=$conn->prepare($reqAp);
   $statementAp->bind_param("i",$CodeL);
   $statementAp->execute();
   $resAp=$statementAp->get_result();
   $rowAp=$resAp->fetch_assoc();
   $nbrC=$rowAp["nbrC"];
   $nbrP=$rowAp["nbrP"];
}
else
{
   $reqAp="SELECT * FROM studio WHERE CodeS=?";
   $statementAp=$conn->prepare($reqAp);
   $statementAp->bind_param("i",$CodeL);
   $statementAp->execute();
   $resAp=$statementAp->get_result();
   $rowAp=$resAp->fetch_assoc();
   $nbrP=$rowAp["nbrP"];
}
if($collocation=='oui')
  {
   $colloc_info.="
   <h4>Colocation<br></h4>
   <p>Ce logement est une colocation qui peut étre partagé par ".$nbrP." personnes.</p>
  ";
  }
else 
  {
   $colloc_info="
   <h4>Colocation<br></h4>
   <p>Ce logement n'est pas une colocation</p>
  ";
  }  
if($pour_etudiant=='oui')
  {
     $etud_info.="<h4>Avis aux étudiants<br></h4>
     <p>Ce logement peut étre louer par les étudiants.<br></p>";
     if($etabe_proche!=''||$etabe_proche!=NULL)
      {
         $etud_info.="<p>Etablissement proche du logement: ".$etabe_proche."</p>";
      }
  }  
else  
{
   $etud_info.="<h4>Avis aux étudiants<br></h4>
     <p>Ce logement ne peut pas étre louer par les étudiants.<br></p>";
}
//recuperation des images du logement
$reqI="SELECT * FROM image where CodeL=?";
$statementI=$conn->prepare($reqI);
$statementI->bind_param("i",$CodeL);
$statementI->execute();
$resI=$statementI->get_result();
$img="";
$imgs="";
$i=1;
while ( ($rowI = mysqli_fetch_array($resI)) && ($i < 4) ) 
{
  $id=$rowI['CodeImg'];
  $src="genere_image.php?id=$id";
  if($i==1)
    {
      $img.="<div class='tab-pane active' id='pic-1'><img class='img-main' src='".$src."' alt='#' /></div>";
      $imgs.="<li class='active'><a data-target='#pic-1' data-toggle='tab'><img class='img-prev' src='".$src."' alt='#' /></a></li>";
    }
  else
    {
      $img.="<div class='tab-pane' id='pic-".$i."'><img class='img-main' src='".$src."' alt='#' /></div>";
      $imgs.=" <li><a data-target='#pic-".$i."' data-toggle='tab'><img class='img-prev' src='".$src."' alt='#' /></a></li>";
    }  


  $i = $i + 1;
}


//recuperation des données du prop 
$reqP="SELECT * from proprietaire where CodeP=?";
$statementP=$conn->prepare($reqP);
$statementP->bind_param("i",$Codepro);
$statementP->execute();
$resP=$statementP->get_result();
$rowP=$resP->fetch_assoc();

$Pnom=$rowP["nom"];
$Pprenom=$rowP["prenom"];
$numeroP=$rowP['tel'];
$numeroP_full=$numeroP;
$numeroP=substr($numeroP, 0, 3)."*******";


$reqP="SELECT * from utilisateur where CodeU=?";
$statementP=$conn->prepare($reqP);
$statementP->bind_param("i",$rowL["CodeP"]);
$statementP->execute();
$resP=$statementP->get_result();
$rowP=$resP->fetch_assoc();
$LU=$rowP["username"];
$emailP=$rowP['email'];
$email_full=$emailP;
$emailP=substr($emailP, 0, 3)."*******";

$srcP="";
if($rowP['imageP']!=NULL)
      {
        $srcP="profilpic.php?UN=$LU";
        
      }
    else
      {
        $srcP="../../Resourse/imgs/ProfileHolder.jpg";
       
      }

//Selection des 4 logements similaires
$srcC="";
$CodeO1=0;
$CodeO2=0;
$CodeO3=0;
$CodeO4=0;



$j=1;
$jR=1;
$fj=0;
$recom1="";
$recom2="";
$count=0;
$rest=4;
$prixR1=$prix+100;
$prixR2=$prix+300;
if($type=="Appartement")
{
 $reqC="SELECT count(*) as test from logement where CodeL!=? and (type='Appartement') and (prix<=?) and (CodeL in(SELECT Codeapp from appartement where nbrC=? and nbrP=?)) ";
 $statementC=$conn->prepare($reqC);
 $statementC->bind_param("idii",$CodeL,$prixR1,$nbrC,$nbrP);
 $statementC->execute();
 $resC=$statementC->get_result();
 $rowC=$resC->fetch_assoc();
 $count=$rowC['test'];
 if($count>=4)
   {     
      $reqC="SELECT * from logement where CodeL!=? and (type='Appartement') and (prix<=?) and (CodeL in(SELECT Codeapp from appartement where nbrC=? and nbrP=?)) order by Rand() LIMIT 4 ";
      $statementC=$conn->prepare($reqC);
      $statementC->bind_param("idii",$CodeL,$prixR1,$nbrC,$nbrP);
      $statementC->execute();
      $resC=$statementC->get_result();

      while ( ($rowC = mysqli_fetch_array($resC)) && ($j <= 4) ) 
        {
         //données du Logement courrant
         $CodeLC=$rowC['CodeL'];
         
         $srcC="genere_one_image.php?id=$CodeLC";
         $prixC=$rowC['prix'];
         $TitreC=$rowC['nom'];
         $adresseC=$rowC['adress'];
         $sprC=$rowC['superficie'];
         $descC=$rowC['description'];
         //récuperation nbr perssone et nbr chambres
         $reqApC="SELECT * from appartement where Codeapp=?";
         $statementApC=$conn->prepare($reqApC);
         $statementApC->bind_param("i",$CodeLC);
         $statementApC->execute();
         $resApC=$statementApC->get_result();
         $rowApC=$resApC->fetch_assoc();
         //nombre de perssones et chambres
         $nbrC2=$rowAp["nbrC"];
         $nbrP2=$rowAp["nbrP"];
         //recuparation des données du prop
         $reqPC="SELECT * from proprietaire where CodeP=?";
         $statementPC=$conn->prepare($reqPC);
         $statementPC->bind_param("i",$rowC["CodeP"]);
         $statementPC->execute();
         $resPC=$statementPC->get_result();
         $rowPC=$resPC->fetch_assoc(); 
         //données du prop
         $PprenomC=$rowPC['prenom'];
         $PnomC=$rowPC['nom'];
         //
              
 
         if($j<=2)
          {
           $recom1.= "<div class='col-md-6'>
                       <div class='small-box-c'>
                        <div class='small-img-b'>
                          <img class='img-responsives' src='".$srcC."' alt='#' />
                        </div> 
                        <div class='dit-t clearfix'>
                         <div class='left-ti'>
                          <h4>".$TitreC."</h4>
                          <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                         </div>
                         <a href='#' tabindex='0'>".$prixC."DH</a>
                        </div>
                        <div class='prod-btn'>
                         <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                         <p></p>
                        </div>
                       </div>
                      </div>";
            if($j==1)
            $CodeO1=$CodeLC;
            else if($j==2) 
            $CodeO2=$CodeLC;  

            $j=$j+1;
            
           } 
         else
          {
            $recom2.="<div class='col-md-6'>
                       <div class='small-box-c div-disp'>
                        <div class='small-img-b'>
                         <img class='img-responsives' src='".$srcC."' alt='#' />
                        </div> 
                        <div class='dit-t clearfix'>
                         <div class='left-ti'>
                         <h4>".$TitreC."</h4>
                         <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                         </div>
                         <a href='#' tabindex='0'>".$prixC."DH</a>
                        </div>
                        <div class='prod-btn'>   
                        <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                         <p></p>
                        </div>
                       </div>
                      </div>";
            if($j==3)
            $CodeO3=$CodeLC;
            else if($j==4) 
            $CodeO4=$CodeLC; 
                   
            $j=$j+1;

          }

            

        }

   }
 else if($count<4)
   {  
      $rest=$rest-$count;



      $reqC="SELECT * from logement where CodeL!=? and (type='Appartement') and (prix<=?) and (CodeL in(SELECT Codeapp from appartement where nbrC=? and nbrP=?))";
      $statementC=$conn->prepare($reqC);
      $statementC->bind_param("idii",$CodeL,$prixR1,$nbrC,$nbrP);
      $statementC->execute();
      $resC=$statementC->get_result();
      if($count==3)
       {
         while ( ($rowC = mysqli_fetch_array($resC)) && ($j<=3) )
           { 
              //données du Logement courrant
             $CodeLC=$rowC['CodeL'];            
             $srcC="genere_one_image.php?id=$CodeLC";
             $prixC=$rowC['prix'];
             $TitreC=$rowC['nom'];
             $adresseC=$rowC['adress'];
             $sprC=$rowC['superficie'];
             $descC=$rowC['description'];
             //récuperation nbr perssone et nbr chambres
             $reqApC="SELECT * from appartement where Codeapp=?";
             $statementApC=$conn->prepare($reqApC);
             $statementApC->bind_param("i",$CodeLC);
             $statementApC->execute();
             $resApC=$statementApC->get_result();
             $rowApC=$resApC->fetch_assoc();
             //nombre de perssones et chambres
             $nbrC2=$rowAp["nbrC"];
             $nbrP2=$rowAp["nbrP"];
             //recuparation des données du prop
             $reqPC="SELECT * from proprietaire where CodeP=?";
             $statementPC=$conn->prepare($reqPC);
             $statementPC->bind_param("i",$rowC["CodeP"]);
             $statementPC->execute();
             $resPC=$statementPC->get_result();
             $rowPC=$resPC->fetch_assoc(); 
             //données du prop
             $PprenomC=$rowPC['prenom'];
             $PnomC=$rowPC['nom'];

             if($j<=2)
               {
                  $recom1.="<div class='col-md-6'>
                  <div class='small-box-c div-disp'>
                   <div class='small-img-b'>
                    <img class='img-responsives' src='".$srcC."' alt='#' />
                   </div> 
                   <div class='dit-t clearfix'>
                    <div class='left-ti'>
                    <h4>".$TitreC."</h4>
                    <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                    </div>
                    <a href='#' tabindex='0'>".$prixC."DH</a>
                   </div>
                   <div class='prod-btn'>   
                   <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                    <p></p>
                   </div>
                  </div>
                 </div>";
                 if($j==1)
                 $CodeO1=$CodeLC;
                 else if($j==2) 
                 $CodeO2=$CodeLC;
                 $j=$j+1;
                 
               }
              else
               {
                  $recom2.="<div class='col-md-6'>
                       <div class='small-box-c div-disp'>
                        <div class='small-img-b'>
                         <img class='img-responsives' src='".$srcC."' alt='#' />
                        </div> 
                        <div class='dit-t clearfix'>
                         <div class='left-ti'>
                         <h4>".$TitreC."</h4>
                         <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                         </div>
                         <a href='#' tabindex='0'>".$prixC."DH</a>
                        </div>
                        <div class='prod-btn'>   
                          <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                         <p></p>
                        </div>
                       </div>
                      </div>";
                      
                  $CodeO3=$CodeLC;
                  $j=$j+1;
                  
                  
               }  
             
           }
       }
      else if($count==2) 
       {
         while ( ($rowC = mysqli_fetch_array($resC)) && ($j <= 2) )
          { 
             //données du Logement courrant
            $CodeLC=$rowC['CodeL'];
            $srcC="genere_one_image.php?id=$CodeLC";
            $prixC=$rowC['prix'];
            $TitreC=$rowC['nom'];
            $adresseC=$rowC['adress'];
            $sprC=$rowC['superficie'];
            $descC=$rowC['description'];
            //récuperation nbr perssone et nbr chambres
            $reqApC="SELECT * from appartement where Codeapp=?";
            $statementApC=$conn->prepare($reqApC);
            $statementApC->bind_param("i",$CodeLC);
            $statementApC->execute();
            $resApC=$statementApC->get_result();
            $rowApC=$resApC->fetch_assoc();
            //nombre de perssones et chambres
            $nbrC2=$rowAp["nbrC"];
            $nbrP2=$rowAp["nbrP"];
            //recuparation des données du prop
            $reqPC="SELECT * from proprietaire where CodeP=?";
            $statementPC=$conn->prepare($reqPC);
            $statementPC->bind_param("i",$rowC["CodeP"]);
            $statementPC->execute();
            $resPC=$statementPC->get_result();
            $rowPC=$resPC->fetch_assoc(); 
            //données du prop
            $PprenomC=$rowPC['prenom'];
            $PnomC=$rowPC['nom'];

            $recom1.="<div class='col-md-6'>
            <div class='small-box-c div-disp'>
              <div class='small-img-b'>
               <img class='img-responsives' src='".$srcC."' alt='#' />
              </div> 
              <div class='dit-t clearfix'>
               <div class='left-ti'>
                <h4>".$TitreC."</h4>
                <p>By <span>".$PprenomC." </span>".$PnomC."</p>
               </div>
               <a href='#' tabindex='0'>".$prixC."DH</a>
              </div>
              <div class='prod-btn'>   
                <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
               <p></p>
              </div>
             </div>
            </div>";
            if($j==1)
            $CodeO1=$CodeLC;
            else if($j==2)
            $CodeO2=$CodeLC;
            $j=$j+1;
            
          }
       }
      else if($count==1)
       {
         $rowC=$resC->fetch_assoc();
          //données du Logement courrant
          $CodeLC=$rowC['CodeL'];
          $srcC="genere_one_image.php?id=$CodeLC";
          $prixC=$rowC['prix'];
          $TitreC=$rowC['nom'];
          $adresseC=$rowC['adress'];
          $sprC=$rowC['superficie'];
          $descC=$rowC['description'];
          //récuperation nbr perssone et nbr chambres
          $reqApC="SELECT * from appartement where Codeapp=?";
          $statementApC=$conn->prepare($reqApC);
          $statementApC->bind_param("i",$CodeLC);
          $statementApC->execute();
          $resApC=$statementApC->get_result();
          $rowApC=$resApC->fetch_assoc();
          //nombre de perssones et chambres
          $nbrC2=$rowAp["nbrC"];
          $nbrP2=$rowAp["nbrP"];
          //recuparation des données du prop
          $reqPC="SELECT * from proprietaire where CodeP=?";
          $statementPC=$conn->prepare($reqPC);
          $statementPC->bind_param("i",$rowC["CodeP"]);
          $statementPC->execute();
          $resPC=$statementPC->get_result();
          $rowPC=$resPC->fetch_assoc(); 
          //données du prop
          $PprenomC=$rowPC['prenom'];
          $PnomC=$rowPC['nom']; 

          $recom1.="<div class='col-md-6'>
            <div class='small-box-c div-disp'>
              <div class='small-img-b'>
               <img class='img-responsives' src='".$srcC."' alt='#' />
              </div> 
              <div class='dit-t clearfix'>
               <div class='left-ti'>
                <h4>".$TitreC."</h4>
                <p>By <span>".$PprenomC." </span>".$PnomC."</p>
               </div>
               <a href='#' tabindex='0'>".$prixC."DH</a>
              </div>
              <div class='prod-btn'>   
              <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
               <p></p>
              </div>
             </div>
            </div>";
            $CodeO1=$CodeLC;

            $j=$j+1;
       } 

      //recuperation des logement secondaires d'aprés le prix
      $reqCR="SELECT * from logement where CodeL!=?  and CodeL!=? and CodeL!=? and CodeL!=? and (type='Appartement') and (prix<=?) order by Rand() LIMIT ".$rest;
      $statementCR=$conn->prepare($reqCR);
      $statementCR->bind_param("iiiid",$CodeL,$CodeO1,$CodeO2,$CodeO3,$prixR2);
      $statementCR->execute();
      $resCR=$statementCR->get_result();       
       while ( ($rowCR = mysqli_fetch_array($resCR)) && ($jR<=$rest) )
       {
          //données du Logement courrant
          $CodeLC=$rowCR['CodeL'];
          $srcC="genere_one_image.php?id=$CodeLC";
          $prixC=$rowCR['prix'];
          $TitreC=$rowCR['nom'];
          $adresseC=$rowCR['adress'];
          $sprC=$rowCR['superficie'];
          $descC=$rowCR['description'];
          //récuperation nbr perssone et nbr chambres
          $reqApC="SELECT * from appartement where Codeapp=?";
          $statementApC=$conn->prepare($reqApC);
          $statementApC->bind_param("i",$CodeLCR);
          $statementApC->execute();
          $resApC=$statementApC->get_result();
          $rowApC=$resApC->fetch_assoc();
          //nombre de perssones et chambres
          $nbrC2=$rowAp["nbrC"];
          $nbrP2=$rowAp["nbrP"];
          //recuparation des données du prop
          $reqPC="SELECT * from proprietaire where CodeP=?";
          $statementPC=$conn->prepare($reqPC);
          $statementPC->bind_param("i",$rowCR["CodeP"]);
          $statementPC->execute();
          $resPC=$statementPC->get_result();
          $rowPC=$resPC->fetch_assoc(); 
          //données du prop
          $PprenomC=$rowPC['prenom'];
          $PnomC=$rowPC['nom'];

          if($rest==1)
           {
            $recom2.="<div class='col-md-6'>
            <div class='small-box-c div-disp'>
             <div class='small-img-b'>
              <img class='img-responsives' src='".$srcC."' alt='#' />
             </div> 
             <div class='dit-t clearfix'>
              <div class='left-ti'>
              <h4>".$TitreC."</h4>
              <p>By <span>".$PprenomC." </span>".$PnomC."</p>
              </div>
              <a href='#' tabindex='0'>".$prixC."DH</a>
             </div>
             <div class='prod-btn'>   
               <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
              <p></p>
             </div>
            </div>
           </div>";


           $jR=$jR+1;
           }
          if($rest==2)
           {
            $recom2.="<div class='col-md-6'>
            <div class='small-box-c div-disp'>
             <div class='small-img-b'>
              <img class='img-responsives' src='".$srcC."' alt='#' />
             </div> 
             <div class='dit-t clearfix'>
              <div class='left-ti'>
              <h4>".$TitreC."</h4>
              <p> <span>".$PprenomC." </span>".$PnomC."</p>
              </div>
              <a href='#' tabindex='0'>".$prixC."DH</a>
             </div>
             <div class='prod-btn'>   
              <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
              <p></p>
             </div>
            </div>
           </div>";


           $jR=$jR+1;
           } 
          if($rest==3)
           {
            if($jR==1)
             {
               $recom1.="<div class='col-md-6'>
               <div class='small-box-c div-disp'>
                <div class='small-img-b'>
                 <img class='img-responsives' src='".$srcC."' alt='#' />
                </div> 
                <div class='dit-t clearfix'>
                 <div class='left-ti'>
                 <h4>".$TitreC."</h4>
                 <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                 </div>
                 <a href='#' tabindex='0'>".$prixC."DH</a>
                </div>
                <div class='prod-btn'>   
                  <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                 <p></p>
                </div>
               </div>
              </div>";


              $jR=$jR+1;
             }
             else
              {
               $recom2.="<div class='col-md-6'>
               <div class='small-box-c div-disp'>
                <div class='small-img-b'>
                 <img class='img-responsives' src='".$srcC."' alt='#' />
                </div> 
                <div class='dit-t clearfix'>
                 <div class='left-ti'>
                 <h4>".$TitreC."</h4>
                 <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                 </div>
                 <a href='#' tabindex='0'>".$prixC."DH</a>
                </div>
                <div class='prod-btn'>   
                  <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                 <p></p>
                </div>
               </div>
              </div>";

              $jR=$jR+1;
              }
           } 
       }     
   }
   
  

}
else if($type=="studio")
{
   $reqC="SELECT count(*) as test from logement where CodeL!=? and (type='studio') and (prix<=?) and (CodeL in(SELECT CodeS from studio where nbrP=?)) ";
   $statementC=$conn->prepare($reqC);
   $statementC->bind_param("idi",$CodeL,$prixR1,$nbrP);
   $statementC->execute();
   $resC=$statementC->get_result();
   $rowC=$resC->fetch_assoc();
   $count=$rowC['test'];
   if($count>=4)
     {     
        $reqC="SELECT * from logement where CodeL!=? and (type='studio') and (prix<=?) and (CodeL in(SELECT CodeS from studio where  nbrP=?)) order by Rand() LIMIT 4 ";
        $statementC=$conn->prepare($reqC);
        $statementC->bind_param("idi",$CodeL,$prixR1,$nbrP);
        $statementC->execute();
        $resC=$statementC->get_result();
  
        while ( ($rowC = mysqli_fetch_array($resC)) && ($j <= 4) ) 
          {
           //données du Logement courrant
           $CodeLC=$rowC['CodeL'];
           $srcC="genere_one_image.php?id=$CodeLC";
           $prixC=$rowC['prix'];
           $TitreC=$rowC['nom'];
           $adresseC=$rowC['adress'];
           $sprC=$rowC['superficie'];
           $descC=$rowC['description'];
           //récuperation nbr perssone et nbr chambres
           $reqApC="SELECT * from appartement where Codeapp=?";
           $statementApC=$conn->prepare($reqApC);
           $statementApC->bind_param("i",$CodeLC);
           $statementApC->execute();
           $resApC=$statementApC->get_result();
           $rowApC=$resApC->fetch_assoc();
           //nombre de perssones 
           $nbrP2=$rowAp["nbrP"];
           //recuparation des données du prop
           $reqPC="SELECT * from proprietaire where CodeP=?";
           $statementPC=$conn->prepare($reqPC);
           $statementPC->bind_param("i",$rowC["CodeP"]);
           $statementPC->execute();
           $resPC=$statementPC->get_result();
           $rowPC=$resPC->fetch_assoc(); 
           //données du prop
           $PprenomC=$rowPC['prenom'];
           $PnomC=$rowPC['nom'];
           //
                
   
           if($j<=2)
            {
             $recom1.= "<div class='col-md-6'>
                         <div class='small-box-c div-disp'>
                          <div class='small-img-b'>
                            <img class='img-responsives' src='".$srcC."' alt='#' />
                          </div> 
                          <div class='dit-t clearfix'>
                           <div class='left-ti'>
                            <h4>".$TitreC."</h4>
                            <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                           </div>
                           <a href='#' tabindex='0'>".$prixC."DH</a>
                          </div>
                          <div class='prod-btn'>
                           <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                           <p></p>
                          </div>
                         </div>
                        </div>";
              if($j==1)
              $CodeO1=$CodeLC;
              else if($j==2) 
              $CodeO2=$CodeLC;  
  
              $j=$j+1;
              
             } 
           else
            {
              $recom2.="<div class='col-md-6'>
                         <div class='small-box-c div-disp'>
                          <div class='small-img-b'>
                           <img class='img-responsives' src='".$srcC."' alt='#' />
                          </div> 
                          <div class='dit-t clearfix'>
                           <div class='left-ti'>
                           <h4>".$TitreC."</h4>
                           <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                           </div>
                           <a href='#' tabindex='0'>".$prixC."DH</a>
                          </div>
                          <div class='prod-btn'>   
                             <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                           <p></p>
                          </div>
                         </div>
                        </div>";
              if($j==3)
              $CodeO3=$CodeLC;
              else if($j==4) 
              $CodeO4=$CodeLC; 
                     
              $j=$j+1;
  
            }
  
              
  
          }
  
     }
   else if($count<4)
     {  
        $rest=$rest-$count;
  
  
  
        $reqC="SELECT * from logement where CodeL!=? and (type='studio') and (prix<=?) and (CodeL in(SELECT CodeS from studio where  nbrP=?))";
        $statementC=$conn->prepare($reqC);
        $statementC->bind_param("idi",$CodeL,$prixR1,$nbrP);
        $statementC->execute();
        $resC=$statementC->get_result();
        if($count==3)
         {
           while ( ($rowC = mysqli_fetch_array($resC)) && ($j<=3) )
             { 
                //données du Logement courrant
               $CodeLC=$rowC['CodeL'];
               $srcC="genere_one_image.php?id=$CodeLC";
               $prixC=$rowC['prix'];
               $TitreC=$rowC['nom'];
               $adresseC=$rowC['adress'];
               $sprC=$rowC['superficie'];
               $descC=$rowC['description'];
               //récuperation nbr perssone et nbr chambres
               $reqApC="SELECT * from appartement where Codeapp=?";
               $statementApC=$conn->prepare($reqApC);
               $statementApC->bind_param("i",$CodeLC);
               $statementApC->execute();
               $resApC=$statementApC->get_result();
               $rowApC=$resApC->fetch_assoc();
               //nombre de perssones 
               $nbrP2=$rowAp["nbrP"];
               //recuparation des données du prop
               $reqPC="SELECT * from proprietaire where CodeP=?";
               $statementPC=$conn->prepare($reqPC);
               $statementPC->bind_param("i",$rowC["CodeP"]);
               $statementPC->execute();
               $resPC=$statementPC->get_result();
               $rowPC=$resPC->fetch_assoc(); 
               //données du prop
               $PprenomC=$rowPC['prenom'];
               $PnomC=$rowPC['nom'];
  
               if($j<=2)
                 {
                    $recom1.="<div class='col-md-6'>
                    <div class='small-box-c div-disp'>
                     <div class='small-img-b'>
                      <img class='img-responsives' src='".$srcC."' alt='#' />
                     </div> 
                     <div class='dit-t clearfix'>
                      <div class='left-ti'>
                      <h4>".$TitreC."</h4>
                      <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                      </div>
                      <a href='#' tabindex='0'>".$prixC."DH</a>
                     </div>
                     <div class='prod-btn'>   
                        <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                      <p></p>
                     </div>
                    </div>
                   </div>";
                   if($j==1)
                   $CodeO1=$CodeLC;
                   else if($j==2) 
                   $CodeO2=$CodeLC;
                   $j=$j+1;
                   
                 }
                else
                 {
                    $recom2.="<div class='col-md-6'>
                         <div class='small-box-c div-disp'>
                          <div class='small-img-b'>
                           <img class='img-responsives' src='".$srcC."' alt='#' />
                          </div> 
                          <div class='dit-t clearfix'>
                           <div class='left-ti'>
                           <h4>".$TitreC."</h4>
                           <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                           </div>
                           <a href='#' tabindex='0'>".$prixC."DH</a>
                          </div>
                          <div class='prod-btn'>   
                             <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                           <p></p>
                          </div>
                         </div>
                        </div>";
                        
                    $CodeO3=$CodeLC;
                    $j=$j+1;
                    
                    
                 }  
               
             }
         }
        else if($count==2) 
         {
           while ( ($rowC = mysqli_fetch_array($resC)) && ($j <= 2) )
            { 
               //données du Logement courrant
              $CodeLC=$rowC['CodeL'];
              $srcC="genere_one_image.php?id=$CodeLC";
              $prixC=$rowC['prix'];
              $TitreC=$rowC['nom'];
              $adresseC=$rowC['adress'];
              $sprC=$rowC['superficie'];
              $descC=$rowC['description'];
              //récuperation nbr perssone et nbr chambres
              $reqApC="SELECT * from appartement where Codeapp=?";
              $statementApC=$conn->prepare($reqApC);
              $statementApC->bind_param("i",$CodeLC);
              $statementApC->execute();
              $resApC=$statementApC->get_result();
              $rowApC=$resApC->fetch_assoc();
              //nombre de perssones
              $nbrP2=$rowAp["nbrP"];
              //recuparation des données du prop
              $reqPC="SELECT * from proprietaire where CodeP=?";
              $statementPC=$conn->prepare($reqPC);
              $statementPC->bind_param("i",$rowC["CodeP"]);
              $statementPC->execute();
              $resPC=$statementPC->get_result();
              $rowPC=$resPC->fetch_assoc(); 
              //données du prop
              $PprenomC=$rowPC['prenom'];
              $PnomC=$rowPC['nom'];
  
              $recom1.="<div class='col-md-6'>
              <div class='small-box-c div-disp'>
                <div class='small-img-b'>
                 <img class='img-responsives' src='".$srcC."' alt='#' />
                </div> 
                <div class='dit-t clearfix'>
                 <div class='left-ti'>
                  <h4>".$TitreC."</h4>
                  <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                 </div>
                 <a href='#' tabindex='0'>".$prixC."DH</a>
                </div>
                <div class='prod-btn'>   
                  <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                 <p></p>
                </div>
               </div>
              </div>";
              if($j==1)
              $CodeO1=$CodeLC;
              else if($j==2)
              $CodeO2=$CodeLC;
              $j=$j+1;
              
            }
         }
        else if($count==1)
         {
           $rowC=$resC->fetch_assoc();
            //données du Logement courrant
            $CodeLC=$rowC['CodeL'];
            $srcC="genere_one_image.php?id=$CodeLC";
            $prixC=$rowC['prix'];
            $TitreC=$rowC['nom'];
            $adresseC=$rowC['adress'];
            $sprC=$rowC['superficie'];
            $descC=$rowC['description'];
            //récuperation nbr perssone et nbr chambres
            $reqApC="SELECT * from appartement where Codeapp=?";
            $statementApC=$conn->prepare($reqApC);
            $statementApC->bind_param("i",$CodeLC);
            $statementApC->execute();
            $resApC=$statementApC->get_result();
            $rowApC=$resApC->fetch_assoc();
            //nombre de perssones 
            $nbrP2=$rowAp["nbrP"];
            //recuparation des données du prop
            $reqPC="SELECT * from proprietaire where CodeP=?";
            $statementPC=$conn->prepare($reqPC);
            $statementPC->bind_param("i",$rowC["CodeP"]);
            $statementPC->execute();
            $resPC=$statementPC->get_result();
            $rowPC=$resPC->fetch_assoc(); 
            //données du prop
            $PprenomC=$rowPC['prenom'];
            $PnomC=$rowPC['nom']; 
  
            $recom1.="<div class='col-md-6'>
              <div class='small-box-c div-disp'>
                <div class='small-img-b'>
                 <img class='img-responsives' src='".$srcC."' alt='#' />
                </div> 
                <div class='dit-t clearfix'>
                 <div class='left-ti'>
                  <h4>".$TitreC."</h4>
                  <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                 </div>
                 <a href='#' tabindex='0'>".$prixC."DH</a>
                </div>
                <div class='prod-btn'>   
                   <a id='".$j."'><i class='far fa-heart'></i> enregistrer</a>
                 <p></p>
                </div>
               </div>
              </div>";
              $CodeO1=$CodeLC;
  
              $j=$j+1;
         } 
  
        //recuperation des logement secondaires d'aprés le prix
        $reqCR="SELECT * from logement where CodeL!=?  and CodeL!=? and CodeL!=? and CodeL!=? and (type='studio') and (prix<=?) order by Rand() LIMIT ".$rest;
        $statementCR=$conn->prepare($reqCR);
        $statementCR->bind_param("iiiid",$CodeL,$CodeO1,$CodeO2,$CodeO3,$prixR2);
        $statementCR->execute();
        $resCR=$statementCR->get_result();       
         while ( ($rowCR = mysqli_fetch_array($resCR)) && ($jR<=$rest) )
         {
            //données du Logement courrant
            $CodeLC=$rowCR['CodeL'];
            $srcC="genere_one_image.php?id=$CodeLC";
            $prixC=$rowCR['prix'];
            $TitreC=$rowCR['nom'];
            $adresseC=$rowCR['adress'];
            $sprC=$rowCR['superficie'];
            $descC=$rowCR['description'];
            //récuperation nbr perssone et nbr chambres
            $reqApC="SELECT * from appartement where Codeapp=?";
            $statementApC=$conn->prepare($reqApC);
            $statementApC->bind_param("i",$CodeLCR);
            $statementApC->execute();
            $resApC=$statementApC->get_result();
            $rowApC=$resApC->fetch_assoc();
            //nombre de perssones 
            $nbrP2=$rowAp["nbrP"];
            //recuparation des données du prop
            $reqPC="SELECT * from proprietaire where CodeP=?";
            $statementPC=$conn->prepare($reqPC);
            $statementPC->bind_param("i",$rowCR["CodeP"]);
            $statementPC->execute();
            $resPC=$statementPC->get_result();
            $rowPC=$resPC->fetch_assoc(); 
            //données du prop
            $PprenomC=$rowPC['prenom'];
            $PnomC=$rowPC['nom'];
  
            if($rest==1)
             {
              $recom2.="<div class='col-md-6'>
              <div class='small-box-c div-disp'>
               <div class='small-img-b'>
                <img class='img-responsives' src='".$srcC."' alt='#' />
               </div> 
               <div class='dit-t clearfix'>
                <div class='left-ti'>
                <h4>".$TitreC."</h4>
                <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                </div>
                <a href='#' tabindex='0'>".$prixC."DH</a>
               </div>
               <div class='prod-btn'>   
                   <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                <p></p>
               </div>
              </div>
             </div>";
  
  
             $jR=$jR+1;
             }
            if($rest==2)
             {
              $recom2.="<div class='col-md-6'>
              <div class='small-box-c div-disp'>
               <div class='small-img-b'>
                <img class='img-responsives' src='".$srcC."' alt='#' />
               </div> 
               <div class='dit-t clearfix'>
                <div class='left-ti'>
                <h4>".$TitreC."</h4>
                <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                </div>
                <a href='#' tabindex='0'>".$prixC."DH</a>
               </div>
               <div class='prod-btn'>   
                  <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                <p></p>
               </div>
              </div>
             </div>";
  
  
             $jR=$jR+1;
             } 
            if($rest==3)
             {
              if($jR==1)
               {
                 $recom1.="<div class='col-md-6'>
                 <div class='small-box-c div-disp'>
                  <div class='small-img-b'>
                   <img class='img-responsives' src='".$srcC."' alt='#' />
                  </div> 
                  <div class='dit-t clearfix'>
                   <div class='left-ti'>
                   <h4>".$TitreC."</h4>
                   <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                   </div>
                   <a href='#' tabindex='0'>".$prixC."DH</a>
                  </div>
                  <div class='prod-btn'>   
                  <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                   <p></p>
                  </div>
                 </div>
                </div>";
  
  
                $jR=$jR+1;
               }
               else
                {
                 $recom2.="<div class='col-md-6'>
                 <div class='small-box-c div-disp'>
                  <div class='small-img-b'>
                   <img class='img-responsives' src='".$srcC."' alt='#' />
                  </div> 
                  <div class='dit-t clearfix'>
                   <div class='left-ti'>
                   <h4>".$TitreC."</h4>
                   <p>By <span>".$PprenomC." </span>".$PnomC."</p>
                   </div>
                   <a href='#' tabindex='0'>".$prixC."DH</a>
                  </div>
                  <div class='prod-btn'>   
                  <a id='".$jR."'><i class='far fa-heart'></i> enregistrer</a>
                   <p></p>
                  </div>
                 </div>
                </div>";
  
                $jR=$jR+1;
                }
             } 
         }     
     }
}



///////////////////////////////////////////// Messagrie /////////////////////////////////////////////

$msg = "";
$userCode = $_SESSION['usercode'];


//checking if user has rated this article
$rt1="";
$rt2="";
$Alrd=0;
$reqCR="SELECT * from ratings WHERE CodeL=? and CodeU=?";
$statementCR=$conn->prepare($reqCR);
$statementCR->bind_param("ii",$CodeL,$userCode);
$statementCR->execute();
$resCR=$statementCR->get_result();
if(($rowCR=$resCR->fetch_assoc()))
{
   $rt1="<h3>Vous avez évalué ".$rowCR['rating']." étoiles</h3>";
   $Alrd=1;

}
else
{
   $rt2="<a id='RTM' data-toggle='modal' data-target='#modalLikeThis2'><i class='far fa-star'></i> Évaluer</a>";
   $Alrd=0;
}

//affichage du rating
   


 //equipements
$reqEQ="SELECT count(*) as EQ from eqlo where CodeL=?";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->bind_param("i",$CodeL);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();
$rowEQ=$resEQ->fetch_assoc();

$nbrEQ=$rowEQ['EQ'];

$eqn=1;
$equiprv1="";
$equiprv2="";
$equiLST="";
$equiLST2="";
$CodeE="";

$reqEQAT="SELECT * from autre_equi  where CodeL=? ";
$statementEQAT=$conn->prepare($reqEQAT);
$statementEQAT->bind_param("i",$CodeL);
$statementEQAT->execute();
$resEQAT=$statementEQAT->get_result();
$eqTitre='';
$eqDesc='';
while($rowEQAT=mysqli_fetch_array($resEQAT))
{
   $eqTitre=$rowEQAT['titre'];
   $eqDesc=$rowEQAT['description'];

   $equiLST2.="  <hr>
   <div class='_czm8crp'>".$eqTitre."</div> 
   <div class='_1jlnvra2'>".$eqDesc."</div>";
   $nbrEQ=$nbrEQ+1;
}

$reqEQ="SELECT CodeE from equipement  where nom='Wi-Fi' ";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();
$rowEQ=$resEQ->fetch_assoc();
$CodeWifi=$rowEQ['CodeE'];

$reqEQ="SELECT CodeE from equipement  where nom='Detecteur de fumée' ";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();
$rowEQ=$resEQ->fetch_assoc();
$CodeDF=$rowEQ['CodeE'];

$reqEQ="SELECT CodeE from equipement  where nom='Chauff-eau' ";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();
$rowEQ=$resEQ->fetch_assoc();
$CodeCffA=$rowEQ['CodeE'];

$reqEQ="SELECT CodeE from equipement  where nom='Climatisation' ";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();
$rowEQ=$resEQ->fetch_assoc();
$CodeClim=$rowEQ['CodeE'];




$reqEQ="SELECT * from eqlo  where CodeL=? and (CodeE=? or CodeE=? or CodeE=? or CodeE=?) ";
$statementEQ=$conn->prepare($reqEQ);
$statementEQ->bind_param("iiiii",$CodeL,$CodeWifi,$CodeDF,$CodeCffA,$CodeClim);
$statementEQ->execute();
$resEQ=$statementEQ->get_result();

$wf=FALSE;
$clm=FALSE;
$df=FALSE;
$ce=FALSE;

while(($rowEQ=mysqli_fetch_array($resEQ)) )
 {
  
   $reqEQs="SELECT * from equipement  where CodeE=? ";
   $statementEQs=$conn->prepare($reqEQs);
   $statementEQs->bind_param("i",$rowEQ['CodeE']);
   $statementEQs->execute();
   $resEQs=$statementEQs->get_result();
   $rowEQs=$resEQs->fetch_assoc();
    
    if(strcmp($rowEQs['nom'], "Wi-Fi")==0)
      {
         $wf=TRUE;
      }
    else if(strcmp($rowEQs['nom'],"Climatisation")==0) 
      {
         $clm=TRUE;
      }
    else if(strcmp($rowEQs['nom'],"Detecteur de fumée")==0) 
      {
         $df=TRUE;
      }
    else if(strcmp($rowEQs['nom'],"Chauff-eau")==0) 
      {
         $ce=TRUE;         
      }        
 }

 if($wf==TRUE)
  {
   $equiprv1.= " <div class='col-md-4'>
   <h4><img src='../../Resourse/imgs/equipements/wifi.png' />&nbsp;Wi-Fi</h4>
  </div>";
  }
 else if($wf==FALSE) 
  {
   $equiprv1.= " <div class='col-md-4'>
   <h4><p><s><img src='../../Resourse/imgs/equipements/wifi.png' />&nbsp;Wi-Fi</s></p></h4>
  </div>";
  }

  if($clm==TRUE)
  {
   $equiprv1.= " <div class='col-md-4'>
   <h4><img src='../../Resourse/imgs/equipements/air-conditioner.png' />&nbsp;Climatisation</h4>
  </div>";
  }
  
 else if($clm==FALSE) 
  {
   $equiprv1.= " <div class='col-md-4'>
   <h4><p><strike><img src='../../Resourse/imgs/equipements/air-conditioner.png' />&nbsp;Climatisation</strike></p></h4>
  </div>";
  }

  if($df==TRUE)
  {
   $equiprv2.= " <div class='col-md-4'>
   <h4><img src='../../Resourse/imgs/equipements/smoke-detector.png' />&nbsp;Detecteur de fumée</h4>
  </div>";
  }
 else if($df==FALSE) 
  {
   $equiprv2.= " <div class='col-md-4'>
   <h4><p><s><img src='../../Resourse/imgs/equipements/smoke-detector.png' />&nbsp;Detecteur de fumée</s></p></h4>
  </div>";
  }

  if($ce==TRUE)
  {
   $equiprv2.= " <div class='col-md-4'>
   <h4><img src='../../Resourse/imgs/equipements/water-heater.png' />&nbsp;Chauff-eau</h4>
  </div>";
  }
 else if($ce==FALSE) 
  {
   $equiprv2.= " <div class='col-md-4'>
   <h4><p><s><img src='../../Resourse/imgs/equipements/water-heater.png' />&nbsp;Chauff-eau</s></p></h4>
  </div>";
  }



 $reqEQ2="SELECT * from eqlo  where CodeL=? ";
 $statementEQ2=$conn->prepare($reqEQ2);
 $statementEQ2->bind_param("i",$CodeL);
 $statementEQ2->execute();
 $resEQ2=$statementEQ2->get_result();

 while(($rowEQ2=mysqli_fetch_array($resEQ2)) )
 {
   $CodeE=$rowEQ2['CodeE'];
   $reqEQS="SELECT * from equipement  where CodeE=? ";
   $statementEQS=$conn->prepare($reqEQS);
   $statementEQS->bind_param("i",$CodeE);
   $statementEQS->execute();
   $resEQS=$statementEQS->get_result();
   $rowEQS=$resEQS->fetch_assoc();
   $nomEQ=$rowEQS['nom'];
   
   $equiLST.="<hr>
              <div class='_czm8crp'>".$nomEQ."</div> 
              <div class='_1jlnvra2'>Description d'equipement</div>";
  
 }

//rating data 
$reqRI="SELECT rating,COUNT(*) as num from ratings where CodeL=? GROUP BY rating";
$statementRI=$conn->prepare($reqRI);
$statementRI->bind_param("i",$CodeL);
$statementRI->execute();
$resRI=$statementRI->get_result();
$nbr5S=0;
$nbr4S=0;
$nbr3S=0;
$nbr2S=0;
$nbr1S=0;
while(($rowRI=mysqli_fetch_array($resRI)) )
{
   $currentC=$rowRI['rating'];
   $currentN=$rowRI['num'];

   if($currentC==5)
    {
     $nbr5S=$currentN;
    }
   if($currentC==4)
    {
     $nbr5S=$currentN;
    }    
   if($currentC==3)
    {
     $nbr5S=$currentN;
    }
   if($currentC==2)
    {
     $nbr5S=$currentN;
    } 
   if($currentC==1)
    {
     $nbr5S=$currentN;
    } 



 }

 $reqRI="SELECT rating From logement where CodeL=?";
 $statementRI=$conn->prepare($reqRI);
 $statementRI->bind_param("i",$CodeL);
 $statementRI->execute();
 $resRI=$statementRI->get_result();
 $rowRI=$resRI->fetch_assoc();
 $OvrRating=$rowRI['rating'];
 

 $wholeR = floor($OvrRating);      
 $fractionR = $OvrRating - $wholeR;
 $ir=0;
 $stars="";
 $countSTR=5-$wholeR;
 while($ir<$wholeR)
 {
    $stars.="<span ><i class='fas fa-star'></i></span>";
    $ir++;
 }

 if($fractionR>=0.8 && $fractionR<=0.9)
  {
    $stars.="<span ><i class='fas fa-star'></i></span>";
    $countSTR=$countSTR-1;
  }
 else if($fractionR>0.2 && $fractionR<0.8)
  {
    $stars.="<span ><i class='fas fa-star-half-alt'></i></span>";
    $countSTR=$countSTR-1;
  }
  else if($fractionR<=0.2 && $fractionR>0.8)
  {
     $stars.="<span ><i class='fas fa-star'></i></span>";
    $countSTR=$countSTR-1;
  }

  $ir=0;
  while($ir<$countSTR)
  {
     $stars.="<span ><i class='far fa-star'></i></span>";
     $ir++;
  }

 $reqRI="SELECT count(*) as ttl From ratings where CodeL=?";
 $statementRI=$conn->prepare($reqRI);
 $statementRI->bind_param("i",$CodeL);
 $statementRI->execute();
 $resRI=$statementRI->get_result();
 $rowRI=$resRI->fetch_assoc();
 $nbrRt=$rowRI['ttl'];
 $nbrRt=$nbrRt;



//Check if this user has this saved
$saved='';
$reqS="SELECT count(*) as cntS from saves where CodeL=? and CodeU=?";
$statementS=$conn->prepare($reqS);
$statementS->bind_param("ii",$CodeL,$userCode);
$statementS->execute();
$resS=$statementS->get_result();

if(($rowS=$resS->fetch_assoc()))
  {
   if($rowS['cntS']!=0)  
     {
       $saved='Y';
     }   
    else if($rowS['cntS']==0)
     {
       $saved='N';
     } 
  }
  

//loading first page of comments comments 
$Comments="";

$reqCMC="SELECT count(*) as nbrCMT from ratings where CodeL=? and comment IS NOT NULL ";
$statementCMC=$conn->prepare($reqCMC);
$statementCMC->bind_param("i",$CodeL);
$statementCMC->execute();
$resCMC=$statementCMC->get_result(); 
$rowCMC=$resCMC->fetch_assoc();
$nbrCMT=$rowCMC['nbrCMT'];

if($nbrCMT==0)
{
   $Comments="<div id='no-cmt-img'><img src='../../Resourse/imgs/userimgs/noComment.png'></div><br><h3 id='no-cmt-txt'>Pas de commantaires</h3><hr>";
}
else{
$reqCM="SELECT * from ratings where CodeL=? and comment is not null";
$statementCM=$conn->prepare($reqCM);
$statementCM->bind_param("i",$CodeL);
$statementCM->execute();
$resCM=$statementCM->get_result();  
while(($rowCM=mysqli_fetch_array($resCM)))
 {
    $CodeCU=$rowCM['CodeU'];
    $comment=$rowCM['comment'];
    $rating=$rowCM['rating'];

    $reqCMU="SELECT * from utilisateur where CodeU=?";
    $statementCMU=$conn->prepare($reqCMU);
    $statementCMU->bind_param("i",$CodeCU);
    $statementCMU->execute();
    $resCMU=$statementCMU->get_result(); 
    $rowCMU=$resCMU->fetch_assoc(); 

    $UserCMU=$rowCMU['username'];
    
    if($rowCMU['imageP']!=NULL)
      {
        $srcCMU="profilpic.php?UN=$UserCMU";
        $ProfilePCMU="<img src='".$srcCMU."' class='img img-rounded img-fluid'/>";
      }
    else
      {
        $srcCMU="../../Resourse/imgs/ProfileHolder.jpg";
        $ProfilePCMU="<img src='".$srcCMU."' class='img img-rounded img-fluid'/>";
      }
    
      $OvrRatingCMU=$rowCM['rating'];


      $wholeRCMU = floor($OvrRatingCMU);      
      $fractionRCMU = $OvrRatingCMU - $wholeRCMU;
      $irCMU=0;
      $starsCMU="";
      $countSTRCMU=5-$wholeRCMU;
      while($irCMU<$wholeRCMU)
      {
         $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
         $irCMU++;
      }
    
      if($fractionRCMU>=0.8 && $fractionRCMU<=0.9)
       {
         $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
         $countSTRCMU=$countSTRCMU-1;
       }
      else if($fractionRCMU>0.2 && $fractionRCMU<0.8)
       {
         $starsCMU.="<span class='float-right'><i class='text-warning fas fa-star-half'></i></span>";
         $countSTRCMU=$countSTRCMU-1;
       }
       else if($fractionRCMU<=0.2 && $fractionRCMU>0.8)
       {
          $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
         $countSTRCMU=$countSTRCMU-1;
       }
    
       $irCMU=0;
       while($irCMU<$countSTRCMU)
       {
          $starsCMU.="<span class='float-right'><i class='text-warning far fa-star'></i></span>";
          $irCMU++;
       }  







   $Comments.= "
                <div class='row'>
                  <div class='col-md-2'>".$ProfilePCMU."</div>
                 <div class='col-md-10'>
                   <p>
                   <a class='float-left' ><strong>".$UserCMU."</strong></a>
                   
                    ".$starsCMU."

                    </p>
                   
                     <p>".$comment."</p>
                     
                   </div>
                 </div>
                
                
                <hr class='cmt'>";
 }
}
/*<a class="float-right btn btn-outline-primary ml-2"> <i class="fa fa-reply"></i> Reply</a>
<a class="float-right btn text-white btn-danger"> <i class="fa fa-heart"></i> Like</a>*/

 //count number of comment pages
 $reqCM="SELECT Count(*) as cnt from ratings where CodeL=?";
 $statementCM=$conn->prepare($reqCM);
 $statementCM->bind_param("i",$CodeL);
 $statementCM->execute();
 $resCM=$statementCM->get_result();  
 $rowCM=$resCM->fetch_assoc();
 $pages=$rowCM['cnt'];

 //count number of saves
 $reqs="SELECT Count(*) as cntS from saves where CodeL=?";
 $statements=$conn->prepare($reqs);
 $statements->bind_param("i",$CodeL);
 $statements->execute();
 $ress=$statements->get_result();  
 
if(($rows=$ress->fetch_assoc()))
  {
     $nbrsaves=$rows['cntS'];
  }
//Selecting 5 random profile pictures of users who comented on this 
$cmt_Lst="";
   
$req5U="SELECT CodeU  from ratings where CodeL=? order by RAND() LIMIT 5";
$statement5U=$conn->prepare($req5U);
$statement5U->bind_param("i",$CodeL);
$statement5U->execute();
$res5U=$statement5U->get_result(); 
while(($row5U=mysqli_fetch_array($res5U)))
 {
   $CodeU_LST=$row5U['CodeU'];
   $reqCMU="SELECT * from utilisateur where CodeU=?";
   $statementCMU=$conn->prepare($reqCMU);
   $statementCMU->bind_param("i",$CodeU_LST);
   $statementCMU->execute();
   $resCMU=$statementCMU->get_result(); 
   $rowCMU=$resCMU->fetch_assoc(); 

   $UserCMU=$rowCMU['username'];
   
   if($rowCMU['imageP']!=NULL)
     {
       $src_Lst="profilpic.php?UN=$UserCMU";
       $cmt_Lst.=" <li>
                    <div class='im-b'><img class='' src='$src_Lst' alt=''></div>
                   </li>";
     }
   else
     {
       $src_Lst="../../Resourse/imgs/ProfileHolder.jpg";
       $cmt_Lst.=" <li>
                    <div class='im-b'><img class='' src='$src_Lst' alt=''></div>
                   </li>";
     }
   


   

 } 

 // checking if the user saved the recomended products

 $SavedL1='';
 $SavedL2='';
 $SavedL3='';
 $SavedL4='';

$reqSR="SELECT count(*) as cntS from saves where CodeL=? and CodeU=?";
$statementSR=$conn->prepare($reqSR);
$statementSR->bind_param("ii",$CodeO1,$userCode);
$statementSR->execute();
$resSR=$statementSR->get_result();

if(($rowSR=$resSR->fetch_assoc()))
  {
   if($rowSR['cntS']!=0)  
     {
       $SavedL1='Y';
     }   
    else if($rowSR['cntS']==0)
     {
       $SavedL1='N';
     } 
  }

$reqSR="SELECT count(*) as cntS from saves where CodeL=? and CodeU=?";
$statementSR=$conn->prepare($reqSR);
$statementSR->bind_param("ii",$CodeO2,$userCode);
$statementSR->execute();
$resSR=$statementSR->get_result();

if(($rowSR=$resSR->fetch_assoc()))
  {
   if($rowSR['cntS']!=0)  
     {
       $SavedL2='Y';
     }   
    else if($rowSR['cntS']==0)
     {
       $SavedL2='N';
     } 
  }

$reqSR="SELECT count(*) as cntS from saves where CodeL=? and CodeU=?";
$statementSR=$conn->prepare($reqSR);
$statementSR->bind_param("ii",$CodeO3,$userCode);
$statementSR->execute();
$resSR=$statementSR->get_result();

if(($rowSR=$resSR->fetch_assoc()))
  {
   if($rowSR['cntS']!=0)  
     {
       $SavedL3='Y';
     }   
    else if($rowSR['cntS']==0)
     {
       $SavedL3='N';
     } 
  }

$reqSR="SELECT count(*) as cntS from saves where CodeL=? and CodeU=?";
$statementSR=$conn->prepare($reqSR);
$statementSR->bind_param("ii",$CodeO4,$userCode);
$statementSR->execute();
$resSR=$statementSR->get_result();

if(($rowSR=$resSR->fetch_assoc()))
  {
   if($rowSR['cntS']!=0)  
     {
       $SavedL4='Y';
     }   
    else if($rowSR['cntS']==0)
     {
       $SavedL4='N';
     } 
  }



  
  $liste_locat="";
  $chatboxs="";
  $openclosejs=" checked = null;";
  $ScriptMsg="";
  $UISc='setInterval(function() {
   showdata="";';
   $jsScript="";

   $i=1;
   
  $reqSRS="SELECT *  from `liste_locataire` where CodeL=? ";
  $statementSRS=$conn->prepare($reqSRS);
  $statementSRS->bind_param("i",$CodeL);
  $statementSRS->execute();
  $resSRS=$statementSRS->get_result();
  $users_found=$resSRS->num_rows;
  while(($rowSRS=mysqli_fetch_array($resSRS)))

  {
   
   $CodeSRSU=$rowSRS['Code_Locataire'];
   $reqSRS="SELECT *  from `utilisateur` where CodeU=? ";
   $statementSRS=$conn->prepare($reqSRS);
   $statementSRS->bind_param("i",$CodeSRSU);
   $statementSRS->execute();
   $resSRS1=$statementSRS->get_result();
   $rowSRS1=$resSRS1->fetch_assoc();
   $UserSRS1=$rowSRS1['username'];
   
   if($rowSRS1['imageP']!=NULL)
      {
        $srcSRS1="profilpic.php?UN=$UserSRS1";
        $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
      }
    else
      {
        $srcSRS1="../../Resourse/imgs/ProfileHolder.jpg";
        $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
      }

     $liste_locat.= "<li class='user-item'>
     <span class='avatar'>
         $ProfilePSRS1
     </span>
     <h5>".$UserSRS1."</h5>
     <h6>".$UserSRS1."</h6>";
     if($rowSRS1['CodeU']!=$userCode)
     {
      $liste_locat.=  "<a  class='btn-fllw' id='a".$CodeSRSU."' >Contacter</a>
        </li>";
      }
      else{
         $liste_locat.=  "</li>";
      }

    

   $chatboxs=$chatboxs.
   '
   <section class="avenue-messenger" id="Chat'.$CodeSRSU.'" style="display:none">
   <div class="menu">
      <div class="button" id="CloseChat'.$CodeSRSU.'" title="End Chat">&#10005;</div> 
   </div>
   <div class="agent-face">
      <div class="half">
      <img class="agent circle" src="'.$srcSRS1.'" >
      </div>
   </div>
   <div class="chat" >
      <div class="chat-title">
      <h1>'.$UserSRS1.'
      </div>
      <div class="messages" >
      <div id="'.$CodeSRSU.'" class="messages-content mCustomScrollbar _mCS_1 mCS_no_scrollbar" >
 
      </div>
      </div>
      <div class="message-box">
         <textarea type="text" id="input'.$CodeSRSU.'" class="message-input" placeholder="Type message..."></textarea>
         <button type="submit" id="send'.$CodeSRSU.'" class="message-submit">Send</button>
      </div>
   </div>
 </section>
   ';
   $openclosejs = $openclosejs.
  "
  $('#a".$CodeSRSU."').click(function(){
    if(checked!=null)
      checked.style='display:none';
      document.getElementById('Chat').style.display='none';
      document.getElementById('Chat".$CodeSRSU."').style='display:block';
      checked=document.getElementById('Chat".$CodeSRSU."');
      updateScrollbar();
    });
    $('#CloseChat".$CodeSRSU."').click(function(){
      document.getElementById('Chat".$CodeSRSU."').style='display:none';
      checked=null;
    });
  ";

  $ScriptMsg = $ScriptMsg.
  '

  $("#send'.$CodeSRSU.'").click(function() {
    $msgtosend=$("#input'.$CodeSRSU.'").val();
    insertMessage("'.$CodeSRSU.'");
    $.ajax({  
          url:"chatbox.php",  
          method:"GET",  
          data:{message:$msgtosend,sender:'.$userCode.',reciever:'.$CodeSRSU.'}
          });
 });

  ';

  $i=$i+1;
  $mCSB_container="'#mCSB_".$i."_container'";
  
  
  $UISc=$UISc.
  '
  $.ajax({  
    url:"chatmsg.php",  
    method:"GET",  
    data:{sender:'.$userCode.',reciever:'.$CodeSRSU.'},  
    success:function(data){
      if(showdata!=data)
      {
        showdata=data;
        $('.$mCSB_container.').html(data);
      }
    }  
  });
  ';



  }

  $Msgclass='"message message-personal"';


$UISc = $UISc.'updateScrollbar(); 
}, 1000);';
$jsScript = "<script>".$openclosejs.$ScriptMsg."</script>";

?>

<!DOCTYPE HTML>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Voire plus</title>

      <!--enable mobile device-->

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--fontawesome css-->
      
      <link rel="stylesheet" href="../../Resourse/cssSm/font-awesome.min.css">
      <!--bootstrap css-->
      <link rel="stylesheet" href="../../Resourse/cssSm/bootstrap.min.css">
      <!--animate css-->
      <link rel="stylesheet" href="../../Resourse/cssSm/animate-wow.css">
      <!--main css-->
      
      <link rel="stylesheet" href="../../Resourse/cssSm/bootstrap-select.min.css">
      <link href="../../Resourse/cssSm/ratingBot/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <link rel="stylesheet" href="../../Resourse/cssSm/slick.min.css">
      <link rel="stylesheet" href="../../Resourse/cssSm/select2.min.css">
      <link rel="stylesheet" href="../../Resourse/cssSm/style.css">
      <!--responsive css-->
      <link rel="stylesheet" href="../../Resourse/cssSm/responsive.css">
      <link href="../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
      
      <!--ChatBox-->
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>
      <link rel="stylesheet" href="../../Resourse/css3/chatbox.css">
      <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
      
      <link rel="stylesheet" href="../../Resourse/Allfooters/Style.css">

      <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
  
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
  <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
<script type="text/javascript" >window.ENV_VARIABLE = 'https://developer.here.com'</script><script src='https://developer.here.com/javascript/src/iframeheight.js'></script>

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
                        <a href="#" class="navbar-brand"><img src="../../Resourse/images/logo-1.png" alt="" /></a>
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
                     <h2 class="titreOne"><?=$Titre?></h2>
                     <p>Par <span><?=$Pnom?> <?=$Pprenom?></span></p>
                  </div>
               </div>
            </div>
            <div class="row" >
              
               <div class="col-md-7 col-sm-8">
                  <div class="md-prod-page">
                     <div class="md-prod-page-in">
                        <div class="page-preview">
                           <div class="preview">

                              <div class="preview-pic tab-content">
                                 <?=$img?>
                                 <!--
                                 <div class="tab-pane active" id="pic-1"><img src="../../Resourse/images/lag-60.png" alt="#" /></div>
                                 <div class="tab-pane" id="pic-2"><img src="../../Resourse/images/lag-61.png" alt="#" /></div>
                                 <div class="tab-pane" id="pic-3"><img src="../../Resourse/images/lag-60.png" alt="#" /></div>
                                 
                                 <div class="tab-pane" id="pic-4"><img src="../../Resourse/images/lag-61.png" alt="#" /></div>
                                 <div class="tab-pane" id="pic-5"><img src="../../Resourse/images/lag-61.png" alt="#" /></div>-->

                              </div>

                              <ul class="preview-thumbnail nav nav-tabs">

                                <?=$imgs?>
                                 <!--
                                 <li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="../../Resourse/images/lag-60.png" alt="#" /></a></li>
                                 <li><a data-target="#pic-2" data-toggle="tab"><img src="../../Resourse/images/lag-61.png" alt="#" /></a></li>
                                 <li><a data-target="#pic-3" data-toggle="tab"><img src="../../Resourse/images/lag-60.png" alt="#" /></a></li>
                                 
                                 <li><a data-target="#pic-4" data-toggle="tab"><img src="../../Resourse/images/lag-61.png" alt="#" /></a></li>
                                 <li><a data-target="#pic-5" data-toggle="tab"><img src="../../Resourse/images/lag-61.png" alt="#" /></a></li>
                                 -->
                              </ul>
                           </div>
                        </div>
                        </div>

                        <div id="cnt-hote-2" class="btn-dit-list clearfix">
                           <div class="left-dit-p">
                             <h4>Prix:  <?=$prix?> Dh</h4>
                         
                           </div>
                           <div class="right-dit-p">
                             <a  class="badge badge-primary ChatPro">Contacter Hote</a>
                           </div>
                        </div> 

                        <div class="btn-dit-list clearfix">
                           <div style="margin-left: 1;margin-right: 30%;" class="left-dit-p">
                              <div class="prod-btn">
                                 <?=$rt2?>
                                 <a id="like-btn"><i class="far fa-heart ff"></i> save this</a>
                                 <p><?=$nbrsaves?> personnes ont enregistrer cet logement </p>
                                <!-- <a style="margin-left:50px">Liste des locataires</a>-->
                                 
                                 <div class="dropdown" id="liste_drop_div">
                                          <a style="margin-left: 110%;margin-top: -12%;" class="btn btn-secondary dropdown-toggle" type="button" id="Type_drop_button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Liste des locataires
                                          </a>
                                          <div style='margin-left: 110%;margin-top:-4% ;width:400px;'id='liste_drop'class='dropdown-menu' aria-labelledby='price_drop_button'>
                                             <div class='cage'>
                                               <ul class='user-list'>
                                                 <?=$liste_locat?>
                                                </ul> 
                                             <div>

                                           </div>
									      </div>
                              </div>
                              <div id="RtBlock">
                                <div>
                                  <?=$rt1?>
                                </div> 
                               
                              </div>
                              <div id="YRT">
                                
                              </div>
                           </div>
                           
                        </div>
                     </div>
                     <div class="description-box">
                        <div style="margin-top:11%" class="dex-a">
                           <h4>Description</h4>
                           <p>
                           <?=$desc?>
                           <br>
                           </p>
                           
                           <p style="color:blue;"><i class="fas fa-ruler-combined" style="margin-right:9px;"></i>Superficie: <?=$sup?> m² <br>
                           <?php if($type=='Appartement'){
                              echo "<i class='fas fa-door-open' style='margin-right:4px;'></i>Nombre de chambre:$nbrC   <br>";
                           }?>
                                 <i style="font-size:25px;margin-right:12px;" class="fas fa-male"></i>Nombre de locataire: <?=$nbrP?>
                           </p>
                           

                           <br>
                           <p><?=$colloc_info?></p>
                           <br>
                           <p><?=$etud_info?></p>
                        </div>
                        <hr>
                        <div class="spe-a">
                           <h4>Équipements</h4>
                           <ul>
                               <!--equipement preview-->
                               <li class="clearfix">
                                <?=$equiprv1?>
                              </li>
                              <li class="clearfix">
                                 <div class="col-md-4">
                                   <!--empty-->
                                 </div>
                                 <div class="col-md-8">
                                    <!--empty-->
                                 </div>
                              </li>
                              <li class="clearfix">
                                 <?=$equiprv2?>
                              </li><br>
                              <div class="col-md-12">
                                    <a type="button" href="#" aria-busy="false" class="equipment" data-toggle="modal" data-target="#modalEquip">Afficher les <?=$nbrEQ?> équipements</a>
                                 </div>
                           </ul>
                        </div>
                        <hr>
                        <div class="spe-a">
                           <h4>Réglements</h4>
                           <p>
                           <?=$regl?>
                           </p>
                        </div>
                        <div class="spe-a">
                           <h4>Évaluations</h4>
                              <div class="container">
                                 <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                       <div class="well well-sm">
                                          <div class="row">
                                             <div class="col-xs-12 col-md-6 text-center">
                                                <h1 class="rating-num">
                                                <?php echo sprintf("%.1f", $OvrRating);?></h1>
                                                <div class="rating">
                                                 <?=$stars?>
                                                </div>
                                                <div>
                                                  <span ><i class="fas fa-user"></i>  </span><?=$nbrRt?> total
                                                </div>
                                             </div>
                                             <div class="col-xs-12 col-md-6">
                                                <div class="row rating-desc">
                                                   <div class="col-xs-3 col-md-3 text-right">
                                                    <span><i class="fas fa-star"></i></span>5
                                                   </div>
                           
                                                   <div class="col-xs-8 col-md-9">
                                                      <div class="progress progress-striped">
                                                       <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                       <span class="sr-only">100%</span>
                                                      </div>
                                    
                                                   </div>
                                                   <div class="col-md-6 col-md-offset-12">
                                                   <span></span>
                                                   </div>
                                                </div>
                            
                                                <!-- end 5 -->
                                                <div class="col-xs-3 col-md-3 text-right">
                                                   <span ><i class="fas fa-star"></i></span>4
                                                </div>
                                                <div class="col-xs-8 col-md-9">
                                                   <div class="progress">
                                                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                                        <span class="sr-only">80%</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- end 4 -->
                                                <div class="col-xs-3 col-md-3 text-right">
                                                  <span ><i class="fas fa-star"></i></span>3
                                                </div>
                                                <div class="col-xs-8 col-md-9">
                                                   <div class="progress">
                                                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                         <span class="sr-only">60%</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- end 3 -->
                                                <div class="col-xs-3 col-md-3 text-right">
                                                   <span ><i class="fas fa-star"></i><span>2
                                                </div>
                                                <div class="col-xs-8 col-md-9">
                                                   <div class="progress">
                                                      <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20"
                                                         aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                         <span class="sr-only">40%</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- end 2 -->
                                                <div class="col-xs-3 col-md-3 text-right">
                                                   <span ><i class="fas fa-star"></i></span>1
                                                </div>
                                                <div class="col-xs-8 col-md-9">
                                                   <div class="progress">
                                                      <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80"
                                                       aria-valuemin="0" aria-valuemax="100" style="width: 15%">
                                                       <span class="sr-only">15%</span>
                                        
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- end 1 -->
                                             </div>
                                             <!-- end row -->
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <hr>
                           <div class="right-dit-p">
                              <div class="like-list">
                                 <ul>
                                   <?=$cmt_Lst?>
                                    <li>
                                       <div class="im-b"><i id="SeeComments" class="fa fa-ellipsis-h" aria-hidden="true"></i></div>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                           <div class="spe-a">
                              <h4>Comments</h4>     
                              <div id="comments" class="card Comments">
	                              <div class="card-body">
                                    <div id="cmt-grp">
	                                  <?=$Comments?>
                                    </div>
                                    
                                 </div>
         
                              </div> 
                        
                     </div>
                  </div>

               <!--Map location-->
                  <div id="map-container">      
                     <div id="map">
                   
                     </div>
                  </div>
                  <div id="sim-res" class="similar-box">
                     <h2>Des résultats similaires</h2>
                     
                     <div class="row cat-pd">
                        <?=$recom1?>
                     </div>

                     <div class="row cat-pd">
                        <?=$recom2?>
                     </div>
                  </div>
               </div>
               <div class="col-md-3 col-sm-12">
                  <div id="price-box" class="price-box-right">
                  <div class="lheaaaad" style="margin-right: 30%;">
                  <div class="media">
  <img class="mr-3 pic" src="<?=$srcP?>" alt="Generic placeholder image">

</div>
                  <h3  id="namednem"><?=$LU?></h3>
                  </div>
                  <div class="btnsss">
                  <button type="button" id="num_btn" class="btn btn-primary hided" ><p style=" white-space:nowrap; width:40px;height:20px; overflow:hidden;text-overflow:ellipsis;"><?=$numeroP?></p></button>
                  <button type="button" id="show_num" class="btn btn-primary showbtn">Afficher le numéro</button><br></br>
                  <button type="button" id="email_btn" class="btn btn-primary hided"><p style=" white-space:nowrap; width:40px;height:20px; overflow:hidden;text-overflow:ellipsis;"><?=$emailP?></p></button>
                  <button type="button" id="show_email" class="btn btn-primary showbtn">Afficher l'émail</button>
               </div>
               <hr>
                     <div class="prixdh">
                     <h4 style="margin-left: 4%;">Prix</h4>
                     <h3 style="margin-left: 24%;"><?=$prix?> Dh</h3> </div>
                  
                     <a class="badge badge-primary ChatPro">Contacter Hote</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>
<!--Modal-->

<div class="modal fade" id="modalEquip"  tabindex="-1" role="dialog">
<div class="modal-dialog-centered" role="document" style="width:40%;margin-left:30%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title _26piifo">Équipements</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="height: 16px; width: 16px; display: block; fill: rgb(118, 118, 118);"><path d="m23.25 24c-.19 0-.38-.07-.53-.22l-10.72-10.72-10.72 10.72c-.29.29-.77.29-1.06 0s-.29-.77 0-1.06l10.72-10.72-10.72-10.72c-.29-.29-.29-.77 0-1.06s.77-.29 1.06 0l10.72 10.72 10.72-10.72c.29-.29.77-.29 1.06 0s .29.77 0 1.06l-10.72 10.72 10.72 10.72c.29.29.29.77 0 1.06-.15.15-.34.22-.53.22" fill-rule="evenodd"></path></svg></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="_1p0spma2">Standard</div>
        <div class="_1lhxpmp">
          <?=$equiLST?>
          <?=$equiLST2?>
         </div>
         <hr>
         

      </div>
      <div class="modal-footer">
        <!--just for the color -->
      </div>
    </div>
  </div>
</div>

<!--Modal-->


<div class="modal" id="modalLikeThis2" tabindex="-1" role="dialog" >
  <div class="modal-dialog-centered" role="document" style="width:40%;margin-left:30%;margin-top:2%;">
  
    <div class="modal-content">
      
      <div class="modal-body" >
         <div class="Mrating">
          <input type="radio" id="Mstar5" name="rating" value="5" />
          <label for="Mstar5" title="Meh">5 stars</label>
          <input type="radio" id="Mstar4" name="rating" value="4" />
          <label for="Mstar4" title="Kinda bad">4 stars</label>
          <input type="radio" id="Mstar3" name="rating" value="3" />
          <label for="Mstar3" title="Kinda bad">3 stars</label>
          <input type="radio" id="Mstar2" name="rating" value="2" />
          <label for="Mstar2" title="Sucks big tim">2 stars</label>
          <input type="radio" id="Mstar1" name="rating" value="1" />
          <label for="Mstar1" title="Sucks big time">1 star</label>
         </div>
         <br>
         <div class="avis">
          <p for="comment" style="margin-top:20%; margin-left:0px;"><h4>Donner un avis<h4></p>
          <textarea id="comment" name="story" rows="5" cols="33" style="border: 1px solid black; "> </textarea>
          
         </div>
      </div>
      <div class="modal-footer">
        <button id="rate-btn" type="button" class="btn btn-primary">Confirmer</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>

<footer id="fh5co-footer" role="contentinfo">
		<div class="container" id="footertext">
			<div class="row row-pb-md">
				<div class="col-md-4 fh5co-widget">
					<h3>ESRENT</h3>
					<p>Site web qui permetra aux client de trouver facilement un logement qui respect leurs criteres de qualité et aux propriétaire d'atendre un grand nombre de loueurs potentiels</p>
					<p><a href="#">Learn More</a></p>
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
						<small class="block">&copy; 2020 ESRENT</small> 
						<small class="block"> All Rights Reserved.</small>
					</p>
					<p>
						<ul class="fh5co-social-icons">
							<li><a href="#"><i class="icon-twitter"></i></a></li>
							<li><a href="#"><i class="icon-facebook"></i></a></li>
							<li><a href="#"><i class="icon-linkedin"></i></a></li>
							<li><a href="#"><i class="icon-dribbble"></i></a></li>
						</ul>
					</p>
				</div>
			</div>

		</div>
	</footer>
      
      <section class="avenue-messenger" id="Chat" style="display:none">
         <div class="menu">
            <div class="button" id="CloseChat" title="End Chat">&#10005;</div> 
         </div>
         <div class="agent-face">
            <div class="half">
            <img class="agent circle" src='<?=$srcP?>' >
            </div>
         </div>
         <div class="chat" >
            <div class="chat-title">
            <h1><?=$Pnom?> <?=$Pprenom?> (<?=$LU?>)</h1>
           
            </div>
            <div class="messages" >
            <div class="messages-content mCustomScrollbar _mCS_1 mCS_no_scrollbar" >

            </div>
            </div>
            <div class="message-box">
               <textarea id="msg_input1" type="text" class="message-input" placeholder="Type message..."></textarea>
               <button type="submit" id="msg_send1" class="message-submit">Send</button>
            </div>
         </div>

      </section>

      

      <?=$chatboxs; ?>
   </body>
   
 
   
   <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js'></script>


   
      <!--bootstrap js--> 
      
      <script src="../../Resourse/js3/bootstrap.min.js"></script> 
      <!--<script src="../../Resourse/js3/bootstrap-select.min.js"></script>-->
      <script src="../../Resourse/js3/slick.min.js"></script> 
      <script src="../../Resourse/js3/select2.full.min.js"></script> 
      <script src="../../Resourse/js3/wow.min.js"></script> 
      <!--custom js--> 
      <script src="../../Resourse/js3/custom.js"></script>

      <script>
    var $MsgCont = $('.messages-content');
    function updateScrollbar() {
      $MsgCont.mCustomScrollbar('scrollTo', 'bottom');
      }

    function insertMessage(messages) {
      var varI='#input'+messages;
      msg = $(varI).val();
        if(msg!=null){
          $('<div class="message message-personal" >' + msg + '</div>').appendTo('#'+messages+' .mCSB_container');
          $(varI).val(null);
          updateScrollbar();
          msg=null;
        }
      }



      <?=$UISc; ?>
      /* 
            setInterval(function() {
      showdata="";
      $.ajax({  
                url:"chatmsg.php",  
                method:"GET",  
                data:{sender,reciever:},  
                success:function(data){
                   if(showdata!=data)
                   {
                     showdata=data;
                     $('.mCSB_container').html(data);
                   }
                }  
           });
                 updateScrollbar(); 
   }, 1000);
      */

    
    </script>
    <?=$jsScript; ?>   
     

<script>

chat = document.getElementById('Chat');
var $messages = $('.messages-content'),
d, h, m,
i = 0;




   function updateScrollbar() {
   $messages.mCustomScrollbar('scrollTo', 'bottom');
   }


   function insertMessage() {
   $('<div class="message message-personal">' + msg + '</div>').appendTo('.messages-content .mCSB_container');
   $('.message-input').val(null);
   updateScrollbar();
   }

   $('#msg_send1').click(function() {
      msg = $('#msg_input1').val();
      if(msg!="")
      {
      insertMessage();
      $.ajax({  
            url:"chatbox.php",  
            method:"GET",  
            data:{message:msg,sender:<?=$userCode; ?>,reciever:<?=$Codepro; ?>}
            });
      }
   });


   $(window).on('keydown', function(e) {
      if (e.which == 13) {
         msg = $('.message-input').val();
         if(msg!="")
         {
         insertMessage();
         $.ajax({  
               url:"chatbox.php",  
               method:"GET",  
               data:{message:msg,sender:<?=$userCode; ?>,reciever:<?=$Codepro; ?>}
               });
         }
      }
   })



   setInterval(function() {
      showdata="";
      $.ajax({  
                url:"chatmsg.php",  
                method:"GET",  
                data:{sender:<?=$userCode; ?>,reciever:<?=$Codepro; ?>},  
                success:function(data){
                   if(showdata!=data)
                   {
                     showdata=data;
                     $('#mCSB_1_container').html(data);
                   }
                }  
           });
      updateScrollbar(); 
   }, 1000);



   



   $('.ChatPro').click(function(){
   chat.style="display:block";
   updateScrollbar();
   });
   $('#CloseChat').click(function(){
   chat.style="display:none";
   });

</script>










<script>
var Mrating=0;
var comment="";



$(document).ready(function(){  

   

       $('#Mstar1').click(function(){  
          Mrating=1;
       }); 
 
       $('#Mstar2').click(function(){
          Mrating=2;   
       }); 
 
       $('#Mstar3').click(function(){
          Mrating=3;
       });
 
       $('#Mstar4').click(function(){
          Mrating=4;
       });
       
       $('#Mstar5').click(function(){
          Mrating=5;
       });
       $("#rate-btn").click(function(){
          comment = document.getElementById("comment").value; 
         $.ajax({  
                 url:"RateL.php",   
                 method:"POST",
                 data:{rating:Mrating,comment:comment,RatedL:<?=$CodeL?>,rater:<?=$userCode?>},
                 success:function(data){  
                    
                    
                    $('#modalLikeThis2').modal('hide');                
                    document.getElementById('RTM').style.display='none';
                    document.getElementById('YRT').innerHTML='<h3>Vous avez évalué ceci '+Mrating+' Stars</h3>';
                    window.location.reload();

                  }
                 });

       });

       
   

 });  
</script>

<script>

$(document).ready(function(){  
   document.getElementById('comments').style.display='none';
   

   $('#SeeComments').click(function(){  
          
     if(hidden==true)     
      {
         document.getElementById('comments').style.display='block';
         hidden=false;
      }   
     else
      { 
         document.getElementById('comments').style.display='none';
         hidden=true;
      }

       });
      });

</script>

<script>

var saved='<?=$saved?>';
var hidden=true;
var CodeL='<?=$CodeL?>';
var CodeU='<?=$userCode?>';
var CodeP='<?=$Codepro?>';
$(document).ready(function(){  

   if(saved=='Y')
       {
          $('#like-btn').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
       }
       else if(saved=='N')
       {
          $('#like-btn').empty().append("<i class='far fa-heart ff'></i> enregistrer");
         }   
   $('#like-btn').click(function(){  
      if(saved=='N')
       {
          $('#like-btn').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
          saved='Y';
          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL,CodeU:CodeU,action:saved},
                 success:function(data){  

                  }
                 });


       }   
      else if(saved=='Y')
       {
          $('#like-btn').empty().append("<i class='far fa-heart ff'></i> enregistrer");
          saved='N';

          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL,CodeU:CodeU,action:saved},
                 success:function(data){  

                  }
                 });

       }
       });   
    });  

</script>
<!--
<script async defer

    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWv8pHQtbrov613r_RMqCjZ_nOrz2y7HM&callback=initMap">
    </script>-->
    <script>

/**
 * Moves the map to display over Berlin
 *
 * @param  {H.Map} map      A HERE Map instance within the application
 */
function moveMapTohouse(map){
  map.setCenter({lat:<?=$lat?>, lng:<?=$lng?>});
  map.setZoom(14);
}

/**
 * Boilerplate map initialization code starts below:
 */

//Step 1: initialize communication with the platform
// In your own code, replace variable window.apikey with your own apikey
var platform = new H.service.Platform({
  apikey:'gNAS-hI7AKsqytfacNxMU-WZqMQa_Zn-nunnoU2p6s4'
});
var defaultLayers = platform.createDefaultLayers();

//Step 2: initialize a map - this map is centered over Europe
var map = new H.Map(document.getElementById('map'),
  defaultLayers.vector.normal.map,{
  center: {lat:33.589886, lng:-7.603869},
  zoom: 8,
  pixelRatio: window.devicePixelRatio || 1
});
// add a resize listener to make sure that the map occupies the whole container
window.addEventListener('resize', () => map.getViewPort().resize());

//Step 3: make the map interactive
// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// Create the default UI components
var ui = H.ui.UI.createDefault(map, defaultLayers);

// Now use the map as required...
window.onload = function () {
  moveMapTohouse(map);
}
var homeIcon = new H.map.Icon("../../Resourse/imgs/adresse.png");
var homeMarker = new H.map.Marker({lat:<?=$lat?>, lng:<?=$lng?>},{icon:homeIcon});
    map.addObject(homeMarker);
  </script>


<script>/*
function initMap(){
   var options = {
      zoom:8,
      center:{lat:<?=$lat?>, lng:<?=$lng?>}

   }
   var map = new google.maps.Map(
      document.getElementById('map'),options);

   var marker = new google.maps.Marker({
      position: {lat:<?=$lat?>, lng:<?=$lng?>}, 
      map: map,
      icon:'../../Resourse/imgs/adresse.png'
      });
   

}*/
</script>

<script>
 var lat_lng_empty='<?=$lat_lng_empty?>';  
  if(lat_lng_empty=='empty')
   {
      $('#map-container').empty().append("<img id='no-map-img' src='../../Resourse/imgs/userimgs/noLocation.png'><br><h3 >Le proprietaire n'a pas specifier la locationdu logement</h3><hr>");
   }


</script>



<script>

   //getting the ids of recommended products 
   var CodeL1=<?=$CodeO1?>;
   var CodeL2=<?=$CodeO2?>;
   var CodeL3=<?=$CodeO3?>;
   var CodeL4=<?=$CodeO4?>;
   //getting the saved signs of recommended products
   var SavedL1='<?=$SavedL1?>';
   var SavedL2='<?=$SavedL2?>';
   var SavedL3='<?=$SavedL3?>';
   var SavedL4='<?=$SavedL4?>';

   $(document).ready(function(){ 
  //filling the save  button with the right icon  for card1  
      if(SavedL1=='Y')
        {
          $('#1').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
        }
       else if(SavedL1=='N')
        {
          $('#1').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
        } 
//filling the save  button with the right icon  for card2  
      if(SavedL2=='Y')
        {
          $('#2').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
        }
       else if(SavedL2=='N')
        {
          $('#2').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
        } 
//filling the save  button with the right icon  for card3  
      if(SavedL3=='Y')
        {
          $('#3').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
        }
       else if(SavedL3=='N')
        {
          $('#3').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
        } 
 //  filling the save  button with the right icon  for card4       
        if(SavedL4=='Y')
        {
          $('#4').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
        }
       else if(SavedL4=='N')
        {
          $('#4').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
        }     






      $('#1').click(function(){

      if(SavedL1=='N')
       {
          $('#1').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
          SavedL1='Y';
          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL1,CodeU:CodeU,action:SavedL1},
                 success:function(data){  

                  }
                 });


       }   
      else if(SavedL1=='Y')
       {
          $('#1').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
          SavedL1='N';

          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL1,CodeU:CodeU,action:SavedL1},
                 success:function(data){  

                  }
                 });

       }
          
      });

      $('#2').click(function(){
          
      if(SavedL2=='N')
       {
          $('#2').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
          SavedL2='Y';
          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL2,CodeU:CodeU,action:SavedL2},
                 success:function(data){  

                  }
                 });


       }   
      else if(SavedL2=='Y')
       {
          $('#2').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
          SavedL2='N';

          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL2,CodeU:CodeU,action:SavedL2},
                 success:function(data){  

                  }
                 });

       }


         });

      $('#3').click(function(){
          
         if(SavedL3=='N')
       {
          $('#3').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
          SavedL3='Y';
          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL3,CodeU:CodeU,action:SavedL3},
                 success:function(data){  

                  }
                 });


       }   
      else if(SavedL3=='Y')
       {
          $('#3').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
          SavedL3='N';

          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL3,CodeU:CodeU,action:SavedL3},
                 success:function(data){  

                  }
                 });

       }


      });

      $('#4').click(function(){
         if(SavedL4=='N')
       {
          $('#4').empty().append("<i class='fas fa-heart ff'></i> Enregistrée");
          SavedL4='Y';
          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL4,CodeU:CodeU,action:SavedL4},
                 success:function(data){  

                  }
                 });


       }   
      else if(SavedL4=='Y')
       {
          $('#4').empty().append("<i class='far fa-heart ff'></i> Enregistrer");
          SavedL4='N';

          $.ajax({  
                 url:"SaveL.php",   
                 method:"POST",
                 data:{CodeL:CodeL4,CodeU:CodeU,action:SavedL4},
                 success:function(data){  

                  }
                 });

       }
      });   
   });   
   
</script>

<script>
   var nbr_cmt=<?=$nbrCMT?>;
   $(document).ready(function(){ 
   if(nbr_cmt==0)
    {
      document.getElementById('comments').style.display='block'; 
      document.getElementById('SeeComments').style.display='none'; 

    }
   });  
</script>

<script>
   $(document).scroll(function() {
    checkOffset();
});

function checkOffset() {
    if($('#price-box').offset().top + $('#price-box').height() 
                                           >= $('#sim-res').offset().top - 10)
        {
           $('#price-box').css('position', 'relative');
           $('#price-box').css('margin-left', '688px');
           $('#price-box').css('top', '-850px');
        
        }
    if($(document).scrollTop() + window.innerHeight < $('#sim-res').offset().top)
       { $('#price-box').css('position', 'fixed'); // restore when you scroll up
         $('#price-box').css('left', '50%');
         $('#price-box').css('top', '25%');
         $('#price-box').css('margin-left', '15%');
       }
}
</script>

<script>
   
orgW=$(window).width();
var width = $(window).width();

document.getElementById('cnt-hote-2').style.display='none'; 

$(window).on('resize', function() {
  if ($(this).width() !== width) {
    width = $(this).width();
    document.getElementById('price-box').style.display='none'; 
    document.getElementById('cnt-hote-2').style.display='block'; 
    
  }

  if ($(this).width() == orgW) {
    width = $(this).width();
    document.getElementById('price-box').style.display='block'; 
    document.getElementById('cnt-hote-2').style.display='none'; 
    
  }

});


</script>


<script>
var act='<?=$act?>';
$(document).ready(function(){ 

   
  $('#show_num').click(function(){
     document.getElementById('show_num').style.display='none';
         $('#num_btn').html("<?=$numeroP_full?>");
   
             $('#num_btn').css("margin-left","15%");
      });
      $('#show_email').click(function(){
         document.getElementById('show_email').style.display='none'; 
   $('#email_btn').html("<?=$email_full?>");
      });
  if(act=="show_email")
  {
   document.getElementById('show_email').style.display='none'; 
   $('#email_btn').html("<?=$email_full?>");
   //$('#email_btn').attr('value', '<?=$email_full?>');
   
  }
  if(act=="show_num")
  {
   document.getElementById('show_num').style.display='none'; 
   $('#num_btn').html("<?=$numeroP_full?>");
   
   $('#num_btn').css("margin-left","15%");
   
   
  }  



});
</script>


<script>
var users_found=<?=$users_found?>;
$(document).ready(function(){

    if(users_found==0)
    {
      $('#Type_drop_button').click(function(){
         $("#Type_drop_button").removeAttr("data-toggle");
 alert("Liste des locataires vide");
});
    }
 });

</script>
</html>