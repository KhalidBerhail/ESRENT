<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

session_start();
// connect to database
  $servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  $errmsg = "";

// Create connection
$conn = new mysqli($servername, $userservername,"", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 






if (isset($_POST['reset-password'])) {
  $email = $_POST['email'];
  if($email!="")
  {  
        $email = $_POST['email'];
        $req = "select * from utilisateur where email=?";
        $statement=$conn->prepare($req);
        $statement->bind_param("s",$email);
        $statement->execute();
        $res=$statement->get_result();
        $row=$res->fetch_assoc();
        
        
        if($res->num_rows==1)
        {
              $userN=$row['username'];

            // generate random password

              $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
              $randpass = '';
              for ($i = 0; $i < 10; $i++) {
                  $randpass = $randpass.$characters[rand(0, strlen($characters))];
              }
            //cryptage du random password
            $cryptedpass=sha1($randpass);

            //update nouveau password
            $req1 = "update utilisateur set pass=? where email=?";
            $statement1=$conn->prepare($req1);
            $statement1->bind_param("ss",$cryptedpass,$email);
            $statement1->execute();




          // Send email to user with the token in a link they can click on
          /////////////////////////////////////////////////////////////////////////////////////
          /////////////////////////////////////////////////////////////////////////////////////



            $to = $email;
            $subject = "Reset your password on examplesite.com";
            $msg = 'Hi '.$userN.', votre nouveau mot de passe : '.$randpass;

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAuth = true;
            $mail->Username = 'ESRENT.services@gmail.com';
            $mail->Password = 'EsRent3363';
            $mail->setFrom('ESRENT.services@gmail.com', 'ESRENT SERVICES');
            $mail->addAddress($to, 'John Doe');
            $mail->isHTML(true);
            $mail->Subject=$subject;
            $mail->Body=$msg;
            $mail->AttBody=$msg;

            if (!$mail->send()) 
            $errmsg= "<h5 class='msg_err'><i class='fas fa-exclamation-circle'></i>  Désolé, email non evoyer </h5>";              
           else 
            header('location: pending.php?email=' . $email);

            
          /////////////////////////////////////////////////////////////////////////////////////
        }else if ($res->num_rows==0){
            $errmsg= "<h5 class='msg_err'><i class='fas fa-exclamation-circle'></i>  Désolé, cet email ne correspond a aucun de nos utilisateurs</h5>";
        }
  }else if($email == "")
      $errmsg= "<h5 class='msg_err'><i class='fas fa-exclamation-circle'></i> Vous devez saisir un Email </h5>";


}

?>


<html>
<head>



<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="format-detection" content="telephone=no"> 
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">


<title>Reset your password</title>

<style type="text/css"> 

    /* Some resets and issue fixes */
    #outlook a { padding:0; }
    body{ width:100% !important; -webkit-text; size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; background-color: ; }     
    .ReadMsgBody { width: 100%; }
    .ExternalClass {width:100%;} 
    .backgroundTable {margin:0 auto; padding:0; width:100% !important;} 
    table td {border-collapse: collapse;}
    .ExternalClass * {line-height: 115%;}           
    /* End reset */


    /* These are our tablet/medium screen media queries */
    @media screen and (max-width: 630px){


        /* Display block allows us to stack elements */                      
        *[class="mobile-column"] {display: block !important;} 

        /* Some more stacking elements */
        *[class="mob-column"] {float: none !important;width: 100% !important;}     

        /* Hide stuff */
        *[class="hide"] {display:none !important;}          

        /* This sets elements to 100% width and fixes the height issues too, a god send */
        *[class="100p"] {width:100% !important; height:auto !important; border:0 !important;}
        *[class="100pNoPad"] {width:100% !important; height:auto !important; border:0 !important;padding:0 !important;}                    

        /* For the 2x2 stack */         
        *[class="condensed"] {padding-bottom:40px !important; display: block;}

        /* Centers content on mobile */
        *[class="center"] {text-align:center !important; width:100% !important; height:auto !important;}            

        /* 100percent width section with 20px padding */
        *[class="100pad"] {width:100% !important; padding:20px;} 

        /* 100percent width section with 20px padding left & right */
        *[class="100padleftright"] {width:100% !important; padding:0 20px 0 20px;} 
    }

    @media screen and (max-width: 300px){
        /* 100percent width section with 20px padding top & bottom */
        *[class="100padtopbottom"] {width:100% !important; padding:0px 0px 40px 0px; display: block; text-align: center !important;} 
    }
</style>


</head>
<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700&display=swap" rel="stylesheet"> 

<body style="padding:0; margin:0">
<img class="wave" src="wave.png">
	

<table border="0" cellpadding="0" cellspacing="0" style="margin: 0" width="100%">
<tr>
<td height="70"></td>
</tr>
<tr>
<td align="center" valign="top">
  <table width="640" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="100p" style="border-radius: 8px; border: 1px solid #E2E5E7; overflow:hidden;">
    <tr>
      <td height="20"></td>
    </tr>    
    <tr>
      <td width="640" valign="top" class="100p">
        <!-- Header -->
        <table border="0" cellspacing="0" cellpadding="0" width="640" class="100p">
          <tr>
            <!-- Logo -->
            <td align="left" width="50%" class="100padtopbottom" style="padding-left: 20px">
              <img alt="Logo" src="logoResetPass.jpg" width="112" style="width: 100%; max-width: 200px; font-family: Arial, sans-serif; color: #ffffff; font-size: 20px; display: block; border: 0px;" border="0">
            </td>
          </tr>
          <tr>
            <td colspan="2" width="640" height="160" class="100p">
              <img alt="Logo" src="bg-pwd2x.jpg" width="640" style="width: 100%; max-width: 640px; font-family: Arial, sans-serif; color: #ffffff; font-size: 20px; display: block; border: 0px; margin-top:0px;" border="0">
            </td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="center" width="640" height="40" class="100p center" style="font-family: Arial, sans-serif; font-weight: bold; font-size:14px;padding: 0px 20px;">
              <font face="Arial, sans-serif"><b>Retrouvez votre compte</b></font>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="center" width="640" class="100p" style="font-family: Arial, sans-serif; font-size:14px; padding: 0px 20px; line-height: 18px;">
              <font face="Arial, sans-serif">
                  	
                 Veuillez saisir votre adresse e-mail pour rechercher votre compte.<br></br>
                </br>
                  
              </font>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="center" width="640" height="20" class="100p center" style="font-family: Arial, sans-serif; font-weight: bold; font-size:1px;padding: 0px 20px;">
>
            <form  action="enter_email.php" method="post">
              		<!-- form validation messages -->
	                	<div class="form-group col-md-6">
	                   		
                       <input style="
  background: rgba(255,255,255,0.1);
  border: none;
  font-size: 16px;
  height: auto;
  margin: 0;
  outline: 0;
  padding: 15px;
  width: 66%;
  background-color: #e8eeef;
  color: black;
  box-shadow: 0 1px 0 rgba(0, 0, 0, 0.03) inset;
  margin-bottom: 5px;" 
    type="email"  class="form-control"  placeholder="Email" name="email">
                      
	                	</div>
                  	
            </td>
         
            <tr>
          <td width="100" class="100p center" height="50" align="center" valign="top">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <?=$errmsg; ?>
                <td align="center" style="border-radius: 18px;">
                  <button name="reset-password" style=" display: block;
  width: 200px;
  height: 40px;
  border-radius: 25px;
  outline: none;
  border: none;
  background-image: linear-gradient(to right, #3F3D56, #3F3D56, rgb(96, 93, 128));
  background-size: 200%;
  font-size: 1rem;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  text-transform: uppercase;
  margin: 0;
  margin-top: 5px;
  margin-bottom: 30px;
  cursor: pointer;
  transition: .5s;
">Envoyer</button>
                   
                </td>
              </tr>
            </table>
          </td>
        </tr>
            </form>
          </tr>
           
          <tr>
            <td colspan="2" align="center" valign="center" width="640" height="20" class="100p center" style="font-family: Arial, sans-serif; font-weight: bold; font-size:1px;padding: 0px 20px;">
            </td>
          </tr>
        </table>
      </td>
    </tr>
   
   <style>
     .msg_err{
        color: brown;
        font-family: Roboto ;
        margin-top: 3px;
        margin-bottom:2px;
     }
   </style>
    
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>







