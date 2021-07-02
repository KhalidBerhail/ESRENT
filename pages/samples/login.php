<?php
session_start();
if( isset($_SESSION['username']))
{
  header("location:../../homeP.php");
}
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
$errorlogin="";
$errorpass="";


if(isset($_POST['connect']))
 {
  
  $login = $_POST["login"];
  $password = $_POST["password"];
  $password = sha1($password);
  $req = "SELECT * from utilisateur where  email=BINARY? or username=BINARY? ";
  $statement=$conn->prepare($req);
  $statement->bind_param("ss",$login,$login);
  $statement->execute();
  $res=$statement->get_result();
  $row=$res->fetch_assoc();
  
  if(($res->num_rows==1) && ($row['pass']==$password))
  {
    $CodeU=$row['CodeU'];
    session_regenerate_id();
    $_SESSION['usercode'] = $CodeU; 
    $_SESSION['username'] = $row['username'];
    $_SESSION['type']=$row['type'];
    session_write_close();
      if($_SESSION['type'] == "admin")
      header("Location:../AdminPages/dash.php");
      else if($_SESSION['type'] == "normal")
        header("Location:../UserPages/User.php");
      else if($_SESSION['type'] == "pro")
        header("Location:../PropPages/Prop.php");

  }else if ($res->num_rows!=1)
  {
    $errorlogin="<i class='fas fa-exclamation-circle'></i> email ou username inexistant";
  }else if ($row['pass']!=$password)
  {
    $errorpass="<i class='fas fa-exclamation-circle'></i> password error";
  }


 }

?>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kapella Bootstrap Admin Dashboard Template</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
  <link href="../../vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../Resourse/CSS/Login.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
</head>

<body >
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper  full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="../../Resourse/images/logo-1.png" alt="logo">
                </div>
                <h4>Connectez-vous pour continuer.</h4>
                
                <form class="pt-3"  method="post" >
                  <div class="form-group-err">
                    <h5 class="msg_err"><?=$errorlogin?></h5>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" value="<?php if(isset($_POST['connect'])) echo htmlentities($_POST['login']); ?>" name="login" id="exampleInputEmail1" placeholder="Username">
                  </div>

                  <div class="form-group-err">
                    <h5 class="msg_err"><?=$errorpass?></h5>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" value="<?php if(isset($_POST['connect'])) echo htmlentities($_POST['password']); ?>" name="password" id="exampleInputPassword1" placeholder="Password">
                  </div>

                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="connect">SE CONNECTER</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input">
                        Gardez-moi connecté!
                      </label>
                    </div>
                    <a href="enter_email.php" class="auth-link text-black">Mot de passe oublié?</a>
                  </div>
                  <div class="mb-2">
                    <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="mdi mdi-facebook mr-2"></i>se connecter avec facebook
                    </button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                  Vous n'avez pas de compte? <a href="register.php" class="text-primary">Créer</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="../../Resourse/vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../Resourse/js2/template.js"></script>
  <!-- endinject -->
</body>

</html>
