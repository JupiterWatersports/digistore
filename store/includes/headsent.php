<?php

error_reporting(0);

function lineout( $text ) {
  for ( $m = 0 ; $m < strlen( $text ); $m++ ) {
    $out = substr( $text, $m , 1 );
    if ( ( ord( $out ) > 32 ) && ( ord( $out ) < 127 ) ) 
      echo htmlspecialchars( $out );
    else 
      echo '[' . sprintf('%02X', ord( $out ) ) . ']';
  }
  echo '<br />' . "\n";
}

if ( $_POST["infile"] ) {

  $infile = ltrim( rtrim( $_POST["infile"] ) );
  $atline = ltrim( rtrim( $_POST["atline"] ) );

  $lines = file( $infile );
  if ( ! $lines ) {
    echo '<div style="margin-left:150px">';
    echo 'File open error!<br /><br />' . "\n";
  } else {

    if ( ( strlen( $atline ) ) && is_numeric( $atline ) )
      $atline = intval( $atline );
    else
      $atline = -1;

    if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' )
      $eol = 2;
    else
      $eol = 1;

    $err_msg = '';

    $i = count( $lines );
    $j = 0;
    while ( true ) {
      if ( strpos( strtolower( $lines[ $j++ ] ), '<?php' ) !== FALSE )
        break;
      if ( $j >= $i ) exit;
    }
    $start = --$j;

    if ( $start != 0 ) {
      $err_msg .= 'The opening PHP tag is not on the first line in the file.<br />';
    } else {
      if ( strpos( strtolower( $lines[ $start ] ), '<?php' ) != 0 ) {
        $err_msg .= 'The opening PHP tag is not at the start of the first line in the file.<br />';
      }
    }

    $j = $i - 1;
    while ( true ) {
      if ( strpos( $lines[ $j-- ], '?>' ) !== FALSE )
        break;
      if ( $j < 0 ) exit;
    }
    $end = ++$j;

    if ( ($end+1) != $i ) {
      $err_msg .= 'The closing PHP tag is not on the last line in the file.<br />';
    } else {
      if ( ( strpos( $lines[ $end ], '?>' ) + (2+$eol) ) < strlen( $lines[ $end ] ) ) {
        $err_msg .= 'The closing PHP tag is not at the end of the last line in the file.<br />';
      }
    }
    
    echo '<div style="margin-left:150px">';


    echo 'O/S = ' . PHP_OS . '.<br />' . "\n";
    echo $infile. ' has ' . $i . ' lines.<br />' . "\n";
    echo 'The PHP start tag is on line ' . ($start+1) . '.<br />' . "\n";
    echo 'The PHP end tag is on line ' . ($end+1) . '.<br />' . "\n";
    echo 'Contents from start of file to the PHP start tag:<br /><br />' . "\n";
    for ( $k = 0 ; $k <= $start; $k++ ) {
      lineout( $lines[$k] );
    }
    echo '<br />-------------------------<br />Contents from PHP end tag to end of file:<br /><br />' . "\n";
    for ( $k = $end ; $k <= ($i-1); $k++ ) {
      lineout( $lines[$k] );
    }

    if ( strlen( $err_msg ) )
      echo '<br /><br />Error Messages:<br /><br />' . $err_msg . '<br />';

    if ( $atline != -1 ) {
      if ( ( ( $atline-1 ) >= 0 ) && ( ( $atline-1 ) < $i  ) ) {
        echo '<br />-------------------------<br />Extended contents of the requested line [' . $atline . '] are:<br /><br />' . "\n";
        lineout( $lines[($atline-1)] );
        echo '<br />-------------------------<br />Raw contents of the requested line [' . $atline . '] are:<br /><br />' . "\n";
        echo $lines[($atline-1)] . "\n" . '<br /><br />';
      } else
        echo '<br />-------------------------<br />The requested line [' . $atline . '] is out of range.<br /><br />' . "\n";
    }
  }
?>
<br />
<a href="<?php echo basename( $_SERVER['SCRIPT_FILENAME'] );?>">Continue</a><br />
</div>
<?php
} else {
?>
<html>
<head>
</head>

<body>

<div style="margin-left:150px">
  <form name="input" action="<?php echo basename( $_SERVER['SCRIPT_FILENAME'] );?>" method="post">
  File: <input type="text" size="50" name="infile">&nbsp;&nbsp;&nbsp;&nbsp;
  Optional Line #: <input type="text" size="5" name="atline">&nbsp;&nbsp;
  <input type="submit" value="Do it"><br /><br />
  </form>


</div>

<script language="JavaScript">
<!--
function BodyOnLoad()
{
  document.input.infile.focus();
}
BodyOnLoad();
//-->
</script>

</body>
</html> 

<?php
}
/* <eof> */
?>
