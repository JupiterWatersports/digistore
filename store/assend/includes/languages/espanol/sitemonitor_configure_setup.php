<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2005/09/24 Jack_mcs
  
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
define('HEADING_SITEMONITOR_CONFIGURE_SETUP', 'Control de configuraci�n');
define('TEXT_SITEMONITOR_CONFGIURE_SETUP', 'Vea por favor las instrucciones para el monitor del sitio en la ayuda, befure usando esto. <br>Use esta secci�n para fijar las opciones para SiteMonitor. Seup la lista de la exclusi�n la manera usted la quiere, siguiendo las direcciones a la derecha de esa secci�n. Entonces 
chasque encendido el bot�n de la actualizaci�n en la parte inferior para ahorrar todos los ajustes al SiteMonitor configuran el archivo.');
define('TEXT_OPTION_ALWAYS_EMAIL', 'Siempre email');
define('TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN', 'Si se comprueba esta opci�n, un email ser� enviado siempre que
la escritura de SiteMonitor es funcion�. Si no, se env�a solamente cuando se encuentran las excepciones.');
define('TEXT_OPTION_VERBOSE', 'Prolijo');
define('TEXT_OPTION_VERBOSE_EXPLAIN', 'Si se comprueba esta opci�n, un mensaje se imprime para cada excepci�n cuando funcion� manualmente.');
define('TEXT_OPTION_LOGFILE', 'Fichero de diario');
define('TEXT_OPTION_LOGFILE_EXPLAIN', 'Si se comprueba esta opci�n, el fichero de diario de SiteMonitor.txt ser� actualizado indicando los cambios, eventualmente, que fueron encontrados.');
define('TEXT_OPTION_LOGFILE_SIZE', 'Tama�o del fichero de diario');
define('TEXT_OPTION_LOGFILE_SIZE_EXPLAIN', 'Entre en el tama�o m�ximo del archivo. Una vez que se alcanza el tama�o, se retitula el archivo y un nuevo fichero de diario ser� creado.');
define('TEXT_OPTION_QUARANTINE', 'Archivos de la cuarentena');
define('TEXT_OPTION_QUARANTINE_EXPLAIN', 'Cualquier nuevo archivo encontrado ser� movido al directorio de la cuarentena en el admin.
Si se utiliza esta opci�n, despu�s un nuevo archivo de referencia necesitar� ser reconstruido cada vez que usted realiza un cambio a cualquier archivo que usted sea supervisi�n o �l ser� movido tambi�n.');
define('TEXT_OPTION_TO_EMAIL', 'A:');
define('TEXT_OPTION_TO_ADDRESS_EXPLAIN', 'Email address que el email de SiteMonitor ser� enviado a.');
define('TEXT_OPTION_FROM_EMAIL', 'De:');
define('TEXT_OPTION_FROM_ADDRESS_EXPLAIN', 'Email address que el email de SiteMonitor est� enviado de (�til para las tiendas m�ltiples).');
define('TEXT_OPTION_REFERENCE_RESET', 'Archivo de referencia de la cancelaci�n:');
define('TEXT_OPTION_REFERENCE_RESET_EXPLAN', 'SiteMonitor suprimir� su archivo de referencia despu�s del n�mero de d�as incorporados ha pasado.
Deje el espacio en blanco para no hacerlo suprimir.');
define('TEXT_OPTION_START_DIR', 'Comience el directorio:');
define('TEXT_OPTION_START_DIR_EXPLAIN', 'Generalmente la ra�z de la tienda. Usando una diversa localizaci�n no puede dar lugar a los mejores resultados.');
define('TEXT_OPTION_ADMIN_DIR', 'Directorio del Admin:');
define('TEXT_OPTION_ADMIN_DIR_EXPLAIN', '�sta es la direcci�n completa de la tela a su admin. Est� solamente 
necesitado para el uso del enrollamiento. Si usted ponet quiere utilizar el enrollamiento o no est� instalado en su servidor, este ajuste puede ser no hecho caso.');
define('TEXT_OPTION_ADMIN_USERNAME', 'Username del Admin:');
define('TEXT_OPTION_ADMIN_USERNAME_EXPLAIN', '�ste es el username para su secci�n del admin. Debe ser completado solamente si se va el enrollamiento a ser utilizado.');
define('TEXT_OPTION_ADMIN_PASSWORD', 'Contrase�a del Admin:');
define('TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN', '�sta es la contrase�a para su secci�n del admin. Debe ser completada solamente si se va el enrollamiento a ser utilizado.');
define('TEXT_OPTION_EXCLUDE_SELECTOR', 'Excluya el selector:');
define('TEXT_OPTION_EXCLUDE_LIST', 'Excluya la lista:');
define('TEXT_OPTION_EXCLUDE_LIST_EXPLAIN', 'Incorpore cualquier directorio que no se deba supervisar aqu�.
Eso puede ser hecha seleccionando una entrada de la lista dropdown sobre o entr�ndolo en directamente en la caja a la izquierda. Si se entran directamente, o se modifican, est� seguro de rodear cada entrada con cotizaciones y de separarlas con comas. La selecci�n del mismo art�culo de la lista la quitar� dos veces.');
define('ERROR_ALREADY_EXISTS', 'No agregado puesto que esta localizaci�n existe ya en la lista.');
define('ERROR_CHILD_EXISTS', 'No agregado puesto que los ni�os de esta localizaci�n existen ya en la lista.');   
define('ERROR_PARENT_EXISTS', 'No agregado puesto que el padre de esta localizaci�n existe ya en la lista.'); 
?>
