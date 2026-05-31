<?php /*  Изменение */
#@ Убрать из массива ненужные элементы
function burn(& $AX, $VAL = null) {
	if ( ! we($AX) ) return poor($AX);
		else list($Data, $did) = of(slice(func_get_args(), 1), 0);
	foreach ( $AX as $k => $v ) {
		if ( here($v, $Data, yes) ) {
			unset($AX[$k]);
			$did++; }}
	return $did; }
#@ Обновление элементов на основе обработчика
class upd {
	public static function __callStatic($FX, $PKG) {
		list ($Arr, $Keys) = safe($PKG, 2);
		$Opt = count($PKG) > 2 ? slice($PKG, 2) : skip;
		if ( miss(so($Keys, (array) $Keys)) ) $Keys = array_keys($Arr);
		list ($i, $p, $Map) = of(-1, 0, map($Arr));
		foreach ( $Keys as $k ) {
			if ( ! clue($k, $Arr) ) continue;
			list ($v, $i, $p) = of($Arr[$k], $Map[$k], $Map[$k] + 1);
			$Arr[$k] = cufa($FX, merge(of($v)
				, (array) $Opt, of($k, $i, $p)));
		}
		return $Arr; }}
#@ Удаление элементов по результату обработчика
class del {
	public static function __callStatic($FX, $PKG) {
		return need(cufa(of('sel', $FX), $PKG), reset($PKG), yes); }}

function xch(& $AX, $SRC, $RPL, $DUP = no) {
	if ( ! arr($AX) ) return cant;
	if ( arr($SRC) && ! arr($RPL) ) {
		$r = $RPL;
		$RPL = ax();
		foreach ( ak($SRC) as $k ) $RPL[$k] = $r; }
	if ( ! arrs($SRC, $RPL) ) return cant; else $i = 0;
	foreach ( $AX as & $v ) {
		if ( ok($k, look($v, $SRC, $DUP) ) && ke($k, $RPL) ) {
			$v = $RPL[$k];
			$i++; } }
	return $i;
}
