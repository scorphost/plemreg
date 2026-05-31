<?php

$self_name = 'DataSelf';

class DataSelf {
	protected $APP;
	protected $GID;
	protected $Stor;
	protected $id = null;

	protected $is_male;
	protected $is_guest;
	protected $is_child;
	protected $have_lact;

	public function __construct($APP, $GID = 'iam') {
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
	public function is_male() { return $this->is_male; }
	public function is_female() { return ! $this->is_male; }
	public function is_guest() { return $this->is_guest;}
	public function is_child() { return $this->is_child;}
	public function have_lact() { return $this->have_lact;}

	// идентифицировать себя (cookies, session)
	public function identify($ID) {
		$sql = xsql::get(_, $ID, 'animals');
		$DB1 = DB::run($sql, _);
		if ( ! we($DB1) ) return no;
		$sql = xsql::get(_, $ID, 'goats_data', 'id_goat');
		$DB2 = DB::run($sql, _);
		unset($DB2['id'], $DB2['id_goat']);
		$this->Stor = merge($DB1, $DB2);
		$this->is_male = 1 == $this->Stor['sex'];
		$this->is_guest = zero($this->Stor['is_reg']);
		$this->is_child = (strtotime($this->Stor['date_born'])
			- strtotime(zdate(today()). '-1 YEAR')) >= 0;
		$this->have_lact = we(id::ff('goats_lact', $ID, 'id_goat'));
		return yes;
	}
}

return $self_name;
