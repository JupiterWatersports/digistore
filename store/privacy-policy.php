<?php
  require('includes/application_top.php');

  $pages_name = $HTTP_GET_VARS["page"];
  $page_query = tep_db_query("select pd.pages_title, pd.pages_body, p.pages_id, p.pages_name, p.pages_image, p.pages_status, p.sort_order from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_name = '" . $pages_name . "' and p.pages_id = pd.pages_id and pd.language_id = '" . (int)$languages_id . "'");
  $page = tep_db_fetch_array($page_query);
  define('NAVBAR_TITLE', $page['pages_title']);
  define('HEADING_TITLE', $page['pages_title']);
  define('TEXT_INFORMATION', ($page['pages_body']));
  define('PAGES_IMAGE', $page["pages_image"]);  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('pages.php?page='.$pages_name, '', 'NONSSL'));
?>


<?php echo $doctype;?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Privacy Policy</title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
</head>
<?php echo $stylesheet; ?>
	<style>#container-has-sidelinks p{font-size:1rem !important; line-height:1.4; }
	</style>
 
<?php require(DIR_WS_INCLUDES . 'template-top-info-pages.php'); ?>
	<h1>Privacy Policy</h1>


<p>Last updated: July 25, 2017</p>


<p>This page informs you of our policies regarding the collection, use and disclosure of Personal Information when you use our Service.</p>

<p>We will not use or share your information with anyone except as described in this Privacy Policy.</p>

<p>We use your Personal Information for providing and improving the Service. By using the Service, you agree to the collection and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, accessible at www.jupiterkiteboarding.com</p>



<h3>What personal information we collect</h3>

<p>When you create an account, or contact us for support we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. Personally identifiable information may include, but is not limited to, your name, email address, phone number, postal address, and credit card information.</p>

<p>We collect this information for the purpose of identifying and communicating with you, responding to your requests/inquiries, servicing your purchase orders, and improving our services. We may disclose specific information upon governmental request, in response to a court order, when required by law or to protect our or others' rights.</p>

<h3>How do I review or update my personal information? </h3>

<p>To help make sure that the contact information we have about you is correct, complete, and up to date you may access it by logging into your <a style="color:#0322ff;" href="https://www.jupiterkiteboarding.com/store/account_history">account</a>.</p>

<p>We may use your Personal Information to contact you with newsletters, marketing or promotional materials and other information that may be of interest to you. You may opt out of receiving any, or all, of these communications from us by following the logging into your account and visiting the <a style="color:#0322ff;" href="https://www.jupiterkiteboarding.com/store/account_newsletters.php"> Subscribe/ Unsubscribe from Newsletters</a> page.  You may also subscribe via the link at the bottom of any newsletter we send. If you wish to completely remove all your information from our site you may email your request to <a style="color:#0322ff;" href="mailto:customersupport@jupiterkiteboarding.com">customersupport@jupiterkiteboarding.com</a>. We may decline to process requests that are frivolous/vexatious, jeopardize the privacy of others, are extremely impractical, or for which access is not otherwise required by local law. </p>


<h3>Log Data</h3>

<p>We may also collect information that your browser sends whenever you visit our Service ("Log Data"). This Log Data may include information such as your computer's Internet Protocol ("IP") address, browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages and other statistics.</p>


<p>In addition, we may use third party services such as Google Analytics that collect, monitor and analyze this type of information in order to increase our Service's functionality. These third party service providers have their own privacy policies addressing how they use such information.</p>



<h3>Cookies</h3>

<p>Cookies are files with a small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a web site and transferred to your device. We use cookies to collect information in order to improve our services for you.</p>

<p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. The Help feature on most browsers provide information on how to accept cookies, disable cookies or to notify you when receiving a new cookie.</p>

<p>If you do not accept cookies, you may not be able to use some features of our Service and we recommend that you leave them turned on.</p>


<h3>Do Not Track Disclosure</h3>

<p>We support Do Not Track ("DNT"). Do Not Track is a preference you can set in your web browser to inform websites that you do not want to be tracked.</p>

<p>You can enable or disable Do Not Track by visiting the Preferences or Settings page of your web browser.</p>



<h3>Service Providers</h3>

<p>We may employ third party companies and individuals to facilitate our Service, to provide the Service on our behalf, to perform Service-related services and/or to assist us in analyzing how our Service is used.</p>

<p>These third parties have access to your Personal Information only to perform specific tasks on our behalf and are obligated not to disclose or use your information for any other purpose.</p>


<h3>Security</h3>

<p>The security of your Personal Information is important to us, and we strive to implement and maintain reasonable, commercially acceptable security procedures and practices appropriate to the nature of the information we store, in order to protect it from unauthorized access, destruction, use, modification, or disclosure.</p>

<h3>International Transfer</h3>

<p>Your information, including Personal Information, may be transferred to — and maintained on — computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from your jurisdiction.</p>

<p>If you are located outside United States and choose to provide information to us, please note that we transfer the information, including Personal Information, to United States and process it there.</p>

<p>Your consent to this Privacy Policy followed by your submission of such information represents your agreement to that transfer.</p>


<h3>Links To Other Sites</h3>

<p>Our Service may contain links to other sites that are not operated by us (ie. Weather Channel, NOAA, Wind Finder, etc.). If you click on a third party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit.</p>

<p>We have no control over, and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>

<h3>Children's Privacy</h3>

<p>Only persons age 18 or older have permission to access our Service. Our Service does not address anyone under the age of 13 ("Children").</p>

<p>We do not knowingly collect personally identifiable information from children under 13. If you are a parent or guardian and you learn that your Children have provided us with Personal Information, please contact us. If we become aware that we have collected Personal Information from a children under age 13 without verification of parental consent please email us at <a href="mailto:customersupport@jupiterkiteboarding.com"> <span style="color:#0322ff;">customersupport@jupiterkiteboarding.com</span></a>  or call us <a href="tel:5614270240"> <span style="color:#0322ff;">561-427-0240</span></a></p>


<h3>Changes To This Privacy Policy</h3>

<p>This Privacy Policy is effective as of July 25, 2017 and will remain in effect except with respect to any changes in its provisions in the future, which will be in effect immediately after being posted on this page.</p>

<p>We reserve the right to update or change our Privacy Policy at any time and you should check this Privacy Policy periodically. Your continued use of the Service after we post any modifications to the Privacy Policy on this page will constitute your acknowledgment of the modifications and your consent to abide and be bound by the modified Privacy Policy.</p>

<p>If we make any material changes to this Privacy Policy, we will notify you either through the email address you have provided us, or by placing a prominent notice on our website.</p>


<h3>Contact Us</h3>

	<p>If you have any questions about this Privacy Policy, please <a style="color:#0322ff;" href="contact-us">contact us.</a></p>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'template-bottom.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>