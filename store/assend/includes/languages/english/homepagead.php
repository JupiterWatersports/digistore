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
define('ADFILE_SIZE','200000');
define('ADFILE_WIDTH','1000');
define('ADFILE_HEIGHT','1000');
define('AD_TITLE_BAR','Homepage Advertisements');
define('AD_FILETYPES' , '<BR><FONT COLOR="FF0000">NOTE:</FONT> Image must be in JPEG / GIF / PNG format and under ' . ADFILE_SIZE . ' kbs in size<P>');
define('AD_FILELOCATION' , 'Select File:');
define('ADERROR_ONE' , 'Filesize is to large, upload must be less than ' . ADFILE_SIZE . ' KBS <BR><BR>Filesize Uploaded: ');
define('ADERROR_TWO' , 'Upload must be a valid image file, JPEG / GIF / PNG<BR><BR>Type uploaded:');
define('ADERROR_FOUR' , 'Image must be less than ' . ADFILE_WIDTH . ' PX wide.<BR><BR>Width Uploaded:');
define('ADERROR_FIVE' , 'Image must be less than ' . ADFILE_HEIGHT . ' PX high.<BR><BR>Height Uploaded:');
define('ADSUCCESS_UPLOAD' , 'Image has been uploaded<BR><BR>Filename:');
define('ADSUCCESS_DELETE' , 'Image has been removed<BR><BR>Filename:');
define('ADINSTRUCTIONS' , 'To disable an advertisement click "hide" this will temporary prevent the advertisement being shown within the store. To reactivate a hidden advertisement click "show".:');
define('AD_MORE_THAN_ONE' , 'When more than one advertisement is activated they will be displayed on a random basis each time the page is loaded.');
define('AD_CONTENT' , 'Advertisement content');
define('AD_UPDATE' , 'Update');
define('AD_DELETE' , 'Delete');
define('AD_NO_ADS' , 'No ads currently available.');
?>

