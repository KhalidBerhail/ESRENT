<?php
session_start();

if( !isset($_SESSION['username']) || $_SESSION['type'] != "admin" )
{
  header("location:../../../indexx.php");
}

$servername = "localhost";
$userservername = "root";
$database = "pfe";
// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$USN=$_SESSION['username'];
$reqIU="SELECT * FROM utilisateur WHERE username=?";
$statementIU=$conn->prepare($reqIU);
$statementIU->bind_param("s",$USN);
$statementIU->execute();
$resIU=$statementIU->get_result();
$rowIU=$resIU->fetch_assoc();
if($rowIU['imageP']!=NULL)
  $src="../profilpic.php?UN=".$USN;
else
	$src="../../../Resourse/imgs/ProfileHolder.jpg";
  $password_conf=$rowIU['pass'];
  $id=$rowIU['CodeU'];
  $date=new DateTime($rowIU['date']);
  $dateToin = $date->format('F d, Y');
  $req="SELECT * FROM `admin` WHERE CodeAd=?";
  $statement=$conn->prepare($req);
  $statement->bind_param("i",$id);
  $statement->execute();
  $res=$statement->get_result();
  $row=$res->fetch_assoc();
  $nom=$row['nom'];
  $prenom=$row['prenom'];
  if(isset($_POST['modif']))
  {
    $newpass=$_POST['newpass'];
    $confnewpass=$_POST['confnewpass'];
    $oldpass=$_POST['oldpass'];
    $oldpass=sha1($oldpass);
    if($oldpass==$password_conf)
    {
      if($newpass==$confnewpass)
        {
          $newpass=sha1($newpass);
          $req="UPDATE `utilisateur` SET `pass`=? WHERE CodeU=?";
          $statement=$conn->prepare($req);
          $statement->bind_param("ss",$newpass,$id);
          $statement->execute();
          header("Location: GestionAdmin2.php");
        }else
        echo"erreur nouveau mot de passe";
    }else
    echo"ancien mot de passe erreur";
  }


?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from https://bootdey.com  -->
    <!--  All snippets are MIT license https://bootdey.com/license -->
    <title>Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://mythemestore.com/friend-finder/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css'>
    <style type="text/css">
    	body{
    background:#eee;    
}
.widget-author {
  margin-bottom: 58px;
}
.author-card {
  position: relative;
  padding-bottom: 48px;
  background-color: #fff;
  box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
}
.author-card .author-card-cover {
  position: relative;
  width: 100%;
  height: 100px;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
.author-card .author-card-cover::after {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  content: '';
  opacity: 0.5;
}
.author-card .author-card-cover > .btn {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 0 10px;
}
.author-card .author-card-profile {
  display: table;
  position: relative;
  margin-top: -22px;
  padding-right: 15px;
  padding-bottom: 16px;
  padding-left: 20px;
  z-index: 5;
}
.author-card .author-card-profile .author-card-avatar, .author-card .author-card-profile .author-card-details {
  display: table-cell;
  vertical-align: middle;
}
.author-card .author-card-profile .author-card-avatar {
  width: 85px;
  border-radius: 50%;
  box-shadow: 0 8px 20px 0 rgba(0, 0, 0, .15);
  overflow: hidden;
}
.author-card .author-card-profile .author-card-avatar > img {
  display: block;
  width: 100%;
}
.author-card .author-card-profile .author-card-details {
  padding-top: 20px;
  padding-left: 15px;
}
.author-card .author-card-profile .author-card-name {
  margin-bottom: 2px;
  font-size: 14px;
  font-weight: bold;
}
.author-card .author-card-profile .author-card-position {
  display: block;
  color: #8c8c8c;
  font-size: 12px;
  font-weight: 600;
}
.author-card .author-card-info {
  margin-bottom: 0;
  padding: 0 25px;
  font-size: 13px;
}
.author-card .author-card-social-bar-wrap {
  position: absolute;
  bottom: -18px;
  left: 0;
  width: 100%;
}
.author-card .author-card-social-bar-wrap .author-card-social-bar {
  display: table;
  margin: auto;
  background-color: #fff;
  box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .11);
}
.btn-style-1.btn-white {
    background-color: #fff;
}
.list-group-item i {
    display: inline-block;
    margin-top: -1px;
    margin-right: 8px;
    font-size: 1.2em;
    vertical-align: middle;
}
.mr-1, .mx-1 {
    margin-right: .25rem !important;
}

.list-group-item.active:not(.disabled) {
    border-color: #e7e7e7;
    background: #fff;
    color: #ac32e4;
    cursor: default;
    pointer-events: none;
}
.list-group-flush:last-child .list-group-item:last-child {
    border-bottom: 0;
}

.list-group-flush .list-group-item {
    border-right: 0 !important;
    border-left: 0 !important;
}

.list-group-flush .list-group-item {
    border-right: 0;
    border-left: 0;
    border-radius: 0;
}
.list-group-item.active {
    z-index: 2;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.list-group-item:last-child {
    margin-bottom: 0;
    border-bottom-right-radius: .25rem;
    border-bottom-left-radius: .25rem;
}
a.list-group-item, .list-group-item-action {
    color: #404040;
    font-weight: 600;
}
.list-group-item {
    padding-top: 16px;
    padding-bottom: 16px;
    -webkit-transition: all .3s;
    transition: all .3s;
    border: 1px solid #e7e7e7 !important;
    border-radius: 0 !important;
    color: #404040;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    text-decoration: none;
}
.list-group-item {
    position: relative;
    display: block;
    padding: .75rem 1.25rem;
    margin-bottom: -1px;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,0.125);
}
.list-group-item.active:not(.disabled)::before {
    background-color: #ac32e4;
}

.list-group-item::before {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background-color: transparent;
    content: '';
}
.penclass {
    background-color: #464dee;;
    border-radius: 100px;
    height: 30px;}

.pen{

  width: 30px;
  border-radius: 100px;
height: 30px;
  padding: 2px;
  background: #464dee;
color:white;
position: absolute;
margin-left: -30px;
margin-top: 60px;


}
#file{
  display: none;
}
.inputs-marg
{
  margin-top:5%;
}
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row inputs-marg">
        <div class="col-lg-4 pb-5">
            <!-- Account Sidebar-->
            <div class="author-card pb-3">
                <div class="author-card-cover"  style="background-image: url(https://demo.createx.studio/createx-html/img/widgets/author/cover.jpg);"><a class="btn btn-style-1 btn-white btn-sm" href="#" data-toggle="tooltip" title="" data-original-title="You currently have 290 Reward points to spend"><i class="fa fa-award text-md"></i>&nbsp;Admin</a></div>
                <div class="author-card-profile" > 
                    <div class="author-card-avatar" style="z-index:0;"> 
                        <img id="item-img-output" src="<?=$src ;?>" alt="Daniel Adams" class="target" style="z-index:-1;">
                    </div>
                    <div class="penclass" style="z-index:1;" >
                        <label for="file" class="pen"><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2" style="padding: 3px;margin-left: 1px;"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg></label>
                        <input type="file" id="file" name="upload_image" accept="image/*" /> 
                    </div>
                    <div class="author-card-details" style="z-index:0;">                    
                        <h5 class="author-card-name text-lg" style="z-index:0;"><?=$nom?> <?=$prenom?></h5><span class="author-card-position">Joined <?=$dateToin?></span>
                    </div>
                </div>
            </div>
            <div class="wizard">
                <nav class="list-group list-group-flush">
                  <a class="list-group-item " href="GestionAdmin.php"><i class="fe-icon-user text-muted"></i>Information génerale</a>
                  <a class="list-group-item active" href="GestionAdmin2.php"><i class="fe-icon-map-pin text-muted"></i>Sécurité</a>
                </nav>
            </div>
        </div>
        <!-- Profile Settings-->
        <div class="col-lg-8 pb-5">
            <form class="row" method="POST">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-fn">nouveau mot de pass</label>
                        <input class="form-control" type="password" name="newpass" id="account-fn" required="">
                    </div>
                </div>
               
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-pass">confirmation du nouveau mot de pass</label>
                        <input class="form-control" type="password" name="confnewpass" id="account-pass">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-confirm-pass">ancien mot de pass</label>
                        <input class="form-control" type="password" name="oldpass" id="account-confirm-pass">
                    </div>
                </div>
                <div class="col-12">
                    <hr class="mt-2 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="custom-control custom-checkbox d-block" style="visibility:hidden">
                            <input class="custom-control-input" type="checkbox" id="subscribe_me" checked="">
                            <label class="custom-control-label" for="subscribe_me">Confirmation</label>
                        </div>
                        <button class="btn btn-style-1 btn-primary" name="modif" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!" data-toast-message="Your profile updated successfuly.">Enregister</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Upload & Crop Image</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8 text-center">
                <div id="image_demo" style="width:350px; margin-top:30px"></div>
              </div>
              <div class="col-md-4" style="padding-top:30px;">
                <button id="cropImageBtn" class="btn btn-success crop_image">Crop & Upload Image</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</body>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js'></script>
  <script src='https://mythemestore.com/friend-finder/js/bootstrap.min.js'></script>
  <script >
    vanilla = $("#image_demo").croppie({
	enableExif: true,
	viewport: { width: 200, height: 200, type: "square" }, // circle or square
	boundary: { width: 300, height: 300 },
	showZoomer: false,
	enableOrientation: true
  });
  $("#file").on("change", function() {
	var reader = new FileReader();
	reader.onload = function(event) {
	  vanilla
		.croppie("bind", {
		  url: event.target.result
		})
		.then(function() {
		  // console.log('jQuery bind complete');
		});
	};
	reader.readAsDataURL(this.files[0]);
	$("#uploadimageModal").modal("show");
  });
  $("#cropImageBtn").click(function(event) {
	vanilla.croppie('result', {
			type: 'base64',
			format: 'jpeg'
			
		}).then(function (resp) {
      idUser=<?=$_SESSION['usercode'];?>;

      var formData = new FormData();
      formData.append('file', $('#file')[0].files[0]);

      $.ajax({  
          url:"../updateImage.php",  
          method:"POST",
          data:{image:resp,iduser:idUser},
          success:function(data){
            $('#uploadimageModal').modal('hide');
            $('#item-img-output').attr('src', resp);
          }
          });
		});
  });
  $(".vanilla-rotate").on("click", function(event) {
	vanilla.rotate(parseInt($(this).data("deg")));
  });

  </script>

</html>