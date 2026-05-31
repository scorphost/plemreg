<?php
#@ Печатная версия содержимого
function xh($X) {
	if ( info($X) ) {
		if ( ok($y1, num($X, yes)) ) {
			$y2 = ciph($X) ? str : wear($y1, '[');
			if ( str($X) ) $X = qq($X. $y2); }
		if ( is_float($X) && itext($X) )
			if ( ! meet('e', $X, 1) && ! meet(dot, $X, 1) ) $X.= '.0';
		if ( some($X) ) {
			if (str($X) ) {
				if ( preg_match('/^\s+$/', $X) ) return '@spc'
					. wear(strlen($X), '[');
				if ( '"' == $X ) return '@qq';
					else if ( "'" == $X ) return '@q';
				if ( strlen(trim($X, spc)) < strlen($X) ) $X = q($X);
				return str_replace(of("\r", "\n", "\t")
					, of("\\r","\\n","\\t"), hsc($X));
			} else return ts($X); }}
	switch ( ctp($X) ) {
		case 's': $res = 'nos'; break;
		case 'n': $res = 'nul'; break;
		case 'b': $res = $X ? 'true' : 'false'; break;
		case 'o':
			$c1 = get_parent_class($X);
			$res = dd('obj'). get_class($X). ( she($c1) ? wear($c1, '(') : '' );
			break;
		case 'r':
			$t = get_resource_type($X);
			if ( 'stream' == $t ) {
				list ($c1, $c2) = av(give('wrapper_type,uri'
					, stream_get_meta_data($X)));
				$s = $c1. wear($c2, '(');
			} else $s = $t;
			$res = dd('rsc'). $s;
			break;
		case 'a':
			list ($c1, $c2) = of(count($X, yes), count($X));
			$res = dd('arr'). $c2;
			if ( $c2 < $c1 ) $res.= wear('+'. ($c1 - $c2));
			break;
		default: $res = 'NOD'; }
	return pre($res, '@'); }
#@ Печатная версия массива
function ah($AX, $LINE = br) {
	if ( ! itext($LINE) ) $LINE = cr;
	if ( we($AX) ) {
		list ($ax1, $ax2, $i, $c) = of(roll($AX), array(), 0, count($AX));
		list ($is_flat, $is_row, $res) = of(yes, yes, dd('@arr'));
		foreach ( $ax1 as $k1 => $v1 ) {
			unset($v2);
			$ak2 = explode(slash, $k1);
			$is_flat = $is_flat && count($ak2) == 1;
			foreach ( $ak2 as & $v2 ) {
				if ( ! inat($v2) ) $is_row = is_bool($v2 = q($v2));
					else $is_row = $is_flat && $is_row && $v2 == $i++; }
			$k2 = wear(j($ak2, ']['), '[');
			if ( is_array($v1) ) $v1 = 'array()';
			$ax2[] = xh($k2). qs('=>'). xh($v1); }
		list($spl, $tab) = $is_flat && $c < 10
			? of(str, spc) : of($LINE, spc(6));
		if ( $is_row && $c < 10 ) $res.= wear( j(to::xh($AX), ',') );
			else $res.= $spl. $tab. j($ax2, $spl. $tab);
	} else $res = arr($AX) ? 'array()' : '@noa';
	return $res; }

#@ Допечатная буферизация данных
class buf {
	protected $eol;
	protected $some = no;
	protected $buff = str;
	protected $tail = str;
	public function __construct($eol) {
		$this->eol = to_text($eol); }
	public function p($O = str, $SPL = str) {
		static $dspl = str;
		if ( func_num_args() == 1 ) $SPL = $dspl;
			else if ( $SPL !== $dspl ) $dspl = $SPL;
		list ($res, $tail) = of::to_text($O, $SPL);
		if ( ! l($res) ) return ;
		if ( $this->some ) $this->buff.= $this->tail. $res;
			else $this->some = done($this->buff.= $res);
		$this->tail = $tail; }
	public function cr() { if ( $this->some ) $this->tail = $this->eol; }
	public function nl() { $this->buff.= $this->eol; }
	public function xp($O) { $this->tail.= to_text($O); }
	public function ed($O = str) { $this->tail = to_text($O); }
	public function z() { return cls($this->buff); }}
#@
class scr extends buf {
	public function __construct() {
		$this->eol = func_num_args() == 0 ? lb() : to_text(func_get_arg(0));
		parent::__construct($this->eol); }
	public function z() { if ( $this->some ) echo parent::z(); }
	public function hr() { if ( $this->some ) $this->tail = hr; }}

#! Для последовательного вывода класс-консоль
/*class vtr extends buf {
	public function __construct() {
		$this->eol = func_num_args() == 0 ? lb() : to_text(func_get_arg(0));
		parent::__construct($this->eol); }
	public function __destruct() { ob_end_flush(); }
	public function p($O = str, $SPL = str) {
		parent::p($O, $SPL);
		if ( $this->some ) {
			ob_start();
			ob_implicit_flush(1);
			echo str_repeat(chr(0), 4096); }}
	public function z() {
		if ( $this->some ) $res = parent::z(); else return ;
		echo $res; flush(); ob_flush(); }
	public function hr() {
		if ( $this->some ) $this->tail = hr; }}
*/#@ Вывод на экран строки с переносом
function wl($W = skip, $BUF = skip) {
	if ( skip($BUF) ) $BUF = scr();
	$BUF->p(to_text($W));
	$BUF->cr();
	$BUF->z(); }
#@ Вывод содержимого в печатном формате
function prx($W) { wl(arr($W) ? ah($W) : xh($W)); }
