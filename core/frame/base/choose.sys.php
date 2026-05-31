<?php /*  Выборка */
#@ Выборка верных собственных значений
class own {
	public static function id($FX, $PKG, $TYPE = _, $RET = 'noop') {
		$Data = _eacher_($FX, $PKG);
		if ( ! we($Data) ) return poor($Data);
			else list( $Arr, $Res ) = of(reset($PKG), array());
		if ( ! call($RET) ) $RET = 'noop';
		if ( non($TYPE) ) $fp = no($TYPE) ? 'ne' : 'non';
			else if ( yes($TYPE) ) $fp = 'is';
				else if ( call($TYPE) ) $fp = $TYPE;
					else return cant;
		foreach ( $Data as $k => $v ) if ( $fp($v) ) $Res[$k] = $Arr[$k];
		return $RET($Res);
	}
	public static function __callStatic($FX, $PKG) {
		return self::id($FX, $PKG, yes); }}
#@ Выборка не-верных собственных значений
class but {
	public static function __callStatic($FX, $PKG) {
		return own::id($FX, $PKG, no); }}
#@ Выборка других собственных значений
class alt {
	public static function __callStatic($FX, $PKG) {
		return own::id($FX, $PKG); }}
#@ Выборка верных собственных значений в виде ключей
class sel {
	public static function __callStatic($FX, $PKG) {
		return ak(own::id($FX, $PKG, yes)); }}
#@ Выборка верных собственных ключей
class gkl {
	public static function id($FX, $PKG, $TYPE = _) {
		if ( ! call($FX) ) return cant;
			else if ( ! we($PKG) ) return poor($PKG);
				else list ($ax, $dz) = of(array_shift($PKG), 1);
		if ( ! rcnt($ax, $Res) ) return $Res;
		if ( set($TYPE) ) {
			if ( $TYPE ) {
				if ( func_num_args() < 4 ) $z = 1;
					else list ($z, $dz) = of(count($ax), -1);
			} else $z = -1; }
		foreach ( $ax as $k => $v ) {
			if ( un($TYPE) ) $z = & $k; else $z+= $dz;
			if ( is(cufa($FX, array_merge(array($z),$PKG))) ) $Res[] = $k; }
		return $Res; }
	public static function __callStatic($FX, $PKG) {
		return self::id($FX, $PKG); }}
#@ Выборка верных собственных индексов
class gki {
	public static function __callStatic($FX, $PKG) {
		return gkl::id($FX, $PKG, no); }}
#@ Выборка верных собственных позиций
class gkp {
	public static function __callStatic($FX, $PKG) {
		return gkl::id($FX, $PKG, yes); }}
#@ Выборка верных собственных отсчетов
class gkc {
	public static function __callStatic($FX, $PKG) {
		return gkl::id($FX, $PKG, yes, _); }}
#@ Сложение/вычитание результатов выборки по значениям
function show() { return _cross_(func_get_args(), 'not', no); }
function hide() { return _cross_(func_get_args(), 'noop', no); }
#@ Сложение/вычитание результатов выборки по ключам
function cast() { return _cross_(func_get_args(), 'not', yes); }
function kill() { return _cross_(func_get_args(), 'noop', yes); }
