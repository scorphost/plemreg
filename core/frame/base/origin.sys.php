<?php /* Набор производных решений общего назначения */
define('nz', 0);
define('pol', 1);
define('neg', 2);
define('poz', 3);
define('nez', 4);
define('chr_mirror', '([{<>}])');
define('hr', '<hr style="color:gray; border:dotted 1px;" />');

#@ {,} Мультипрогон аргументов или элементов
function _eacher_($FX, $PKG, $IS_ARGS = no) {
	if ( ! call($FX) ) return cant;
		else if ( ! arr($PKG, 1) ) return poor($PKG);
	if ( $IS_ARGS ) {
		$ax = & $PKG;
		$Opt = array();
	} else {
		$ax = array_shift($PKG);
		$Opt = & $PKG; }
	if ( ! rcnt($ax, $Res) ) return $Res;
	foreach ( $ax as $k => $x )
		$Res[$k] = call_user_func_array($FX
			, array_merge(array($x), $Opt));
	return $Res; }
#@ Сложение или вычитание результатов выборки
function _cross_($ARGS, $FX = 'noop', $KEYMAP = no) {
	$Mark = $Arr = array_shift($ARGS);
	$idx = $KEYMAP ? 'v' : 'k';
	foreach ( $Mark as $k => & $z ) $z = 0;
	foreach ( own::we($ARGS) as $EachArr )
		foreach ( $EachArr as $k => $v ) $Mark[$$idx]++;
	foreach ( $Mark as $k => $v) if ( $FX($v) ) unset($Arr[$k]);
	return $KEYMAP ? array_keys($Arr) : $Arr; }
#@ Растет или падает список числовых элементов?
function _up_($AX, $DX = no, $_UP = yes) {
	list ($fx1, $fx2, $k) = $_UP ? of('less', 'more', 1)
		: of('more', 'less', -1);
	if ( ! is_bool($DX) && ! inum($DX, pol) ) return cant;
	if ( are::num($AX) ) $Res = our::most($AX); else return cant;
	if ( has::$fx1($Res) ) return no;
		else if ( ! is_bool($DX) ) return are::eq($Res, $k * $DX);
	return $DX ? are::$fx2($Res) : yes; }
#@ Получение первого/последнего ключа
function _first_($AX, $MODE = array(), & $FAKE = no, $_REV) {
	if ( ! we($AX) ) return nil($FAKE = yes);
	$v = $_REV ? end($AX) : reset($AX);
	if ( skip($MODE) ) return of(key($AX), $v);
		else if ( is_array($MODE) ) return array(key($AX) => $v);
			else return $MODE ? key($AX) : $v; }
#@ Нестрогое вхождение текста в искомый перечень
function _in_($X, $QX, $NATIVE = no, & $KEY = _, $fx1 = 'noop', $fx2 = 'noop') {
	if ( ! atom($X) || ! bank($QX) ) return cant;
	if ( ! size($QX, 1, str($QX)) ) return no;
		else if ( str($X) ) $X = $fx1($fx2($X));
	if ( $NATIVE ) $Arr = $QX; else $Arr = & $QX;
	if ( str($QX) ) {
		if ( ! info($X) || ! ok($QX, u($fx1($QX))) ) return cant;
	} else fresh($QX, to::strtolower(own::str($QX)));
	if ( ! ok($k, look($X, $QX)) ) return no;
		else return done($KEY = $NATIVE ? $Arr[$k] : $k); }
