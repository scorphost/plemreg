<?php
## a,`1;b,`;c,`+;d,`-;e,`/(массив);f,`&1отсылка на первый аргумент);
function xarr($COD) {
	list ($n, $Res, $Data) = of(func_num_args(), array(), by($COD, ch(';,')));
	foreach ( an($Data) as $KeyVal ) {
		list ($k, $v) = $KeyVal;
		if ( saw(0, $v, '`') ) {
			$v0 = cut($v, 1);
			if ( ! inum($v0, no, yes) ) {
				switch ($v0) {
					case str: $v = null; break;
					case '-': $v = no; break;
					case '+': $v = yes; break;
					case '/': $v = array(); break;
					default:
						if ( saw(0, $v0, '&') && str($v0, 2) ) {
							$v1 = cut($v0, 1);
							if ( inat($v1, pol) && $n >= $v1 )
								$v = func_get_arg($v1); }
						$v = $v0; }
			} else $v = $v0; }
		if ( l($k) ) $Res[$k] = $v; else $Res[] = $v; }
	return $Res; }

#@ препарсинг и вывод
/*
{{{{16/17}}}} - просто вывод элемента с ключом [16][17]
{{&{{16}}%%{{17}}%%{{18}}}} - вывод с дилеммой (первый истина/ложь)
{{?{{16}}%%{{17}}}} - вывод с альтернативой (если пустое)
{{!{{16}}%%{{17}}}} - вывод 17 если 16 не пустое
{{:{{16}}}} - вывод с обратным вызовом
{{+{{16}}%%()}} - вывод с экранированием в случае знакоместа
{{>{{16}}%%, }} - вывод с удлиннением в случае знакоместа
{{<{{16}}%%-}} - вывод с префиксом в случае знакоместа
{{-{{16}}%%,}} - вывод с подгонкой до запятой к соседям если ничего нет
{{@{{16}}%%,}} - разворачивание массива, подстановка ключей через запятую
{{*{{16}}%%<b>text</b>}} - вывод текста через результат ACL
*/
function tell($RAW, $DATA, & $SEQ = array()) {
	if ( ! arr($SEQ) ) $SEQ = ax();
	while ( done($XY = array()) ) {
		list ($Opn, $Clo) = to::seek(of('{{', '}}'), $RAW);
		if ( empty($Opn) ) return $RAW;
		foreach ( $Clo as $pos ) {
			$y1 = o2v(-1, sel::less($Opn, $pos));
			$XY[ $Opn[$y1] ] = $pos;
			unset($Opn[$y1]); }
		while ( we($XY) ) {
			$x2 = reset($XY);
			$x1 = key($XY);
			list ($pos, $cut) = of($x1 + 2, str);
			$SEQ[] = $seq = substr($RAW, $pos, us($pos, $x2 - 1));
			switch ( see(0, $seq) ) {
				case '&':
					list ($d1, $d2, $d3) = safe(explode('%%', $seq), 3, str);
					$bind = & bind($DATA, cut($d1, 1), $fake);
					if ( $fake ) $text = str; else $text = $bind ? $d2 : $d3;
					break;
				case '?':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$d1 = cut($d1, 1);
					$text = miss($d1) ? $d2 : $d1;
					break;
				case '=':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$d1 = cut($d1, 1);
					$text = miss($d1) ? str : $d2;
					break;
				case '+':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$text = cut($d1, 1);
					if ( some($text) ) $text = $d2{0}. $text. $d2{1};
					break;
				case '>':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$text = cut($d1, 1);
					if ( some($text) ) $text.= $d2;
					break;
				case '<':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$text = cut($d1, 1);
					if ( some($text) ) $text = $d2. $text;
					break;
				case '-':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$text = cut($d1, 1);
					if ( miss($text) ) $cut = $d2;
					break;
				case '@':
					list ($d1, $d2) = safe(explode('%%', $seq), 2, str);
					$d1 = cut($d1, 1);
					$bind = & bind($DATA, $d1, $fake);
					$d4 = roll($bind);
					$d5 = ax();
					foreach ( ak($d4) as $d3 )
						$d5[] = wear(j(of($d1, $d3), slash), '{', 2);
					$text = j($d5, $d2);
					break;
				case ':':
					$d1 = by($seq, ch('^,'));
					if ( ! ke(1, $d1) ) $d1[1] = array();
					if ( end($d1[1]) === dot(2) ) {
						$d1[1][ key($d1[1]) ] = $RAW;
						$d1[1][] = $DATA; }
						$text = cufa(cut(solo($d1[0]), 1), $d1[1]);
					break;
				default:
					$bind = & bind($DATA, $seq, $fake);
					$text = to_text($bind); }
			list ($l0, $l1) = of(len($seq, 4), strlen($text));
			$Part = two($RAW, $x1);
			$Part[1] = substr($Part[1], $l0);
			unset($XY[$x1], $p1);
			if ( some($cut) ) {
				if ( ok($p1, o2v(-1, seek($cut, $Part[0]))) ) {
					$sh = re(len($Part[0], -$p1));
					$Part[0] = cut($Part[0], $sh);
					list ($_XY1, $_XY2) = of(ak($XY), av($XY));
					foreach ( $_XY1 as $i => $p1 ) {
						if ( $_XY1[$i] > $x1 ) $_XY1[$i]+= $sh;
						if ( $_XY2[$i] > $x1 ) $_XY2[$i]+= $sh; }
					$XY = array_combine($_XY1, $_XY2); }}
			$RAW = $Part[0]. $text. $Part[1];
			if ( so($sh, most($l1, $l0)) != 0 ) {
				list ($_XY1, $_XY2) = of(ak($XY), av($XY));
				foreach ( $_XY1 as $i => & $p1 ) {
					if ( $p1 > $x2 ) $p1+= $sh;
					if ( $_XY2[$i] > $x2 ) $_XY2[$i]+= $sh; }
				$XY = array_combine($_XY1, $_XY2); }}}}

function a2x($AK, $AV = skip) {
	$Res = ax();
	if ( skip($AV) ) foreach ( $AK as $k => $v ) $Res[] = "${k},${v}";
		else foreach ( $AK as $k => $v ) $Res[] = $v. ','. who($k, $AV);
	return j($Res, ';');
}
