<?php
  session_start();
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

$codeU = $_SESSION['usercode'];

if(isset($_POST['acc']))
{
    $img=file_get_contents($_FILES["img"]["tmp_name"]);
    $req="UPDATE `utilisateur` SET `imageP`=? WHERE CodeU=?";
    $statement=$conn->prepare($req);
    $statement->bind_param("si",$img,$codeU);
    $statement->execute();
}


?>
<html>
<body>
<form method="POST" enctype="multipart/form-data">
<input type="file" name="img" required>    
<button name="acc">accepte</button>
</form>
</body>
</html>