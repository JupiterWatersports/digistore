<?php
/*
  $Id: form_check.js.php 1739 2007-12-20 00:52:16Z hpdl $

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
?>
<script type="text/javascript"><!--
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function check_input(field_name, field_size, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
    
    var Big_error = false;
    if (field_name === 'email_address'){

    var valueee = $('#forms :input[name="email_address"]').val();

       // var patt = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
    //ehhh    var email_check = new RegExp(/^@[A-Z0-9.-]+\.ru$/i);
        var email_check2 = new RegExp(/^\w+@[a-zA-Z_]+?\.ru$/g);

        var email_check_xyz = new RegExp(/^\w+@[a-zA-Z_]+?\.xyz$/g);
        var email_check_top = new RegExp(/^\w+@[a-zA-Z_]+?\.top$/g);

    /*    if (patt.test(valueee)){
            alert('success 0');
        }

        if (email_check.test(valueee)){
            alert('success 1');
        } */

        if (email_check2.test(valueee)) {
            Big_error = true;
        }
        else if (email_check_xyz.test(valueee)) {

            Big_error = true;
        }
        else if (email_check_top.test(valueee)) {     
            Big_error = true;
        }

        else if (valueee.indexOf('@yeah.net')>= 0) {

            Big_error = true;
        }

        else if (valueee.indexOf('@163.com')>= 0) {

            Big_error = true;
        }

         else {

           Big_error = false; 
        }

        if (Big_error == true){
        $('form[name="create_account"]').trigger("reset");
        alert('You seem to have forgotten to fill out both fields for your address.');   
        }
    }  
  }
}

function check_radio(field_name, message) {
  var isChecked = false;

  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var radio = form.elements[field_name];

    for (var i=0; i<radio.length; i++) {
      if (radio[i].checked == true) {
        isChecked = true;
        break;
      }
    }

    if (isChecked == false) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == field_default) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password = form.elements[field_name_1].value;
    var confirmation = form.elements[field_name_2].value;

    if (password.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password != confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    }
  }
}

function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password_current = form.elements[field_name_1].value;
    var password_new = form.elements[field_name_2].value;
    var password_confirmation = form.elements[field_name_3].value;

    if (password_current.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      //error = true;
    } else if (password_new.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    } else if (password_new != password_confirmation) {
      error_message = error_message + "* " + message_3 + "\n";
      error = true;
    }
  }
}

function check_form(form_name) {
  if (submitted == true) {
 
    return false;
  }

  error = false;
  form = form_name;
  error_message = "";


  check_input("firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_FIRST_NAME_ERROR; ?>");
  check_input("lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo ENTRY_LAST_NAME_ERROR; ?>");

<?php if (ACCOUNT_DOB == 'true') echo '  check_input("dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . ENTRY_DATE_OF_BIRTH_ERROR . '");' . "\n"; ?>

    check_input("email_address", "6", "Please provide a valid email");   
    check_input("street_address", "4", "Please provide a valid street address");
    check_input("city", "4", "Please provide a valid city");    
    check_input("postcode", "4", "Please provide a valid postal code");
    check_input("telephone", "7", "Please provide a valid phone number");

var v = grecaptcha.getResponse();
    if(v.length == 0)
    {
        document.getElementById('captcha').innerHTML="You must prove you are Human";
        return false;
    }
    
<?php
// PWA BOF
  if (!isset($HTTP_GET_VARS['guest'])) {
?>

  check_password("password", "confirmation", "8", "Your Password must contain a minimum of 8 characters.", "Your passwords must match.");
  check_password_new("password_current", "password_new", "password_confirmation", "8", "Your new Password must contain a minimum of 8 characters.", "Your new Password must contain a minimum of 8 characters.", "The Password Confirmation must match your new Password.");

<?php
  } // PWA EOF
?>

  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}

    var req; 
function loadXMLDoc(key) {
   var url="state_dropdown.php?country="+key;
   getObject("states").innerHTML = '&nbsp;<img style="vertical-align:middle" src="images/loading.gif">Please wait...';
   try { req = new ActiveXObject("Msxml2.XMLHTTP"); } 
   catch(e) { 
      try { req = new ActiveXObject("Microsoft.XMLHTTP"); } 
      catch(oc) { req = null; } 
   } 
   if (!req && typeof XMLHttpRequest != "undefined") { req = new XMLHttpRequest(); } 
   if (req != null) {
      req.onreadystatechange = processChange; 
      req.open("GET", url, true); 
      req.send(null); 
   } 
} 
function processChange() { 
   if (req.readyState == 4 && req.status == 200) { 
      getObject("states").innerHTML = req.responseText;
      document.account.state.focus();
   } 
}


var changecs=false;
function changeCS(){
    if(changecs){
        jQuery("#country").attr("disabled", false); 
        jQuery("select[name='zone_id']").attr("disabled", false); 
        changecs=false;
    }else{
        jQuery('#country option[value="223"]').prop('selected', true);
        jQuery("#country").attr("disabled", true); 
        loadXMLDoc('223');
        setTimeout(function(){
            jQuery('select[name="zone_id"] option[value="Florida (Palm Beach County)"]').prop('selected', true); 
            jQuery("select[name='zone_id']").attr("disabled", true);
        }, 500);

        changecs=true;
    }	
}

//--></script>

<style>.gender, #gender{display:none;}</style>