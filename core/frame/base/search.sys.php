<?php /* Поиск по значению */
#@ {k} Поиск по значению элемента (правильный)
function look($X, $AX, $DUP = no) {
	if ( ! is_array($AX) ) return cant;
	if ( ! $DUP && inum($X) ) {
		foreach ( $AX as $k => $v ) if ( eqn($v, $X) ) return $k;
		return no;
	} else return array_search($X, $AX, yes); }
#@ {,} Список всех ключей для искомого элемента
function scan($X, $AX, $DUP = no) {
	if ( ! rcnt($AX, $Res) ) return $Res;
	while ( ok($k, look($X, $AX, $DUP)) ) {
		unset($AX[$k]);
		$Res[] = $k; }
	return $Res; }
#@ {,} Список всех ключей в котором нет искомого элемента
function other($X, $AX, $DUP = no) {
	if ( ! rcnt($AX, $Res) ) return $Res;
	while ( ok($k, look($X, $AX, $DUP)) ) unset($AX[$k]);
	return ak($AX); }
#@ {?} Подтверждения присутствия элемента
function here($X, $AX, $DUP = no) {
	return on(look($X, $AX, $DUP), no, cant, yes); }
#@ {?} Признак нахождения указанного элемента в достаточном количестве
function there($X, $AX, $MIN = 0, $DUP = no, & $cnt = _) {
	if ( inat($MIN, poz) && icnt(scan($X, $AX, $DUP), $cnt))
		return $cnt >= $MIN; }
#@ {#} В каком из набора аргументов встречается элемент
function where($X, $DUP, $BX1) {
	list ($n, $Args, $Res) = of(func_num_args(), func_get_args(), array());
	list ($dup, $p) = arr(func_get_arg(1)) ? of(no, 1) : of($DUP, 2);
	foreach ( slice($Args, $p) as $i => $EachBank )
		if ( here($X, $EachBank, $DUP) ) $Res[] = $i;
	return $Res; }
#@ Вхождение текста в искомый перечень
function in1($X, $QX, $NATIVE = no, & $KEY = _ ) { ## как есть
	return _in_($X, $QX, $NATIVE, $KEY); }
function in2($X, $QX, $NATIVE = no, & $KEY = _ ) { ## в любом начертании
	return _in_($X, $QX, $NATIVE, $KEY, 'strtolower'); }
function in($X, $QX, $NATIVE = no, & $KEY = _ ) { ## что-нибудь похожее
	return _in_($X, $QX, $NATIVE, $KEY, 'strtolower', 'trim'); }
#@ {#} Позиция аргумента, в котором есть соответсвие
function among($X, $B1) {
	if ( ok($i, look($X, slice(1, func_get_args()), yes)) ) return $i + 1; }
#@ Поиск по совпадению
function like($X, $PTRN, $TYPE = skip, $CASE = false) {
	if ( ! mean($X, $PTRN) ) return cant;
	switch ( bad($TYPE, 'e') ) {
		case 'e':
			$ans = is($CASE ? strpos($X, $PTRN) : stripos($X, $PTRN));
			break;
		case 'g':
			$ans = fnmatch($PTRN, $X, $CASE ? FNM_CASEFOLD : 0);
			break;
		case 'q': $PTRN = preg_quote($PTRN, '/-');
		case 'r':
			$regx = "/$PTRN/Uu";
			$ans = (bool) preg_match($regx.= $CASE ? str : 'i', $X);
			break;
		case 's': $ans = are(sscanf($PTRN, $X)); }
	if ( isset($ans) ) return $ans; }
#@ {,} Список ключей для тех элементов, что удалось отыскать
function find($AX, $ARR, $DUP = no, & $Lost = array()) {
	if ( ! pair($AX, $ARR, $Res) ) return $Res; else $Lost = an($Lost);
	foreach ( $AX as $k => $x ) {
		if ( ok($ak, scan($x, $ARR, $DUP)) ) {
			$Res[$k] = $ak;
			unset($ARR[$ak]);
		} else $Lost[] = $k; }
	return $Res; }
#@ {,} Смещения для тех элементов, которые последовательно попали в массив
function been($AX, $ARR, $DUP = no, & $Res = array()) {
	if ( ! pair($AX, $ARR, $Res, yes) ) return lie($Res);
	list ($cnt, $z, $fx) = of(pure($ARR), 0, let($DUP, 'twin', 'eq'));
	foreach ( $AX as $k => $x ) {
		for ( $i = $z; $i < $cnt; $i++ ) {
			if ( $fx($x, $ARR[$i]) ) {
				list ($Res[$k], $z) = of($i, $i + 1);
				break; } }
		if ($z == $cnt) break; }
	return eqc($Res, $AX); }
#@ Групповой поиск по совпадению
function under($KEY, $DATA, $TYPE = skip, $CASE = no) {
	foreach ( $DATA as $tmpl => $v)
		if ( like($KEY, $tmpl, $TYPE, $CASE) ) return $v;
}
