<?php


if(isset($_POST['logment']) && isset($_POST['timeout']))
{
    $logment = $_POST['logment'];
    $logsize = (int)sizeof($logment);
    $timeout = $_POST['timeout'];
    $prix = 39;
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

    echo '<input type="text" class="form-control" value="Prix : '.$totale.' dh" disabled>';
}else
{
    echo '<input type="text" class="form-control" value="Selecter un logement" disabled>';    
}


?>