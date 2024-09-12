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
define('ADMIN_TITLE' , 'Anuncio del homepage');
define('ADFILE_SIZE','200000');
define('ADFILE_WIDTH','1000');
define('ADFILE_HEIGHT','1000');
define('AD_TITLE_BAR','Anuncios del homepage');
define('AD_FILETYPES' , '<BR><FONT COLOR="FF0000">NOTE:</FONT> La imagen debe estar adentro JPEG / GIF / PNG formato y debajo ' . ADFILE_SIZE . ' kbs de tamaño<P>');
define('AD_FILELOCATION' , 'Seleccione el archivo:');
define('ADERROR_ONE' , 'El tamaño del archivo está a grande, carga por teletratamiento debe ser menos que ' . ADFILE_SIZE . ' KBS <BR><BR>Tamaño del archivo cargado: ');
define('ADERROR_TWO' , 'La carga por teletratamiento debe ser un archivo de imagen válido, JPEG / GIF / PNG<BR><BR>Tipo cargado:');
define('ADERROR_FOUR' , 'La imagen debe ser menos que ' . ADFILE_WIDTH . ' PX de par en par. <BR><BR>Anchura cargada:');
define('ADERROR_FIVE' , 'La imagen debe ser menos que ' . ADFILE_HEIGHT . 'PX alto. <BR><BR>Altura cargada:');
define('ADSUCCESS_UPLOAD' , 'Se ha cargado la imagen<BR><BR>Nombre de fichero:');
define('ADSUCCESS_DELETE' , 'Se ha quitado la imagen<BR><BR>Nombre de fichero:');
define('ADINSTRUCTIONS' , 'Para inhabilitar un del tecleo del anuncio; "hide" esto temporal prevendrá el anuncio que es demostrado dentro del almacén. Para reactivar un ocultado del tecleo del anuncio; "show":');
define('AD_MORE_THAN_ONE' , 'Cuando se activa más de un anuncio serán exhibidos sobre una base al azar que la página se carga cada vez.');
define('AD_CONTENT' , 'Contenido del anuncio');
define('AD_UPDATE' , 'Actualización');
define('AD_DELETE' , 'Cancelación');
define('AD_NO_ADS' , 'Ningunos anuncios actualmente disponibles.');
?>

