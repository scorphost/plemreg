<?php /* Групповой анализ на соответствие */
#@ {&} Все пригодны
function are($AX) {
	if ( is_array($AX) ) foreach ( $AX as $k => $x )
		if ( non($x) ) return no;
	return isset($k); }
#@ {&} Полностью непригодны
function none($AX) {
	if ( is_array($AX) ) foreach ( $AX as $x ) if ( is($x) ) return no;
	return yes; }
#@ {#} Позиция первого пригодного элемента группы
function has($AX, $i = 1) {
	if ( is_array($AX) ) {
		foreach ( $AX as $x ) if ( is($x) ) return $i; else $i++;
		return 0; }}
#@ {#} Позиция первого непригодного элемента группы
function lost($AX, $i = 1) {
	if ( is_array($AX) ) {
		foreach ( $AX as $x ) if ( non($x) ) return $i; else $i++;
		return 0;
	} else return -1; }
#@ Итоговый результат пригодности после обработки каждого элемента
class are { public static function __callStatic($FX, $PKG)
	{ $c = __CLASS__; return $c(_eacher_($FX, $PKG)); }}
class none { public static function __callStatic($FX, $PKG)
	{ $c = __CLASS__; return $c(_eacher_($FX, $PKG)); }}
class has { public static function __callStatic($FX, $PKG)
	{ $c = __CLASS__; return $c(_eacher_($FX, $PKG)); }}
class lost { public static function __callStatic($FX, $PKG)
	{ $c = __CLASS__; return $c(_eacher_($FX, $PKG)); }}
#@ Итоговый результат пригодности после обработки каждого элемента
function pass() { return are(func_get_args()); }
function dont() { return none(func_get_args()); }
function have() { return has(func_get_args()); }
function fail() { return lost(func_get_args()); }
class all { public static function __callStatic($FX, $ARGS)
	{ return are(_eacher_($FX, $ARGS, yes)); }}
class dont { public static function __callStatic($FX, $ARGS)
	{ return none(_eacher_($FX, $ARGS, yes)); }}
class have { public static function __callStatic($FX, $ARGS)
	{ return has(_eacher_($FX, $ARGS, yes)); }}
class fail { public static function __callStatic($FX, $ARGS)
	{ return lost(_eacher_($FX, $ARGS, yes)); }}
