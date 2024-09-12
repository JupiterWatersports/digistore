<?php
/*
  $Id: upload.php 1739 2007-12-20 00:52:16Z hpdl $
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/
  class upload {
    var $file, $filename, $destination, $permissions, $extensions, $tmp_filename, $message_location, $medium, $retina, $small;
    function upload($file = '', $destination = '', $permissions = '777', $extensions = '') {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);
      $this->medium = array();
      $this->set_retina_image($retina);
      $this->set_small_image($small);
      $this->set_output_messages('direct');
      if (tep_not_null($this->file) && tep_not_null($this->destination)) {
        $this->set_output_messages('session');
        if ( ($this->parse() == true) && ($this->save() == true) ) {
          return true;
        } else {
          return false;
        }
      }
    }
    function parse() {
      global $HTTP_POST_FILES, $messageStack;
      $file = array();
      if (isset($_FILES[$this->file])) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      } elseif (isset($HTTP_POST_FILES[$this->file])) {
        $file = array('name' => $HTTP_POST_FILES[$this->file]['name'],
                      'type' => $HTTP_POST_FILES[$this->file]['type'],
                      'size' => $HTTP_POST_FILES[$this->file]['size'],
                      'tmp_name' => $HTTP_POST_FILES[$this->file]['tmp_name']);
      }
      if ( tep_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        if (sizeof($this->extensions) > 0) {
          if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
            if ($this->message_location == 'direct') {
              $messageStack->add(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            } else {
              $messageStack->add_session(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            }
            return false;
          }
        }
        $this->set_file($file);
        $this->set_filename($file['name']);
        $this->set_tmp_filename($file['tmp_name']);
        return $this->check_destination();
      } else {
        if ($this->message_location == 'direct') {
          //$messageStack->add(WARNING_NO_FILE_UPLOADED, 'warning');
        } else {
          $messageStack->add_session(WARNING_NO_FILE_UPLOADED, 'warning');
        }
        return false;
      }
    }
    function save() {
      global $messageStack;
      if (substr($this->destination, -1) != '/') $this->destination .= '/';
      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
        chmod($this->destination . $this->filename, $this->permissions);
        if ($this->message_location == 'direct') {
          $messageStack->add(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        } else {
          $messageStack->add_session(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        }
        
          
        /* 
        // imaj boyutland˝rma ba˛lar
        $img = $this->destination . $this->filename;
        $jpg_quality = 90; // JPG quality as a percentage
        $constrain = 1;  // 1 or 0: set 1 if width or length are both, or width or length contraction occurs
        $w = 600;  // width of the image to be reduced
        $h = 600;  // height of the image to be reduced
        $x = @getimagesize($img); // Let's find the size and type of the image
        $sw = $x[0]; // width of the uploaded image
        $sh = $x[1]; // height of the uploaded image
        if ($sw > $w || $sh > $h) { // If the width or length of the uploaded image is bigger than the size we want, let's continue the process.
            if (isset ($w) AND !isset ($h)) { // only if the height value is given
                $h = (100 / ($sw / $w)) * .01;
                $h = @round ($sh * $h);
            } elseif (isset ($h) AND !isset ($w)) { // only if the width value is given
                $w = (100 / ($sh / $h)) * .01;
                $w = @round ($sw * $w);
            } elseif (isset ($h) AND isset ($w) AND isset ($constrain)) {
                // $If the constrain value and the size and width of the picture to be created are given together, it is adjusted according to the size, whichever is appropriate.
                $hx = (100 / ($sw / $w)) * .01;
                $hx = @round ($sh * $hx);
                $wx = (100 / ($sh / $h)) * .01;
                $wx = @round ($sw * $wx);
                
                if ($hx < $h) {
                    $h = (100 / ($sw / $w)) * .01;
                    $h = @round ($sh * $h);
                } else {
                    $w = (100 / ($sh / $h)) * .01;
                    $w = @round ($sw * $w);
                }
            }
            if (function_exists( 'exif_imagetype' )) $img_type = exif_imagetype($img); // do you have this function?
            else $img_type = $x[2];
            switch ($img_type) {
               case IMAGETYPE_GIF  : $im = @ImageCreateFromGIF ($img); break;
               case IMAGETYPE_JPEG : $im = @ImageCreateFromJPEG ($img); break;
               case IMAGETYPE_PNG  : $im = @ImageCreateFromPNG ($img); break;
        //	   case IMAGETYPE_WBMP : $im = @ImageCreateFromwbmp ($img); break;
               default : $im = false; // If the image is not JPEG, PNG, wBMP or GIF
               }
            if ($im) {
                $thumb = @ImageCreateTrueColor ($w, $h);
                @ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
                @ImageJPEG ($thumb, $img, $jpg_quality); // create a resized image
                @imagedestroy($thumb);
            }
        }
        // End Image Sizing
        */
          
        $filename = $this->destination . $this->filename;
          
        $maxHeight = array('500', '400', '200');
        $maxWidth = array('500', '400', '200');  
          
       // $maxHeight = 600; 
       // $maxWidth = 600;
          
        $find_string = '_1000.jpg';
        $replace_500_string = '_500.jpg';
        $replace_400_string = '_400.jpg';
        $replace_200_string = '_200.jpg';  
        
        for ($i=0; $i<3; $i++ ){
           // Rename file
            ${'filename_'.$maxHeight[$i]} = str_replace($find_string,${'replace_'.$maxHeight[$i].'_string'},$filename);
            
            $extension = substr($filename,-3); 
            if($extension == 'gif') { 
                $src = imagecreatefromgif($filename); 
            } elseif($extension == 'png') { 
                $src = imagecreatefrompng($filename); 
            } elseif($extension == 'jpg'||$extension == 'jpeg') { 
                $src = imagecreatefromjpeg($filename); 
            }
            
            $oldWidth = imagesx($src); 
            $oldHeight = imagesy($src); 
            if($oldWidth > $maxWidth[$i]||$oldHeight > $maxHeight[$i]) { 
                if(($maxWidth[$i]/$oldWidth) >= ($maxHeight[$i]/$oldHeight)) 
                    $factor = $maxHeight[$i]/$oldHeight; 
                else 
                    $factor = $maxWidth[$i]/$oldWidth; 
                    $newWidth = $oldWidth*$factor; 
                    $newHeight = $oldHeight*$factor;               
            } else { 
                $newWidth = $oldWidth; 
                $newHeight = $oldHeight; 
            }
            
            ${'trimmed_filename_'.$i} = substr(${'filename_'.$maxHeight[$i]}, strrpos(${'filename_'.$maxHeight[$i]}, '/')+ 1);
            
            if($maxHeight[$i] == '500'){
                $this->medium = array('1' => ${'trimmed_filename_'.$i});
            }
            
            if($maxHeight[$i] == '400'){
                $this->retina = array('1' => ${'trimmed_filename_'.$i});
            }
            
            if($maxHeight[$i] == '200'){
                $this->small = array('1' => ${'trimmed_filename_'.$i});
            }
            
            $tmp = imagecreatetruecolor($newWidth,$newHeight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);
            
            // Enable interlancing
            imageinterlace($src, true);

            // Save the interlaced image
            imagejpeg($tmp, ${'filename_'.$maxHeight[$i]},80); 
            
            imagedestroy($tmp);   
            imagedestroy($src);     
               
        }  
        // rename file to 500px  
          
       // $filename_500 = str_replace($find_string,$replace_500_string,$filename);  
/*
        $extension = substr($filename,-3); 
        if($extension == 'gif') { 
            $src = imagecreatefromgif($filename); 
        } elseif($extension == 'png') { 
            $src = imagecreatefrompng($filename); 
        } elseif($extension == 'jpg'||$extension == 'jpeg') { 
            $src = imagecreatefromjpeg($filename); 
        }
        
        $oldWidth = imagesx($src); 
        $oldHeight = imagesy($src); 
        if($oldWidth > $maxWidth||$oldHeight > $maxHeight) { 
            if(($maxWidth/$oldWidth) >= ($maxHeight/$oldHeight)) 
                $factor = $maxHeight/$oldHeight; 
            else 
				$factor = $maxWidth/$oldWidth; 
				$newWidth = $oldWidth*$factor; 
				$newHeight = $oldHeight*$factor;               
        } else { 
            $newWidth = $oldWidth; 
            $newHeight = $oldHeight; 
        } 
        
        $tmp = imagecreatetruecolor($newWidth,$newHeight); 
        imagecopyresized($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);         
          
		imagejpeg($tmp,$filename,100);   
        imagedestroy($tmp);   
        imagedestroy($src);   
         */
          
        return true;
      } else {
        if ($this->message_location == 'direct') {
          $messageStack->add(ERROR_FILE_NOT_SAVED, 'error');
        } else {
          $messageStack->add_session(ERROR_FILE_NOT_SAVED, 'error');
        }
        return false;
      }
    }
            
    function set_file($file) {
      $this->file = $file;
    }
    function set_destination($destination) {
      $this->destination = $destination;
    }
    function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }
    function set_filename($filename) {
      $this->filename = $filename;
    
    }
      
    function set_medium_image(){
      //  echo $this->medium;
       $this->medium = substr($medium, 0, strrpos($medium, '/'));
    }
    
    function set_retina_image($medium){
        
    }  
    
    function set_small_image($medium){
        
    }  
      
    function set_tmp_filename($filename) {
      $this->tmp_filenames = $filename;
    }
    function set_extensions($extensions) {
      if (tep_not_null($extensions)) {
        if (is_array($extensions)) {
          $this->extensions = $extensions;
        } else {
          $this->extensions = array($extensions);
        }
      } else {
        $this->extensions = array();
      }
    }
    function check_destination() {
      global $messageStack;
      if (!is_writeable($this->destination)) {
        if (is_dir($this->destination)) {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          }
        } else {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          }
        }
        return false;
      } else {
        return true;
      }
    }
    function set_output_messages($location) {
      switch ($location) {
        case 'session':
          $this->message_location = 'session';
          break;
        case 'direct':
        default:
          $this->message_location = 'direct';
          break;
      }
    }
  }
?>