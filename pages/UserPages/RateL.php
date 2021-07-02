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
$CodeR=$_POST['rater'];
$CodeL=$_POST['RatedL'];
$rating=$_POST['rating'];
$comment=$_POST['comment'];


$r="INSERT INTO `ratings`(`CodeL`, `CodeU`, `rating`, `comment`) VALUES (?,?,?,?)";
$s=$conn->prepare($r);
$s->bind_param("iiis",$CodeL,$CodeR,$rating,$comment);
$s->execute();

$r="UPDATE `logement` SET `rating`=? WHERE CodeL=?";
$s=$conn->prepare($r);
$s->bind_param("ii",$rating,$CodeL);
$s->execute();

$tm_stmp=date('Y-m-d H:i:s', time());
$r="INSERT INTO `user_notis`(`CodeU`, `CodeP`,`action`,`CodeL`,`status`,`date`) VALUES (?,?,'rated',?,'new',?)";
$s=$conn->prepare($r);
$s->bind_param("iiis",$CodeU,$CodeP,$CodeL,$tm_stmp);
$s->execute();



?>