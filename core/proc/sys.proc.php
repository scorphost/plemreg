<?php
class Sys
{
	public static $F2C = array();
	public static $C2O = array();
	public static function tmpl($PAGE, $SECT = str) {
		if ( some($SECT) ) {
			if ( slash == $SECT ) list ($dir, $SECT) = of('album', str);
				else $dir = 'bloc'; }
		$file_tmpl = cpath(path_tmpl, ua2tpl(), ini($dir, str)
			, post($PAGE, $SECT, dot). '.tmpl', yes);
		return lines(load($file_tmpl), '--', yes);
	}
	public static function item($PAGE, $NAME) {
		$file_tmpl = fpath(path_tmpl, ua2tpl(), 'item'
			, "${PAGE}.${NAME}.tmpl");
		return lines(load($file_tmpl), '--', yes);
	}
	## new instance(null), this(yes), clone(no) FACTORY
	## virtual class app
	public static function with($NAME, $TYPE, $MODE = _) {
		if ( the($NAME) ) {
			$obj = $NAME;
			$NAME = $obj->as_name();
		} else $obj = null;
		if ( ke(so($f2c, $NAME. dot. $TYPE), self::$F2C) ) {
			if ( set($MODE) ) { ## new/clone of existed instance
				$cl = self::$F2C[$f2c];
				return $MODE ? new $cl($obj) : clone $cl($obj);
			} else return self::$C2O[ self::$F2C[$f2c] ];
		} else $file = fpath(path_logic, "${f2c}.php");
		self::$F2C[$f2c] = $class = require_once($file);
		return self::$C2O[$class] = new $class($obj);
	}
	public static function soft($NAME) {
		$file = fpath(path_soft, $NAME, 'include.php');
		return done(include($file));
	}
}
