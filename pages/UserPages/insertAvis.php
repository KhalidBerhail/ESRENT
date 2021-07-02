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
$comment=$_POST['comment'];




    $r="INSERT INTO `avis_clients`(`CodeU`, `commentaire`) VALUES (?,?)";
    $s=$conn->prepare($r);
    $s->bind_param("is",$CodeU,$comment);
    $s->execute();
    





?>