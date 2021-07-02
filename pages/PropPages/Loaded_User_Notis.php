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



$CodeP=$_POST['CodeP'];

//user notifications(saves/ratings)
$new_user_notis="";
$nbr_noti=$_POST['nbrnts'];
$reqNS="SELECT * from user_notis where CodeP=? and status='loaded' ";
$statementNS=$conn->prepare($reqNS);
$statementNS->bind_param("i",$CodeP);
$statementNS->execute();
$resNS=$statementNS->get_result();
while(($rowNS = mysqli_fetch_array($resNS)))
{
  $user=$rowNS['CodeU'];
  $action=$rowNS['action'];
  $logement=$rowNS['CodeL'];
  $nt_code=$rowNS['idN'];

  $reqUS="SELECT * from utilisateur where CodeU=?";
  $statementUS=$conn->prepare($reqUS);
  $statementUS->bind_param("i",$user);
  $statementUS->execute();
  $resUS=$statementUS->get_result();
  $rowUS=$resUS->fetch_assoc();

  $nt_usern=$rowUS['username'];

  $reqUS="SELECT * from logement where CodeL=?";
  $statementUS=$conn->prepare($reqUS);
  $statementUS->bind_param("i",$logement);
  $statementUS->execute();
  $resUS=$statementUS->get_result();
  $rowUS=$resUS->fetch_assoc();
  $nt_loge=$rowUS['nom'];
  

  if($action=='saved')
   {
     $new_user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                      <div class='preview-thumbnail'>
                          <div class='preview-icon bg-success'>
                           <i class='mdi mdi-heart text-normal'></i>
                          </div>
                      </div>
                      <div class='preview-item-content'>
                        <h6 class='preview-subject font-weight-normal'>".$nt_usern." a enregistré votre logement '".$nt_loge."'</h6>
                        <p class='font-weight-light small-text mb-0 text-muted'>
                         Just now
                        </p>
                      </div>
                    </a>";
                    $nbr_noti=$nbr_noti+1;
                    
   }
  else if($action=='rated')
   {
    $new_user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                      <div class='preview-thumbnail'>
                          <div class='preview-icon bg-success'>
                           <i class='fas fa-star'>
                          </div>
                      </div>
                      <div class='preview-item-content'>
                        <h6 class='preview-subject font-weight-normal'>".$nt_usern." a évalué votre logement '".$nt_loge."'</h6>
                        <p class='font-weight-light small-text mb-0 text-muted'>
                         Just now
                        </p>
                      </div>
                    </a>";
                    $nbr_noti=$nbr_noti+1;
                    
   }
  else if($action=='commented') 
   {
    $new_user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
                      <div class='preview-thumbnail'>
                          <div class='preview-icon bg-success'>
                            <i class='far fa-comment'></i> 
                          </div>
                      </div>
                      <div class='preview-item-content'>
                        <h6 class='preview-subject font-weight-normal'>".$nt_usern." a commenté sur votre logement '".$nt_loge."'</h6>
                        <p class='font-weight-light small-text mb-0 text-muted'>
                         Just now
                        </p>
                      </div>
                    </a>";
                    $nbr_noti=$nbr_noti+1;
                    
   }

}
$result2=$nbr_noti;
$response2 = array('result2'=>$result2);

echo json_encode($response2);

?>

