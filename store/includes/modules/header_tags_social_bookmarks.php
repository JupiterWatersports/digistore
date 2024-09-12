<?php
$TITLE = '';
$NAME = '';
$URL = '';
$IMG = '';
 
switch (true) {
    case (basename($_SERVER['PHP_SELF']) === FILENAME_PRODUCT_INFO):
        $NAME = htmlspecialchars($product_info['products_name'], ENT_QUOTES);
        $TITLE = urlencode($product_info['products_name']);
        $URL = urlencode(StripSID(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id'], 'NONSSL', false )));
        $IMG = (tep_not_null($product_info['products_image']) ? "&amp;media=" . HTTP_SERVER . DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES . $product_info['products_image'] : '');
    break;

    case (! tep_not_null($TITLE) && isset($_GET['cPath'])):
        $parts = explode("_", $_GET['cPath']);
        $category_id = $parts[count($parts) - 1];
        $category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c left join " .  TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where c.categories_id = '" . (int)$category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
        $category = tep_db_fetch_array($category_query);
        $NAME = htmlspecialchars($category['categories_name'], ENT_QUOTES);
        $TITLE = urlencode($category['categories_name']);
        $URL = urlencode(StripSID(tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_id , 'NONSSL', false )));
        $IMG = (tep_not_null($category['categories_image']) ? "&amp;media=" . HTTP_SERVER . DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES . $category['categories_image'] : '');
    break;

    case (defined('FILENAME_ARTICLE_INFO') && basename($_SERVER['PHP_SELF']) === FILENAME_ARTICLE_INFO):
        $NAME = htmlspecialchars($article_info['articles_name'], ENT_QUOTES);
        $TITLE = urlencode($article_info['articles_name']);
        $URL = urlencode(StripSID(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $article_info['articles_id'], 'NONSSL', false )));
    break;  

    case (defined('FILENAME_INFORMATION') && basename($_SERVER['PHP_SELF']) === FILENAME_INFORMATION):
        $NAME = htmlspecialchars($title, ENT_QUOTES);
        $TITLE = urlencode($title);
        $URL = urlencode(StripSID(tep_href_link(FILENAME_INFORMATION, 'info_id=' . (int)$_GET['info_id'], 'NONSSL', false )));
    break;     

    case (defined('FILENAME_PAGES') && basename($_SERVER['PHP_SELF']) === FILENAME_PAGES):
        $NAME = htmlspecialchars($header_tags_array['title'], ENT_QUOTES);
        $TITLE = urlencode($header_tags_array['title']);
        $URL = urlencode(StripSID(tep_href_link(FILENAME_PAGES, 'pages=' . tep_db_prepare_input($_GET['page']), 'NONSSL', false )));
    break;       

    default: 
        $URL = urlencode(StripSID(tep_href_link(basename($_SERVER['PHP_SELF']))));
}          
?>
<script type="text/javascript" src="javacript/plusone.js"></script>

<tr>
 <td align="right"><table border="0" width="20%">
  <tr>

  <td><div class="g-plusone;"></div></td>
  
  <td><a rel="nofollow" target="_blank" href="http://del.icio.us/post?url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/delicious.png', 'Add ' . $NAME . ' to del.icio.us'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://digg.com/submit?phase=2&amp;url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/digg.png', 'Add ' . $NAME . ' to Digg'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://ekstreme.com/socializer/?url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/Socializer16.png', 'Add ' . $NAME . ' to Ekstreme'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://www.facebook.com/share.php?u=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/facebook.png', 'Add ' . $NAME . ' to Facebook'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://furl.net/storeIt.jsp?t=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/furl.png', 'Add ' . $NAME . ' to Furl'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/google.png', 'Add ' . $NAME . ' to Google'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $URL . '&amp;description=' . $TITLE; ?>  <?php echo $IMG; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/pinterest.png', 'Add ' . $NAME . ' to PInterest'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://www.newsvine.com/_tools/seed&amp;save?u==<?php echo $URL . '&amp;h=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/newsvine.png', 'Add ' . $NAME . ' to Newsvine'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://reddit.com/submit?url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/reddit.png', 'Add ' . $NAME . ' to Reddit'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://technorati.com/cosmos/search.html?url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/technorati.png', 'Add ' . $NAME . ' to Technorati'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://twitter.com/home?status=Check out <?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/twitter.png', 'Add ' . $NAME . ' to Twitter'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://myweb.yahoo.com/myresults/bookmarklet?u=<?php echo $URL . '&amp;t=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/yahoo.png', 'Add ' . $NAME . ' to Yahoo myWeb'); ?></a></td>

  <td><a rel="nofollow" target="_blank" href="http://www.stumbleupon.com/submit?url=<?php echo $URL . '&amp;title=' . $TITLE; ?>">
  <?php echo tep_image(DIR_WS_IMAGES . 'socialbookmark/stumble1.gif', 'Add ' . $NAME . ' to Stumbleupon'); ?></a></td>
  </tr>
 </table></td>
</tr>