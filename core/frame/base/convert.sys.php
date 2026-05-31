<?php /* Конвертирование данных */
#@ Смещение в индекс
function o2i($X, $SIZE) {
	if (ok($sz, was($SIZE)) && inat($X) ) {
		$pos = $X < 0 ? $sz - abs($X) : $X;
		return hit($pos, 0, $sz - 1) ? $pos : no; }}
#@ Циклическое/граничное смещение
function o2j($X, $SIZE, $IS_CYCLE = no) {
	if ( ok($ix, o2i($X, $SIZE)) ) return $ix; else $sz = was($SIZE);
	if ( no($ix) ) {## вышли за переделы
		$ix = $IS_CYCLE ? ( let($X < 0, abs($X), $X + 1) % $sz ) : 0;
		if ( 0 == $ix ) return $X < 0 ? 0 : $sz - 1;
			else return $X < 0 ? o2i(-$ix, $sz) : ti($ix - 1); }}
#@ {#} Второе смещение после прыжка
function jump($OX1, $JMP, $SIZE = skip, $IS_WALL = no) {
	if ( ! nats($OX1, $JMP) ) return cant;
	if ( ! skip($SIZE) && ! ok($sz, was($SIZE)) ) return cant;
	list ($eqp, $ox2) = of(eqp($OX1, $JMP), $OX1 + $JMP);
	if ( skip($SIZE) ) return $eqp || eqp($ox2, $OX1) ? $ox2 : no;
		else $ix1 = o2i($OX1, $sz);
	if ( 0 == $JMP || (no($ix1) && $eqp) ) return $ix1; else $res = no;
	if ( is($ix1) ) {
		$ix2 = $ix1 + $JMP;
		if ( $JMP < 0 ) {
			if ( eqp($ix2, $ix1) ) $res = $ix2;
				else if ( $IS_WALL ) $res = 0;
		} else $res = $IS_WALL ? o2j($ix2, $sz) : o2i($ix2, $sz);
	} else {
		if ( eqp($ox2, $OX1) ) $res = o2i($ox2, $sz);
			else if ( $IS_WALL ) $res = $JMP < 0 ? 0 : $sz - 1; }
	return $res; }
#@ Конвертирование смещение ~> ключ
function o2k($X, $AX, $JMP = 0) {
	if ( ! inum($JMP) ) return cant;
	if ( ! ok($i, jump($X, (int) $JMP, cnt($AX), td($JMP))) ) return $i;
		else return who($i, ak($AX)); }
#@ Конвертирование смещение ~> значение
function o2v($X, $AX, $JMP = 0, & $fake = no) {
	if ( ok($k, o2k($X, $AX, $JMP)) ) return $AX[$k]; else $fake = yes; }
#@ Конвертирование ключ ~> смещение
function k2i($X, $AX) { return who($X, map($AX)); }
#@ Конвертирование ключ ~> смещение
function v2k($X, $AX, $OFFS = 0, $DUP = no, $RULE = 'scan') {
	$Keys = $RULE($X, $AX, $DUP);
	return ok($res, o2i($OFFS, $Keys)) ? who($res, $Keys) : $res; }
#@ Конвертирование ключ ~> смещение
function v2i($X, $AX, $OFFS = 0, $DUP = no, $RULE = 'scan') {
	return ok($res, v2k($X, $AX, $OFFS, $DUP, $RULE))
		? k2i($res, $AX) : $res; }
#@ Конвертирование ключ ~> смещение
function k2k($X, $AX, $JMP = 0) {
	return o2k( jump(k2i($X, $AX), $JMP, $AX, is_float(num($JMP))), $AX ); }
#@ Объединение массива в строку
function j($AX, $GLUE = str, $REV = no) {
	if ( is_array($AX) )
		return count($AX) == 0 ? str
			: implode($GLUE, $REV ? array_reverse($AX) : $AX); }
#@ Преобразование roll ~> index
function r2i($X, $SEQ) {
	$Data = roll(by($X, $SEQ));
	list ($Len, $Res, $i) = of(to::len($Data), array(), 0);
	foreach ( $Len as $k => $l ) {
		$Res[$i] = $Data[$k];
		$i+= $l + ((int) $i > 0); }
	return $Res; }
