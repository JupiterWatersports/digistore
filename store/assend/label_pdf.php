<?php
/*******************************************************************************
* Address-label printing                                                          
*                                                                         
* Version: 0.1                                                              
* Date:    2010-02-04                                                     
* Author:  Heiko Hoebel, http://www.ib-hoebel.de     
*  Released under the GNU General Public License                                              
*******************************************************************************/

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');

require_once('includes/application_top.php');

include_once(DIR_WS_CLASSES . 'order.php');
$order[] = array();

// read saved order-ID's from Session-variables
for ($i=0;$i<(ADDRESS_LABEL_QUANTITY_X*ADDRESS_LABEL_QUANTITY_Y);$i=$i+1) {
    if (is_numeric($_SESSION['savedLabel' . $i])) {
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$_SESSION['savedLabel' . $i] . "'");
        $order[$i] = new order($_SESSION['savedLabel' . $i]);
    } else {
        $order[$i] = 0;
    }
    //delete saved order-ID's
    $_SESSION['savedLabel' . $i] = '';
}
    $_SESSION['lastLabel'] = 0;

//choose paper-format
if (ADDRESS_LABEL_PAPER_SIZE == 'A4') {
    $PaperFormat = 'A4';
    $PageWidth = 210.0;
    $PageHeight = 297.0;
} elseif (ADDRESS_LABEL_PAPER_SIZE == 'A5') {
    $PaperFormat = 'A5';
    $PageWidth = 148.0;
    $PageHeight = 210.0; 
} elseif (ADDRESS_LABEL_PAPER_SIZE == 'letter') {
    $PaperFormat = 'letter';
    $PageWidth = 216.0;
    $PageHeight = 279.0; 
} else { 
    $PageWidth = ADDRESS_LABEL_PAPER_WIDTH;
    $PageHeight = ADDRESS_LABEL_PAPER_HEIGHT;
    $PaperFormat = array($PageWidth, $PageHeight);  
}  
if (ADDRESS_LABEL_PAPER_ORIENTATION == 'Portrait') {
    $PaperOrientation = 'P';
} else {
    $PaperOrientation = 'L';
}
  
$pdf=new FPDF($PaperOrientation,'mm',$PaperFormat);

//calculate label-size, margins and offsets
$LabelWidth = ($PageWidth - (2*ADDRESS_LABEL_MARGIN_X)) / ADDRESS_LABEL_QUANTITY_X;
$LabelHeight = ($PageHeight - (2*ADDRESS_LABEL_MARGIN_Y)) / ADDRESS_LABEL_QUANTITY_Y; 
$MarginY = $LabelHeight / 10.0;
//standard-font-size
$FontSize = $LabelHeight / 3.6;
if ($FontSize > ADDRESS_LABEL_MAX_FONTSIZE) {
    $FontSize = ADDRESS_LABEL_MAX_FONTSIZE;
}

$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages();
// no margins
$pdf->SetMargins(0,0,0);
$pdf->AddPage();


//process all labels
for ($i=0;$i<(ADDRESS_LABEL_QUANTITY_X*ADDRESS_LABEL_QUANTITY_Y);$i+=1) {
    if ($order[$i] != 0) {
        $Label_X = (int)($i % ADDRESS_LABEL_QUANTITY_X);
        $Label_Y = (int)($i / ADDRESS_LABEL_QUANTITY_X);
        
        //set small font-size for "FROM"
        $pdf->SetFont('Arial','',$FontSize/1.5);
        //check if "FROM" fits onto label
        $StringFrom = $pdf->GetStringWidth(STORE_OWNER . ' • ' . 'Juipter Kiteboarding' . ' • ' . 'Jupiter, Florida');
        $pdf->SetFont('Arial','',$FontSize);
        $StringTo = 0;
        //check if "TO" fits onto label
        $address = explode("\n",tep_address_format($order[$i]->delivery['format_id'], $order[$i]->delivery, '', '', "\n"));
        foreach ($address as $$value) {
            $string_length = $pdf->GetStringWidth($value);
            if ($string_length > $StringTo) {
                $StringTo = $string_length;
            }
        }
        $pdf->SetFont('Arial','',$FontSize/1.5);
        //calculate a offset for each label to center the address on the label
        if ($StringTo < $StringFrom) {
            if ($StringFrom > $LabelWidth) {
                $FontSize = ($LabelWidth*$FontSize)/($StringFrom+5);
                $MarginX = 2;
            } else {
                $MarginX = ($LabelWidth-$StringFrom)/2;
            }
        } else {
             if ($StringTo > $LabelWidth) {
                $FontSize = ($LabelWidth*$FontSize)/($StringTo+5);
                $MarginX = 2;
            } else {
                $MarginX = ($LabelWidth-$StringTo)/2;
            }            
        }       
        //print "FROM"
        $pdf->SetTextColor(0);
        $pdf->Text(ADDRESS_LABEL_MARGIN_X+$MarginX+($Label_X*$LabelWidth),ADDRESS_LABEL_MARGIN_Y+$MarginY+($Label_Y*$LabelHeight), STORE_OWNER . ' • ' . 'Juipter Kiteboarding' . ' • ' . 'Jupiter, Florida');
        //print "TO"
        $pdf->SetFont('Arial','',$FontSize);
        $pdf->SetXY(ADDRESS_LABEL_MARGIN_X+$MarginX+($Label_X*$LabelWidth),ADDRESS_LABEL_MARGIN_Y+$MarginY+4+($Label_Y*$LabelHeight));
        $pdf->MultiCell($LabelWidth-(2*$MarginX), $FontSize/2.7, html_entity_decode(tep_address_format($order[$i]->delivery['format_id'], $order[$i]->delivery, '', '', "\n")),0,'L');
    }
}
$pdf->Output();

?>
