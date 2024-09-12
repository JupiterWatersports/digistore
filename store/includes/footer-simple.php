</div>
<footer>
<div class="wrapper-simple">
<ul class="legal">
<li class="legal-links">&copy; <?php echo date("Y"); ?> Jupiterkiteboarding. All Rights Reserved.</li>
<li class="legal-links"><a href="<?php echo tep_href_link('contact-us.php'); ?>">Contact Us</a></li>
<li class="legal-links"><a href="<?php echo tep_href_link('privacy-policy'); ?>">Privacy Policy</a></li>
<li class="legal-links"><a href="<?php echo tep_href_link('conditions-of-use-i-2.html'); ?>">Conditions of Use</a></li>
</ul>
</div>
</footer>

<!-- Hotjar Tracking Code for jupiterkiteboarding.com -->


<?php
/*** Begin Header Tags SEO ***/
if ($request_type == NONSSL) { 
  if (HEADER_TAGS_DISPLAY_TAG_CLOUD == 'true') {
      echo '<tr><td align="center"><div style="text-align:center">';
      include(DIR_WS_INCLUDES . 'headertags_seo_tagcloud_footer.php');
      echo '</div></td></tr>';
  }
}
/*** End Header Tags SEO ***/
?>
