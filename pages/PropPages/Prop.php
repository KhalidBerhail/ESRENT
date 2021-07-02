<?php
session_start();
if(isset($_POST['logoutbtn'])) 
{
	unset($_SESSION['type']);
	unset($_SESSION['username']);
}
if( !isset($_SESSION['username']) || $_SESSION['type'] != "pro" )
{
  header("location:../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
$msg="";
$openclosejs=" checked = null;";
$jsScript="";
$chatboxs="";
$AllCodeSenders=" var codes = new array();";
$ScriptMsg="";
$sendScr="";
$url='"chatbox.php"';
$method='"GET"';
$i=1;
$UISc='setInterval(function() {
  showdata="";';


// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//remplisage des données d'utilisateur courrant
$src="";
$ProfileP="";
$USN=$_SESSION['username'];
$reqIU="SELECT * FROM utilisateur WHERE username=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$USN);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();

$reqIU="SELECT * FROM logement WHERE CodeP=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$rowIU['CodeU']);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIUss=$resIU->fetch_assoc();
$CodeL=$rowIUss['CodeL'];


if($rowIU['imageP']!=NULL)
{
  	$src="../Samples/profilpic.php?UN=$USN";
	$ProfileP="<img src='".$src."' alt='profile'/>";
}
else
{
	$src="../../Resourse/imgs/ProfileHolder.jpg";
	$ProfileP="<img src='".$src."' alt='profile'/>";
}

$codeU = $_SESSION['usercode'];
$ntMsg = "";

$req="SELECT idMsg, Codesender, Msg FROM messages 
WHERE Codereciever=?
GROUP BY Codesender  ORDER BY idMsg DESC LIMIT 3";
$statement=$conn->prepare($req);
$statement->bind_param("i",$codeU);
$statement->execute();
$res=$statement->get_result();
while ( $row = mysqli_fetch_array($res) )
{
  $sender=$row['Codesender'];
  $sms=$row['Msg'];
      $reqP="SELECT * from utilisateur where CodeU=?";
      $statementP=$conn->prepare($reqP);
      $statementP->bind_param("i",$sender);
      $statementP->execute();
      $resP=$statementP->get_result();
      $rowP=$resP->fetch_assoc();
      $Pusername=$rowP["username"];

  $ntMsg = $ntMsg.
  '
  <a class="dropdown-item preview-item" id="a'.$sender.'">
    <div class="preview-thumbnail">
        <img src="Proprofile.php?id='.$sender.'" alt="image" class="profile-pic">
    </div>
    <div class="preview-item-content flex-grow">
        <h6 class="preview-subject ellipsis font-weight-normal">'.$Pusername.'
        </h6>
        <p class="font-weight-light small-text text-muted mb-0">
          '.$sms.'
        </p>
    </div>
  </a>
  ';


  $chatboxs = $chatboxs.
  '
  <section class="avenue-messenger" id="Chat'.$sender.'" style="display:none">
  <div class="menu">
     <div class="button" id="CloseChat'.$sender.'" title="End Chat">&#10005;</div> 
  </div>
  <div class="agent-face">
     <div class="half">
     <img class="agent circle" src="Proprofile.php?id='.$sender.'" alt="profile">
     </div>
  </div>
  <div class="chat" >
     <div class="chat-title">
     <h1>'.$Pusername.'
     </div>
     <div class="messages" >
     <div id="'.$sender.'" class="messages-content mCustomScrollbar _mCS_1 mCS_no_scrollbar" >

     </div>
     </div>
     <div class="message-box">
        <textarea type="text" id="input'.$sender.'" class="message-input" placeholder="Type message..."></textarea>
        <button type="submit" id="send'.$sender.'" class="message-submit">Send</button>
     </div>
  </div>
</section>
  ';

  $openclosejs = $openclosejs.
  "
  $('#a".$sender."').click(function(){
    if(checked!=null)
      checked.style='display:none';
      document.getElementById('Chat".$sender."').style='display:block';
      checked=document.getElementById('Chat".$sender."');
      updateScrollbar();
    });
    $('#CloseChat".$sender."').click(function(){
      document.getElementById('Chat".$sender."').style='display:none';
      checked=null;
    });
  ";

  $ScriptMsg = $ScriptMsg.
  '

  $("#send'.$sender.'").click(function() {
    $msgtosend=$("#input'.$sender.'").val();
    insertMessage("'.$sender.'");
    $.ajax({  
          url:"chatbox.php",  
          method:"GET",  
          data:{message:$msgtosend,sender:'.$codeU.',reciever:'.$sender.'}
          });
 });

  ';


  $mCSB_container="'#mCSB_".$i."_container'";
  $i=$i+1;
  
  $UISc=$UISc.
  '
  $.ajax({  
    url:"chatmsg.php",  
    method:"GET",  
    data:{sender:'.$codeU.',reciever:'.$sender.'},  
    success:function(data){
      if(showdata!=data)
      {
        showdata=data;
        $('.$mCSB_container.').html(data);
      }
    }  
  });
  ';




}

$Msgclass='"message message-personal"';


$UISc = $UISc.'updateScrollbar(); 
}, 1000);';

$jsScript = "<script>".$openclosejs.$ScriptMsg."</script>";


//logement vue
$Vue = array();
$Vue[1]=0; $Vue[2]=0; $Vue[3]=0; $Vue[4]=0;
$Vue[5]=0; $Vue[6]=0; $Vue[7]=0; $Vue[8]=0;
$Vue[9]=0;$Vue[10]=0;$Vue[11]=0;$Vue[12]=0;

//logement Rec
$Rec = array();
$Rec[1]=0; $Rec[2]=0; $Rec[3]=0; $Rec[4]=0;
$Rec[5]=0; $Rec[6]=0; $Rec[7]=0; $Rec[8]=0;
$Rec[9]=0;$Rec[10]=0;$Rec[11]=0;$Rec[12]=0;

$thisyear = date("Y");

$reqP="SELECT * FROM logement where CodeP=?";
$statementP=$conn->prepare($reqP);
$statementP->bind_param("s",$codeU);
$statementP->execute();
$resP=$statementP->get_result();
while ( $rowP = mysqli_fetch_array($resP) )
  {
    $logementtochar=$rowP['CodeL'];


    $reqVue="SELECT COUNT(*) as sumres,MONTH(date) as mou FROM `log_vues` where YEAR(date) = ?
    and `idL`=? GROUP BY MONTH(date)";
    $statementVue=$conn->prepare($reqVue);
    $statementVue->bind_param("si",$thisyear,$logementtochar);
    $statementVue->execute();
    $resVue=$statementVue->get_result();
    while ( $rowVue = mysqli_fetch_array($resVue) )
    {
      $Vue[$rowVue['mou']] = $Vue[$rowVue['mou']] + $rowVue['sumres'];
    }

    $reqRec="SELECT COUNT(*) as sumres,MONTH(date) as mou FROM `log_recomm` where YEAR(date) = ?
    and `idL`=? GROUP BY MONTH(date)";
    $statement=$conn->prepare($reqRec);
    $statement->bind_param("si",$thisyear,$logementtochar);
    $statement->execute();
    $resRec=$statement->get_result();
    while ( $rowRec = mysqli_fetch_array($resRec) )
    {
      $Rec[$rowRec['mou']] = $Rec[$rowRec['mou']] + $rowRec['sumres'];
    }

  }




//chec pack expiration time

$reqP="SELECT * FROM pack";
$statementP=$conn->prepare($reqP);
$statementP->execute();
$resP=$statementP->get_result();
$date = new DateTime(date('Y-m-d'));
while ( $rowP = mysqli_fetch_array($resP) )
  {
    $date = new DateTime(date('Y-m-d'));
    $DBdate = new DateTime($rowP['ExpeTo']);
    if( $DBdate <= $date )
      {
        $reqEX="DELETE FROM pack WHERE CodeL=?";
        $statementEX=$conn->prepare($reqEX);
        $statementEX->bind_param("i",$rowP['CodeL']);
        $statementEX->execute();
      }
  }
//chec complete




//----------------- ----------Notifications------------
$nbr_nts=0;
// notifications des packs
$notif="";
$reqN="SELECT * FROM logement WHERE CodeP=? and (CodeL NOT IN (SELECT CodeL FROM pack where CodeU=?))";
$statementN=$conn->prepare($reqN);
$statementN->bind_param("ii",$codeU,$codeU);
$statementN->execute();
$resN=$statementN->get_result();

if($resN->num_rows!=0)
  {
    $notif='
    <a class="dropdown-item preview-item" href="Packs.php">
    <div class="preview-thumbnail">
        <div class="preview-icon bg-success">
          <i class="mdi mdi-information mx-0"></i>
        </div>
    </div>
    <div class="preview-item-content">
        <h6 class="preview-subject font-weight-normal">Logement en mode normal</h6>
        <p class="font-weight-light small-text mb-0 text-muted">
          click to go update
        </p>
    </div>
  </a>
    ';
    $nbr_nts=$nbr_nts+1;
  }
//Notifications de localisation de logement:
  $reqN="SELECT * from logement where CodeP=?";
  $statementN=$conn->prepare($reqN);
  $statementN->bind_param("i",$codeU);
  $statementN->execute();
  $resN=$statementN->get_result();
  $notifs="";
  $cnt=1;
  $CodeL1;
  $CodeL2;
  while(($rowN = mysqli_fetch_array($resN)))
  {
    
    if($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=1)
     { 
       $CodeL1=$rowN['CodeL'];
       $notifs=" <a id='LLoc1' class='dropdown-item preview-item'>
             <div class='preview-thumbnail'>
               <div class='preview-icon bg-success'>
                <i class='fas fa-map-marked-alt'></i>
               </div>
             </div>
             <div class='preview-item-content'>
               <h6 class='preview-subject font-weight-normal'>Localiser votre ".$rowN['type']." '".$rowN['nom']."'</h6>
               <p class='font-weight-light small-text mb-0 text-muted'>
                 Just now
               </p>
             </div>
           </a>";
           $cnt=$cnt+1;
           $nbr_nts=$nbr_nts+1;
     }  
    else if ($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=2)
     {
      if($rowN['lng']==NULL && $rowN['lat']==NULL && $cnt=1)
      { 
        $CodeL2=$rowN['CodeL'];
        $notifs=" <a id='LLoc2' class='dropdown-item preview-item'>
              <div class='preview-thumbnail'>
                <div class='preview-icon bg-success'>
                 <i class='fas fa-map-marked-alt'></i>
                </div>
              </div>
              <div class='preview-item-content'>
                <h6 class='preview-subject font-weight-normal'>Localiser votre ".$rowN['type']." '".$rowN['nom']."'</h6>
                <p class='font-weight-light small-text mb-0 text-muted'>
                  Just now
                </p>
              </div>
            </a>";
            $cnt=$cnt+1;
            $nbr_nts=$nbr_nts+1;
      } 
     }    
  }
//user notifications(saves/ratings)
  $user_notis="";
  $reqN="SELECT * from user_notis where CodeP=? and status='old'";
  $statementN=$conn->prepare($reqN);
  $statementN->bind_param("i",$codeU);
  $statementN->execute();
  $resN=$statementN->get_result();
  while(($rowN = mysqli_fetch_array($resN)))
  {
    $user=$rowN['CodeU'];
    $action=$rowN['action'];
    $logement=$rowN['CodeL'];
    $nt_code=$rowN['idN'];

    $reqU="SELECT * from utilisateur where CodeU=?";
    $statementU=$conn->prepare($reqU);
    $statementU->bind_param("i",$user);
    $statementU->execute();
    $resU=$statementU->get_result();
    $rowU=$resU->fetch_assoc();

    $nt_usern=$rowU['username'];

    $reqU="SELECT * from logement where CodeL=?";
    $statementU=$conn->prepare($reqU);
    $statementU->bind_param("i",$logement);
    $statementU->execute();
    $resU=$statementU->get_result();
    $rowU=$resU->fetch_assoc();
    $nt_loge=$rowU['nom'];
    

    if($action=='saved')
     {
       $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
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
                      //$nbr_nts=$nbr_nts+1;
     }
    else if($action=='rated')
     {
        $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
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
                     // $nbr_nts=$nbr_nts+1;
     }
    else if($action=='commented') 
     {
       $user_notis.=" <a id='".$nt_code."' class='dropdown-item preview-item'>
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
                    //  $nbr_nts=$nbr_nts+1;
     }
    

  }
  $modifnotif="";
    $reqNT="SELECT * FROM `demande` where codeP=?";
    $statementNT=$conn->prepare($reqNT);
    $statementNT->bind_param("i",$codeU);
    $statementNT->execute();
    $resNT=$statementNT->get_result();
    while(($rowNT = mysqli_fetch_array($resNT)))
    {

      $modifnotif.='
      <a class="dropdown-item preview-item" href="../Gestions/Logement/ModifierLog.php?idL='.$rowNT['CodeL'].'">
        <div class="preview-thumbnail">
          <div class="preview-icon bg-info">
            <i class="mdi mdi-home-modern mx-0"></i>
          </div>
        </div>
        <div class="preview-item-content">
            <h6 class="preview-subject font-weight-normal">demande de modification</h6>
            <p class="font-weight-light small-text mb-0 text-muted">
              '.$rowNT['motiv'].'
            </p>
        </div>
      </a>
      ';
    }

$liste_locat="";
$reqSRS="SELECT *  from `liste_locataire` where CodeL=? ";
  $statementSRS=$conn->prepare($reqSRS);
  $statementSRS->bind_param("i",$CodeL);
  $statementSRS->execute();
  $resSRS=$statementSRS->get_result();
  while(($rowSRS=mysqli_fetch_array($resSRS)))

  {
   $CodeSRSU=$rowSRS['Code_Locataire'];
   $reqSRS="SELECT *  from `utilisateur` where CodeU=? ";
   $statementSRS=$conn->prepare($reqSRS);
   $statementSRS->bind_param("i",$CodeSRSU);
   $statementSRS->execute();
   $resSRS1=$statementSRS->get_result();
   $rowSRS1=$resSRS1->fetch_assoc();
   $UserSRS1=$rowSRS1['username'];
   
   if($rowSRS1['imageP']!=NULL)
      {
        $srcSRS1="../UserPages/profilpic.php?UN=$UserSRS1";
        $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
      }
    else
      {
        $srcSRS1="../../Resourse/imgs/ProfileHolder.jpg";
        $ProfilePSRS1="<img src='".$srcSRS1."' class='img img-rounded img-fluid'/>";
      }
      $liste_locat.= "<li class='user-item'>
     <span class='avatar'>
         $ProfilePSRS1
     </span>
     <h5>".$UserSRS1."</h5>
     <h6>".$UserSRS1."</h6>";
     if($rowSRS1['CodeU']!=$codeU)
     {
      $liste_locat.=  "<a  class='btn-fllw' id='remove".$CodeSRSU."' ><i class='fas fa-user-times'></i></a>
        </li>";
      }
      else{
         $liste_locat.=  "</li>";
      }
}

$option_loge="";
$reqSRS="SELECT *  from `logement` where CodeP=? ";
   $statementSRS=$conn->prepare($reqSRS);
   $statementSRS->bind_param("i",$codeU);
   $statementSRS->execute();
   $resSRS=$statementSRS->get_result();
   while(($rowSRS=mysqli_fetch_array($resSRS)))
   {
    $option_loge.="<option>".$rowSRS['nom']."</option>";
   }
  


?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kapella Bootstrap Admin Dashboard Template</title>
    <link rel="stylesheet" type="text/css" href="../../Resourse/CSS/semantic.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
 

  <link rel="stylesheet" href="../../Resourse/css2/styleRe.css">
  <link rel="shortcut icon" href="../../Resourse/images/favicon.png" />


  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>
  <link rel="stylesheet" href="../../Resourse/css3/chatbox.css">
  <link href="../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="../../Resourse/Allfooters/Style.css">

  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_horizontal-navbar.html -->
      <div class="horizontal-menu">
        <nav class="navbar top-navbar col-lg-12 col-12 p-0">
          <div class="container-fluid">
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
            
            <ul class="navbar-nav navbar-nav-left">
                <li class="nav-item ml-0 mr-5 d-lg-flex d-none">
                  <a href="#" class="nav-link horizontal-nav-left-menu"><i class="mdi mdi-format-list-bulleted"></i></a>
                </li>
                <li class="nav-item dropdown">
                  <a  id="noti_open" class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell mx-0"></i>
                    <span id="nbrnts" class="count bg-success"><?php if($nbr_nts>0) echo $nbr_nts?></span>
                  </a>
                  <div id="notifs" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p> <br>
                    <hr>
                    <a id="clr_all">Clear all</a>
                    
                    <div id="stsx">
                      
                      </div>
                    <div id="new_user_notis">
                      
                    </div>

                    <div id="loaded_user_notis">
                      
                    </div>
                    
                    <?=$modifnotif?>
                    <div id="old_user_notis">
                    <?=$user_notis?>   
                    </div>
                    <?=$notif; ?>
                    <?=$notifs?>

                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-email mx-0"></i>
                    <span class="count bg-primary">4</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>

                    <?=$ntMsg;?>
                    

                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link count-indicator "><i class="mdi mdi-message-reply-text"></i></a>
                </li>
                <li class="nav-item nav-search d-none d-lg-block ml-3">
                  <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                          <i class="mdi mdi-magnify"></i>
                        </span>
                      </div>
                      <input type="text" class="form-control" placeholder="search" aria-label="search" aria-describedby="search">
                  </div>
                </li>	
              </ul>
  
                
    
              <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                  <a class="navbar-brand" href="dash.html"><img src="../../Resourse/images/logo-1.png" alt="logo"/></a>
              </div>
              <ul class="navbar-nav navbar-nav-right">
                
                
              
                  <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                      <span class="nav-profile-name"><?=$USN?></span>
                      <span class="online-status"></span>
                      <?=$ProfileP?>
                    </a>
                    <form method="post" class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                      <a href="../Gestions/prop/gestionProp.php" class="dropdown-item">
                        <i class="mdi mdi-account text-primary"></i>
                        Mon Compte
                      </a>
                      <a href="../Gestions/Logement/GestionLog.php" class="dropdown-item">
                        <i class="mdi mdi-home-modern text-primary"></i>
                        Les Logement
                      </a>
                      <button name="logoutbtn" class="dropdown-item">
                      <i class="mdi mdi-logout text-primary"></i>
                      Logout
                      </button>
                      <a id="loca_gst" class="dropdown-item " data-toggle='modal' data-target='#modalLikeThis2' >
                           <i class="fas fa-users-cog text-primary"></i>
                      Liste des locatires
                    </a>
                    </form>
                  </li>
              </ul>
              <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <span class="mdi mdi-menu"></span>
              </button>
            </div>
          </div>
        </nav>
      </div>
      <div class="main-panel">
				<div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <h4 class="card-title">Statistiques des vues</h4>
                            <canvas id="lineChart" width="682" height="340" class="chartjs-render-monitor" style="display: block; height: 227px; width: 455px;"></canvas>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <h4 class="card-title">Statistiques des recommandations</h4>
                            <canvas id="barChart" style="display: block; height: 227px; width: 455px;" width="682" height="340" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                        </div>
                    </div>
				</div>
				<!-- content-wrapper ends -->
				<!-- partial:partials/_footer.html -->
       

				<!-- partial -->
			</div>



		  <!-- page-body-wrapper ends -->
    </div>
    <div class='modal' id='modalLikeThis2' tabindex='-1' role='dialog' >
                      
                      <div class='modal-dialog-centered' role='document' style='width:40%;margin-left:30%;margin-top:2%;'>
      
                        <div class='modal-content'>
                           
                          <div class='modal-body' >
                            
                           <div id='the_list' class='the_list'> 
                            <?=$liste_locat;?>
                           </div>
                           <div id='adding_list' class='adding_list'> 
                            
                           </div>
                          <hr style="margin-bottom: 0px;" class="hrAddUser">
			                    <i style="color: blue;margin-left: 97%;" class="fas fa-plus-circle addUser" id="addUser"></i>
                             
                          </div>
                          <div class='modal-footer'>
                            <button type='submit' id='env_avis' type='button' class='btn btn-primary'>Envoyer</button>
                            <button type='cls_avis' class='btn btn-secondary' data-dismiss='modal'>Annuler</button>
                          </div>
                             
                          </div>
                        </div>
                        
    </div>
                    
    <?=$chatboxs; ?>
    








    <!-- container-scroller -->
    <!-- base:js -->
    <script src="../../Resourse/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="../../Resourse/js2/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="../../Resourse/vendors/chart.js/Chart.min.js"></script>
    <script src="../../Resourse/vendors/progressbar.js/progressbar.min.js"></script>

		<script src="../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->

    <!-- chat-box -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>

    <script>
    var $MsgCont = $('.messages-content');
    function updateScrollbar() {
      $MsgCont.mCustomScrollbar('scrollTo', 'bottom');
      }

    function insertMessage(messages) {
      var varI='#input'+messages;
      msg = $(varI).val();
        if(msg!=null){
          $('<div class="message message-personal" >' + msg + '</div>').appendTo('#'+messages+' .mCSB_container');
          $(varI).val(null);
          updateScrollbar();
          msg=null;
        }
      }
      <?=$UISc; ?>
    </script>
    <?=$jsScript; ?>

  
<script>
$(document).ready(function(){  


   $('#LLoc1').click(function(){  
          
    location.href = 'getLocation.php?target=<?=$CodeL1?>&owner=<?=$codeU?>';
       });
       $('#LLoc2').click(function(){  
          
          location.href = 'getLocation.php?target=<?=$CodeL1?>&owner=<?=$codeU?>';
             });    
       
  
 
 });  
</script>

<script>
document.getElementById("notifs").addEventListener('click', function (event) 
         {  
          event.stopPropagation(); 
         });
</script>


<script>
var CodePst=<?=$codeU?>;
var nbrnts=<?=$nbr_nts?>;
var nbrnts2=0;
var audioNT = new Audio('../sound/time-is-now.mp3');




  setInterval(function(){ 
  
//new notis

    $.ajax({  
                          url:"User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:nbrnts,act:'get'},  
                          dataType : 'json',
                          success:function(response){
                           
                            nbrnts=response.result;
                            nbrnts2=response.result;

                            if(nbrnts ><?=$nbr_nts?>)
                            {$('#nbrnts').empty().append(nbrnts);
                              audioNT.play();
                            }
                           nbrnts=<?=$nbr_nts?>;
                           
                             
                          }  
                    });
//loaded notis
                    $.ajax({  
                          url:"Loaded_User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response2){

                            $('#loaded_user_notis').html(response2.echo);
                            nbrnts=response2.result2;
                            if(nbrnts ><?=$nbr_nts?>)
                            {$('#nbrnts').empty().append(nbrnts);}
                            nbrnts=<?=$nbr_nts?>;

                             
                          }  
                    });

//old notis       

$.ajax({  
                          url:"Old_User_Notis.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response3){

                            $('#old_user_notis').html(response3.echo);
                            
                            
                             
                          }  
                    });
    }, 1000);

   


   


   
</script>

<script>
var mns=0;
$(document).ready(function(){  

nt_clsd="Y";

   $('#noti_open').click(function(){  
          if(nt_clsd=="Y")
           {
            $.ajax({  
                          url:"noti_is_old.php",  
                          method:"POST",  
                          data:{CodeP:CodePst,nbrnts:<?=$nbr_nts?>},  
                          dataType : 'json',
                          success:function(response4){
                            mns=response4.result4;
                            if(mns>0)
                            $('#nbrnts').empty().append(mns);
                          // arr=response3.tab;
                           //$('#old_user_notis').insertAdjacentHTML('afterbegin',response3.echo);
                          //  $('#old_user_notis').html();
                            

                             
                          }  
                    });

             nt_clsd="N";
           }
          else if(nt_clsd=="N")
           {
            

            nt_clsd="Y";
           }
          
       });
        
       
  
 
 }); 
</script>

<script>

$(function() {
  var Linedata = {
    labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
    datasets: [{
      label: '# of Votes',
      data: [<?=$Vue[1] ;?>,<?=$Vue[2] ;?>,<?=$Vue[3] ;?>,<?=$Vue[4] ;?>, <?=$Vue[5] ;?>,
        <?=$Vue[6] ;?>, <?=$Vue[7] ;?>, <?=$Vue[8] ;?>, <?=$Vue[9] ;?>, <?=$Vue[10] ;?>,
        <?=$Vue[11] ;?>, <?=$Vue[12] ;?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: false
    }]
  };
  var Bardata = {
    labels: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sept", "Oct", "Nov", "Dec"],
    datasets: [{
      label: '# of Votes',
      data: [<?=$Rec[1] ;?>,<?=$Rec[2] ;?>,<?=$Rec[3] ;?>,<?=$Rec[4] ;?>, <?=$Rec[5] ;?>,
        <?=$Rec[6] ;?>, <?=$Rec[7] ;?>, <?=$Rec[8] ;?>, <?=$Rec[9] ;?>, <?=$Rec[10] ;?>,
        <?=$Rec[11] ;?>, <?=$Rec[12] ;?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: false
    }]
  };


  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }
  };
  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: Bardata,
      options: options
    });
  }

  if ($("#lineChart").length) {
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: Linedata,
      options: options
    });
  }

});

</script>



<footer id="fh5co-footer" role="contentinfo">
		<div class="container" id="footertext">
			<div class="row row-pb-md">
				<div class="col-md-4 fh5co-widget">
					<h3  style="color:white;">ESRENT</h3>
					<p  style="color:white;">Site web qui permetra aux client de trouver facilement un logement qui respect leurs criteres de qualité et aux propriétaire d'atendre un grand nombre de loueurs potentiels</p>
					<p><a href="#"  style="color:white;">Learn More</a></p>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-6 col-md-push-1">
					<ul class="fh5co-footer-links">
						<li><a href="#">À PROPOS</a></li>
						<li><a href="#">Contact</a></li>
						<li><a href="#">termes</a></li>
						<li><a href="#">Aidez-moi</a></li>
				
					</ul>
				</div>

				<div class="col-md-2 col-sm-4 col-xs-6 col-md-push-1">
					<ul class="fh5co-footer-links">
						<li><a href="#">PAGES</a></li>
						<li><a href="#">Accueil</a></li>
						<li><a href="#">recherche avancée</a></li>
						<li><a href="#">Handbook</a></li>
						<li><a href="#">voir plus</a></li>
					</ul>
				</div>

				
			</div>

			<div class="row copyright">
				<div class="col-md-12 text-center">
					<p>
						<small class="block"  style="color:white;">&copy; 2020 ESRENT</small> 
						<small class="block"  style="color:white;"> All Rights Reserved.</small>
					</p>
					<p>
					
					</p>
				</div>
			</div>

		</div>
	</footer>
</body>
</html>

<script>
var entery_num=1;
var selected_loge="";
var selected_user="";

$(document).ready(function(){  



  $('#addUser').click(function(){
    
    
    if(entery_num!=1)
    {
      
    document.getElementById('#addUser').disabled=true;
    }
    
    else{
    document.getElementById('adding_list').insertAdjacentHTML("beforeend","<li class='user-item'><select  class='add_slct ' name='random' id='slct"+entery_num+"'><?=$option_loge?></select>&nbsp;<br><input type='text' class='form-control add_input' id='input"+entery_num+"' placeholder='username'></input><div class='err_add' id='err"+entery_num+"'></div> <button class='add_bttn' id='cnfrm"+entery_num+"'>Confirmer</button></li>");
    
    $('#cnfrm'+entery_num).click(function(){
      
     
      selected_loge=document.querySelector('#slct'+entery_num).value;
      selected_user=document.querySelector('#input'+entery_num).value;
      $.ajax({  
                url:"addLocataire.php",  
                method:"POST",  
                data:{logement:selected_loge,
                      user:selected_user

                      },
                dataType : 'json',
                success:function(response){
                  //alert(response.result+"--->"+response.display)

                   if(response.result=="found")
                    {
                      document.getElementById('the_list').insertAdjacentHTML("beforeend",response.display)
                      $("#adding_list").html("");
                    }
                   else 
                    {
                      $("#err"+entery_num).html("");
                      document.getElementById('err'+entery_num).insertAdjacentHTML("beforeend",'<i class="fas fa-exclamation-circle"></i>'+response.display)
                    }
                  
                  
                 }   
            });           

          });
  
          entery_num=entery_num+1;
          $('#addUser').css("color","grey");}
  });
  
});  
</script>



<style>
  .err_add{
    color:red;
  }
  .add_input{
    width:30%;
    height:30px;


  }

  .add_slct{
    margin-bottom:3%;
    background-color: white;
  border: thin solid blue;
  border-radius: 4px;
  display: inline-block;
  font: inherit;
  line-height: 1.5em;
  padding: 0.5em 3.5em 0.5em 1em;

  /* reset */

    
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;

  background-image:
    linear-gradient(45deg, transparent 50%, gray 50%),
    linear-gradient(135deg, gray 50%, transparent 50%),
    linear-gradient(to right, #ccc, #ccc);
  background-position:
    calc(100% - 20px) calc(1em + 2px),
    calc(100% - 15px) calc(1em + 2px),
    calc(100% - 2.5em) 0.5em;
  background-size:
    5px 5px,
    5px 5px,
    1px 1.5em;
  background-repeat: no-repeat;
  }

  .add_slct:focus{
    background-image:
    linear-gradient(45deg, green 50%, transparent 50%),
    linear-gradient(135deg, transparent 50%, green 50%),
    linear-gradient(to right, #ccc, #ccc);
  background-position:
    calc(100% - 15px) 1em,
    calc(100% - 20px) 1em,
    calc(100% - 2.5em) 0.5em;
  background-size:
    5px 5px,
    5px 5px,
    1px 1.5em;
  background-repeat: no-repeat;
  
  outline: 0;
  
  }
  select:-moz-focusring {
  color: transparent;
  text-shadow: 0 0 0 #000;
}

















  .user-list {
	position: relative;
	list-style: none;
	overflow-y: scroll;
	width: 100%;
	height: 298px;
	height: 328px;
	margin: 40px auto;
	border: 2px solid #ededed;
  }
  .user-list:before {
	content: "";
	display: block;
	position: absolute;
	z-index: 3;
	top: 0;
	left: 0;
	width: 100%;
	height: 2px;
	background: #fff;
  }
  .user-list:after {
	content: "";
	display: block;
	position: absolute;
	z-index: 3;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 2px;
	background: #fff;
  }
  
  .user-item {
	position: relative;
	overflow: hidden;
	padding: 10px 10px 12px;
	border-bottom: 2px solid #fafafa;
  }
  .user-item:last-child {
	border-bottom: 0;
  }
  .user-item:last-child:after {
	content: "";
	display: block;
	position: absolute;
	z-index: 30;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 2px;
	background: #fff;
  }
  .user-item .avatar {
	display: block;
	position: relative;
	width: 50px;
	height: 50px;
	border-radius: 5px;
	overflow: hidden;
	float: left;
	margin-right: 1em;
  }
  .user-item .avatar:after {
	content: "";
	display: block;
	position: absolute;
	z-index: 2;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	box-shadow: inset 0 0 0 2px rgba(0, 0, 0, 0.15);
	border-radius: 5.5px;
  }
  .user-item .avatar img {
	display: block;
	width: 100%;
	height: auto;
  }
  .user-item h5 {
	font-size: 1.5em;
	color: #555;
  }
  .user-item h6 {
	font-size: 1em;
	color: #888;
  }
  .user-item .btn-fllw {
	position: absolute;
	top: 14px;
	right: 14px;
	line-height: 40px;
	font-size: 0.875em;
	font-weight: bold;

  }
  .user-item .btn-fllw:hover {
	color: #fff;
	background-color: #55bad9;
	border: 2px solid #46a1bd;
	text-decoration: none;
  }
</style>