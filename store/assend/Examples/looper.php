<?php
$filess = $_FILES['file'];

 include ('../Classes/PHPExcel/IOFactory.php');
include ('../Classes/PHPExcel.php');

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filess['name']);
echo '<table border=1>' . "\n";
    $n = $objPHPExcel->getSheetCount(); 
    $q = 0;
    $temp = 0;
    for($z = 0; $z<$n; $z++){
        $objWorksheet = $objPHPExcel->setActiveSheetIndex($z);
    
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
$objWorksheet = $objPHPExcel->getActiveSheet();


$j = 0;
foreach ($objWorksheet->getRowIterator() as $row) {
  echo '<tr>' . "\n";
  $cellIterator = $row->getCellIterator();
  $cellIterator->setIterateOnlyExistingCells(false); 
    // This loops all cells,
    // even if it is not set.
    // By default, only cells
    // that are set will be 
    // iterated.
    $i = 0;
    
  foreach ($cellIterator as $cell) {
      if ($i>7){
          break;
      }
      switch($i){
        case '0':
              $input = 'name="date['.$q.']"';
            break;
        case '1':
              $input = '';
              break;
          
        case '3':
              $input = 'name="name['.$q.']"';
            break;
        case '4':
              $input = 'name="description['.$q.']"';
          break;
        case '5':
              $input = 'name="account['.$q.']"';
          break;
        case '6':
              $input = 'name="pay_method['.$q.']"';
            break;
        case '7':
              $input = 'name="amount['.$q.']"';
          break;
          default: 
              $inupt = '';
        
          
      }
    echo '<td><input class="form-control" '.$input.'value="'. $cell->getValue() . '"></td>' . "\n";
      $i++;
    
  }
  echo '</tr>' . "\n";
 

   
  if($q > $j){
        $q++;
        $temp = $j;
       
    } else {
        $temp++;
        $q++;
       
    }
    $j++;
}

  } 
echo '</table>' . "\n";


echo 'hello';
?>