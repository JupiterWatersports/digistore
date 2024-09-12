<?PHP
/*******************************************************************************
* Address-label printing                                                          
*                                                                         
* Version: 0.1                                                              
* Date:    2010-02-04                                                     
* Author:  Heiko Hoebel, http://www.ib-hoebel.de     
*  Released under the GNU General Public License                                              
*******************************************************************************/
require('includes/application_top.php');

if ($_GET['label']!='' && (int)$_GET['label'] >= 0) {
    $oID = (int)$_GET['oID'];
    //set "checked" to next label
    $_SESSION['lastLabel'] = (int)$_GET['label']+1;
    if ($_SESSION['lastLabel'] >= (ADDRESS_LABEL_QUANTITY_X*ADDRESS_LABEL_QUANTITY_Y)) $_SESSION['lastLabel'] = 0;
    //save current passed order-ID at the choosen label
    $_SESSION['savedLabel' . $_GET['label']] = $oID; 
    //if printing is choosen -> start PDF-creation
    if (isset($_GET['print'])) {
       tep_redirect('label_pdf.php');
    }   
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?PHP if (isset($_GET['save'])) echo '<script language="javascript">
<!-- 
self.close();
-->
</script>'; 
?>
<meta http-equiv="Content-Type" content="text/html;">
<title>Please choose label</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>

<body><table><tr><td width="300" align="center">
<form action="choose_label.php" method="get"><br>
Please choose label-position:<br><br>
<table border="1" cellspacing="0" bgcolor="#EEEEEE" >
<?PHP
    $num = 0;
    for ($j=0;$j<ADDRESS_LABEL_QUANTITY_Y;$j+=1) {
?>
    <tr>
<?PHP
        for ($i=0;$i<ADDRESS_LABEL_QUANTITY_X;$i+=1) {
            echo '<td width="' . (int)(200/ADDRESS_LABEL_QUANTITY_X) . '" height="' . (int)(260/ADDRESS_LABEL_QUANTITY_Y) . '" align="center" valign="middle"><input type="radio" name="label" value="' . $num . '" ' .(($_SESSION['lastLabel']==$num)?'CHECKED':'') . '></td>';
            $num += 1;
        }
?>
    </tr>
<?PHP
    }
?>
</table><br>
<input type="submit" name="save" value="Save">
<input type="hidden" name="oID" value="<?PHP echo $_GET['oID']; ?>">
<table><tr><td width="200"  align="center"><br>
        <input type="submit" name="print" value="Print"></td><tr></table>

</form></td></tr></table>
</body>