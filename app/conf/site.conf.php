<?php
/* Глобальные настройки сайта */
define('salt', 'abgregistry');
define('site_closed', false);
define('site_locked', false);
define('css_version', 4);

define('cr', PHP_SAPI == 'cli' ? PHP_EOL : br);

$CfgProc = Array
(
	'DB' => Array
	(
		'broker' => 'mysqli.drv.php',
	),
);

$CfgDB = Array
(
	'user' => 'plemreg',
	'pass' => 'zoot6Xie5o',
	'base' => 'plemreg',
);

/* TEMPLATE SELECTOR
$CfgTMPL = Array
(
	'actual' => array('*windows*', '*linux*'),
	'mobi' => array('*android*'),
);
*/
