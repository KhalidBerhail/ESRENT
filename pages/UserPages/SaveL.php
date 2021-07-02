<?php
$servername = "localhost";
$userservername = "root";
$database = "pfe";


// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$CodeU=$_POST['CodeU'];
$CodeL=$_POST['CodeL'];
$action=$_POST['action'];
$rf="SELECT CodeP from logement where CodeL=?";
$sf=$conn->prepare($rf);
$sf->bind_param("i",$CodeL);
$sf->execute();
$rsf=$sf->get_result();
$rwf=$rsf->fetch_assoc();
$CodeP=$rwf['CodeP'];
//$CodeP=$_POST['CodeP'];

if($action=='Y')
{
    $r="INSERT INTO `saves`(`CodeL`, `CodeU`) VALUES (?,?)";
    $s=$conn->prepare($r);
    $s->bind_param("ii",$CodeL,$CodeU);
    $s->execute();
    $tm_stmp=date('Y-m-d H:i:s', time());
    $r="INSERT INTO `user_notis`(`CodeU`, `CodeP`,`action`,`CodeL`,`status`,`date`) VALUES (?,?,'saved',?,'new',?)";
    $s=$conn->prepare($r);
    $s->bind_param("iiis",$CodeU,$CodeP,$CodeL,$tm_stmp);
    $s->execute();
}
else if($action=='N')
{
    $r="DELETE FROM `saves` WHERE CodeL=? and CodeU=?";
    $s=$conn->prepare($r);
    $s->bind_param("ii",$CodeL,$CodeU);
    $s->execute();

    $r="DELETE FROM `user_notis` where CodeU=? and CodeP=? and CodeL=? and action='saved' ";
    $s=$conn->prepare($r);
    $s->bind_param("iii",$CodeU,$CodeP,$CodeL);
    $s->execute();
}






?>