<?php
/*
  $Id: sitemonitor_admin.php,v 1.2 2005/09/24 Jack_mcs
  
  Digistore v4.0,  Open Source E-Commerce Solutions
  http://www.digistore.co.nz

  Copyright (c) 2003 osCommerce, http://www.oscommerce.com

  Released under the GNU General Public License
*/
define('HEADING_SITEMONITOR_CONFIGURE_SETUP', 'Control de configuración');
define('TEXT_SITEMONITOR_CONFGIURE_SETUP', 'Vea por favor las instrucciones para el monitor del sitio en la ayuda, befure usando esto. <br>Use esta sección para fijar las opciones para SiteMonitor. Seup la lista de la exclusión la manera usted la quiere, siguiendo las direcciones a la derecha de esa sección. Entonces 
chasque encendido el botón de la actualización en la parte inferior para ahorrar todos los ajustes al SiteMonitor configuran el archivo.');
define('TEXT_OPTION_ALWAYS_EMAIL', 'Siempre email');
define('TEXT_OPTION_ALWAYS_EMAIL_EXPLAIN', 'Si se comprueba esta opción, un email será enviado siempre que
la escritura de SiteMonitor es funcionó. Si no, se envía solamente cuando se encuentran las excepciones.');
define('TEXT_OPTION_VERBOSE', 'Prolijo');
define('TEXT_OPTION_VERBOSE_EXPLAIN', 'Si se comprueba esta opción, un mensaje se imprime para cada excepción cuando funcionó manualmente.');
define('TEXT_OPTION_LOGFILE', 'Fichero de diario');
define('TEXT_OPTION_LOGFILE_EXPLAIN', 'Si se comprueba esta opción, el fichero de diario de SiteMonitor.txt será actualizado indicando los cambios, eventualmente, que fueron encontrados.');
define('TEXT_OPTION_LOGFILE_SIZE', 'Tamaño del fichero de diario');
define('TEXT_OPTION_LOGFILE_SIZE_EXPLAIN', 'Entre en el tamaño máximo del archivo. Una vez que se alcanza el tamaño, se retitula el archivo y un nuevo fichero de diario será creado.');
define('TEXT_OPTION_QUARANTINE', 'Archivos de la cuarentena');
define('TEXT_OPTION_QUARANTINE_EXPLAIN', 'Cualquier nuevo archivo encontrado será movido al directorio de la cuarentena en el admin.
Si se utiliza esta opción, después un nuevo archivo de referencia necesitará ser reconstruido cada vez que usted realiza un cambio a cualquier archivo que usted sea supervisión o él será movido también.');
define('TEXT_OPTION_TO_EMAIL', 'A:');
define('TEXT_OPTION_TO_ADDRESS_EXPLAIN', 'Email address que el email de SiteMonitor será enviado a.');
define('TEXT_OPTION_FROM_EMAIL', 'De:');
define('TEXT_OPTION_FROM_ADDRESS_EXPLAIN', 'Email address que el email de SiteMonitor está enviado de (útil para las tiendas múltiples).');
define('TEXT_OPTION_REFERENCE_RESET', 'Archivo de referencia de la cancelación:');
define('TEXT_OPTION_REFERENCE_RESET_EXPLAN', 'SiteMonitor suprimirá su archivo de referencia después del número de días incorporados ha pasado.
Deje el espacio en blanco para no hacerlo suprimir.');
define('TEXT_OPTION_START_DIR', 'Comience el directorio:');
define('TEXT_OPTION_START_DIR_EXPLAIN', 'Generalmente la raíz de la tienda. Usando una diversa localización no puede dar lugar a los mejores resultados.');
define('TEXT_OPTION_ADMIN_DIR', 'Directorio del Admin:');
define('TEXT_OPTION_ADMIN_DIR_EXPLAIN', 'Ésta es la dirección completa de la tela a su admin. Está solamente 
necesitado para el uso del enrollamiento. Si usted ponet quiere utilizar el enrollamiento o no está instalado en su servidor, este ajuste puede ser no hecho caso.');
define('TEXT_OPTION_ADMIN_USERNAME', 'Username del Admin:');
define('TEXT_OPTION_ADMIN_USERNAME_EXPLAIN', 'Éste es el username para su sección del admin. Debe ser completado solamente si se va el enrollamiento a ser utilizado.');
define('TEXT_OPTION_ADMIN_PASSWORD', 'Contraseña del Admin:');
define('TEXT_OPTION_ADMIN_PASSWORD_EXPLAIN', 'Ésta es la contraseña para su sección del admin. Debe ser completada solamente si se va el enrollamiento a ser utilizado.');
define('TEXT_OPTION_EXCLUDE_SELECTOR', 'Excluya el selector:');
define('TEXT_OPTION_EXCLUDE_LIST', 'Excluya la lista:');
define('TEXT_OPTION_EXCLUDE_LIST_EXPLAIN', 'Incorpore cualquier directorio que no se deba supervisar aquí.
Eso puede ser hecha seleccionando una entrada de la lista dropdown sobre o entrándolo en directamente en la caja a la izquierda. Si se entran directamente, o se modifican, esté seguro de rodear cada entrada con cotizaciones y de separarlas con comas. La selección del mismo artículo de la lista la quitará dos veces.');
define('ERROR_ALREADY_EXISTS', 'No agregado puesto que esta localización existe ya en la lista.');
define('ERROR_CHILD_EXISTS', 'No agregado puesto que los niños de esta localización existen ya en la lista.');   
define('ERROR_PARENT_EXISTS', 'No agregado puesto que el padre de esta localización existe ya en la lista.'); 
?>
