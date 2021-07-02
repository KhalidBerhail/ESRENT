<?php
session_start();
$servername = "localhost";
$userservername = "root";
$database = "pfe";
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$codeU = $_SESSION['usercode'];
$type = 'super';
if(isset($_POST['logment']) && isset($_POST['timeout']))
{
    $arraylogment = $_POST['logment'];
    $timeout = $_POST['timeout'];
    $logsize = (int)sizeof($arraylogment);
    $prix = 19;
    $promo = 1;
    
    //demunition du temps du promo
    if($timeout == 3)
        $promo = $promo - 0.1;         
    else if($timeout == 12)
        $promo = $promo - 0.2;                
    //promo nombre des logement 
    if($logsize>1)
        $promo = $promo - (sizeof($logment)/10);
    $totale = $prix*$timeout*$promo;

    $datenow = new DateTime(date('Y-m-d'));
    $datenow = $datenow->format('Y-m-d');

    
    $reqTr="INSERT INTO `sts_trans`(`id`, `date`, `value`) VALUES (?,?,?)";
    $statementTr=$conn->prepare($reqTr);
    $statementTr->bind_param("iss",$codeU,$datenow,$totale);
    $statementTr->execute();


    foreach ($arraylogment as $Codelogment) {
        $req="SELECT * FROM pack WHERE CodeL=?";
        $statement=$conn->prepare($req);
        $statement->bind_param("i",$Codelogment);
        $statement->execute();
        $res=$statement->get_result();
        $row=$res->fetch_assoc();
        if($res->num_rows==0)
        {
            $date = new DateTime(date('Y-m-d'));
            $date->add(new DateInterval('P'.$timeout.'M'));
            $dateToreq = $date->format('Y-m-d');



            $reqIn="INSERT INTO `pack`(`CodeL`, `CodeU`, `type`, `ExpeTo`, `datein`) VALUES(?,?,?,?,?)";
            $statementIn=$conn->prepare($reqIn);
            $statementIn->bind_param("iisss",$Codelogment,$codeU,$type,$dateToreq,$datenow);
            $statementIn->execute();
        }else if ($res->num_rows==1)
        {
            if($row['type']=="super")
            {
                $DBdate = new DateTime($row['ExpeTo']);
                $date = new DateTime(date('Y-m-d'));
                $diff = $date->diff($DBdate)->format("%m");
                $diff = (int)$diff + $timeout ;
                $date->add(new DateInterval('P'.$diff.'M'));
                $dateToreq = $date->format('Y-m-d');
                $reqIn="UPDATE `pack` SET `ExpeTo` = ? WHERE CodeL = ?";
                $statementIn=$conn->prepare($reqIn);
                $statementIn->bind_param("si",$dateToreq,$Codelogment);
                $statementIn->execute();
            }
        }

    }



    $chang = 'document.getElementById("suAccepte").innerHTML = "';
    $chang = $chang."<div class='spinner-border text-light' role='status'><span class='sr-only'>Loading...</span></div>" ;
    $chang = $chang.'";';

    echo '<script>'.$chang.'
    setInterval(function() {
        location.replace("Prop.php");
    }, 2500);
        </script>';
}else
{
    echo '<input type="text" class="form-control" value="Selecter un logement" disabled>';    
}


?>