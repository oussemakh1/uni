<?php 

namespace cgc\platform\traits;

Trait UploadImageTrait {

    function uploadImage ($image)
    {
        // extract info from image
        $filename = $image["name"][0];
        
        $tmp_file = $image['tmp_name'][0];
        
        $size = $image['size'][0];
    
        // get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $image_name = 'logo'.date("His").'.'.$extension;

          // destination of the file on the server
          $destination = 'app/public/'.$image_name;
        // the physical file on a temporary uploads directory on the server       
    
        if (!in_array($extension, ['png', 'jpeg', 'jpg'])) {
            return false;
        } elseif ($size> 100000000) { // file shouldn't be larger than 1Megabyte
            return false;
        } 
        else {
            //move the uploaded (temporary) file to the specified destination
           
            if (move_uploaded_file($tmp_file, $destination)) {
              return $image_name;
            } else {
               return false;
            }
           
        }
    }
   
}