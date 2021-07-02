
<?php
session_start();
if( isset($_SESSION['username']))
{
  header("location:../../homeP.php");
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

$msg_errE="";
$msg_errU="";
if(isset($_POST['Sign']))
    {
 	  $inUsername=$_POST['Username'];
      $inEmail=$_POST['Email'];
      $inPass=$_POST['PasswordIn'];
      $inPass=sha1($inPass);
      //test si le nom d'utilisateur existe déjà
      $reqU = "SELECT * from utilisateur where username=?";
      $statementU=$conn->prepare($reqU);
      $statementU->bind_param("s",$inUsername);
      $statementU->execute();
      $resU=$statementU->get_result();

      //test si l'email existe déjà
      $reqE = "SELECT * from utilisateur where email=?";
      $statementE=$conn->prepare($reqE);
      $statementE->bind_param("s",$inEmail);
      $statementE->execute();
      $resE=$statementE->get_result();
      

      if ($resE->num_rows==0 and $resU->num_rows==0)
       {
        if(!empty($_POST['agree']))
        //Insertion des données 
        {
          $reqI = "INSERT INTO `utilisateur`(`username`, `email`, `pass`, `type`) VALUES (?,?,?,'normal')";
          $statementI=$conn->prepare($reqI);
          $statementI->bind_param("sss",$inUsername,$inEmail,$inPass);
          $statementI->execute();
          session_regenerate_id();
          $_SESSION['usercode'] = $conn->insert_id;
	        $_SESSION['username']=$inUsername;
	        $_SESSION['type']='normal';
	        session_write_close();
          header("Location:../../Pages/UserPages/User.php");
        }
        else{
          echo"You didnt agree to the terms";
        }
       }
      else
       {
        if ($resE->num_rows!=0)
         {
          $msg_errE="<i class='fas fa-exclamation-circle'></i> Email existe déjà";
         }
        if ($resU->num_rows!=0)
         {
        $msg_errU="<i class='fas fa-exclamation-circle'></i> Nom d'utilisateur existe déjà";
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
  <!-- base:css -->
  <link rel="stylesheet" href="../../Resourse/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../Resourse/vendors/base/vendor.bundle.base.css">
  <link href="../../vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../Resourse/css2/register.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../Resourse/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="../../Resourse/images/logo-1.png" alt="logo">
                </div>
                <h4>New here?</h4>
                <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" action="<?= $_SERVER['PHP_SELF'] ?> " method="post">
                  <div class="form-group-err">
                     <h5 class="msg_err">  <?=$msg_errU; ?></h5>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" id="exampleInputUsername1" value="<?php if(isset($_POST['Sign'])) echo htmlentities($_POST['Username']); ?>" name="Username" placeholder="Username">
                  </div>


                  <div class="form-group-err">
                     <h5 class="msg_err">  <?=$msg_errE; ?></h5>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="exampleInputEmail1"  value="<?php if(isset($_POST['Sign'])) echo htmlentities($_POST['Email']); ?>" name="Email" placeholder="Email">
                  </div>

                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="PasswordIn" placeholder="Password">
                  </div>
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" name="agree" value="N">
                        I agree to all Terms & Conditions
                      </label>
                    </div>
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="Sign">SIGN UP</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                    Already have an account? <a href="login.php" class="text-primary">Login</a>
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
