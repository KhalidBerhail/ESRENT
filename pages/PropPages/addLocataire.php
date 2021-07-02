<?php
session_start();

  $servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  $msg="";
  $result="";
  $display="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$logement=$_POST['logement'];
$user=$_POST['user'];


    $reqIU="SELECT * FROM logement WHERE nom=BINARY? ";
    $statementIU=$conn->prepare($reqIU);
    $statementIU->bind_param("s",$logement);
    $statementIU->execute();
    $resIU=$statementIU->get_result();
    $rowIU=$resIU->fetch_assoc();
    $codeL=$rowIU['CodeL'];

$reqIU="SELECT * FROM utilisateur WHERE username=BINARY?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$user);
$statementIU->execute();
$resIU=$statementIU->get_result();
if(($rowIU=$resIU->fetch_assoc()))
{  
    $codeU=$rowIU['CodeU'];
    $reqIU="SELECT * FROM `liste_locataire` WHERE CodeL=? and `Code_Locataire`=?";
    $statementIU=$conn->prepare($reqIU);
    $statementIU->bind_param("ii",$codeL,$codeU);
    $statementIU->execute();
    $resIU=$statementIU->get_result();
    if(($rowIU1=$resIU->fetch_assoc()))
    {
        $result="already";
        $display="utilisateur existe déjà";
    }
    else
    {
        $result="found";
        $display="";
        if($rowIU['imageP']!=NULL)
          {
            $srcSRS1="../UserPages/profilpic.php?UN=$user";
            $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
          }
        else
          {
            $srcSRS1="../../Resourse/imgs/ProfileHolder.jpg";
            $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
          }
          $display.= "<li class='user-item'>
         <span class='avatar'>
             $ProfilePSRS1
         </span>
         <h5>".$user."</h5>
         <h6>".$user."</h6>
         <a  class='btn-fllw' id='remove".$codeU."' ><i class='fas fa-user-times'></i></a>
            </li>
            ";
            $reqIU="INSERT into `liste_locataire`(`codeL`, `Code_Locataire`) values(?,?) ";
            $statementIU=$conn->prepare($reqIU);
            $statementIU->bind_param("ii",$codeL,$codeU);
            $statementIU->execute();    
    }
    
    




}
else{
    $result="not_found";
    $display="utilisateur non trouvé";
}




$response=array('result'=>$result,"display"=>$display);
  echo json_encode($response);
?>