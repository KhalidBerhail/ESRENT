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




$req = "SELECT * FROM image where CodeL=? limit 0,1";
$statement=$conn->prepare($req);
$statement->bind_param("i",$id);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$img=$row['image'];


header('Content-Type: image');
echo $img;

?>