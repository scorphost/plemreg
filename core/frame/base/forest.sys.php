<?php /* Работа с деревьями */
#@ Конструктор дерева из массива
class tree
{
	const VARS = 'A,P,R,L,K,V';
	private $A;		// Array details
	private $R;		// Relations
	private $P;		// Place
	private $L;		// Level
	private $K;		// Keys
	private $V;		// Values
	private $idn;	// Next id
	public function __construct($AX = array()) {
		foreach ( u(self::VARS) as $var ) $this->$var = array();
		if ( ! we($AX) ) return is_array($AX) ? $this : cant;
		list ($Arr, $Nodes, $c, $o, $i, $id)
			= of(array($AX), array(0), cnt($AX, 1), 0, 1, 1);
		while ( $i < $c ) $this->P[$i] = $i++;
		do {
			foreach ( $Arr[$o] as $k => $v ) {
				if ( is_array($v) ) {
					$Arr[] = & $Arr[$o][$k];
					list ($Nodes[], $c, $i) = of($id, cnt($v, 1), 1);
					while ( $i < $c ) $this->P[] = $i++;
				} else $this->V[$id] =  $Arr[$o][$k];
				list ($this->R[$id], $this->K[$id]) = of($Nodes[$o], $k);
				$this->L[$id] = count($this->trace($id));
				$id++; }
		} while ( array_key_exists(++$o, $Arr) );
		unset($Arr[0]);
		list ($this->idn, $this->A) = of($id, array_combine(ak(need(ak(
			$this->V), afk(row(count($this->R), 1)), yes)), $Arr));
		return $this; }
	#` Трассировка снизу-вверх
	function trace($ID_FROM) {
		$Trace = array($id = $this->R[$ID_FROM]);
		while ( $id != 0 ) $Trace[] = $id = $this->R[$id];
		return $Trace; }
	#` Сборка массива
	public function arr() {
		list($DB, $Res, $Nodes, $l) = of($this->R, array(), array(), 1);
		if ( ! c($DB) ) return $Res; else $Nodes[] = & $Res;
		do {
			foreach ( $DB as $id => $par ) {
				if ( $l != $this->L[$id] ) continue;
					else $k = $this->K[$id];
				if ( we(scan($id, $DB, yes)) ) {
					$Nodes[$par][$k] = array();
					$Nodes[$id] = & $Nodes[$par][$k];
				} else {
					if ( isset($this->V[$id]) )
						$Nodes[$par][$k] = $this->V[$id];
							else $Nodes[$par][$k] = array(); }
				unset($DB[$id]); }
			$l++;
		} while ( we($DB) );
		return $Res;
	}
	#` Получение списков аттрибутов
	public function R() {
		if ( 0 == func_num_args() ) return $this->R;
			else if ( clue(so($id, func_get_arg(0)), $this->R) )
				return $this->R[$id]; }
	public function L() {
		if ( 0 == func_num_args() ) return $this->L;
			else if ( clue(so($id, func_get_arg(0)), $this->L) )
				return $this->L[$id]; }
	public function P() {
		if ( 0 == func_num_args() ) return $this->P;
			else if ( clue(so($id, func_get_arg(0)), $this->P) )
				return $this->P[$id]; }
	public function K() {
		if ( 0 == func_num_args() ) return $this->K;
			else if ( clue(so($id, func_get_arg(0)), $this->K) )
				return $this->K[$id]; }
	public function V() { return $this->V; }
	#` Получение значения по его ключу
	public function id2v($ID, & $fake = no) {
		if ( clue($ID, $this->V) ) return $this->V[$ID];
			else if ( clue($ID, $this->A) ) return $this->A[$ID];
				else $fake = yes; }
	#` Замена значения по ключу
	public function set($ID, $VAL = null) {
		if ( so($ans, clue($ID, $this->V)) ) $this->V[$ID] = $VAL;
		return $ans; }
	#` Обновление значений по ключам
	public function push($VALS = null) {
		if ( are::clue(ak($VALS), $this->V) ) return tie($this->V, $VALS); }
}
