    <table width="90px">
     <tr>
      <td><?php
       $info_box_contents = array();
       $info_box_contents[] = array(
           'text' => 'shoping with us'
       );
       
       new rightColumnHeading($info_box_contents, false, false);
       
       $info_box_contents = array();
       $info_box_contents[] = array(
           'text' => 'shoping with us we are a great shop for customer service'
       );
       
       new rightColumnBox($info_box_contents);
      ?></td>
     </tr>

     <tr>
      <td><?php
       $info_box_contents = array();
       $info_box_contents[] = array(
           'text' => 'checking out'
       );
       
       new rightColumnHeading($info_box_contents, false, false);
       
       $info_box_contents = array();
       $info_box_contents[] = array(
           'text' => 'returns policy'
       );
       
       new rightColumnBox($info_box_contents);
      ?></td>
     </tr></table>