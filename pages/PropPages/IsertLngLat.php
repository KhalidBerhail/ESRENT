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

$CodeL=$_POST['CodeL'];
$CodeU=$_POST['CodeU'];
$lat=$_POST['lat'];
$lng=$_POST['lng'];

$reqL = "UPDATE `logement` SET `lat`=?,`lng`=? WHERE CodeL=? and CodeP=? ";
$statementL=$conn->prepare($reqL);
$statementL->bind_param("ssii",$lat,$lng,$CodeL,$CodeU);
$statementL->execute();

?>