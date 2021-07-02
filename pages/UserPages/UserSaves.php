<?php
session_start();
if(isset($_POST['logoutbtn'])) 
{
	unset($_SESSION['type']);
	unset($_SESSION['username']);
}
if( !isset($_SESSION['username']) || $_SESSION['type'] != "normal" )
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


$reqS="SELECT * from saves where CodeU=?";
$statementS=$conn->prepare($reqS);
$statementS->bind_param("i",$codeU);
$statementS->execute();
$resS=$statementS->get_result();
$pos=1;
$saves="";

if(mysqli_num_rows($resS)==0) 
{
    $saves.="   <div id='No-Saves' class='center-side'>
                        <img src='../../Resourse/imgs/userimgs/real-estate.png'/><br>
                        <p><h3>Vous n'avez aucun enregistrement</h3><p>

                 </div><!-- end center-side -->";
}
else
{
  while(($rowS = mysqli_fetch_array($resS)))
  {
    $reqS1="SELECT * from logement where CodeL=?";
    $statementS1=$conn->prepare($reqS1);
    $statementS1->bind_param("i",$rowS['CodeL']);
    $statementS1->execute();
    $resS1=$statementS1->get_result(); 
    $rowS1=$resS1->fetch_assoc();
    $TitreS=$rowS1['nom'];
    $TypeS=$rowS1['type'];
    $PrixS=$rowS1['prix'];
    $CodePS=$rowS1['CodeP'];
    $reqS1="SELECT * from utilisateur where CodeU=?";
    $statementS1=$conn->prepare($reqS1);
    $statementS1->bind_param("i",$CodePS);
    $statementS1->execute();
    $resS1=$statementS1->get_result(); 
    $rowS1=$resS1->fetch_assoc();
    $UsernameS=$rowS1['username'];

    $reqI="SELECT * FROM image where CodeL=? limit 1";
    $statementI=$conn->prepare($reqI);
    $statementI->bind_param("i",$rowS['CodeL']);
    $statementI->execute();
    $resI=$statementI->get_result();
    $rowI=$resI->fetch_assoc();
    $id_img=$rowI['CodeImg'];
    $srcL="genere_image.php?id=$id_img";
    $nbrimg=0;
     if($pos==1)
      {
        $nbrimg=$nbrimg+1;
        $saves.=" <div class='row'>  <div class='left-side'>
                        <div class='masonry-box post-media'>
                            <img  src='".$srcL."' alt='' class='img-fluid'>
                            <div id='".$rowS['CodeL']."' class='shadoweffect imgdisp'>
                                <div class='shadow-desc'>
                                    <div class='blog-meta'>
                                      <span class='bg-aqua'><a href='' title=''>".$TypeS."</a></span>
                                      <h4><a href='SeeMore.php?smr=".$rowS['CodeL']."' title=''>".$TitreS."</a></h4>
                                      <small><a href='' title=''>".$PrixS." DH</small>
                                      <small><a href='' title=''>by ".$UsernameS."</a></small>
                                    </div><!-- end meta -->
                                </div><!-- end shadow-desc -->
                             </div><!-- end shadow -->
                        </div><!-- end post-media -->
                    </div><!-- end left-side -->";
        $pos=$pos+1;
      }
     else if($pos==2)
      {
        $nbrimg=$nbrimg+1;
        $saves.="   <div class='center-side'>
                        <div class='masonry-box post-media'>
                            <img  src='".$srcL."' alt='' class='img-fluid'>
                            <div id='".$rowS['CodeL']."' class='shadoweffect imgdisp'>
                                <div class='shadow-desc'>
                                    <div class='blog-meta'>
                                      <span class='bg-aqua'><a href='' title=''>".$TypeS."</a></span>
                                      <h4><a href='SeeMore.php?smr=".$rowS['CodeL']."' title=''>".$TitreS."</a></h4>
                                      <small><a href='' title=''>".$PrixS."</a></small>
                                      <small><a href='' title=''>by ".$UsernameS."</a></small>
                                    </div><!-- end meta -->
                                </div><!-- end shadow-desc -->
                             </div><!-- end shadow -->
                        </div><!-- end post-media -->
                    </div><!-- end center-side -->";
        $pos=$pos+1;
      }
     else if($pos==3)
      {
        $nbrimg=$nbrimg+1;
         $saves.="   <div class='right-side-side'>
                        <div class='masonry-box post-media'>
                            <img   src='".$srcL."' alt='' class='img-fluid'>
                            <div id='".$rowS['CodeL']."' class='shadoweffect imgdisp'>
                                <div class='shadow-desc'>
                                    <div class='blog-meta'>
                                      <span class='bg-aqua'><a href='' title=''>".$TypeS."</a></span>
                                      <h4><a href='SeeMore.php?smr=".$rowS['CodeL']."' title=''>".$TitreS."</a></h4>
                                      <small><a href='' title=''>".$PrixS."</a></small>
                                      <small><a href='' title=''>by ".$UsernameS."</a></small>
                                    </div><!-- end meta -->
                                </div><!-- end shadow-desc -->
                             </div><!-- end shadow -->
                        </div><!-- end post-media -->
                    </div><!-- end right-side --></div>";
                    
         $pos=1;
      }
   
  }
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
 
  <link rel="stylesheet" href="../../Resourse/ForUserPage/styleCardd.css">
  <link rel="stylesheet" href="../../Resourse/ForUserPage/css/grid2.css">
  <link rel="stylesheet" href="../../Resourse/ForUserPage/css/colors.css">
  <link rel="stylesheet" href="../../Resourse/ForUserPage/css/responsive.css">

  <link rel="stylesheet" href="../../Resourse/css2/styleUser.css">
  <link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="../../Resourse/cssSm/font-awesome.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>
  <link rel="stylesheet" href="../../Resourse/css3/chatbox.css">


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
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                  <i class="mdi mdi-bell mx-0"></i>
                  <span class="count bg-success">2</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                  <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-success">
                          <i class="mdi mdi-information mx-0"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal">Application Error</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                          Just now
                        </p>
                    </div>
                  </a>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-warning">
                          <i class="mdi mdi-settings mx-0"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal">Settings</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                          Private message
                        </p>
                    </div>
                  </a>
                  <a class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                        <div class="preview-icon bg-info">
                          <i class="mdi mdi-account-box mx-0"></i>
                        </div>
                    </div>
                    <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal">New user registration</h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                          2 days ago
                        </p>
                    </div>
                  </a>
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
                <form class="input-group" action="searshResult.php" methode="POST">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="search">
                        <i class="mdi mdi-magnify"></i>
                      </span>
                    </div>
                    <input type="text" name="rech" class="form-control" placeholder="Search a very wide input..." aria-label="search" aria-describedby="search">
                </form>
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
                    <a class="dropdown-item" href="../Gestions/User/Gestionuser.php">
                      <i class="mdi mdi-account text-primary"></i>
                      Mon Compte
                    </a>
                    <a href="UserSaves.php" class="dropdown-item">
                      <i class="mdi mdi-heart text-primary"></i>
                      Enregistrements
                    </a>
                    <button name="logoutbtn" class="dropdown-item">
                    <i class="mdi mdi-logout text-primary"></i>
                    Logout
                    </button>
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
    <div class="container-fluid">
    <br>
    <nav aria-label="breadcrumb">
  <ol class="SectionName">
    <p class="breadcrumb-item active" aria-current="page">Vos logements enregistrés</p>
  </ol>
</nav>
    <div class="container-fluid">
                <div class="masonry-blog clearfix">
                    <?=$saves?>
                </div><!-- end masonry -->
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
		<script src="../../Resourse/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js"></script>
		<script src="../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->
    <script src="../../Resourse/js2/Card.js"></script>

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
      /* 
            setInterval(function() {
      showdata="";
      $.ajax({  
                url:"chatmsg.php",  
                method:"GET",  
                data:{sender,reciever:},  
                success:function(data){
                   if(showdata!=data)
                   {
                     showdata=data;
                     $('.mCSB_container').html(data);
                   }
                }  
           });
                 updateScrollbar(); 
   }, 1000);
      */

    
    </script>
    <?=$jsScript; ?>

<script>
var idImg;
$(document).ready(function(){

$(document).on('click','.imgdisp',function(){
  
      idImg=$(this).attr('id') ;
      window.location.href = "SeeMore.php?smr="+idImg;
  });
});  
</script>

</body>
</html>