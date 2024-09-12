<?php
/*
 $prog id: en_de_crypt.php

 Emmeth Funches 062906
 JTH Computer Systems
 http://www.jthcomputersys.com

 Protions Copyright (c) 2006 by JTH Computer Systems
 Released under the GNU General Public License

JTH Computer Systems Special thanks to:
  Alexander Valyalkin posted original script on www.php.net on 30-Jun-2004 01:41
    Below is MD5-based block cypher (MDC-like), which works in 128bit CFB mode.
    It is very useful to encrypt secret data before transfer it over the network.
    $iv_len -- initialization vector's length.

JTH Computer Systems notes and warnings:
  If you pass a value to the $iv_len please remember the value or store it somewhere,
  else you will not be able to decrypt the information.
  The same goes for the variable $password, its value should not change after first use.

*/

function get_rnd_iv($iv_len)
{
   $iv = '';

// Alexanders's original While statement
//   while ($iv_len-- > 0) {
//       $iv .= chr(mt_rand() & 0xff);
//   }

//  Enchaned version by JTH Computer Systems using the OsCommerce function tep_rand() for random number seeding.
//   while ($iv_len-- > 0) {
//       $max_rand = tep_rand();
//       $iv .= chr(mt_rand($iv_len, $max_rand) & 0xff );
//   }


//  Ascii Table elements only from 0 to 255 version by JTH Computer Systems.
   while ($iv_len-- > 0) {
       $max_rand = mt_rand(0, 255);
       $iv .= chr($max_rand & 0xff);
   }

   return $iv;
}

function md5_encrypt($plain_text, $password, $iv_len = 16)
{
   $plain_text .= "\x13";
   $n = strlen($plain_text);
   if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
   $i = 0;
   $enc_text = get_rnd_iv($iv_len);
   $iv = substr($password ^ $enc_text, 0, 512);
   while ($i < $n) {
       $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
       $enc_text .= $block;
       $iv = substr($block . $iv, 0, 512) ^ $password;
       $i += 16;
   }
   return base64_encode($enc_text);
}

function md5_decrypt($enc_text, $password, $iv_len = 16)
{
   $enc_text = base64_decode($enc_text);
   $n = strlen($enc_text);
   $i = $iv_len;
   $plain_text = '';
   $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
   while ($i < $n) {
       $block = substr($enc_text, $i, 16);
       $plain_text .= $block ^ pack('H*', md5($iv));
       $iv = substr($block . $iv, 0, 512) ^ $password;
       $i += 16;
   }
   return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}


/*
//  If Oscommerce MS2 or later -- this should already existing in the general.php.
//  If exist please remove from coding by remark out or deleting.
//
////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }
*/

?>
