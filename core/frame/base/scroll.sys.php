<?php /* Последовательности */
#@ {,} Ряд n-элементов начиная с x0
function row($N, $X0 = 0, $DX = 1) {
	if ( nums($X0, $DX) && inat($N, pol) ) $Res = array($X0);
		else return cant;
	for ($i = 1; $i < $N; $i++) $Res[] = $X0 += $DX;
	return $Res; }
#@ Разложение унифицированной строки
function u($X, $SEP = ',', $DEL_MISS = no) {
	if ( ! mean($X, $SEP) ) return cant;
	if ( ! l($X) ) return of($X); else if ( ! l($SEP) ) return str_split($X, 1);
	$Res = explode($SEP, $X);
	return $DEL_MISS ? own::l($Res) : $Res; }
function ut($X) { return to::trim(u($X)); }
class u {
	public static function __callStatic($FX, $ARGS) {
		if ( ok($Data, call_user_func_array(__CLASS__, $ARGS)) )
			return to::$FX($Data); }}
function ch($X) { return u($X, str); }
function p($X) { return func_num_args() > 1
	? j(func_get_args(), slash) : u($X, slash); }

class rom {
	protected $ROM = array();
	public static function __callStatic($VAR, $SEQ) {
		if ( we($SEQ) ) {
			$Data = yes(who(1, $SEQ)) ? u($SEQ[0]) : $SEQ[0];
			return self::$ROM[$VAR] = $Data;
		} else return self::$ROM[$VAR]; } }
#@ {,} Числовая последовательность на основе луча
function ray($X, $Y, $DX = 1, $WITH_TAIL = no) {
	if ( ! ok($jmp, foot($X, $Y, $DX, $WITH_TAIL)) ) return $jmp;
		else if ( 0 == $jmp ) return array(num($X));
			else return row(abs($jmp), $X, sig($DX, $jmp)); }
#@ Возвращение карты ключ/смещение
function map($AX) {
	if ( is_array($AX) ) return array_flip(array_keys($AX)); }
#@ {,} Последовательность символов
function abc($pair) {
	if (! note($pair, 2)) return cant; else $Res = array();
	foreach ( ray(ord($pair{0}), ord($pair{1}), 1, yes) as $i)
		$Res[] = chr($i);
	return $Res; }
#@ {,} Банк n-элементов с заданным значением
function many($VAL = null, $N = 1, $IS_ARR = no) {
	if ( ! inat($N, pol) || (! $IS_ARR && ! itext($VAL)) ) return cant;
		else $Res = array();
	for ( $i = 0; $i < $N; $i++ ) $Res[] = $VAL;
	return $IS_ARR ? $Res : j($Res); }
function nodes($N = 1) { return many(array(), $N, yes); }
#@ Внутриэлементный расчет
class our {
	public static function __callStatic($FX, $PKG) {
		if ( ! we($PKG) ) return cant;
			else list ($ax, $Res) = of(av(array_shift($PKG)), array());
		if ( icnt($ax, $cnt) && $cnt > 1 ) {
			for ($i = 1; $i < $cnt; $i++)
					$Res[] = cufa( $FX, array_merge(of($ax[$i]
						, $ax[$i - 1]), $PKG) );
				return $Res; }}}
