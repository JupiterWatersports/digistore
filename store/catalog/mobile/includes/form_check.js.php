<?php
/*
  $Id: form_check.js.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<script>



$('.ui-page').bind('pageinit', function(event) {
			

	$('form').validate({
				
		rules: {
<?php 
if (ACCOUNT_GENDER == 'true') {
echo '					
					gender: {
						required: true
					},
';
}
echo '
					firstname: {
						required: true
					},
					lastname: {
						required: true
					},
';

echo '					
					street_address: {
						required: true
					},
					email_address: {
						required: true
					},
					postcode: {
						required: true
					},
					password: {
					required: "true",
					minlength: "5"
					},
					confirmation: {
					required: "true",
					minlength: "5",

					},
					telephone: {
						required: true
					},
					city: {
						required: true
					},
					country: {
						required: true
					},
					state: {
						required: true
					},
';

 if (ACCOUNT_DOB == 'true') {		

echo '
					dob: {
						required: true
					}
';
}
?>
				}

			});
		});
</script>
