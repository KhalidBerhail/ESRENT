<?php
session_start();
$servername = "localhost";
$userservername = "root";
$database = "pfe";
// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



$codeL=$_POST['codeL'];
$data=$_POST['img'];

$img_ar1=explode(";",$data);
$img_ar2=explode(",",$img_ar1[1]);
$data = base64_decode($img_ar2[1]);

$imageName = time() .'.png';
file_put_contents($imageName,$data);
$image_file= file_get_contents($imageName);
echo $image_file;




$req = "INSERT INTO `image`(`CodeL`, `image`) VALUES (?,?)";
$statement=$conn->prepare($req);
$statement->bind_param("is",$codeL,$image_file);
$statement->execute();

unlink($imageName);

$sta="modification";
$req = "UPDATE `logement` SET `status`=? WHERE `CodeL`=?";
$statement=$conn->prepare($req);
$statement->bind_param("ss",$sta,$codeL);
$statement->execute();



?>