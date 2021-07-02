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

$text = null;
$sender = isset($_GET['sender']) ? $_GET['sender'] : null;
$reciever = isset($_GET['reciever']) ? $_GET['reciever'] : null;

$reqP="SELECT * from utilisateur where CodeU=?";
$statementP=$conn->prepare($reqP);
$statementP->bind_param("i",$reciever);
$statementP->execute();
$resP=$statementP->get_result();
$rowP=$resP->fetch_assoc();
$LU=$rowP["username"];

$srcP="";
if($rowP['imageP']!=NULL)
      {
        $srcP="profilpic.php?UN=$LU";
        
      }
    else
      {
        $srcP="../../Resourse/imgs/ProfileHolder.jpg";
       
      }

      
$req = "SELECT * FROM `messages` WHERE (`Codesender` = ? AND `Codereciever` = ?) OR (`Codesender` = ? AND `Codereciever` = ?) order by `datemsg`  ";
$statement=$conn->prepare($req);
$statement->bind_param("iiii",$sender,$reciever,$reciever,$sender);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
    $idM = $row['idMsg'];
    $msg = $row['Msg'];
    $from = $row['Codesender'];
    $status = $row['vue'];
    $timestamp=$row['datemsg'];
    $date=new DateTime($timestamp);
    $date->format("H:i");
    /*$datetime = explode(" ",$timestamp);
    $date = $datetime[0];
     $time = $datetime[1];*/
    //$time = date('Gi.s', $timestamp);
    if($from == $sender)
    {
        $text = $text.'<div class="message message-personal">'.$msg.'<div class="checkmark-sent-delivered">✓</div>';
        if($status != 0)
            $text = $text.'<div class="checkmark-read">✓</div></div>';
        else
            $text = $text.'</div>';
    }
    else
    {   $val="";
        $audio="";
        if($status == 0)
        {
            $audio = '<audio autoplay="true" style="display:none;">
                <source src="../sound/dilin.mp3" type="audio/wav">
            </audio>';


            $val=' new';
            $reqU = "UPDATE `messages` SET vue = 1 WHERE `idMsg` = ? ";
            $statementU=$conn->prepare($reqU);
            $statementU->bind_param("i",$idM);
            $statementU->execute();
        }
        $text = $text.
        '
        <div class="message'.$val.'">
        <figure class="avatar"><img src="'.$srcP.'"></figure>
        '.$msg.'
        '.$audio.'
        
        </div>
        ';

    }

}



echo $text;

?>
