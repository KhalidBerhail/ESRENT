
<?php
  
  
  $rech=$_GET['rech'];
  $LGTP=$_GET['LGTP'];
  $RCMI=$_GET['RCMI'];
  $RCMA=$_GET['RCMA'];
  $servername = "localhost";
  $userservername = "root";
  $database = "pfe";
  
  
  //  Create connection  
  $conn = new mysqli($servername, $userservername,"", $database);
  
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $nbrA_Tanga=0;
  $nbrA_Oriental=0;
  $nbrA_Fes=0;
  $nbrA_Rabat=0;
  $nbrA_BeniMelal=0;
  $nbrA_Casa=0;
  $nbrA_Marakech=0;
  $nbrA_Taafilalt=0;
  $nbrA_Sous=0;
  $nbrA_Guelmim=0;
  $nbrA_Laayoun=0;
  $nbrA_Dakhla=0;
  
  $req_nbrA="SELECT region,count(*) as nbrA FROM `logement` GROUP BY region  ";
  $statement_nbrA=$conn->prepare($req_nbrA);
  $statement_nbrA->execute();
  $res_nbrA=$statement_nbrA->get_result();                    
  while(($row_nbrA= mysqli_fetch_array($res_nbrA)))
  {
     if($row_nbrA['region']=="Tanger-Tétouan-Al Hoceïma")
     {
      $nbrA_Tanga=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="L'Oriental")
     {
      $nbrA_Oriental=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Fès- Meknès")
     {
      $nbrA_Fes=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Rabat-Salé-Kénitra")
     {
      $nbrA_Rabat=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Béni-Mellal-Khénifra")
     {
      $nbrA_BeniMelal=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Casablanca-Settat")
     {
      $nbrA_Casa=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Marrakech-Safi")
     {
      $nbrA_Marakech=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Drâa-Tafilalet")
     {
      $nbrA_Taafilalt=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Souss-Massa")
     {
      $nbrA_Sous=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Guelmim-Oued Noun")
     {
      $nbrA_Guelmim=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Laâyoune-Sakia El Hamra")
     {
      $nbrA_Laayoun=$row_nbrA['nbrA'];
     }
     else if($row_nbrA['region']=="Dakhla-Oued Ed-Dahab")
     {
      $nbrA_Dakhla=$row_nbrA['nbrA'];
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
  
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
 

 
  <link rel="shortcut icon" href="favicon.png" />


  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>

  <link href="../../../Resourse/vendors/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="../../../Resourse/Allfooters/Style.css">
  <link rel="stylesheet" href="styleRe.css">
  <!--leaflet css-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
  </head>
  <body>
    




    <div class="container-scroller">
      <!-- partial:partials/_horizontal-navbar.html -->
     
      <div class="main-panel">
				<div class="content-wrapper">
        
          <div class="row"> 
            
            <div class="col-lg-6 grid-margin stretch-card">

              <div id="map_lst" class="nav flex-column nav-pills map_lst" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link" id="a01"   href="Tanger-Tétouan-Al Hoceima.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma" role="tab" aria-controls="a01" aria-selected="true">   Tanger-Tétouan-Al Hoceïma    (<?=$nbrA_Tanga?> Logements) </a>
                <a class="nav-link" id="a02" data-toggle="pill" href="#a02" role="tab" aria-controls="a02" aria-selected="false">L'Oriental    (<?=$nbrA_Oriental?> Logements)                </a>
                <a class="nav-link" id="a03" data-toggle="pill" href="#a03" role="tab" aria-controls="a03" aria-selected="false">Fès- Meknès    (<?=$nbrA_Fes?> Logements)               </a>
                <a class="nav-link" id="a04"  href="Rabat-Sale-Kenitra.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra" role="tab" aria-controls="a04" aria-selected="false">Rabat-Salé-Kénitra    (<?=$nbrA_Rabat?> Logements)        </a>
                <a class="nav-link" id="a05" data-toggle="pill" href="#a05" role="tab" aria-controls="a05" aria-selected="false">Béni-Mellal-Khénifra    (<?=$nbrA_BeniMelal?> Logements)      </a>
                <a class="nav-link" id="a06"  href="Casablanca-Settat.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat" role="tab" aria-controls="a06" aria-selected="false">Casablanca-Settat    (<?=$nbrA_Casa?> Logements)         </a>
                <a class="nav-link" id="a07"  href="Marakech-safi.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi" role="tab" aria-controls="a07" aria-selected="false">Marrakech-Safi    (<?=$nbrA_Marakech?> Logements)            </a>
                <a class="nav-link" id="a08" data-toggle="pill" href="#a08" role="tab" aria-controls="a08" aria-selected="false">Drâa-Tafilalet    (<?=$nbrA_Taafilalt?> Logements)            </a>
                <a class="nav-link" id="a09" data-toggle="pill" href="#a09" role="tab" aria-controls="a09" aria-selected="false">Souss-Massa    (<?=$nbrA_Sous?> Logements)               </a>
                <a class="nav-link" id="a10" data-toggle="pill" href="#a10" role="tab" aria-controls="a10" aria-selected="false">Guelmim-Oued Noun    (<?=$nbrA_Guelmim?> Logements)         </a>
                <a class="nav-link" id="a11" data-toggle="pill" href="#a11" role="tab" aria-controls="a11" aria-selected="false">Laâyoune-Sakia El Hamra    (<?=$nbrA_Laayoun?> Logements)   </a>
                <a class="nav-link" id="a12" data-toggle="pill" href="#a12" role="tab" aria-controls="a12" aria-selected="false">Dakhla-Oued Ed-Dahab    (<?=$nbrA_Dakhla?> Logements)      </a>
              </div>  
              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
              </div>
              
              <nav aria-label="breadcrumb">
              
              
              <ol class="breadcrumb">
              
              <li class="breadcrumb-item active" aria-current="page">Choisissez une région</li> 
              
              </ol>
            
              </nav> 
              
            </div> 
            <div class="col-lg-6 grid-margin stretch-card">

              <div class="card">
              
               
               <div id="map_card" class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
               <a href="../SearshResult.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=ALL&province=ALL"> <button type="button" class="btn btn-primary">afficher les resultat dans tous les regions</button></a> &nbsp;
                <div id="map_svg" class="map_svg">
                <svg style="margin-top:-20%" xmlns="http://www.w3.org/2000/svg" xmlns:amcharts="http://amcharts.com/ammap" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewbox="0 0 597 920">

	                <g>
                  
                    <a  xlink:title="Tanger-Tétouan-Al Hoceïma" xlink:href="Tanger-Tétouan-Al Hoceima.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Tanger-Tétouan-Al Hoceïma"       ><path  id="MA-01" d="M505.091,182.589L504.892,186.859L506.041,198.086L506.195,198.152L502.255,200.168L495.946,200.872L491.886,205.353L488.042,202.629L484.853,207.196L480.646,208.863L469.044,205.177L466.305,200.334L461.938,203.157L455.035,203.297L452.987,210.197L453.268,214.479L447.364,217.28L444.398,214.698L444.195,211.412L440.184,207.794L431.143,203.926L431.007,196.757L424.616,195.599L422.689,193.654L411.977,192.597L418.39,173.967L420.41,170.051L424.166,155.313L428.854,155.229L431.125,157.004L434.659,154.417L439.282,154.501L441.866,151.162L445.108,150.166L449.411,151.162L447.848,155.252L448.236,160.122L450.504,166.518L453,167.294L457.149,173.011L462.344,177.222L471.697,182.512L484.015,185.115L500.469,179.842z"/></a> 
	                  <a  xlink:title="L'Oriental"                xlink:href=""       ><path  id="MA-02" d="M531.531,341.948L526.256,342.012L525.506,340.089L531.379,336.585L528.32,334.86L526.167,327.685L527.414,322.496L522.088,322.895L514.66,320.173L506.68,319.698L505.547,311.562L500.222,308.356L501.128,304.346L497.765,301.387L501.509,296.345L502.669,292.225L509.196,284.92L513.063,279.298L525.129,279.699L534.43,278.549L536.678,276.136L541.826,275.618L536.533,267.937L531.24,262.231L526.994,261.671L526.072,258.819L534.838,250.793L534.521,248.786L522.692,247.158L520.925,243.899L516.857,242.319L511.136,249.383L505.743,253.721L507.51,258.379L504.428,262.328L502.344,258.649L500.032,260.868L494.549,258.649L494.606,253.038L498.971,251.201L500.105,246.48L504.683,237.349L505.181,230.332L501.191,226.787L504.5,223.948L504.183,218.045L507.719,216.567L507.537,211.311L511.525,209.338L513.505,203.219L513.197,199.808L506.432,198.254L506.041,198.086L504.892,186.859L505.091,182.589L508.797,178.616L517.093,182.77L523.41,183.208L532.626,178.978L535.409,174.188L538.721,180.152L538.679,183.518L542.953,187.241L552.317,188.232L557.662,185.154L565.806,188.09L565.675,189.806L582.934,203.267L580.289,208.888L583.548,214.39L582.213,220.062L585.147,223.835L585.122,227.832L587.301,233.125L585.346,243.933L587.103,252.506L589.563,259.431L586.701,266.263L586.422,270.59L590.066,276.757L594.421,281.585L591.297,285.403L597.449,295.244L601.682,297.217L612,305.153L607.348,309.864L604.396,310.349L602.74,319.238L605.155,321.342L604.157,324.638L588.479,323.913L582.99,324.099L572.767,321.628L569.431,325.888L564.717,325.802L556.162,322.821L550.505,325.888L544.704,325.206L540.788,328.016L542.747,337.79L538.033,341.44z"/></a>  
	                  <a  xlink:title="Fès- Meknès"               xlink:href=""       ><path id="MA-03"  d="M497.765,301.387L495.633,299.827L492.856,302.339L488.552,302.339L486.523,296.195L479.359,288.59L472.218,289.074L468.633,286.308L469.103,283.149L465.74,279.456L459.494,280.04L456.503,286.494L453.186,285.979L452.479,286.43L450.738,288.815L450.656,288.813L450.781,288.675L450.291,287.063L447.408,283.385L448.496,278.102L443.928,278.232L440.013,272.605L440.556,268.979L434.465,266.322L428.663,262.339L428.675,262.258L431.431,258.839L430.444,256.075L430.676,255.191L433.171,249.02L430.397,245.242L433.497,240.48L427.624,237.543L425.085,233.586L428.983,228.743L432.867,230.37L433.226,233.976L438.229,234.63L443.232,231.622L443.452,226.647L440.107,222.847L445.817,220.322L447.364,217.28L453.268,214.479L452.987,210.197L455.035,203.297L461.938,203.157L466.305,200.334L469.044,205.177L480.646,208.863L484.853,207.196L488.042,202.629L491.886,205.353L495.946,200.872L502.255,200.168L506.195,198.152L506.432,198.254L513.197,199.808L513.505,203.219L511.525,209.338L507.537,211.311L507.719,216.567L504.183,218.045L504.5,223.948L501.191,226.787L505.181,230.332L504.683,237.349L500.105,246.48L498.971,251.201L494.606,253.038L494.549,258.649L500.032,260.868L502.344,258.649L504.428,262.328L507.51,258.379L505.743,253.721L511.136,249.383L516.857,242.319L520.925,243.899L522.692,247.158L534.521,248.786L534.838,250.793L526.072,258.819L526.994,261.671L531.24,262.231L536.533,267.937L541.826,275.618L536.678,276.136L534.43,278.549L525.129,279.699L513.063,279.298L509.196,284.92L502.669,292.225L501.509,296.345z"/></a>  
	                  <a  xlink:title="Rabat-Salé-Kénitra"        xlink:href="Rabat-Sale-Kenitra.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Rabat-Salé-Kénitra"       ><path id="MA-04" d="M447.364,217.28L445.817,220.322L440.107,222.847L443.452,226.647L443.232,231.622L438.229,234.63L433.226,233.976L432.867,230.37L428.983,228.743L425.085,233.586L427.624,237.543L433.497,240.48L430.397,245.242L433.171,249.02L430.676,255.191L430.444,256.075L431.431,258.839L428.675,262.258L428.53,263.21L430.202,269.187L428.609,270.862L413.593,267.792L412.433,262.864L408.734,263.037L405.616,270.383L408.3,274.007L401.772,277.887L398.469,273.72L391.025,272.981L391.042,272.972L391.983,266.841L391.187,262.345L388.666,256.995L388.264,257.063L383.862,258.017L384.106,254.337L380.786,246.845L378.581,245.792L384.137,242.166L391.555,233.934L400.93,217.479L407.3,204.466L411.977,192.597L422.689,193.654L424.616,195.599L431.007,196.757L431.143,203.926L440.184,207.794L444.195,211.412L444.398,214.698z"/></a> 		
	                  <a  xlink:title="Béni-Mellal-Khénifra"      xlink:href=""       ><path id="MA-05" d="M450.781,288.675L449.91,289.641L448.441,291.188L448.605,300.324L447.245,305.012L444.473,308.605L440.524,308.238L438.878,311.557L441.844,313.987L439.777,318.275L434.61,318.787L433.142,323.519L427.567,324.062L426.317,328.531L429.09,332.805L426.785,336.235L423.45,336.32L421.13,340.564L417.068,343.109L410.38,344.126L405.685,348.786L398.795,350.986L393.977,350.227L392.835,357.574L384.568,358.25L382.069,355.667L378.84,356.813L377.58,354.056L379.798,349.04L375.51,347.169L374.649,344.296L371.227,342.167L373.852,339.292L376.752,340.48L383.278,339.801L384.873,336.065L379.994,327.531L379.802,315.284L379.847,314.338L383.673,305.742L383.922,301.686L381.841,296.748L384.22,285.314L386.532,284.024L387.823,274.898L390.179,273.451L391.025,272.981L398.469,273.72L401.772,277.887L408.3,274.007L405.616,270.383L408.734,263.037L412.433,262.864L413.593,267.792L428.609,270.862L430.202,269.187L428.53,263.21L428.663,262.339L434.465,266.322L440.556,268.979L440.013,272.605L443.928,278.232L448.496,278.102L447.408,283.385L450.291,287.063z"/></a>  
                    <a  xlink:title="Casablanca-Settat"         xlink:href="Casablanca-Settat.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Casablanca-Settat"       ><path id="MA-06" d="M378.581,245.792L380.786,246.845L384.106,254.337L383.862,258.017L388.264,257.063L388.666,256.995L391.187,262.345L391.983,266.841L391.042,272.972L390.179,273.451L387.823,274.898L386.532,284.024L384.22,285.314L381.841,296.748L383.922,301.686L383.673,305.742L379.847,314.338L379.825,314.79L372.788,311.808L370.931,308.763L367.758,307.801L361.063,309.147L354.86,299.259L350.354,299.215L346.563,291.857L344.042,293.109L342.918,302.819L337.982,308.285L332.472,310.32L330.98,313.813L325.464,314.868L321.766,305.294L311.613,306.406L307.843,302.983L309.221,298.099L305.02,295.919L315.39,285.831L321.21,278.445L321.336,276.219L325.579,271.675L330.225,270.982L334.002,266.848L340.541,264.741L357.948,256.354L363.535,255.127L374.698,246.96z"/></a> 	
                    <a  xlink:title="Marrakech-Safi"            xlink:href="Marakech-safi.php?rech=<?=$rech?>&LGTP=<?=$LGTP?>&RCMI=<?=$RCMI?>&RCMA=<?=$RCMA?>&region=Marrakech-Safi"       ><path id="MA-07" d="M379.825,314.79L379.802,315.284L379.994,327.531L384.873,336.065L383.278,339.801L376.752,340.48L373.852,339.292L371.227,342.167L374.649,344.296L375.51,347.169L379.798,349.04L377.58,354.056L372.733,359.288L366.7,360.04L357.017,365.255L355.602,365.853L349.027,370.714L345.965,377.862L341.578,374.487L332.641,379.911L324.349,377.801L316.461,380.699L315.575,373.908L308.345,377.659L305.72,380.383L300.747,380.092L299.874,383.061L294.03,380.231L289.806,381.128L287.056,379.866L282.878,382.904L278.8,382.746L279.06,378.522L275.974,375.065L275.138,368.249L276.757,360.729L275.466,354.552L281.042,346.191L282.481,340.97L293.634,327.43L297.243,320.593L297.899,315.165L296.173,311.887L298.396,307.096L296.322,304.087L305.02,295.919L309.221,298.099L307.843,302.983L311.613,306.406L321.766,305.294L325.464,314.868L330.98,313.813L332.472,310.32L337.982,308.285L342.918,302.819L344.042,293.109L346.563,291.857L350.354,299.215L354.86,299.259L361.063,309.147L367.758,307.801L370.931,308.763L372.788,311.808z"/></a> 	
	                  <a  xlink:title="Drâa-Tafilalet"            xlink:href=""       ><path id="MA-08" d="M497.765,301.387L501.128,304.346L500.222,308.356L505.547,311.562L506.68,319.698L514.66,320.173L522.088,322.895L527.414,322.496L526.167,327.685L528.32,334.86L531.379,336.585L525.506,340.089L526.256,342.012L531.531,341.948L531.586,344.746L522.231,344.683L516.575,350.083L512.877,351.796L509.942,350.137L508.715,354.861L505.161,357.566L505.161,364.235L514.157,373.467L503.961,385.417L502.862,389.543L492.149,391.208L485.495,393.693L480.295,398.858L477.335,398.902L473.029,403.215L463.625,408.957L455.117,416.612L452.888,416.729L447.34,422.537L446.308,425.406L440.706,430.392L437.552,437.922L437.116,441.279L430.481,438.979L429.503,436.118L425.913,438.481L418.19,439.974L413.079,440.036L404.106,436.003L404.837,429.32L402.389,427.14L404.292,424.021L402.661,420.433L407.171,416.491L398.854,406.206L402.502,401.418L400.576,398.673L396.092,398.793L395.251,394.88L387.32,401.024L381.471,402.35L375.649,399.457L373.61,404.813L368.852,408.597L361.822,405.092L359.697,400.189L363.231,390.612L360.785,388.726L360.921,385.107L355.891,384.793L359.969,379.596L355.618,379.28L356.026,372.814L354.53,368.709L356.241,365.583L357.017,365.255L366.7,360.04L372.733,359.288L377.58,354.056L378.84,356.813L382.069,355.667L384.568,358.25L392.835,357.574L393.977,350.227L398.795,350.986L405.685,348.786L410.38,344.126L417.068,343.109L421.13,340.564L423.45,336.32L426.785,336.235L429.09,332.805L426.317,328.531L427.567,324.062L433.142,323.519L434.61,318.787L439.777,318.275L441.844,313.987L438.878,311.557L440.524,308.238L444.473,308.605L447.245,305.012L448.605,300.324L448.441,291.188L449.91,289.641L450.656,288.813L450.738,288.815L452.479,286.43L453.186,285.979L456.503,286.494L459.494,280.04L465.74,279.456L469.103,283.149L468.633,286.308L472.218,289.074L479.359,288.59L486.523,296.195L488.552,302.339L492.856,302.339L495.633,299.827z"/></a> 	
	                  <a  xlink:title="Souss-Massa"               xlink:href=""       ><path id="MA-09" d="M404.106,436.003L401.175,437.828L394.086,438.482L391.941,440.381L386.938,438.922L377.749,437.742L368.371,442.459L360.609,444.112L358.851,446.967L353.498,449.347L346.876,454.799L331.838,465.192L328.831,468.423L319.884,472.823L319.895,489.832L317.971,489.56L309.677,479.253L309.54,475.247L304.782,474.322L304.51,471.083L301.383,469.386L302.878,464.29L297.44,457.949L302.471,456.402L304.238,450.205L301.111,447.26L300.431,443.069L297.848,438.72L292.138,438.098L289.418,441.36L285.883,439.341L282.756,442.292L280.581,439.808L276.23,443.225L273.783,441.205L272.967,435.609L269.432,438.098L267.975,434.039L269.784,431.039L275.529,425.376L280.757,414.649L282.894,408.696L284.429,399.333L280.429,393.078L277.392,390.131L273.35,389.225L276.535,381.081L275.974,375.065L279.06,378.522L278.8,382.746L282.878,382.904L287.056,379.866L289.806,381.128L294.03,380.231L299.874,383.061L300.747,380.092L305.72,380.383L308.345,377.659L315.575,373.908L316.461,380.699L324.349,377.801L332.641,379.911L341.578,374.487L345.965,377.862L349.027,370.714L355.602,365.853L356.241,365.583L354.53,368.709L356.026,372.814L355.618,379.28L359.969,379.596L355.891,384.793L360.921,385.107L360.785,388.726L363.231,390.612L359.697,400.189L361.822,405.092L368.852,408.597L373.61,404.813L375.649,399.457L381.471,402.35L387.32,401.024L395.251,394.88L396.092,398.793L400.576,398.673L402.502,401.418L398.854,406.206L407.171,416.491L402.661,420.433L404.292,424.021L402.389,427.14L404.837,429.32z"/></a> 	
	                  <a  xlink:title="Guelmim-Oued Noun"         xlink:href=""       ><path id="MA-10" d="M267.975,434.039L269.432,438.098L272.967,435.609L273.783,441.205L276.23,443.225L280.581,439.808L282.756,442.292L285.883,439.341L289.418,441.36L292.138,438.098L297.848,438.72L300.431,443.069L301.111,447.26L304.238,450.205L302.471,456.402L297.44,457.949L302.878,464.29L301.383,469.386L304.51,471.083L304.782,474.322L309.54,475.247L309.677,479.253L317.971,489.56L319.895,489.832L319.917,517.687L319.687,548.31L308.136,545.751L296.806,526.738L292.274,529.277L285.929,529.786L280.037,527.5L273.239,518.348L267.121,520.893L260.096,513L254.884,511.982L248.993,514.529L246.953,512.746L227.465,513L225.426,515.293L219.081,512.236L211.453,512.178L203.841,500.49L201.509,494.307L213.427,489.022L219.405,480.692L229.299,470.819L243.574,463.309L249.711,459.191L254.208,453.108L259.53,448.162L266.197,438.58z"/></a>  
                    <a  xlink:title="Laâyoune-Sakia El Hamra"   xlink:href=""       ><path id="MA-11" d="M201.509,494.307L203.841,500.49L211.453,512.178L219.081,512.236L225.426,515.293L227.465,513L246.953,512.746L248.993,514.529L254.884,511.982L260.096,513L267.121,520.893L273.239,518.348L280.037,527.5L285.929,529.786L292.274,529.277L296.806,526.738L308.136,545.751L319.687,548.31L319.639,588.706L285.783,588.605L192.799,588.664L192.708,639.494L192.823,651.653L185.861,649.866L180.649,650.608L172.038,653.58L157.083,648.628L148.245,649.37L139.181,648.132L131.703,653.58L110.855,648.132L107.683,650.361L101.792,652.343L99.299,650.608L94.54,653.828L91.821,651.847L90.461,647.636L88.195,646.148L83.664,647.141L82.239,643.817L85.117,634.463L85.498,628.193L84.535,622.022L86.767,613.034L90.556,606.564L92.587,598.145L95.148,595.025L97.868,588.765L98.693,580.983L101.391,577.522L107.433,574.364L109.793,570.676L117.613,568.725L129.051,561.312L133.432,557.06L138.839,542.966L139.283,539.241L142.542,533.891L148.489,517.13L152.161,514.891L155.515,508.356L158.012,505.557L167.461,504.377L190.984,499.38z"/></a>  
                    <a  xlink:title="Dakhla-Oued Ed-Dahab"      xlink:href=""       ><path id="MA-12" d="M82.239,643.817L83.664,647.141L88.195,646.148L90.461,647.636L91.821,651.847L94.54,653.828L99.299,650.608L101.792,652.343L107.683,650.361L110.855,648.132L131.703,653.58L139.181,648.132L148.245,649.37L157.083,648.628L172.038,653.58L180.649,650.608L185.861,649.866L192.823,651.653L192.947,694.781L188.127,695.494L179.29,700.158L170.452,701.631L154.363,712.659L149.851,721.183L149.302,724.558L154.787,781.783L108.279,781.313L45.258,781.36L4.778,781.954L2.363,790.975L0,792L1.45,780.603L3.164,772.464L3.164,765.989L9.831,747.878L14.613,742.422L16.337,743.514L21.724,741.25L24.274,733.101L26.866,730.834L27.48,724.838L29.596,717.841L32.411,716.881L35.183,712.128L33.659,709.772L44.135,690.236L50.22,681.435L51.616,676.673L49.572,674.312L56.082,670.386L56.822,668.299L66.282,659.371L72.303,651.907L77.827,649.42L79.212,645.937z"/></a>  
	
                	</g>
                </svg>
                </div>
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
   
    








    <!-- container-scroller -->
    <!-- base:js -->
    <script src="../../../Resourse/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="../../../Resourse/js2/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
  

		<script src="../../../Resourse/vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="../../../Resourse/vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="../../../Resourse/js2/dashboard.js"></script>
    <!-- End custom js for this page-->

    <!-- chat-box -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>
<!--MAP JavaScript-->   
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
   
</html>
    <script src="../../Resourse/JS/JSG/jquery-3.4.1.min.js"></script>
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
 // var shape_id="";
  var map=document.querySelector('#map_card');
var shapes=document.querySelectorAll('.map_svg path');
var links=document.querySelectorAll('.map_lst a');
var link_id="";
  $(document).ready(function(){ 
  
//Polyfill for foreach in browsers that don't suport it
if(NodeList.prototype.forEach === undefined){
  NodeList.prototype.forEach=function(callback){
    [].forEach.call(this, callback);
  }
}


//adding the mouseenter event listener on all svg shapes to activate the corespandent link <a> 
shapes.forEach(function (shape){
  shape.addEventListener('mouseenter', function(f){
    var shape_id=this.id.replace('MA-','a');
      
/*
      document.querySelectorAll('.active').forEach(function (item){
        item.classList.remove('active');
      });
      */
      document.querySelector("#"+shape_id).classList.add('active');
      shape_id="";
      
  });

  shape.addEventListener('mouseleave', function(f){
    
    document.querySelectorAll('.active').forEach(function (item){
      item.classList.remove('active');
    });

});

 
});


links.forEach(function (link){
  link.addEventListener('mouseenter', function(f1){
    
    
     link_id=this.id.replace('a','MA-');
      
/*
      document.querySelectorAll('.shape_active').forEach(function (item1){
        item1.classList.remove('shape_active');
      });*/
      
      document.querySelector("#"+link_id).classList.add('shape_active');
      link_id="";
});

link.addEventListener('mouseleave', function(f1){
    
     document.querySelectorAll('.shape_active').forEach(function (item1){
       item1.classList.remove('shape_active');
     });

});

});


});

</script>

<!--MAP Loading JavaScreept-->
<script>
/*
const here = {
  apiKey:'gNAS-hI7AKsqytfacNxMU-WZqMQa_Zn-nunnoU2p6s4'
}
const style = 'normal.day';

const hereTileUrl = `https://2.base.maps.ls.hereapi.com/maptile/2.1/maptile/newest/${style}/{z}/{x}/{y}/512/png8?apiKey=${here.apiKey}&ppi=320`;

const map = L.map('map_svg', {
   center: [33.589886, -7.603869],
   zoom: 8,
   layers: [L.tileLayer(hereTileUrl)]
});
map.attributionControl.addAttribution('&copy; HERE 2019');
*/
</script>