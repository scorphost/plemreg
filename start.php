<?php
foreach ( $PathBoot as $path )
	foreach ( glob($path) as $file ) require_once($file);
#
scr(new scr(cr));
$APP_VARS = array();
# Loading processors
foreach ( $CfgProc as $proc => $Data )
{
	foreach ( $Data as $name => $file ) {
		if ( 'broker' == $name ) {
			$drv_file = fpath(path_core, 'broker', $file);
			$drv_class = require_once($drv_file);
			$theProc = new $drv_class(Env::cfg($proc));
			$proc::obj($theProc->obj());
		}
	}
}
#
require_once(fpath(path_app, 'app.class.php'));
