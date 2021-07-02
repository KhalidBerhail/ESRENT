<?php



?>
<html>
 <head>
 </head>
 <body>
    <button id="cl">current Location</button>  
    <button id="ntm">navigate through map</button> 
    <div id="mapblock">
     <div id="map">
                   
     </div>
     <button id="cnfrm">confirmer</button>
    </div>

   <script src="../../Resourse/Js/JSG/jquery.min.js"></script> 
   
   <script>
   var CL=<?=$_GET['target']?>;
   var CU=<?=$_GET['owner']?>;
   var lng;
   var lat;
    $('#cl').click(function(){
      if(navigator.geolocation)
        {
          navigator.geolocation.getCurrentPosition(function(position){
           console.log(position);
           lat=position.coords.latitude;
            lng=position.coords.longitude;
            
           $.ajax({  
                url:"isertLngLat.php?",  
                method:"POST",  
                data:{CodeL:CL,CodeU:CU,lng:lng,lat:lat},  
           });

          });
        }
      else
        {
          console.log("geolocation is not supported");  
        }  

    });
   </script>   

 
<script type="text/javascript">
        var map;
        var clck=0;
        var marker;
        
        function initMap() {                            
            var latitude = 34.0531 ; // latitude
            var longitude =-6.79846; //longtitude
            
            var myLatLng = {lat: latitude, lng: longitude};
            
            map = new google.maps.Map(document.getElementById('map'), {
              center: myLatLng,
              zoom: 8,
              disableDoubleClickZoom: true, 
            });
        
            // obtenire la positoin et placement d'un marker
            google.maps.event.addListener(map,'dblclick',function(event) {

              lat= event.latLng.lat();
              lng =  event.latLng.lng();
              var MLatLng={lat: lat, lng: lng};
               if(clck==0)
             {   marker = new google.maps.Marker({
                  position: event.latLng, 
                  map: map, 
                  
                  
                }); clck=1;}
                else
                marker.setPosition(MLatLng);
                    
            });
            
           
        }
        </script>
        <script >
        $(document).ready(function(){
           document.getElementById('mapblock').style.display='none';
          $('#ntm').click(function () {
        document.getElementById('mapblock').style.display='block';
});

        });
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWv8pHQtbrov613r_RMqCjZ_nOrz2y7HM&callback=initMap"
        async defer></script>


        <script>   
    $('#cnfrm').click(function(){
     
           $.ajax({  
                url:"isertLngLat.php?",  
                method:"POST",  
                data:{CodeL:CL,CodeU:CU,lng:lng,lat:lat},  
           });

     
    });
   </script>

<style>
#map{
	height:400px;
	width:50%;
}

</style>
 </body>
</html>