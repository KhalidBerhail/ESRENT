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



$CodeP=$_POST['CodeP'];

//user notifications(saves/ratings)
$rmv=0;
$all=array();

$reqNS="SELECT * from user_notis where CodeP=? and status='loaded' ";
$statementNS=$conn->prepare($reqNS);
$statementNS->bind_param("i",$CodeP);
$statementNS->execute();
$resNS=$statementNS->get_result();
while(($rowNS = mysqli_fetch_array($resNS)))
{
  
  $nt_code=$rowNS['idN'];

  


  $idN=$rowNS['idN'];
  $reqNSU="UPDATE `user_notis`  SET status='old' where idN=? ";
  $statementNSU=$conn->prepare($reqNSU);
  $statementNSU->bind_param("i",$idN);
  $statementNSU->execute(); 
  $rmv=$rmv+1;
  
  
}

$result4=$rmv;
$response4 = array('result4' => $result4);


echo json_encode($response4);
/*
$response3 = array('tab' => $all);
echo json_encode($response3);
array_push($all,$idN);*/
?>

