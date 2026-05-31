<?php /* Счет */
#@ {?} Размерность хранилища с допустимыми пределами
	function size($X, $LIMIT = skip, $OR_STR = no) {
		if ( ! skip($LIMIT) && ! inat($LIMIT) ) return cant;
		list ($fx1, $fx2) = $OR_STR ? array('is_string', 'strlen')
			: array('is_array', 'count');
		if ( $fx1($X) ) {
			if ( set($LIMIT) )
				$ans = $LIMIT < 0 ? $fx2($X) <= abs($LIMIT)
					: $fx2($X) >= $LIMIT;
			return isset($ans) ? $ans : yes; }}
#@ Сколько было элементов
	function was($X) {
		if ( bank($X) ) $X = is_string($X) ? strlen($X) : count($X);
		if ( inat($X, pol) ) return $X;
	}
#@ {#} Определение длины/количества
	function cnt($X, $P = 0) {
		if ( is_array($X) && inat($P) && so($res, count($X) + $P) >= 0 )
			return $res; }
	function len($X, $P = 0) {
		if ( is_string($X) && inat($P) && so($res, strlen($X) + $P) >= 0 )
			return $res; }
	function pcnt($X) { if ( icnt($X, $c) ) return $c > 0; }
	function plen($X) { if ( ilen($X, $l) ) return $l > 0; }
#@ {&} Наличие длины/количества
	function l($STR, $L = 0) { return strlen($STR) > $L; }
	function c($ARR, $C = 0) { return count($ARR) > $C; }
	function zc($X) { if ( is_array($X) ) return count($X) == 0; }
	function zl($X) { if ( is_string($X) ) return strlen($X) == 0; }
	function zero($X) { if ( inum($X) ) return 0 == $X; }
#@ Попадание размеров в заданный интервал
	function lin($X, $A, $B = 0) {
		return hit(len($X), $A, $B); }
	function cin($X, $A, $B = 0) {
		return hit(cnt($X), $A, $B); }
#@ Сколько элементов находятся между двумя смещениями
	function us($OX1, $OX2, $SIZE = skip) {
		if ( ! nums($OX1, $OX2) ) return cant;
		if ( ! skip($SIZE) ) {
			list ($ix1, $ix2) = to::o2i(of($OX1, $OX2), $SIZE);
			if ( fail($ix1, $ix2) ) return cant;
		} else if ( eqp($OX1, $OX2) ) {
			list ($ix1, $ix2) = $OX1 < 0 ? of::abs($OX1, $OX2) : of($OX1, $OX2);
		} else return cant;
		return $OX1 != $OX2 ? max($ix1, $ix2) - min($ix1, $ix2) + 1 : 1; }
#@ Работа с позицией next, prev
function nxt($STEP = skip, $BX = skip) {
	static $pos = null;
	static $sz = null;
	static $map = str;
	if ( skip($STEP) ) { ##update
		if ( ! ok($sz, was($BX))) return nil($pos = _, $sz = _, $map = str);
		list ($sz, $pos, $STEP) = of($sz - 1, 0, 0);
		if ( arr($BX) ) $map = j(ak($BX), ','); }
	if ( ! inat($STEP) || ! is($pos) ) return cant;
		else list ($Map, $pos1) = of(u($map), $pos + $STEP);
	if ( hit($pos1, 0, $sz) ) $pos = $pos1; else return no;
	return miss($map) ? $pos1 : $Map[$pos1]; }
