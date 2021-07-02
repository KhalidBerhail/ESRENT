<?php
//$date->add(new DateInterval('P1Y'));
//$date->add(new DateInterval('P1M'));



$DBdate = new DateTime("2020-04-19");
$date = new DateTime(date('Y-m-d'));

$diff = $date->diff($DBdate)->format("%a");

echo $diff."\n";




?>