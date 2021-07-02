<?php
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

$codeL=$_GET['codeL'];
$codeE=$_GET['codeE'];
echo $codeL;
echo $codeE;
$codeEq="";
if($codeE<10)
  $codeEq="0".$codeE;
else
  $codeEq=$codeE;



$req = "SELECT * FROM `eqlo` WHERE `CodeE`=? and `CodeL`=? ";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$codeEq,$codeL);
$statement->execute();
$res=$statement->get_result();
if($res->num_rows==1)
  {
    $req = "DELETE FROM `eqlo` WHERE `CodeE`=? and `CodeL`=? ";
    $statement=$conn->prepare($req);
    $statement->bind_param("ss",$codeEq,$codeL);
    $statement->execute();
  }else if($res->num_rows==0)
    {
      $req = "INSERT INTO `eqlo`(`CodeE`, `CodeL`) VALUES (?,?)";
      $statement=$conn->prepare($req);
      $statement->bind_param("ss",$codeEq,$codeL);
      $statement->execute();      
    }
?>