<?php /* Инструменты для работы со сообщениями */
#@ {"} Экранирование текста
function wear($X, $SEQ = '(', $CNT = 1) {
	if ( itext($X) && inum($CNT, pol) ) $rcov = $lcov = str; else return cant;
	if ( ! l(my_text($SEQ)) ) return $X;
	list ($plen, $SEQ) = of(strlen(chr_mirror), str_repeat($SEQ, $CNT));
	foreach ( str_split($SEQ, 1) as $char ) {
		$lcov.= $char;
		$rcov.= ok($pos, strpos(chr_mirror, $char))
			? substr(chr_mirror, $plen - $pos - 1, 1) : $char; }
	return $lcov. $X. $rcov; }
#@ Экранирование различными кавычками
function q($X) { return wear($X, q); }
function qq($X) { return wear($X, qq); }
#@ Составление на основе пробела
function sp($X, $A = spc) { if ( itext($X) ) return itext($A) ? $X. $A : $X; }
function dd($X, $SPC = yes) { return $SPC ? sp($X, ': ') : sp($X, ':'); }
function qs($X, $C = 1) { return wear($X, spc($C)); }
function ss($X, $C = 1) { return spc($C). $X; }
#@ {"} Удлиннение строки
function long($X, $Y, $BTW = ' ', & $PLEN = null) {
	if ( done($PLEN = null) && ! is_string($X) ) return cant;
		else if ( ! mean($Y, $BTW) ) return $X;
	if ( ! l($Y) && done($PLEN = 0)) return $X; else if ( ! l($X) ) $BTW = str;
	$X.= $BTW. $Y;
	$PLEN = strlen($BTW) + strlen($Y);
	return $X; }
#@ {"} Расширение текста
function post($X, $TEXT, $PRE = spc, & $PLEN = null) {
	if ( ! itext($X) ) return cant; else $len = strlen($X);
	$res = mean($TEXT, $PRE) && l($TEXT) ? $X. $PRE. $TEXT : $X;
	$PLEN = strlen($res) - $len;
	return $res; }
#@ {"} Префиксирование текста
function pre($X, $PRE, $CHECK_EXISTS = no) {
	if ( ! mean($X, $PRE) ) return cant;
		else if ( fail::l($X, $PRE) ) return $X;
	return $CHECK_EXISTS && you($PRE, $X, yes) ? $X : $PRE . $X; }
#@ {"} Посмотреть результат по этим смещениям
function see($MX, $TXT) {
	if ( ! itext($TXT) ) return cant;
		else $Map = kind(uniq(to::o2i((array) $MX, $TXT)));
	if ( ! c($Map) ) return arr($Map) ? str : cant;	else $word = str;
	foreach ( $Map as $i ) if ( is($i) ) $word.= $TXT{$i};
	return $word; }
#@ {"} Посмотреть результат без этих смещений
function bye($MX, $TXT) {
	if ( ! itext($TXT) ) return cant;
		else $Map = kind(uniq(to::o2i((array) $MX, $TXT)));
	burn($Map, null, no);
	if ( ! ok($ret, we($Map)) ) return on($ret, $TXT);
		else array_unshift($Map, -1);
	list($Map[], $Len, $res) = of(strlen($TXT), array(), str);
	for ( $i = 1, $j = 0, $c = count($Map); $i < $c; $i++, $j++) {
		$far = $Map[$i] - $Map[$j];
		if ( $far > 1 ) {
			$n = $Map[$j] + 1;
			$Len[] = $far > 2 ? array($n, us($n, $Map[$i] - 1)) : $n; }}
	foreach ( $Len as $i )
		$res.= we($i) ? substr($TXT, $i[0], $i[1]) : $TXT{$i};
	return $res; }
#@ Обрезать текст на определенное количество символов
function cut($TXT, $N1 = 1) {
	if ( ! itext($TXT) || ! inat($N1) ) return cant;
		else $Seq = array();
	if ( func_num_args() > 2 ) {
		$n2 = func_get_arg(2);
		if ( ! inat($n2, poz) || $N1 < 0 ) return cant;
	} else $n2 = 0;
	if ( $N1 < 0 ) swap($N1, $n2);
	if ( $N1 != 0 ) $Seq = array_merge($Seq, row($N1, 0));
	if ( $n2 != 0 ) $Seq = array_merge($Seq, row(abs($n2), -1, -1));
	return strlen($TXT) > count($Seq) ? bye($Seq, $TXT) : str; }
#@ Вырезать от-до
function snip($X, $LP, $RP, $NEXT = no, $CASE = no) {
	if ( ! she($X) ) return poor($X, str);
	$fx1 = $CASE ? 'strpos' : 'stripos';
	if ( ! $NEXT ) $fx2 = $CASE ? 'strrpos' : 'strripos'; else $fx2 = $fx1;
	if ( set($LP) ) {
		if ( she($LP) )$p0 = (int) $fx1($X, $LP); else return cant;
	} else $p0 = 0;
	if ( set($RP) ) {
		if ( ! she($RP) ) return cant;
		$p1 = nat($NEXT ? $fx2($X, $RP, let(zero($p0), $p0, $p0 + 1))
			: $fx2($X, $RP));
	} else $p1 = null;
	if ( zero($p0) && un($p1) ) return $X;
		else if ( $p0 === $p1 ) return str;
	return isset($p1) ? substr($X, $p0, us($p0, $p1)) : substr($X, $p0);
}
#@ Вставить строку в строку
function into(& $STR, $X, $POS = 0) {
	if ( ! ilen($STR, $ls) || 0 == $ls || ! itext($X) ) return cant;
		else if ( ! l($X) ) return no;
	if ( ! ok($i, o2i($POS, $STR)) ) return $i;
		else $Part = two($STR, $POS);
	return done($STR = implode(str, of($Part[0].$X, $Part[1]))); }
#@ Отредактировать текст
function edit(& $STR, $X, $POS = 0) {
	if ( ! ilen($STR, $ls) || 0 == $ls || ! itext($X) ) return cant;
		else if ( ! l($X) ) return no;
	if ( ! ok($i, o2i($POS, $STR)) ) return $i;
		else $Part = two($STR, $POS);
	return done($STR = implode(str, of($Part[0]
		, $X. cut($Part[1], strlen($X)) ))); }
#@ Собрать строку после разбора и редактирования
function say($AX, $SEQ, $RULE = 'noop') {
	if ( ! call($RULE) ) return cant;
	if ( ! we($AX) ) return bad($AX, _, str); else $def = str;
	if ( ! arr($SEQ) ) {
		if ( itext($SEQ) ) $SEQ = array($def = $SEQ); else return cant;
	} else if ( ! ok($SEQ, to::text($SEQ)) ) return cant;
	$char = reset($SEQ);
	if ( flat($AX) ) return are::text($AX) ? implode($char, $AX) : cant;
	do {
		$t = new tree($AX);
		$Lvl = $t->L();
		if ( ! isset($max) ) $max = max($Lvl);
		list($i, $Res) = of(0, array());
		foreach ( sel::eq($Lvl, $max) as $idb ) {
			list ($Trace, $Keys) = of($t->trace($idb), array());
			array_pop($Trace);
			foreach ( $Trace as $idt ) $Keys[] = $t->K($idt);
			$k = strrev(implode(slash, $Keys));
			if ( ! ke($k, $Res) ) $Res[$k] = $i++; }
		foreach ( ak($Res) as $k ) {
			$Text = array();
			$Node = & bind($AX, $k, $fake);
			if ( $fake ) return cant;
			foreach ( $Node as $val ) {
				if ( ! itext($val) ) {
					if ( ! is_array($val) ) return cant;
				} else $Text[] = $val; }
			$Node = implode($char, $RULE($Text, $max));
			unset($Node); }
		$char = bad(next($SEQ), $def);
		unset($t);
	} while ( --$max > 1 );
	$Text = array();
	foreach ( $AX as $val ) if ( itext($val) ) $Text[] = $val;
	return implode($char, $RULE($Text, 1)); }
#@ Вывод через sprintf
function val($PAT, $TEXT, $MISS = str, $NO = str) {
	if ( ! inews($PAT) ) return poor($PAT, str);
	if ( ! itext($TEXT) ) $res = to_text($NO);
		else if ( ! l($TEXT) ) $res = to_text($MISS);
	if ( isset($res) ) {
		if ( str($res, 2)  && see(of(0, 1), $res) == dot(2) )
			list ($PAT, $res) = of(str, cut($res, 2));
	} else $res = $TEXT;
	return l($PAT) ? sprintf($PAT, $res) : $res; }
#@ Выкачать данные со всех возможных источников
function src($X) {
	if ( call($X) ) $X = to_text($X());
	if ( ! info($X) ) {
		switch ( ctp($X) ) {
			case 'a': if ( isolo($X) ) $res = src($X); break;
			case 'o': if ( method_exists($X, 'toString')) $res = ts($X); break;
			case 'r': $res = @stream_get_contents($X); break;
			default: $res = str; }
	} else $res = $X;
	return to_text($res); }
#@ Расширенная версия sprintf
function spr() {
	$Args = func_get_args();
	$Tmpl = array_shift($Args);
	return vsprintf($Tmpl, $Args); }
function ispr(& $TMPL) {
	$Args = func_get_args();
	return $TMPL = vsprintf($TMPL, slice($Args, 1)); }
#@ Расширенная версия implode
function sj($AX, $SEP = str, $AND_MISS = no) {
	if ( ! we($AX) ) return arr($AX) ? str : cant;
		else if ( ! itext($SEP) ) return cant;
			else $ax = to::text($AX);
	$Res = $AND_MISS ? own::some($ax) : own::len($ax);
	return implode($SEP, $Res); }

function code() {
	$res = str;
	foreach ( func_get_args() as $arg )
		if ( ! itext($arg) || ! l($arg) ) continue;
		 	else $res.= $arg. sp(';');
	return lone($res, ';'); }
