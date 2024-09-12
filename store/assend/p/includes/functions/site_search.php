<?php
/*
  $Id: site_search.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
//search the indicated direcory for the search term
function CheckFileContents($dir, $searchTerm, &$fileList, $languageDir)
{  
   $matches = glob($dir . '/*'); 
   
   if (is_array($matches)) 
   {  
      foreach ($matches as $filePath) 
      { 
         if (is_dir($filePath)) 
         {
           if ($languageDir === end(explode("/", $filePath)))
             CheckFileContents($filePath, $searchTerm, $fileList, $languageDir);
         } 
         else  
         {
           $fileName = end(explode("/", $filePath));
           if (end(explode(".", $fileName)) == 'php') //NOTE: fails if there's an extenstion such file.php.old
           {
      		  $contents = file_get_contents($filePath);
             
              if (strpos(strtolower($contents), strtolower($searchTerm))) 
      	     { 
                 if (file_exists(DIR_FS_CATALOG . '/' . $fileName))     
                 {
                    $fileList[] = array('file' => $fileName, 'ext' => ''); 
                 }  
              } 
           }  
         }  
      } 
   } 
   
   return $fileList;
} 

function CheckAllPages($dir, $searchTerm, &$fileList, &$articlesList, &$pagesList, $languages_id)
{
   $languageDir = GetLanguageDirectory($languages_id); //need the name of the directory to search
   $fileList = CheckFileContents($dir, $searchTerm, $fileList, $languageDir);
   $pagesList = CheckInfomationPages($searchTerm, $pagesList, $languages_id);
   $articlesList = CheckArticles($searchTerm, $languages_id);
}
    
function CheckArticles($searchTerm, $languages_id)
{
   $articlesList = array();
   
   $config_query = tep_db_query("select count(*) as total from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_title LIKE '" . 'Articles Manager' . "'");
   $config = tep_db_fetch_array($config_query);
   
   if ($config['total'] > 0)
   {
     $articles_query = tep_db_query("select articles_id, articles_name, articles_description from " . TABLE_ARTICLES_DESCRIPTION . " where ( articles_name like '%" . $searchTerm . "%' or articles_description like '%" . $searchTerm . "%' or articles_head_desc_tag like '%" . $searchTerm . "%' ) and language_id = " . (int)$languages_id); 
     while ($articles = tep_db_fetch_array($articles_query))
     {
       $articlesList[] = array('id' => $articles['articles_id'], 'name' => $articles['articles_name']);
     }
   }
   return $articlesList;
}

function CheckInfomationPages($searchTerm, $pagesList, $languages_id)
{
   if (defined('TABLE_PAGES'))
   {
     $pages_query = tep_db_query("select pd.pages_id, p.pages_name from " . TABLE_PAGES . " p left join " . TABLE_PAGES_DESCRIPTION . " pd on p.pages_id = pd.pages_id where ( pages_title like '%" . $searchTerm . "%' or pages_body like '%" . $searchTerm . "%' ) and language_id = " . (int)$languages_id); 
     while ($pages = tep_db_fetch_array($pages_query))
     {
       $pagesList[] = array('file' => FILENAME_PAGES, 'ext' => 'page='.$pages['pages_name']); 
     }
   }
   return $pagesList;
}
  
function SortFileLists($a, $b) {
 return strnatcasecmp($a["file"], $b["file"]);
}

function GetLanguageDirectory($language_id)  //cut down version just for site search to use 
{ 
  $languages_query = tep_db_query("select directory from " . TABLE_LANGUAGES . " where languages_id = '" . $language_id . "' LIMIT 1");
  $languages = tep_db_fetch_array($languages_query);
  return $languages['directory'];
}
?>
