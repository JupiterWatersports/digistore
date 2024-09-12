<?php
/*
  $Id: sitemonitor.php,v 1.2 2006/10/28 by Jack_mcs

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  require('sitemonitor_configure.php');
  require('includes/functions/sitemonitor_functions.php');
  
  if(! (bool)ini_get('safe_mode'))
    set_time_limit(0);

  if ($reference_reset > 0) //delete the reference file 
  {
     $referenceFile = "sitemonitor_reference.php";
     if (file_exists($referenceFile)) 
     { 
       $r = @stat($referenceFile);
       if (floor((time()- $r[9]) / 86400) > $reference_reset)
       {
          runSitemonitor($verbose);  //first run the script to catch any late changes

          if (unlink($referenceFile)) //then remove it
          {
             if ($verbose) echo 'Reference file deleted due to reset.';
          }
       }
     }    
  }     
    
  runSitemonitor($verbose); 
?>