<?php
/*
  $Id: advertisement.php,v 1.00 2005/11/01 01:45:58 hpdl Exp $   
   ============================================  
   DIGISTORE FREE ECOMMERCE OPEN SOURCE VER 3.2  
   ============================================
      
   (c)2005-2006
   The Digistore Developing Team NZ   
   http://www.digistore.co.nz                       
                                                                                           
   SUPPORT & PROJECT UPDATES:                                  
   http://www.digistore.co.nz/support/
   
   Portions Copyright (c) 2003 osCommerce, http://www.oscommerce.com
   http://www.digistore.co.nz   
   
   This software is released under the
   GNU General Public License. A copy of
   the license is bundled with this
   package.   
   
   No warranty is provided on the open
   source version of this software.
   
   ========================================
*/

define('ADMIN_TITLE' , 'New Homepage Advertisment');
define('ADFILE_SIZE','20000');
define('ADFILE_WIDTH','720');
define('ADFILE_HEIGHT','140');
define('AD_FILETYPES' , '<BR><FONT COLOR="FF0000">NOTE:</FONT> Image must be in JPEG / GIF / PNG format and under ' . ADFILE_SIZE . ' kbs in size<P>');
define('AD_FILELOCATION' , 'Select File:');
define('ADERROR_ONE' , 'Filesize is to large, upload must be less than ' . ADFILE_SIZE . ' KBS <BR><BR>Filesize Uploaded: ');
define('ADERROR_TWO' , 'Upload must be a valid image file, JPEG / GIF / PNG<BR><BR>Type uploaded:');
define('ADERROR_FOUR' , 'Image must be less than ' . ADFILE_WIDTH . ' PX wide.<BR><BR>Width Uploaded:');
define('ADERROR_FIVE' , 'Image must be less than ' . ADFILE_HEIGHT . ' PX high.<BR><BR>Height Uploaded:');
define('ADSUCCESS_UPLOAD' , 'Image has been uploaded<BR><BR>Filename:');
define('ADSUCCESS_DELETE' , 'Image has been removed<BR><BR>Filename:');

define('NEW_AD_TITLE' , 'Add new homepage advertisment:');
define('ADSUCCESS_DELETE' , 'Image has been removed<BR><BR>Filename:');
define('NEW_AD_CONTENT' , 'Input a product or catagory link into this box, this is used for the 
            <em>click here</em> button that is displayed on the advertisement.<br>
            eg ( <em>index.php/cPath/21 </em>) - this will show all products in 
            the category 21 when clicked.');
define('NEW_AD_TEXT' , 'Text');
define('NEW_AD_TEXT_CONTENT' , 'This is the text displayed on the advertisement ontop of the image.<br>This can be left blank if text is not required.');
define('NEW_AD_SELECT' , 'Select image file');
define('NEW_AD_SHOW' , 'Check to show the ad');
?>