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



$r="INSERT INTO `ratings`(`CodeL`, `CodeU`, `rating`) VALUES (?,?,?)";
$s=$conn->prepare($r);
$s->bind_param("iii",$CodeL,$CodeR,$rating);
$s->execute();

?>