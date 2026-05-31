<?php /* Превращение в циклические данные */
#@ {,} Формирование массивов
function ax() { return array(); }
function a($X) { return is_array($X) ? $X : array($X); }
function an($AX) { return is_array($AX) ? $AX : array(); }
#@ {,} Списки
function av($AX) { if ( is_array($AX) ) return array_values($AX); }
function ak($AX) { if ( is_array($AX) ) return array_keys($AX); }
#@ {,} Группа из результатов обработки каждого её элемента
class to { public static function __callStatic($FX, $PKG) {
	return _eacher_($FX, $PKG); }}
class to2 { public static function __callStatic($FX, $PKG) {
	if ( vxch($PKG, i2k(0, $PKG), i2k(1, $PKG)) )
		return _eacher_($FX, $PKG); }}
#@ {,} Группа из результатов обработки каждого аргумента
class of { public static function __callStatic($FX, $ARGS) {
	return _eacher_($FX, $ARGS, yes); }}
#@ Создать массив как countdown
function down($AX) {
	if ( ! ok($i, sub(cnt($AX), 1)) ) return $i; else $Res = array();
	foreach ( $AX as $v ) $Res[$i--] = $v;
	return $Res; }
#@ Создать массив на базе ключей
function kx($AX) {
	if ( ! we($AX) ) return poor($AX);
	if ( ! so($is_auto, func_num_args() == 1) ) $Def = func_get_arg(1);
	$Res = array();
	foreach ( $AX as $k ) if ( set(clue($k)) ) $Res[$k] = $is_auto ? $k : $Def;
	return $Res; }
