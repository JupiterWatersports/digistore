<?php

/*

  $Id: footer.php,v 1.12 2005/11/01 16:54:12 hpdl Exp $   

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

?>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

  <tr>

    <td width="12" class="adminssl">&nbsp;</td>

    <td width="688" class="adminssl" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666;">

      <?php

 if (getenv('HTTPS') == 'on') {

 echo (BOX_CONNECTION_PROTECTED);

 } else {

 echo (BOX_CONNECTION_UNPROTECTED);

 }



  ?>

<td > <div class="support" align="right"><a href="<?PHP echo FILENAME_SUPPORT; ?>" target="_blank"><?PHP echo HEADER_TITLE_SUPPORT; ?></a> &nbsp; </div></td></tr>

<tr>

<td width="12" class="adminssl">&nbsp;</td>

<td class="adminssl" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#666;">	

     <?PHP 

	 // This copyright notice CAN NOT be REMOVED or MODIFIED as required in the license agreement.

	 echo COPYRIGHT_NOTICE . '<BR>' . DIGIADMIN_VERSION; ?>
     
	</td>

  </tr>

</table>


<script type="text/javascript" src="ext/jquery/ui/head_search_cust_controller.js"></script>

<script type="text/javascript" src="ext/jquery/ui/header_product_controller.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/idle.js"></script>
<?php if( ($check_admin['admin_groups_id'] == '8' || $check_admin['admin_groups_id'] == '9') && $readonly != false){ ?>
  <script type="text/javascript" src="js/readonly.js"></script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="head_live.css" />




<script type='javascript'>
$('#searchbox').focus(function() { 
  $('this').val(''); 
});
</script>


<script>

  body_sizer();
  $(window).resize(body_sizer);

function body_sizer() {
  var bodyheight = document.body.clientHeight,
  navHeight = bodyheight -80;
   $(".dropdown-menu").css({maxHeight: navHeight + 'px'});
};
 </script> 

<?php if( $check_admin['admin_groups_id'] == '6' || $check_admin['admin_groups_id'] == '1' ){ ?>
  <script>
    function submitstep3(){
        allow_update();
    };
  </script>
<?php } else{ ?>
  <script>
    function submitstep3(){
      $("#stepthree").submit(function(e){
            e.preventDefault();
        });
      if($('.step3-items option:selected').text().indexOf('**') != -1){
        let pass = prompt('Enter Manager Password');
        $.ajax({
            type : 'GET',
            url  : 'manager_override.php?code='+pass,
            success :  function(data) {
              if(data == 'false'){
                alert('Password incorrect, try again');
              }else{
                allow_update();
              }
            },
            error: function (error) {
              console.log(error);
            }
          });
      }else{
        allow_update();
      }
    };
  </script>
<?php } ?>

