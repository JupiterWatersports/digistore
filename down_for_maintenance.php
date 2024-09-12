<?php
/*
  Created by: Linda McGrath osCOMMERCE@WebMakers.com
  
  Update by: Disp507 10-02-2007

  down_for_maintenance.php v1.2

  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/

  file_put_contents("/home/live/log/mysql-error.log", "\n\nTEST\n\n", FILE_APPEND);
  
  ?>
  
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <body>
    <div style="text-align: center; margin: 50px auto; font-size: 18px;">Sorry, we are currently down for maintenance. We will be back up as soon as possible.</div>
  </body>
</html>