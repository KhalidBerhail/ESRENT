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

$UN=$_GET['UN'];
$req = "SELECT * FROM utilisateur where username=?";
$statement=$conn->prepare($req);
$statement->bind_param("s",$UN);
$statement->execute();
$res=$statement->get_result();
$row=$res->fetch_assoc();
$img=$row['imageP'];


header('Content-Type: image');
echo $img;

?>