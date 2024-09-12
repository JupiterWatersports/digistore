<?php 
/*
  SEO_Assistant for OSC 2.2 MS2 v2.0  03/02/08
  Originally Created by: Jack_mcs - oscommerce-solution.com
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/
           

 for($i=0, $x=1;$i<$hotlinktotal;$i+=$hits_per_page,$x+=$hits_per_page)
 {   
    $filename = "http://images.google.com/images?q=site:$searchurl&num={$hits_per_page}&hl=en&&um=1&start=$x&sa=N";
    $hotlinkConditions = "dyn.Img(.*)/";
    $conditions = "http://(.*)/";
    
    // Open the search page.
    $file = fopen($filename, "r");
    if (!$file) 
    {
    	echo "<p>Unable to open remote file $filename.\n";
    }
    else
    {    
       /******************************** GET THE LINKS ****************************/
       while (!feof($file))  // load the file into a variable line at a time
       {	 
    	    $var = fgets($file, 1024); 
  
          if (eregi($hotlinkConditions, $var, $out)) // find the html code this SE uses to show the site URL
    	    {  
          	 $out[1] = strtolower(strip_tags($out[1]));
             $out[1] = "dyn.img". $out[1]; //add back the intial missing tag
             
             $p1 = 0;
             $startPos = 0;
             
             while ($p1 !== FALSE && $startPos < strlen($out[1]))
             {
               $p2 = 0;
               $endPos = false;
             
               if (($p1 = strpos($out[1],"dyn.img(\"http:", $startPos)) !== FALSE)
               { 
                  $url = substr($out[1], $p1 + 16); //isolate the domain name
               
                  if (strlen($url) > 0)             //skip empty links
                  {  
                     $totalHotlinkLinks_Google++;
                     if ($p1 === strlen($out[1]) - strlen($url) || strpos($url, $searchurl) === FALSE)
                       $endPos = true;
   
                     if (($p2 = strpos($url,"/")) !== FALSE || $endPos)
                     {
                        if (! $endPos && strpos(substr($url, 0, $p2), $searchurl) === FALSE)
                        {
                          $p = (($p3 = strpos($url,"&")) !== FALSE) ? $p3 : $p2;
                          $hotlinkURL_Google[] = "http://" . substr($url, 0, $p);
                          $foundHotlink_Google++;
                        }
                        else if ($endPos && strpos($url, $searchurl) === FALSE)
                        {
                          if (($p3 = strpos($url,"&")) !== FALSE)
                            $hotlinkURL_Google[] = "http://" . substr($url, 0, $p3);
                          else                            
                            $hotlinkURL_Google[] = "http://" . $url;

                          $foundHotlink_Google++;
                        }
                     }
                   }   
                }
                if ($p2 === FALSE)
                 break;
                else 
                 $startPos += $p1 + $p2;
           
             }  // while loop ended; 
          }   
       }
    }		 
    fclose($file);	
 }         
?>