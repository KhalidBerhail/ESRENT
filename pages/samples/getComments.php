<?php
session_start();

  $servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  $msg="";
   $result="";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$limit=$_POST['limit'];
$CodeL=$_POST['CodeL'];
$offset=$_POST['offset'];



  //loading first page of comments comments 
  $Comments="";
  $reqCM="SELECT * from ratings where CodeL=? and comment is not NULL limit ?,5 ";
  $statementCM=$conn->prepare($reqCM);
  $statementCM->bind_param("ii",$CodeL,$offset);
  $statementCM->execute();
  $resCM=$statementCM->get_result();
   
  while(($rowCM=mysqli_fetch_array($resCM)))
   {
      $CodeCU=$rowCM['CodeU'];
      $comment=$rowCM['comment'];
      $rating=$rowCM['rating'];

      $reqCMU="SELECT * from utilisateur where CodeU=?";
      $statementCMU=$conn->prepare($reqCMU);
      $statementCMU->bind_param("i",$CodeCU);
      $statementCMU->execute();
      $resCMU=$statementCMU->get_result(); 
      $rowCMU=$resCMU->fetch_assoc(); 

      $UserCMU=$rowCMU['username'];
      
      if($rowCMU['imageP']!=NULL)
        {
          $srcCMU="profilpic.php?UN=$UserCMU";
	       $ProfilePCMU="<img src='".$srcCMU."' class='img img-rounded img-fluid'/>";
        }
      else
        {
	       $srcCMU="../../Resourse/imgs/ProfileHolder.jpg";
	       $ProfilePCMU="<img src='".$srcCMU."' class='img img-rounded img-fluid'/>";
        }
      
        $OvrRatingCMU=$rowCM['rating'];
  

        $wholeRCMU = floor($OvrRatingCMU);      
        $fractionRCMU = $OvrRatingCMU - $wholeRCMU;
        $irCMU=0;
        $starsCMU="";
        $countSTRCMU=5-$wholeRCMU;
        while($irCMU<$wholeRCMU)
        {
           $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
           $irCMU++;
        }
      
        if($fractionRCMU>=0.8 && $fractionRCMU<=0.9)
         {
           $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
           $countSTRCMU=$countSTRCMU-1;
         }
        else if($fractionRCMU>0.2 && $fractionRCMU<0.8)
         {
           $starsCMU.="<span class='float-right'><i class='text-warning fas fa-star-half'></i></span>";
           $countSTRCMU=$countSTRCMU-1;
         }
         else if($fractionRCMU<=0.2 && $fractionRCMU>0.8)
         {
            $starsCMU.="<span class='float-right'><i class='text-warning fa fa-star'></i></span>";
           $countSTRCMU=$countSTRCMU-1;
         }
      
         $irCMU=0;
         while($irCMU<$countSTRCMU)
         {
            $starsCMU.="<span class='float-right'><i class='text-warning far fa-star'></i></span>";
            $irCMU++;
         }  







     $Comments.= "
                  <div class='row'>
                   <div class='col-md-2'>".$ProfilePCMU
                     
                     ."
                   </div>
                   <div class='col-md-10'>
                     <p>
                     <a class='float-left' href='https://maniruzzaman-akash.blogspot.com/p/contact.html'><strong>".$UserCMU."</strong></a>
                    
                     ".$starsCMU."

                     </p>
                     <div class='clearfix'></div>
                       <p>".$comment."</p>
                       
                     </div>
                   </div>
                  </div>
                  <hr class='cmt'>";
   }

   $num_comments=$resCM->num_rows; 
   $response2 = array('num_comments'=>$num_comments);
?>

<html>


<?=$Comments;?>


</html>