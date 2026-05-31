<?php
/* Манипуляции с колонками таблицы */
class col {
	## Сдвиг колонки
	public static function sh($TBL, $COL, $KEY, $AFTER = -1) {
		list ($cr, $cc) = of(count($TBL), count($Row = ak(reset($TBL))));
		list ($Col, $i) = of(av(a($COL)), 0);
		$Stand = safe($Col, $cr, end($Col));
		$d = ( inum($AFTER) ? o2k($AFTER, $Row) : look($AFTER, $Row) ) + 1;
		if ( ! skip($AFTER) ) {
			foreach ( $TBL as $i => & $Line ) {
				list ($P1, $P2) = two($Line, $d);
				$Line = merge($P1, array($KEY => $Stand[$i++]), $P2); }
		} else {
			foreach ( $TBL as $Row => & $Line )
				$Line = merge(array($KEY => $Stand[$i++]), $Line); }
		return $TBL; }
	## Замена содержимого колонки
	public static function rep($TBL, $COL, $SRC, $REP) {
		$cc = count($Row = reset($TBL));
		$d = inum($COL) ? o2k($COL, $Row) : $COL;
		foreach ( $TBL as $i => & $Line )
			foreach ( $Line as $j => $cell)
				if ( $j === $d && ok($k, look($cell, $SRC)) )
					$Line[$j] = $REP[$k];
		return $TBL; }
	public static function upd($TBL, $COL, $UPD) {
		$cc = count($Row = reset($TBL));
		$d = inum($COL) ? o2k($COL, $Row) : $COL;
		foreach ( $TBL as $i => & $Line )
			foreach ( $Line as $j => $cell )
				if ( $j === $d  ) $Line[$j] = $UPD[$i. slash. $j];
		return $TBL; }
	public static function del($TBL, $COL) {
		$cc = count($Row = reset($TBL));
		foreach ( a($COL) as $col ) {
			$d = inum($col) ? o2k($col, $Row) : $col;
			foreach ( $TBL as $i => $Line )
				foreach ( $Line as $j => $cell)
					if ( $j === $d  ) unset($TBL[$i][$j]); }
		return $TBL; }
	public static function ren($TBL, $KX) {
		$Res = ax();
		foreach ( $TBL as $i => $Line ) {
			$Res[$i] = ax();
			foreach ( $Line as $j => $cell) $Res[$i][ subst($j, $KX) ] = $cell;
		}
		return $Res;
	}
	public static function flt($TBL, $MISS = str, $UN = str) {
		foreach ( $TBL as $i => & $Line ) {
			foreach ( $Line as $j => $cell) {
				if ( un($cell) ) $Line[$j] = to_text($UN);
					else if ( miss($cell) ) $Line[$j] = to_text($MISS);
			}
		}
		return $TBL;
	}
	## Подмена элемента в таблице
	public static function pod($TBL, $SRC, $REPL) {
		foreach ( $TBL as $i => & $Line ) {
			foreach ( $Line as $j => $cell) {
				if ( ok($k, look($cell, $SRC)) && clue($k, $REPL) )
					$Line[$j] = $REPL[$k];
			}
		}
		return $TBL;
	}
	public static function sli($TBL1, $TBL2) {
		foreach ( $TBL2 as $i => $Line )
			if ( ke($i, $TBL1) )
				foreach ( $Line as $j => $cell )
					if ( ke($j, $TBL1[$i]) ) $TBL1[$i][$j] = $cell;
		return $TBL1;
	}
	public static function gap($TBL, $COL, $KEY, $AFTER = skip) {
		list ($cr, $cc) = of(count($TBL), count($Row = ak(reset($TBL))));
		list ($i, $Gaps) = of(0, ax());
		if ( ! ok($d, inc(inum($AFTER) ? o2k($AFTER, $Row)
			: look($AFTER, $Row))) ) return $TBL;
		foreach ( a($KEY) as $k ) $Gaps[$k] = $COL;
		if ( ! skip($AFTER) ) {
			foreach ( $TBL as $i => & $Line ) {
				list ($P1, $P2) = two($Line, $d);
				$Line = merge($P1, $Gaps, $P2);
			}
		} else foreach ( $TBL as $Row => & $Line ) $Line = merge($Gaps, $Line);
		return $TBL; }
## Для чтения из одной колонки и запись в другую
	public static function io(& $TBL, $XY, $COL) {
		list ($x, $y) = u($XY, slash);
		$cell = & $TBL[$x][$COL];
		if ( func_num_args() < 4 ) return $cell;
			else return done($cell = func_get_arg(3));
	}
	## Ячейку упаковать в ссылку
	public static function c2l(& $TBL, $COL, $PURL, $CSRC) {
		foreach ( own::some(col($TBL, $COL)) as $xy => $val ) {
			$Res = array();
			foreach ( a($CSRC) as $src )
				$Res[] = col::io($TBL, $xy, $src);
			col::io($TBL, $xy, $COL
				, atag($val, vsprintf($PURL, $Res)));
		}
	}
	public static function c2v(& $TBL, $COL, $DATA) {
		foreach ( own::some(col($TBL, $COL)) as $xy => $val ) {
			col::io($TBL, $xy, $COL, who($val, $DATA));
		}
	}
	public static function sort($TBL, $COL, $DSC = no) {
		$Res = ax();
		foreach ( kind(col($TBL, $COL), yes, $DSC) as $xy => $val )
			$Res[] = $TBL[who(0, u($xy, slash))];
		return $Res;
	}
	public static function sold() {
		$Args = func_get_args();
		$Max = ax();
		foreach ( $Args as $k => & $arg ) {
			if ( ! arr($arg) ) $arg = ax();
			$Max[] = count($arg);
		}
		unset($arg);
		$max = max($Max);
		if ( 0 == $max ) return no; else $Res = ax();
		$AK = ak(reset($Args[ look($max, $Max) ]));
		foreach ( $Args as $j => $Data ) {
			$y = $j + 1;
			for ( $i = 0; $i < $max; $i++) {
				foreach ( $AK as $j => $k ) {
					if ( isset($Data[$i][$k]) ) {
						$Res[$i][$k. "_{$y}"] = $Data[$i][$k];
					} else {
						$Res[$i][$k. "_{$y}"] = null;
					}
				}
			}
		}
		return $Res;
	}
	public static function anm(& $TBL, $COL, $START = 1, $STEP = 1) {
		$x = $START;
		foreach ( col($TBL, $COL) as $xy => $val ) {
			col::io($TBL, $xy, $COL, $x);
			$x += $STEP;
		}
	}
}

// Получение колонки как обходного массива
function col($TBL, $COL) {
	$cc = count($Row = reset($TBL));
	$d = inum($COL) ? o2k($COL, $Row) : $COL;
	$Res = array();
	foreach ( $TBL as $i => $Line )
		foreach ( $Line as $j => $cell)
			if ( $j == $d ) $Res[ $i. slash. $j ] = $cell;
	return $Res; }

class cols {
	public static function __callStatic($FX, $ARGS) {
		list ($Tbl, $col) = take($ARGS, u('0,1'));
		foreach ($Tbl as $row => & $Line )
			if ( ke($col, $Line) ) $Line[$col]
				= cufa($FX, merge(of($Line[$col]), $ARGS));
		return $Tbl;
	}
}
