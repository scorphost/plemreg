<?php

$self_name = 'DataGoat';

class DataGoat {
	protected $APP;
	protected $GID;
	protected $Stor;
	protected $id = null;
	protected $breed_alias = null;
	protected $breed_id = null;
	protected $breed_name = null;
	public function __construct($APP, $GID = 'iGoat') {
		$this->APP = $APP;
		$APP::hello($this->GID = $GID, $this);
	}
	// автоклонирование структуры
	public function redo($NEW) {
		$class_name = __CLASS__;
		$theObj = new $class_name($this->APP, $NEW);
	}
	public function __call($FLD, $ARGS) {
		$res = who($FLD, $this->Stor);
		if ( we($ARGS) ) {
			$type = array_shift($ARGS);
			if ( call($type) ) {
				$res = cufa($type, merge(of($res), $ARGS));
			} else {
				if ( un($res) ) $res = who(0, $ARGS); else type($res, $type);
			}
		}
		return $res;
	}

	public function breed_alias() {
		if ( func_num_args() > 0 ) {
			$this->breed_alias = func_get_arg(0);
			if ( fail($this->breed_id, $this->breed_name) ) {
				$sql = xsql::get(u('id,name'), $this->breed_alias
					, 'breeds', 'alias');
				list($this->breed_id, $this->breed_name) =
					DB::args($sql, 2);
			}
		} else return $this->breed_alias;
	}
	public function breed_id() {
		if ( func_num_args() > 0 ) {
			$this->breed_id = func_get_arg(0);
		} else return $this->breed_id;
	}
	public function breed_name() {
		if ( func_num_args() > 0 ) {
			$this->breed_name = func_get_arg(0);
		} else return $this->breed_name;
	}

	// идентифицировать себя (cookies, session)
	public function identify() {
	}
}

return $self_name;
