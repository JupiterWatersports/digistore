<?php
/*
  $Id: advertisement.php,v 1.00 2005/11/01 01:45:58 hpdl Exp $   
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

define('ADMIN_TITLE' , 'Nuevo anuncio del homepage');
define('ADFILE_SIZE','20000');
define('ADFILE_WIDTH','720');
define('ADFILE_HEIGHT','140');
define('AD_FILETYPES' , '<BR><FONT COLOR="FF0000">NOTE:</FONT> La imagen debe estar en formato del JPEG/del GIF/del png y debajo ' . ADFILE_SIZE . ' kbs de tama�o<P>');
define('AD_FILELOCATION' , 'Seleccione el archivo:');
define('ADERROR_ONE' , 'El tama�o del archivo est� a grande, carga por teletratamiento debe ser menos que ' . ADFILE_SIZE . ' KBS <BR><BR>Tama�o del archivo cargado: ');
define('ADERROR_TWO' , 'La carga por teletratamiento debe ser un archivo de imagen v�lido, JPEG / GIF / PNG<BR><BR>Tipo cargado:');
define('ADERROR_FOUR' , 'La imagen debe ser menos que ' . ADFILE_WIDTH . ' PX wide.<BR><BR>La anchura carg�:');
define('ADERROR_FIVE' , 'La imagen debe ser menos que ' . ADFILE_HEIGHT . ' PX high.<BR><BR>La altura carg�:');
define('ADSUCCESS_UPLOAD' , 'Se ha cargado la imagen<BR><BR>Nombre de fichero:');
define('ADSUCCESS_DELETE' , 'Se ha quitado la imagen<BR><BR>Nombre de fichero:');

define('NEW_AD_TITLE' , 'Agregue el nuevo anuncio del homepage:');
define('ADSUCCESS_DELETE' , 'Se ha quitado la imagen<BR><BR>Nombre de fichero:');
define('NEW_AD_CONTENT' , 'Entre un producto o el acoplamiento en esta caja, �ste de la categor�a se utiliza para chasque aqu� el bot�n que se exhibe en el anuncio.<br>
 eg. (index.php/cPath/21) - �ste demostrar� todos los productos adentro la categor�a 21 cuando est� chascado.');
define('NEW_AD_TEXT' , 'Texto');
define('NEW_AD_TEXT_CONTENT' , '�ste es el texto exhibido en el ontop del anuncio de la imagen.<br>Esto puede ser dejada en blanco si el texto no se requiere.');
define('NEW_AD_SELECT' , 'Seleccione el archivo de imagen');
define('NEW_AD_SHOW' , 'Compruebe para demostrar el anuncio');
?>