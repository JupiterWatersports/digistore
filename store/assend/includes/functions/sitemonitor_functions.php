<?php
/*
  $Id: sitemonitor_functions.php, v 1.0 2006/07/22 
   by Jack_mcs - oscommerce-solution.com

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
 
function AddToExcludeList(&$curList, $newItem, $admin)  //check to see if the new path is already in the list
{      
  $comma = (empty($curList) ? '' : ", ");               //add a comma separator where needed
  $parts = explode(",", $curList);  

  for ($i = 0; $i < count($parts); ++$i)
  {
     $item = trim(str_replace("\"", "", $parts[$i])); //contains the directory name
     if ($item === ltrim($admin . "quarantine", "/"))
       continue;

     if ($item === $newItem)  //file already in list
     {
        $dirName = sprintf("\"%s\",", $newItem);
        $curList = str_replace($dirName, "", $curList);
        return ''; //ERROR_ALREADY_EXISTS;
     }
     
     if (substr($newItem, 0, strlen($item)) == $item ) //parent in list
        return ERROR_PARENT_EXISTS; //parent exists in list

     if ($newItem == substr($item, 0, strlen($newItem)))
       return ERROR_CHILD_EXISTS; //child exists in list
  }    

  $curList = stripslashes(sprintf("%s%s\"%s\"",$curList, $comma, $newItem));
  return '';
} 

function CheckExcludeList($str)
{
  if (empty($str) || $str[0] != '"')     //list does not begin with a quote
    return "FAILED: Exclude list does not begin with quotes.";
    
  $parts = explode(",", $str);
  for ($i = 0; $i < count($parts); ++$i)
  {
    $parts[$i] = trim($parts[$i]);
    if ($parts[$i][0] != '"' || $parts[$i][strlen($parts[$i])-1] != '"')
      return (sprintf("FAILED: %s isn't enclosed in quotation marks.",$parts[$i]));   //each item is not surrounded by quotes
  }   
    
  $cleanstring = ereg_replace("[\t\r\n]+","",trim($str)); 
 
  return $cleanstring;                   //remove spaces, tabs and newlines
}

function CheckLogSize()  //create a new log file if current one is too large
{
  global $logfile, $logfile_size;

  if ($logfile && (int)$logfile_size > 0 && filesize('sitemonitor_log.txt') >= $logfile_size)
  {
    if (copy('sitemonitor_log.txt', 'sitemonitor_log_' .  date("d_m_Y") . '.txt'))
    {
      if (($fp = fopen("sitemonitor_log.txt", "w")))
        fclose($fp);  
    }
  }  
}

function CreateDirectories($base, $backupLocn, $mode = 0755)
{
  $dirs = explode('/' , $backupLocn);
  $count = count($dirs);
  
  if (strpos($dirs[$count - 1], ".php") !== FALSE)
    unset($dirs[$count - 1]);
  
  $subDir = '';
  
  for ($i = 0; $i < $count; ++$i) 
  {
    $path = (tep_not_null($subDir)) ? $subDir . '/' . $dirs[$i] : $base . $subDir . $dirs[$i];
    
    if (!is_dir($path) && ! @mkdir($path, $mode)) 
      return false;
    else 
      $subDir = $path; 
  }
  return true;
}
 
function CreateReferenceFile($dir,$level,$last,&$files)
{  
  $dp=opendir($dir); 
 
  while (false!=($file=readdir($dp)) && $level == $last)
  { 
     if ($file!="." && $file!="..") 
     {  
        $path = $dir."/".$file;
        if (is_dir($path)) 
        { 
          if (ExcludeDirectory($dir, $path))
            continue;
            
          CreateReferenceFile($path,$level+1,$last+1,$files); // uses recursion 
        } 
        else
        {   
          if (strpos($file, "sitemonitor_") !== FALSE ||  //exclude all sitemonitor files
              strpos($file, "error_log") !== FALSE )
            continue;
        
          $locn = "$dir/$file";
          $r = @stat($locn); 
          $str = sprintf("%s,%d,%d,%d", $locn, $r[7], $r[9],substr(sprintf('%o', @fileperms($locn)), -3));
          $files[] = $str;  // reads the file into an array
        } 
     } 
  } 
} 
 
function DisplayMessage($verbose, $msg)
{ 
  $str = $msg;
  if ($verbose) echo $str . '<br>';
  return ($str . "\n");
}
 
function ExcludeDirectory($start_dir, $dir)
{
  global $excludeList;
  $start_dir = ltrim($start_dir);
  $path = GetDirectoryName($start_dir, $dir);
  return (in_array($path, $excludeList));
}

function GetConfigureSetting($str, $beginDelimiter, $endDelimiter)
{
  if (($posStart = strpos($str, $beginDelimiter)) !== FALSE)
    if (($posStop = strpos($str, $endDelimiter, $posStart + 1)) !== FALSE)
     return (substr($str, $posStart + 1, $posStop - $posStart - 1));

  return '';
}
 
function GetDirectoryName($start_dir, $path)  //return the partial directory 
{
  if (strpos($path, $start_dir) !== FALSE)
   $path = substr($path, strlen($start_dir) + 1);

  return $path;
}

function GetFileName($full_path)
{
  global $start_dir;
  return substr($full_path, strlen($start_dir) + 1);
}
 
function GetList($dir, $level, $last, $dir_list) //build list of site for exclude list selector
{
  $dp=opendir($dir); 

  while (false!=($file=readdir($dp)) && $level == $last)
  { 
     if ($file!="." && $file!="..") 
     {
        if (is_dir($dir.$file)) 
        { 
           $dir_list[] = ltrim(substr($dir, strlen(DIR_FS_CATALOG)-1).$file, "/");
           $dir_list = GetList($dir.$file."/",$level+1,$last+1, $dir_list);
        }
     }
  }      
 
  closedir($dp);
  return $dir_list;
}
    
function GetSize($path) 
{  
  if(!is_dir($path))return @filesize($path); 
  $dir = opendir($path); 
  while($file = readdir($dir)) 
  {  
    if(is_file($path."/".$file))$size+=filesize($path."/".$file); 
    if(is_dir($path."/".$file) && $file!="." && $file !="..")$size +=get_size($path."/".$file);     
  } 
  return $size; 
} 

function GetPart($part, $path)
{
  $parts = explode(",", $path);   
  return trim($parts[$part]);
}
 
function GetReferenceFiles($path) //use curl if possible to read in site information
{
  global $username, $password, $admin_dir;
  $lines = array();
  
  if (! empty($admin_dir) && ! empty($username) && ! empty($password) && function_exists('curl_init')) 
  {
    $path = $admin_dir . '/' . $path;
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, $path);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    $lines = explode("\n", $file_contents); 
  }
  else
  {
    $fd = fopen ($path, "r");
    while (!feof ($fd)) 
    {
      $buffer = fgets($fd, 4096);
      $lines[] = $buffer;
    }
    fclose ($fd);   
  }
  
  if (empty($lines))
  {
     echo 'Failed to read Reference File';
     exit;
  }

  return $lines;  
}
  
function WriteFile($filename, $files)
{
  $fpOut = fopen($filename, "w");
 
  if (! $fpOut)
  {
     echo 'Failed to open file '.$filename;
     exit;
  }
       
  $size = count($files) - 1;              //don't write last line     
  for ($idx = 0; $idx < $size; ++$idx)
  {
    $str = $files[$idx]."\n";
    
    $str = str_replace('./','',$str);
    if (fwrite($fpOut, $str) === FALSE)
    {
       echo "Cannot write to file ($filename)";
       exit;
    } 
  }  
  
  $str = $files[$idx];                    //write the last line without a line feed
  $str = str_replace('./','',$str);
  if (fwrite($fpOut, $str) === FALSE)
  {
     echo "Cannot write to file ($filename)";
     exit;
  } 
  
  fclose($fpOut);   
}

function WriteConfigureFile($filename, $fp)
{
  if (!is_writable($filename)) 
  {
     if (!chmod($filename, 0666)) {
        echo "Cannot change the mode of file ($filename)";
        exit;
     }
  }
  $fpOut = fopen($filename, "w");
 
  if (!fpOut)
  {
     echo 'Failed to open file '.$filename;
     exit;
  }

  for ($idx = 0; $idx < count($fp); ++$idx)
  {
    if (fwrite($fpOut, $fp[$idx]) === FALSE)
    {
       echo "Cannot write to file ($filename)";
       exit;
    }
  }   
  fclose($fpOut);   
}

function WriteLogFile($logEntry, $today)
{ 
  $fp = fopen("sitemonitor_log.txt", "a");

  if ($fp)
  {  
    fputs($fp, "SiteMonitor Log Entry for " . $today. "\n");
    
    if (count($logEntry) == 0)
      fputs($fp, "No mismatches found\n");
    else
    {
      foreach(array_keys($logEntry) as $groupKey) 
      {
        fputs($fp, $groupKey . "\n");
        
        foreach($logEntry[$groupKey] as $item)
          fputs($fp, "\t" .$item . "\n");
      }     
    }
    fputs($fp, "\n\n");
    fclose($fp);     
  }     
}
function microtime_float() //just used for testing
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
function runSitemonitor($verbose)
{
  global $start_dir, $always_email, $logfile, $reference_reset, $excludeList, $quarantine, $to, $from, $admin_dir, $username, $password;
  
  if (! defined('NAME')) define('NAME', 0);
  if (! defined('SIZE')) define('SIZE', 1);
  if (! defined('TIME')) define('TIME', 2);
  if (! defined('PERM')) define('PERM', 3);
 
  $msg = '';
  $files = array(); 
  $logEntry = array(); 
  
  CheckLogSize();
  clearstatcache();    
  $referenceFile = "sitemonitor_reference.php";

  /************** READ IN THE FILES ****************/
  
  //$time_start = microtime_float();
  CreateReferenceFile($start_dir, 1, 1, $files);
  //print microtime_float() - $time_start;
 
  /************** SAVE THE FILES OR, IF PRESENT, READ THEM IN ****************/
  if (! file_exists($referenceFile))
  {
    if (empty($files))
    {
      echo 'Reference file creation failed.';
      return -1;
    }  

    WriteFile($referenceFile, $files);
    if ($verbose) echo 'First time ran. Reference file was created and saved.';
    return -2;
  } 
  else
  {
    $refFiles = GetReferenceFiles($referenceFile);   //read in the saved file 
    $size = count($refFiles);
    
    for ($i = 0; $i < $size; ++$i)
    {
      $refFiles[$i] = rtrim($refFiles[$i]);
      $pos = strpos($refFiles[$i], ",");
      $refFiles[$i] = substr($refFiles[$i], 0, $pos); 
    }  
  }

  /************** COVERT NEW FILES TO NORMAL FILENAME ****************/
  $size = count($files);
  for ($i = 0; $i < $size; ++$i)
  {
    $files[$i] = str_replace("./", "", $files[$i]);
    $pos = strpos($files[$i], ",");
    $files[$i] = substr($files[$i], 0, $pos); 
  }    

  /************** SEE IF THERE ARE ANY NEW FILES ****************/
  $diff_added = array_diff($files, $refFiles);
  $msg = "NEW FILES:\n";
  $ttlErrors = 0;
  
  if (count($diff_added) > 0)
  { 
     foreach($diff_added as $key => $value)  //can't use for loop due to keys staying constant - key 0 may not be present
     { 
       $msg .= DisplayMessage($verbose, ('Found a new file named ' . GetFileName($value)));
       $logEntry['New File Added'][] = $value;  
       $ttlErrors++;   
       
       if ($quarantine)                     //new found file is to be moved to a safe directory
       {
         $file = GetFileName($value);
         $backupFile = $file;
         $newfile = "quarantine/$file";     //get new file location and name

         if (file_exists($newfile))         //rename won't overwrite so create a new name
         {
           $path_parts = pathinfo($file);
           if (($pos = strpos($file, $path_parts['extension'])) !== FALSE) //get the extension
           {
             $newfile = sprintf("quarantine/%s_%s.%s  ",substr($file, 0, $pos - 1), date("d_m_Y"),$path_parts['extension'] );
             $backupFile = sprintf("%s_%s.%s  ",substr($file, 0, $pos - 1), date("d_m_Y"),$path_parts['extension'] );
           }  
         }

         if (CreateDirectories(DIR_FS_ADMIN . 'quarantine/', $backupFile))
         {
           if (rename($value, $newfile))      //move the file 
           {
             $msg .= DisplayMessage($verbose, ('Quarantined new file: ' . GetFileName($value)));
             $logEntry['New File Quarantined'][] = $value; 
           }
         }
         else
         {
           $msg .= DisplayMessage($verbose, ('Failed to create Quarantine directory for: ' . GetFileName($value)));
           $logEntry['Failed to create Quarantine directory for'][] = $value; 
         }
       }
     }   
  }
  else
     $msg .= DisplayMessage($verbose, 'No new files found...');

  /************** SEE IF THERE ARE ANY DELETED FILES ****************/
  $diff_deleted = array_diff($refFiles, $files);
  $msg .= "\nDELETED FILES:\n";

  if (count($diff_deleted) > 0)
  { 
     foreach($diff_deleted as $key => $value)  //can't use for loop due to keys staying constant - key 0 may not be present
     { 
        $msg .= DisplayMessage($verbose, ('Found a deleted file named ' . GetFileName($value)));
        $logEntry['File Deleted'][] = $value; 
        $ttlErrors++;
     }
  }
  else
    $msg .= DisplayMessage($verbose, 'No deleted files found...');

  /************** SEE IF THE FILE SIZES ARE DIFFERENT ****************/
  $error = 0;
  $msg .= "\nSIZE MISMATCH:\n";
  
  if (! $diff_deleted)
  {
    $size = count($files);
    $refFiles = GetReferenceFiles($referenceFile);  //reload for all checks below

    if ($size == count($refFiles)) 
    {  
      for ($i = 0; $i < $size; ++$i)
      {
         $newSize = GetSize($files[$i]);
         $oldSize = GetPart(SIZE, $refFiles[$i]);
         if ($newSize != $oldSize) 
         {
            $msg .= DisplayMessage($verbose, ('Difference found: New-> '. GetFileName($files[$i]) . ' '. $newSize . ' Original-> ' . $oldSize));
            $logEntry['Size Changed'][] = $files[$i]; 
            $error++;
            $ttlErrors++;
         }  
      }
    }  
    else if ($size > count($refFiles))  //files were added
    {
       $sizeA2 = count($refFiles);
       
       for ($i = 0; $i < $size; ++$i)
       {
         if (in_array($files[$i], $diff_added))        //ignore the new file
            continue;
          
         for ($t = 0; $t < $sizeA2; ++$t)
         {
            if ($files[$i] === GetPart(NAME, $refFiles[$t]))
            { 
               $newSize = GetSize($files[$i]);
               $oldSize = GetPart(SIZE, $refFiles[$t]);
               if ($newSize != $oldSize) 
               {
                 $msg .= DisplayMessage($verbose, ('Difference found: New-> '. GetFileName($files[$i]) . ' '.$newSize. ' Original-> ' .$oldSize));
                 $logEntry['Size Changed'][] = $files[$i]; 
                 $error++;
                 $ttlErrors++;
                 break;
               } 
            }  
         }
       }
    }
    if (! $error)
      $msg .= DisplayMessage($verbose, 'No size differences found...');
  }
  else
    $msg .= DisplayMessage($verbose, 'Size differences not checked due to deleted file(s)');
      
  /************** SEE IF THE TIMESTAMPS ARE DIFFERENT ****************/  

  $msg .= "\nTIME MISMATCH:\n";
  if (! $diff_deleted)
  {
    $error = 0;
    $size = count($files);
    
    if ($size == count($refFiles)) //increase by one to account for sitemonitor_reference.php
    {         
       for ($i = 0; $i < $size; ++$i)
       {
          $r = @stat($files[$i]);
          if ($r[9] != GetPart(TIME, $refFiles[$i]))
          {
            $msg .= DisplayMessage($verbose, ('Time Mismatch on '. GetFileName($files[$i]). ' Last Changed on  ' . gmstrftime ("%A, %d %b %Y %T %Z", $r[9])));
            $logEntry['Time Changed'][] = $files[$i]; 
            $error++; 
            $ttlErrors++;
          }  
        }
     }
     else if ($size > count($refFiles))
     {
       $sizeA2 = count($refFiles);
       
       for ($i = 0; $i < $size; ++$i)
       {
         if (in_array($files[$i], $diff_added))        //ignore the new file
            continue;
              
         for ($t = 0; $t < $sizeA2; ++$t)
         {        
            if ($files[$i] === GetPart(NAME, $refFiles[$t]))
            {        
               $r = @stat($files[$i]);
               if ($r[9] != GetPart(TIME, $refFiles[$t]))
               {
                 $msg .= DisplayMessage($verbose, ('Time Mismatch on '. GetFileName($files[$i]). ' Last Changed on  ' . gmstrftime ("%A, %d %b %Y %T %Z", $r[9])));
                 $logEntry['Time Changed'][] = $files[$i]; 
                 $error++; 
                 $ttlErrors++;
                 break;
               }  
            }  
         }
       }   
     }
     if (! $error)
       $msg .= DisplayMessage($verbose, 'No time mismatches found...');
   }
   else
     $msg .= DisplayMessage($verbose, 'Time differences not checked due to deleted file(s)');
   
        
  /************** SEE IF THE PERMISSIONS ARE DIFFERENT ****************/  
  $msg .= "\nPERMISSIONS MISMATCH:\n";
  
  if (! $diff_deleted)
  {  
    $error = 0;
    $size = count($files);
    
    if ($size == count($refFiles))  
    {  
       for ($i = 0; $i < $size; ++$i)
       {
          $pCurrent = substr(sprintf('%o', @fileperms($files[$i])), -3);
          $pLast =  GetPart(PERM, $refFiles[$i]);
          
          if ($pCurrent != $pLast)
          {
            $msg .= DisplayMessage($verbose, ('permissions Mismatch on '. GetFileName($files[$i]). ' Currently set to "' . $pCurrent . '" was set to "' . $pLast .'"'));
            $logEntry['Permissions Change'][] = $files[$i]; 
            $error++; 
            $ttlErrors++;
          }  
        }
     }
     else if ($size > count($refFiles))
     {
       $sizeA2 = count($refFiles);
       
       for ($i = 0; $i < $size; ++$i)
       {
         if (in_array($files[$i], $diff_added))        //ignore the new file
            continue;
          
         for ($t = 0; $t < $sizeA2; ++$t)
         {
            if ($files[$i] === GetPart(NAME, $refFiles[$t]))
            {
               $pCurrent = substr(sprintf('%o', @fileperms($files[$i])), -3);
               $pLast =  GetPart(PERM, $refFiles[$t]);
               if ($pCurrent != $pLast)
               {
                 $msg .= DisplayMessage($verbose, ('permissions Mismatch on '. GetFileName($files[$i]). ' Currently set to ' . $pCurrent . ' was set to ' . $pLast));
                 $logEntry['Permissions Change'][] = $files[$i]; 
                 $error++; 
                 $ttlErrors++;
                 break;
               }  
            }  
         } 
       }    
     }     
     if (! $error)
       $msg .= DisplayMessage($verbose, 'No permissions mismatches found...');
   }   
   else
     $msg .= DisplayMessage($verbose, 'Permissions not checked due to deleted file(s)');
 
   $today = date("F j, Y, g:i a");
   $msg .= DisplayMessage($verbose, '');
   $msg .= DisplayMessage($verbose, '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
   $msg .= DisplayMessage($verbose, "Sitemonitor ran on " . $today);
   $msg .= DisplayMessage($verbose, "Total mismatches found were " . $ttlErrors);
   $msg .= DisplayMessage($verbose, "Total files being monitored is " . count($refFiles));
   
   if ($ttlErrors || $always_email)
   {
     mail($to, 'Site Monitor Results', $msg, $from); 
     if ($verbose)
      echo 'Email sent to shop owner.' .'<br>';
   }   

   if ($logfile) 
     WriteLogFile($logEntry, $today);
   
   return $ttlErrors;
  }
?>