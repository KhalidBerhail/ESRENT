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



$codeU=$_POST['iduser'];

$data=$_POST['image'];

$img_ar1=explode(";",$data);
$img_ar2=explode(",",$img_ar1[1]);
$data = base64_decode($img_ar2[1]);

$imageName = time() .'.png';
file_put_contents($imageName,$data);
$image_file= file_get_contents($imageName);
echo $image_file;




$req = "UPDATE `utilisateur` SET `imageP`=? WHERE `CodeU`=?";
$statement=$conn->prepare($req);
$statement->bind_param("si",$image_file,$codeU);
$statement->execute();

unlink($imageName);


?>