<?php

$self_name = 'DataUser';

class DataUser {
	const table = 'users';
	protected $APP;
	protected $GID;
	protected $Stor;
	protected $id = null;
	public function __construct($APP, $GID = 'iUser') {
		$this->APP = $APP;
		$APP::hello($this->GID = $GID, $this);
	}
	// автоклонирование структуры
	public function redo($NEW) {
		$class_name = __CLASS__;
		$theObj = new $class_name($this->APP, $NEW);
	}
	public function x2id($X, $FLD) {
		if ( ! inews($FLD) ) return cant;
			else $sql = xsql::get(_, $X, $this::table, $FLD);
		$this->Stor = DB::run($sql, _);
		$this->Stor['id'] = $this->id = nat(who('id', $this->Stor));
		return is($this->id);
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
	// идентифицировать себя (cookies, session)
	public function identify() {
		$secret = Cook::get('uid_token');
		self::x2id(Cook::get('uid_token'), 'secret');
	}
	public function admin() { return $this->acl() > 9; }
	public function guest() { return un($this->acl()); }
	public function not_apk() {
		if ( $this->guest() ) return yes;
			else return $this->admin() ? no : zero($this->is_apk());
	}
}

return $self_name;
