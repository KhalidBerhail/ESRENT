<div class="row">
  <div class="col-sm-4">
    <div class="bloc-text-image">
      <img src="http://codecondo.com/wp-content/uploads/2014/02/11-Free-jQuery-Photo-Gallery-Lightbox-Plugins.jpg">
      <a><i class="delete far fa-times-circle"></i></a>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="bloc-text-image">
      <img src="http://codecondo.com/wp-content/uploads/2014/02/11-Free-jQuery-Photo-Gallery-Lightbox-Plugins.jpg">
      <a><i class="delete far fa-times-circle"></i></a>
    </div>
  </div>
</div>



<div class="row">
    <div class="col-sm-4">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <div class="file-upload">
      <div class="image-upload-wrap">
         <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
         <div class="drag-text">
                  <h3>Ajouter image</h3>
         </div>
      </div>
      <div class="file-upload-content">
         <img class="file-upload-image" src="#" alt="your image" />
         <div class="image-title-wrap">
            <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
         </div>
      </div>
   </div>
</div>


<div class="row">
   <div class="col-sm-4">
      <div class="bloc-text-image">
         <img src="http://codecondo.com/wp-content/uploads/2014/02/11-Free-jQuery-Photo-Gallery-Lightbox-Plugins.jpg">
         <div class="description">
            Petit text d'intro
            <div class="hide">encore plus de texte !</div>
         </div>
      </div>
   </div>
   <div class="col-sm-4">
   <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
   <div class="file-upload">
      <div class="image-upload-wrap">
         <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
         <div class="drag-text">
                  <h3>Ajouter image</h3>
         </div>
      </div>
      <div class="file-upload-content">
         <img class="file-upload-image" src="#" alt="your image" />
         <div class="image-title-wrap">
            <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
         </div>
      </div>
   </div>
</div>














if($i==1)
      {
         $images.='
         <div class="row">
          <div class="col-sm-4">
            <div class="bloc-text-image">
              <img src="http://codecondo.com/wp-content/uploads/2014/02/11-Free-jQuery-Photo-Gallery-Lightbox-Plugins.jpg">
              <a><i class="delete far fa-times-circle"></i></a>
            </div>
          </div>

         ';
      }else ($i==1)
      {

      }