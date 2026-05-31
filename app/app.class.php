<?php
class Subj {
	public $Dirs;
	public function __construct($INC) {
		if ( $INC) $this->as_incs(); else $this->as_dirs();
	}
	public function __call($KEY, $DIR) {
		if ( ke($KEY, $this->Dirs) ) {
			$path = $this->Dirs[$KEY];
			if ( we($DIR) ) $path = cufa('cpath', merge(a($path), $DIR));
		}
		return $path;
	}
	protected function as_dirs() {
		foreach ( $GLOBALS['PathPlaces'] as $alias => $dir )
			$this->Dirs[$alias] = cpath(site_root, $dir);
	}
	protected function as_incs() {
		foreach ( $GLOBALS['PathPlaces'] as $alias => $dir )
			$this->Dirs[$alias] = $dir;
	}
}
class App {
	public static $Subj;
	public static function init() {
		// hitch(page_tech);
		self::$Subj = array();
		#` Загрузка сущностей
		foreach ( glob(fpath(path_app, 'subj', '*.php')) as $file ) {
			$class_name = require_once($file);
			new $class_name(__CLASS__);
		}
		#` Загрузка каталога директорий
		$theSubj = new Subj(false);
		self::$Subj['dir'] = $theSubj;
		$theSubj = new Subj(true);
		self::$Subj['inc'] = $theSubj;
		#` Идентификация пользователя
		self::x('iUser')->identify();
	}
	public static function hello($IDN, $OBJ) {
		self::$Subj[$IDN] = $OBJ;
	}
	public static function x($IDN) {
		return self::$Subj[$IDN];
	}

	public static function y() {
		return of(self::$Subj['inc'], self::$Subj['dir']);
	}
}

App::init();
