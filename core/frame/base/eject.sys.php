<?php /* Извлечение */
#@ Извлечение значения по его ключу
function who($X, $AX, & $fake = no) {
	if ( clue($X, $AX)) return $AX[$X]; else $fake = yes; }
function get($MX, $AX, $LST = no) {
	$Res = func_num_args() > 3 ? need($MX, $AX, no, func_get_arg(3))
		: need($MX, $AX);
	if ( $LST ) return safe($Res, cnt(a($MX)));
	return arr($MX) ? $Res : solo($Res); }
#@ Получение данных из массива по его ключам (+ несуществующие)
function need($MX, $AX, $OR_OTHER = no) {
	if ( ! rcnt($AX, $Res) ) return $Res;
		else if ( bit($OR_OTHER) ) $Res = & $AX;
			$Data = (array) $MX;
	if ( $OR_OTHER && ! c($Data) ) return array();
		else if ( so($is_force, func_num_args() > 3) )
			$def = func_get_arg(3);
	foreach ( $Data as $kx ) {
		if ( so($ans, clue($kx, $AX)) ) {
			if ( $OR_OTHER ) unset($Res[$kx]); else $Res[$kx] = $AX[$kx]; }
		if ( ! $OR_OTHER && $is_force && no($ans) ) $Res[$kx] = $def; }
	return $Res; }
#@ То же, что и need, но по собственным ключам
function must($KX, $AX, $OR_OTHER = no) {
	return func_num_args() < 4 ? need(bad(ak($KX), no), $AX, $OR_OTHER)
		: need(bad(ak($KX), no), $AX, $OR_OTHER, func_get_arg(3)); }
#@ Умный вариант вариант need (ключи, список, строка)
function give($UX, $AX, $OR_OTHER = no) {
	return func_num_args() < 4 ? need(u($UX), $AX, $OR_OTHER)
		: need(u($UX), $AX, $OR_OTHER, func_get_arg(3)); }
#@ Упрощенный вариант need для индексов
function view($UX, $AX, $OR_OTHER = no) {
	return need(to::o2i(u($UX), $AX), $AX, $OR_OTHER); }
#@ Изъятие данных из массива по его ключам
function take(& $AX, $MX, $OR_OTHER = no) {
	if ( ! rcnt($AX, $Res) ) return $Res;
		else if ( bit($OR_OTHER) ) $Res = $AX;
			$Data = (array) $MX;
	if ( $OR_OTHER && ! c($Data) ) return array();
	foreach ( $Data as $kx ) {
		if ( clue($kx, $AX) ) {
			if ( ! $OR_OTHER ) {
				$Res[$kx] = $AX[$kx];
				unset($AX[$kx]);
			} else unset($Res[$kx]); }}
	if ( $OR_OTHER ) foreach ( array_keys($Res) as $k ) unset($AX[$k]);
	return $Res; }
#@ Привязка к элементу по конкретному ключу
function & bind(& $AX, $KX, & $fake = no) {
	$Link = & $AX;
	if ( $fake = ! we(so($Keys, u($KX, slash))) ) return $Link;
	foreach ( $Keys as $k )
		if ( $fake = ! clue($k, $Link) ) return $Link;
			else $Link = & $Link[$k];
	return $Link; }
#@ Смарт функция разделения на приближенно равные доли
function pie($X, $CNT = 1) {
	if ( ! inat($X) && ! inat($CNT, pol) ) return cant;
	if ( $CNT >= $X ) list ($b, $r) = of(0, $X);
		else list ($b, $r) = of(mod($X, $CNT), rest($X, $CNT));
	list ($Res, $i) = of(array_fill(0, $CNT, $b), 0);
	while ( $r-- != 0 ) $Res[$i++]++;
	return $Res; }
 #@ Смарт функция разложения на две части
function two($X, $PC = _, $ARG_NAT = no) {
	if ( $ARG_NAT && ! inat($X) ) return of(cant, cant);
		else $id = have(is_int($X), is_string($X), is_array($X));
	if ( 0 == $id ) return of(cant, cant);
		else if ( 1 == $id ) $cnt = abs($X);
			else if ( 2 == $id ) $cnt = strlen($X);
				else $cnt = count($X);
	if ( $cnt < 0 ) return of(cant, cant); else $x0 = $X;
	if ( 0 == $cnt ) {
		if ( 1 == $id ) return of(0, 0);
			else return arr($X) ? nodes(2) : of(str, str); }
	if ( ! skip($PC) ) {
		if ( ! inat($PC) ) return of(cant, cant);
		if ( 0 == $PC ) return of(clr($x0), $X);
		if ( $cnt >= so($p1, abs($PC)) ) $p2 = $cnt - $p1;
			else list($p1, $p2) = of($cnt, 0);
		if ( $PC < 0 ) swap($p1, $p2);
	} else list($p1, $p2) = pie($cnt, 2);
	if ( 0 == $p1 ) return of(clr($x0), $X);
		else if ( 0 == $p2 ) return of($X, clr($x0));
	if ( $id > 1 ) {
		if ( 2 == $id ) $Res = of(substr($X, 0, $p1), substr($X, $p1));
			else $Res = of(slice($X, 0, $p1, yes), slice($X, $p1, _, yes));
	} else $Res = of(sign($p1, $X), sign($p2, $X));
	return $Res; }
#@ выбор данных по этому ключу из всех массивов
// list ($a, $b, $Lost) = be($k, $A1, $A2)
function be($X) {
	$Args = func_get_args();
	$k = array_shift($Args);
	$Lost = $Res = array();
	foreach ( $Args as $i => $Data ) {
		if ( ! we($Data) ) {
			list ($Lost[], $Res[]) = of($i + 1, cant);
			continue; }
		if ( clue($X, $Data) ) {
			$Res[] = $Data[$X];
			continue; }
		if ( array_key_exists(str, $Data) ) {
			$Res[] = $Data[str];
			continue;
		}
		list ($Lost[], $Res[]) = of($i + 1, cant); }
	$Res[] = $Lost;
	return $Res; }
#@ Получение первого/последнего элемента/ключа/пары
function lead($AX, $MODE = array(), & $FAKE = no) {
	return _first_($AX, $MODE, $FAKE, no); }
function last($AX, $MODE = array(), & $FAKE = no) {
	return _first_($AX, $MODE, $FAKE, yes); }
