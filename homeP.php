  <?php
session_start();

//sessions
/*
if( !isset($_SESSION['username']) )
{
  header("location:page.php");
}else
*/

if( isset($_SESSION['username']) )
{
  if($_SESSION['type'] == "admin")
  header("Location:pages/AdminPages/dash.php");
  else if($_SESSION['type'] == "normal")
    header("Location:pages/UserPages/User.php");
  else if($_SESSION['type'] == "pro")
    header("Location:pages/PropPages/Prop.php");  
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


if(isset($_POST['connect']))
 {
  $login = $_POST["login"];
  $password = $_POST["password"];

  $req = "SELECT * from utilisateur where email=? and pass=?";
  $statement=$conn->prepare($req);
  $statement->bind_param("ss",$login,$password);
  $statement->execute();
  $res=$statement->get_result();
  $row=$res->fetch_assoc();
  
  if($res->num_rows==1)
  {
    
    
    $CodeU=$row['CodeU'];
    session_regenerate_id();
    $_SESSION['username']=$row['username'];
    $_SESSION['type']=$row['type'];
    session_write_close();
    
      if($_SESSION['type'] == "admin")
      header("Location:AdminPages/dash.php");
      else if($_SESSION['type'] == "normal")
        header("Location:NorUserPages/Home.php");
      else if($_SESSION['type'] == "pro")
        header("Location:ProUserPages/Home.php");


  }

 }

?>

<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Homepage</title>
  <link rel="stylesheet" type="text/css" href="Resourse/CSS/semantic.min.css">
  <link href="Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" type="text/css" href="Resourse/CSS/Style.css">  
  <link rel="stylesheet" type="text/css" href="Resourse\Responsive\home\HomeRespo.css">

</head>
<style type="text/css">
       .lwhite{
      width: 100px;
      height: 25px;
      margin-top: 20px;
    }
    .lwhite2{
      width: 100px;
      height: 25px;
      margin-top: 20px;
    }
</style>

<body>

<!-- Following Menu -->

<div class="ui inverted segment large top fixed hidden menu">
  <div class="ui inverted secondary menu">
    <div class="ui container">
      <div class="right menu">
        <a href="pages/AdminPages/LoginHote.php" class="active item">Devenez hôte</a>
        <a href="pages/samples/Aide.html" class="item">Aide</a>
        <a href="pages/samples/register.php" class="item">Inscription</a>
        <a href="pages/samples/login.php" class="item">Connexion</a>
      </div>
  </div>
</div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
  <a class="active item">Home</a>
  <a class="item">Work</a>
  <a class="item">Company</a>
  <a class="item">Careers</a>
  <a href="pages/samples/login.php" class="item">Login</a>
  <a href="pages/samples/register.php" class="item">Signup</a>
</div>


<!-- Page Contents -->
<div class="pusher">
  <div class="ui inverted vertical masthead center aligned segment">
  
    <div class="ui container">
      <div class="ui large secondary pointing inverted menu">
        
        <img src="Resourse/images/logoBlack.png" class="lwhite" />
        <div class="right item">
        <a href="pages/AdminPages/LoginHote.php" class="active header item">Devenez hôte</a>
        <a href="pages/samples/Aide.html" class="item">Aide</a>
        <a href="pages/samples/register.php" class="item">Inscription</a>
        <a href="pages/samples/login.php" class="item">Connexion</a>
        </div>
      </div>
    </div>
    <div class="ui text container">
        <h1 class="ui inverted header">
        <img src="Resourse/images/logoBlack.png" class="lwhite2" />
        </h1>
        <h2>Nous vous aiderons à trouver un endroit que vous aimerez.</h2>
       
      </div>
  <!--filter div-->
  <div class="ui equal width center aligned padded grid">
  
  <div class="row search" >
  <form class="ui card" action="pages/samples/searshResult.php" methode="POST">
        <div class="ui fluid icon input">
        
        <input type="text" name="rech" placeholder="Search a very wide input..." required >
        <i class="fas fa-search"></i> 
               
        </div>
      </div>

  </form>
  </div>
 
</div>

 
 





  <div class="textSEP">Nous avons le plus d'annonces et de mises à jour constantes. <br>     
           Vous ne manquerez donc jamais rien..</div>

    
          
       

          <div class="ui link cards">
            <div class="card">
              <div class="image">
                <img src="Resourse/imgs/matthew.png">
              </div>
              <div class="content">
                <div class="header">Appartement1</div>
                <div class="meta">
                  <a>Mr Alami dodo</a>
                </div>
                <div class="description">
                  Description de l'appartement
                </div>
              </div>
              <div class="extra content">
                <span class="right floated">
                 2100 dh
                </span>
                <span>
                <i class="fab fa-gratipay"></i> 
                  75 Like
                </span>
              </div>
            </div>
            <div class="card">
              <div class="image">
                <img src="Resourse/imgs/matthew.png">
              </div>
              <div class="content">
                <div class="header">Appartement2</div>
                <div class="meta">
                  <a>Mr semsawi fofo</a>
                </div>
                <div class="description">
                  Description de l'appartement
                </div>
              </div>
              <div class="extra content">
                <span class="right floated">
                  1500 dh
                </span>
                <span>
                <i class="fab fa-gratipay"></i> 
                  35 Like
                </span>
              </div>
            </div>
            <div class="card">
              <div class="image">
                <img src="Resourse/imgs/matthew.png">
              </div>
              <div class="content">
                <div class="header">Appartement3</div>
                <div class="meta">
                  <a>Mr tertawi toto</a>
                </div>
                <div class="description">
                  Description de l'appartement
                </div>
              </div>
              <div class="extra content">
                <span class="right floated">
                 5000 dh
                </span>
                <span>
                <i class="fab fa-gratipay"></i> 
                  175 Like
                </span>
              </div>
            </div>
          </div>




  <div class="ui inverted vertical  footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">About</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Sitemap</a>
            <a href="#" class="item">Contact Us</a>
            <a href="#" class="item">Religious Ceremonies</a>
            <a href="#" class="item">Gazebo Plans</a>
          </div>
        </div>
        <div class="three wide column">
          <h4 class="ui inverted header">Services</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Banana Pre-Order</a>
            <a href="#" class="item">DNA FAQ</a>
            <a href="#" class="item">How To Access</a>
            <a href="#" class="item">Favorite X-Men</a>
          </div>
        </div>
        <div class="seven wide column">
          <h4 class="ui inverted header">Footer Header</h4>
          <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
        </div>
      </div>
    </div>
  </div>
</div>


</body>

<script src="Resourse/Js/JSG/jquery.min.js"></script>
<script src="Resourse/Js/JSG/semantic.min.js"></script>


<script src="Resourse/Js/JSG/visibility.js"></script>
<script src="Resourse/Js/JSG/sidebar.js"></script>
<script src="Resourse/Js/JSG/transition.js"></script>
<script src="Resourse/Js/main2.js"></script>

<script src="Resourse/Js/LoginPage/main.js"></script>


</html>