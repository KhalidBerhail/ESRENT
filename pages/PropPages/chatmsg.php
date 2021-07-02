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


$req = "SELECT * FROM `messages` WHERE (`Codesender` = ? AND `Codereciever` = ?) OR (`Codesender` = ? AND `Codereciever` = ?) ";
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
        <figure class="avatar"><img src="Proprofile.php?id='.$reciever.'"></figure>
        '.$msg.'
        '.$audio.'
        <div class="timestamp">15:22</div>
        </div>
        ';

    }

}



echo $text;

?>
