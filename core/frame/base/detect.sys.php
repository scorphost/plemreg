<?php /* Разбор и анализ строковых данных */
#@ {?} Строка начинается с этой последовательности
function you($X, $SEQ, $CASE = no) {
	if ( ! mean($X, $SEQ) ) return cant;
		else $fx = $CASE ? 'strpos' : 'stripos';
	return $fx($X, $SEQ) === 0; }
#@ {?} Строка заканчивается этой последовательностью
function did($X, $SEQ, $CASE = no) {
	if ( mean($X, $SEQ) ) return you(strrev($X), strrev($SEQ), $CASE); }
#@ Узнать, встречается ли подстрока начиная с этого смещения
function meet($X, $TXT, $FROM = 0, $CASE = no, & $POS = null) {
	if ( ! mean($X, $TXT) || ! inat($FROM) ) return cant;
	if ( ! ok($FROM, o2i($FROM, $TXT)) ) return $FROM;
		else $fx = $CASE ? 'strpos' : 'stripos';
	return is($POS = $fx($TXT, $X, $FROM)); }
#@ {,} Где встречается каждое из слов
function dict($SX, $SET) {
	if ( ! ilen($SX, $rl) || ! icnt($SET, $rc) ) return cant;
		else if ( 0 == $rl || 0 == $rc ) return array();
			else list ($Res, $ini, $Len) = of(ax(), 0, to::len(uniq($SET)));
	burn($Len, null);
	arsort($Len);
	$Map = must($Len, $SET);
	while ( yes ) {
		$Good = array();
		foreach ( $Map as $k => $sq )
			if ( ok($pos, strpos($SX, $sq, $ini)) ) $Good[$k] = $pos;
		if ( ! we($Good) ) break;
			else $Best = must(near($ini, $Good), $Len);
		$bst = key($Best);
		$pos = $Good[$bst];
		list ($ini, $Res[$pos]) = of($pos + $Len[$bst], $bst); }
	return $Res; }
#@ Поиск всех вхождений фразы в строке в виде списка ключей
function seek($X, $TXT, $CASE = no) {
	if ( ! mean($X, $TXT) ) return cant;
		else if ( have::miss($X, $TXT) ) return ax();
			else list ($i, $len, $Res) = of(0, strlen($X), ax());
	$fx = $CASE ? 'strpos' : 'stripos';
	while ( yes ) {
		if ( ! ok($pos, $fx($TXT, $X, $i)) ) break;
			else list($i, $Res[]) = of($pos + $len, $pos); }
	return $Res; }
#@ Выбивание ненужных символов из строки
function blow($MX, $STR, $CASE = no) {
	if ( ! she($STR) ) return poor($STR, str);
	foreach ( (array) $MX as $x ) {
		if ( ! we(so($Data, seek(my_text($x), $STR, $CASE)) ) ) continue;
			else list($len, $from) = of(len($x, - 1), reset($Data));
		list ($to, $in, $n, $res) = of($from + $len, no, strlen($STR), str);
		for ($i = 0; $i < $n; $i++) {
			if ( ! $in ) $in = $i == $from;
			if ( $in ) {
				if ( ! so($in, $i < $to) ) {
					$from = next($Data);
					$to = $from + $len; }
			} else $res.= $STR{$i}; }
		$STR = $res; }
	return $STR; }
#@ Разрешение использовать только символы из набора
function stay(& $STR, $SET) {
	if ( ! are::str($SET) ) return cant;
		else if ( ! she($STR, $res) ) return lie($STR, str);
	foreach ( dict($STR, $SET) as $pos => $key ) $res.= $SET[$key];
	return done($STR = $res); }
#@ Разбить текст по символам
function by($X, $DIV, $CLEAR = no) {
	if ( ! we($DIV) ) return is_array($DIV) ? array($X) : cant;
		else $t = new tree(u($X, array_shift($DIV)));
	if ( ! the($t) ) return cant; else $Arr = $t->arr();
	while ( we($DIV) ) {
		$t = new tree($Arr);
		$t->push(to::u($t->V(), array_shift($DIV)));
		$Arr = $t->arr();
		unset($t); }
	return $CLEAR ? to::trim($Arr) : $Arr; }
#@ Разбить текст по группам
function live($STR, $G1) {
	$Grp = to::ta(slice(func_get_args() ,1, _, yes));
	for ( $pos = 0, $l = strlen($STR); $pos < $l; $pos++) {
		list ($ch, $k) = of($STR{$pos}, 0);
		foreach ( $Grp as $i => $Data ) {
			if ( here($ch, $Data) ) {
				$k = $i;
				break; }}
		$Res[$k][$pos] = $ch; }
	return kind($Res, _); }
#@ Мульти-функция проверки и извлечения символа
function he($SX, $POS, & $CHAR = null) {
	if ( ! ok($ans, str(my_text($SX, cant), 1) ) ) return $ans;
		else if ( ! ok($i, o2i($POS, $SX)) ) return $i;
			else return done($CHAR = $SX{$i}); }
#@ Получение следующих нескольких символов
function that($SX, $POS, $LEN = skip, & $TEXT = null) {
	if ( ! ok($ans, str(my_text($SX, cant), 1) ) ) return $ans;
		else $lx = strlen($SX);
	if ( ! ok($p1, o2i($POS, $lx)) ) return $p1;
	if ( skip($LEN) ) list ($LEN, $l) = of($l + 1, 1);
		else if ( zero($LEN) ) list ($LEN, $l) = of(re($l + 1), 1);
			else $l = ab($LEN);
	if ( ! ok($p2, jump($p1, $LEN, $lx, yes)) ) return $p2;
	$is_inv = high($p1, $p2);
	$str = cut(substr($SX, $p1, us($p1, $p2)), $is_inv ? 1 : -1);
	return str($TEXT = $str, $l); }
#@ Получение среза строки
function they($SX, $P1, $P2, & $TEXT = null) {
	if ( ! ok($ans, str(my_text($SX, cant), 1) ) ) return $ans;
		else $lx = strlen($SX);
	if ( ! ok($p1, o2i($P1, $lx)) ) return $p1;
		else if ( ! ok($p2, o2i($P2, $lx)) ) return $p2;
	if ( $p1 == $p2 ) return ! done($TEXT = str);
		else high($p1, $p2);
	return done($TEXT = substr($SX, $p1, $p2 - $p1 + 1)); }
#@ {?} Есть ли результат по этим смещениям?
function saw($MX, $TXT, $RES = skip, & $WORD = str) {
	if ( ! itext($TXT) ) return cant;
		else $Map = kind(uniq(to::o2i((array) $MX, $TXT)));
	if ( ! c($Map) ) return lie($Map); else $WORD = str;
	foreach ( $Map as $i ) if ( is($i) ) $WORD.= $TXT{$i};
	return skip($RES) || $WORD === text($RES); }
#@ {?} Строка содержит символы из этого набора
function cont($X, $SEQ, & $MISS = array()) {
	if ( ! ilen($X, $l) ) return cant;
		else if ( ! ok($r, we($SEQ)) ) return $r;
			else if ( $l == 0 ) return no;
				else $MISS = array();
	for ( $i = 0; $i < $l; $i++ )
		if ( ! here($X{$i}, $SEQ) ) $MISS[$i] = $X{$i};
	return ! we($MISS); }
