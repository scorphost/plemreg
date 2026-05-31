<?php /* Специальные обработчики */
#@ Трехмерный массив как однорядник
function roll($AX, $V_ONLY = no, $PROC = skip) {
	$Nodes = $Map = $Keys = $Res = array();
	$i = $j = $c = 0;
	$Nodes[$j++] = & $AX;
	do {
 		foreach ( $Nodes[$i] as $eachKey => $eachVal ) {
			if ( ! is_array($eachVal) || (! $V_ONLY && empty($eachVal)) ) {
				if ( 0 == $i ) $Res[$eachKey] = $eachVal;
			} else {
				$Nodes[$j] = & $Nodes[$i][$eachKey];
				$Map[$j] = $i;
				$Keys[$j++] = $eachKey; }}
	} while( array_key_exists(++$i, $Nodes) );
	unset($Nodes[0]);
	#`
	foreach ( $Nodes as $eachPtr => $EachSubNode ) {
		foreach ( $EachSubNode as $eachKey => $eachVal ) {
			$allow = ! is_array($eachVal) || (! $V_ONLY && empty($eachVal));
			if ( $allow ) {
				$path = strrev($eachKey). slash. strrev($Keys[$eachPtr]);
				$up = $eachPtr;
				while ( $Map[$up] != 0 ) {
					$path.= slash. strrev($Keys[ $Map[$up] ]);
					$up = $Map[$up]; }
				$Res[ strrev($path) ] = $eachVal; }}}
	if ( ! skip($PROC) ) $PROC ? ksort($Res) : krsort($Res);
	return $Res; }

function coil($ROLL) {
	$Res = array();
	foreach ( $ROLL as $k => $v ) {
		list ($Acc1, $Keys) = of(array(), u($k, slash));
		foreach ( $Keys as $i => $e ) {
			unset($Link);
			$Acc2 = $Acc1;
			$Acc1[] = $e;
			if ( 0 == $i ) {
				if ( ! ke($e, $Res) ) $Res[$e] = array();
				continue;
			} else $j = j($Acc1, slash);
			$Link = & Bind($Res, $j, $fake);
			if ( ! $fake ) continue;
			$Link = & Bind($Res, j($Acc2, slash), $fake);
			$Link[$e] = array(); }
		$Link = & Bind($Res, $j);
		$Link = $v;
		unset($Link); }
	return $Res; }
#@ Процессор массивов
class arr {
	public static function __callStatic($QX, $ARGS) {
		if ( ! str($QX, 2) ) return cant;
			else list($SQ, $PQ) = of(substr($QX, 0, 2), substr($QX, 2));
		list ($Arr, $px1, $px2) = safe($ARGS, 3);
		if ( ! we(so($Map, map($Arr))) ) return poor($Map);
			else list ($Keys, $in, $cnt) = of(ak($Arr), '++', cnt($Arr));
		#`
		if ( 'my' == $SQ ) $SQ{1} = '*'; else if ( 'cc' == $SQ ) $SQ = '-*';
		#`
		for ( $i = 0; $i < 2; $i++ ) {
			list ($p, $k, $dup) = of('px'.($i + 1), 'kx'.($i + 1), no);
			switch ( $SQ{$i} ) {
				case '*': break;
				case '_':#`
					if ( 0 == $i ) { reset($Arr); $$k = key($Arr); }
						else { end($Arr); $$k = key($Arr); }
					break;
				case 'k': #`
					if ( non(so($ans, clue($$p, $Arr))) ) {
						if ( no($ans) ) {
							$SQ{$i} = '_'; $in{$i--} = '-'; continue;
						} else return cant;
					} else $$k = $$p;
					break;
				case 'l': #`
					if ( 0 == $i || ! inat($px2) || $px2-- < 1) return cant;
					$SQ{$i--} = 'j';
					break;
				case 'j': #`
					if ( 0 == $i || ! inat($px2) ) return cant;
					if ( $in{0} != '-' ) {
						$idx = '+' == $in{0} ? $Map[$kx1] : $px1;
						$res = jump($idx, $px2, $Arr, yes);
						if ( no($res) ) $in = '--'; else $$k = $Keys[$res];
					} else $in{1} = '-';
					break;
				case 'p': #`
					if ( ! ok($res, nat($$p)) || $res-- < 1 ) return cant;
					$$p = $res; $SQ{$i--} = 'o';
					break;
				case 'o': #`
					if ( non(so($res, o2i($$p, $Arr))) ) {
						if ( no($res) ) { $SQ{$i} = '_'; $in{$i--} = '?'; }
							else return cant;
					} else $$k = $Keys[$res];
					break;
				#`
				case 'e': $dup = yes;
				case 'v':
					$Res = scan($$p, $Arr, $dup);
					if ( ! isset($Res[$i]) ) {
						$SQ{$i} = '_'; $in{$i--} = '-'; }
							else $$k = $Res[$i];
				break;
				#`
				case 'n': $dup = yes;
				case 'w':
					if ( 0 == $i ) return cant;
					if ( '+' == $in{0} && in_array($SQ{0}, u('e,v')) ) {
						list ($Res, $idx) = of(scan($$p, $Arr, $dup), null);
						foreach ( $Res as $idx => $res )
							if ( $res == $kx1 ) { $idx++; break; }
						if ( clue($idx, $Res) ) $kx2 = $Res[$idx];
							else { $SQ{$i} = '_'; $in{$i--} = '-'; }
					} else $SQ{$i--} = $dup ? 'e' : 'v';
					break;
				#`
				case 'x': $dup = yes;
				case 'z':
					if ( 0 == $i ) return cant;
					if ( '+' == $in{0} && in_array($SQ{0}, u('e,v')) ) {
						list ($Res, $idx) = of(scan($$p, $Arr, $dup), null);
						foreach ( $Res as $idx => $res )
							if ( $res == $kx1 ) { $idx++; break; }
						if ( isset($idx) ) {
							$Res = slice($Res, $idx); $res = end($Res);
							if ( is($res) ) { $kx2 = $res; break; }}
							$SQ{$i} = '_'; $in{$i--} = '-';
					} else $SQ{$i--} = $dup ? 'e' : 'v';
					break;
				#`
				case 'm':
					if ( ! inat($px1) ) return cant;
					if ( abs($px1) >= $cnt ) {
						list ($kx1, $kx2) = of($Keys[0], end($Keys));
							break; }
						else if ( $px1 == 0 ) { $in = '--'; break; }
					if ( $px1 < 0 ) {
						$kx1 = $Keys[$cnt - abs($px1)]; $kx2 = end($Keys);
					} else { $kx1 = reset($Keys); $kx2 = $Keys[$px1 - 1]; }
					break;
				#`
				case '-':
					if ( skip($px2) ) $px2 = 0;
					if ( ! inat($px1, poz) || ! inat($px2, poz) ) return cant;
					if ( ($px1 + $px2) >= $cnt ) { $in = '--'; break; }
					$kx1 = $Keys[$px1];
					$kx2 = $Keys[$cnt - $px2 - 1];
					break;
				default: return cant;
			}
		}
		#`
		if ( '??' == $in ) return eqp($px1, $px2) ? array() : $Arr;
			else if ( '--' == $in ) return array();
		list ($ix1, $ix2) = of($Map[$kx1], $Map[$kx2]);
		if ( so($is_rev, $ix1 > $ix2) ) swap($kx1, $kx2);
		$ix0 = $Map[$kx1];
		$cx = $Map[$kx2] - $ix0 + 1;
		$Data = slice($Arr, $ix0, $cx, yes);
		#`
		for ( $i = 0, $l = strlen($PQ); $i < $l; $i++ ) {
			$keep = no; $o = 0;
			switch ( $PQ{$i} ) {
				case 'N':
					if ( $is_rev ) $Data = array_reverse($Data, $keep);
					break;
				case 'I': $keep = yes;
				case 'R': $Data = array_reverse($Data, $keep); break;
				case 'K': $Data = array_keys($Data); break;
				case 'V': $Data = array_values($Data); break;
				case 'O': $Data = map($Data); break;
				case 'P': $Data = to::inc(map($Data)); break;
				case 'S': shuffle($Data); break;
				case 'U': sort($Data); break;
				case 'D': rsort($Data); break;
				case 'T': asort($Data); break;
				case 'B': arsort($Data); break;
				case 'H': ksort($Data); break;
				case 'L': krsort($Data); break;
				case 'C': $Data = arr::cc($Data, 1, 1); break;
				case 'D':
					return array($ix0, $cx, $ix1, $ix2, $kx1, $kx2); break;
				case 'Y': $o = 1;
				case 'X':
					foreach ( $Data as $k => & $v ) {
						if ( ! clue($k, $Map) ) return cant;
							else $v = $Map[$k] + $o; }
					break;
				case 'M':
					list ($Mixed, $Clone, $Data)
						= of(ak($Data), $Data, ax());
					shuffle($Mixed);
					foreach ( $Mixed as $k ) $Data[$k] = $Clone[$k];
					break;
				default: return cant;
			}
		}
		return $Data; }}
