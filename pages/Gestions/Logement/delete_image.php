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

$id=$_GET['id'];

$req = "DELETE FROM image where CodeImg=?";
$statement=$conn->prepare($req);
$statement->bind_param("i",$id);
$statement->execute();


?>