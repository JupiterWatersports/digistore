<?php 
/*
  SEO_Assistant for OSC 2.2 MS2 v2.0  08.03.2004
  Originally Created by: Jack_mcs
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/
  
 for($i=0, $x=1;$i<$harmfultotal;$i+=$hits_per_page,$x+=$hits_per_page)
 {   
    $filename = "http://www.google.com/search?q=site:$searchurl&num={$hits_per_page}&hl=en&lr=&filter=0&start=$x";
    $harmConditions = "help>(.*)</a>";
    $conditions = "<span class=a>(.*)</span><nobr>";
    
    // Open the search page.
    $file = @fopen($filename, "r");
    if (!$file) 
    {
    	echo "<p>Unable to open remote file $filename.\n";
    }
    else
    {    
       /***************************** GET THE TOTAL LINKS *************************/
       $stop = false;
       while (!feof($file))
       { 
         $var = fgets($file, 1024);
        
         if (eregi($harmConditions,$var,$out))
         { 
            $totalHarmfulLinks_Google = $out[1]; 
            break;
         }
       }
 

       /******************************** GET THE LINKS ****************************/
       while (!feof($file))  // load the file into a variable line at a time
       {	 
    	   $var = fgets($file, 1024); 
  
         if (eregi($conditions, $var, $out)) // find the html code this SE uses to show the site URL
    	   {  
            if (eregi($harmConditions, $var))
            {
            	$out[1] = strtolower(strip_tags($out[1]));
               if (($p1 = strpos($out[1], "this site may harm your computer")) !== FALSE)
               { 
                 if (preg_match_all("/(.+?) - .*/",$out[1], $res))
                 {                        
                    $harmfulURL_Google[] = "http://" . str_replace(" ", "", $res[1][0]);
                    $foundHarmful_Google++;
                 }
               }    
            }  
         }   
       }
    }		 
    fclose($file);	
 }         
?>