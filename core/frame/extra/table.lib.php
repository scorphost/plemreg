<?php
class grid {
	public static $Grid;
	public static $Row;
	public static $Col;
	public static $nod;
#: processors
	public static function cols($COL) {
		self::$Col = array();
		foreach ( self::$Grid as $row => $Line )
			for ( $i = 0; $i < $COL; $i++ )
				if ( 'x' == $Line[$i] ) self::$Col[$row][$last = $i] = 1;
					else if ( '+' == $Line[$i] ) self::$Col[$row][$last]++;
		foreach ( self::$Col as $row => & $Line ) {
			for ( $i = 0; $i < $COL; $i++ ) {
				if ( ! isset($Line[$i]) ) continue;
				if ( 1 == $Line[$i] ) {
					unset($Line[$i]);
					if ( empty(self::$Col[$row]) ) unset(self::$Col[$row]);}}}}
#`
	public static function make($AX, $COL = skip) {
		self::$Grid = self::$Row = $Swt = array();
		if ( skip($COL) ) $COL = max(to::cnt($AX));
		$cnt = count($AX) - 1;
		foreach ( $AX as $row => & $Line ) {
			for ( $i = 0; $i < $COL; $i++ ) {
				self::$Grid[$row][$i] = so($ok, ke($i, $Line))
					? 'x' : '+';
				if ( 0 == $i && ! $ok ) $Swt[] = $row; }
		}
		$NewAX = & $AX;
		foreach ( $Swt as $row ) {
			$i = $k = 0;
			do {
				self::$Grid[$row][$i] = self::$Grid[$row]
					[ $k = look('x', slice(self::$Grid[$row], $k, _, yes)) ];
				$NewAX[$row][$i] = $NewAX[$row][$k];
				unset($NewAX[$row][$k]);
				self::$Grid[$row][$k++] = '+';
				if ( $again = isset(self::$Grid[$row][$k])
					&& '+' == self::$Grid[$row][$k] ) $i = $k;
			} while ( $again );
			ksort($NewAX[$row]); }
		self::cols($COL);
		return $NewAX; }
	public static function full($AX, $VALT = str, $VUN = str, $VNKE = str) {
		$col = max(to::cnt($AX));
		self::$Col = array();
		$NewAX = & $AX;
		foreach ( $AX as $row => & $Line ) {
			for ( $i = 0; $i < $col; $i++ ) {
				self::$Grid[$row][$i] = 'x';
				if ( ke($i, $Line) ) {
					$cell = $Line[$i];
					if ( un($cell) ) $Line[$i] = to_text($VUN);
						else if ( ! inews($cell) ) $Line[$i] = to_text($VALT);
							else $Line[$i] = $cell;
				} else $Line[$i] = to_text($VNKE);
			}
			ksort($Line); }
		return $NewAX; }
	public static function pret($AX, $VALT = str, $VUN = str) {
		$col = max(to::cnt($AX));
		foreach ( $AX as $row => & $Line ) {
			foreach ( $Line as $i => $cell ) {
				if ( un($cell) ) $Line[$i] = to_text($VUN);
					else if ( ! inews($cell) ) $Line[$i] = to_text($VALT); }}
		return $AX; }
#: parsers
	## статическая на базе ручной схемы
	public static function hand($GRID) {
		if ( ! ok($ans, we($GRID)) ) return $ans;
			else list (self::$Grid, $col) = of($GRID, strlen(reset($GRID)));
		$c = cnt($GRID, -1);
		for ( $z = $c; $z >= 0; $z-- ) {
			for ( $i = $col - 1; $i >= 0; $i-- ) {
				if ( '^' == $GRID[$z]{$i} ) {
					$j = $z; $span = 2;
					while ( yes ) {
						if ( 0 == $j-- ) return cant;
							else if ( 'x' == $GRID[$j]{$i} ) break;
						$GRID[$j]{$i} = '.';
						$span++;
					}
					self::$Row[$j][$i] = $span;
				}
			}
		}
		self::cols($col);
		return done(self::$Grid = to::ch($GRID));
	}
	## динамическая на базе массива
	public static function auto($DATA, $HEAD = array(), $FOOT = array()) {
		if ( ! arr($DATA) ) return cant;
		if ( we($HEAD) ) {
			if ( so(self::$nod, ! we($DATA)) )
				$DATA[0][ lead($HEAD, yes) ] = 'No data!';
			array_unshift($DATA, $HEAD);
		} else {
			if ( ! we($DATA) ) {
				if ( ! arr($HEAD) ) return cant;
					else self::$nod = done($DATA[0][0] = 'No data!');
			} else self::$nod = no;
			array_unshift($DATA, array()); }
		$Cnt = to::cnt(pure($DATA));
		arsort($Cnt);
		$mk = key($Cnt);
		$Map = map($DATA[$mk]);
		if ( yes($HEAD) ) {
			if ( we($DATA) ) $DATA[0] = kx(ak($DATA[$mk])); else return cant; }
		if ( we($FOOT) ) $DATA[] = $FOOT;
		if ( empty($DATA[0]) ) unset($DATA[0]);
		$Res = array();
		foreach ( $DATA as $row => $Line ) {
			$Res[$row] = array();
			foreach ( $Line as $i => $j ) {
				if ( ke($i, $Map) )	$Res[$row][$Map[$i]] = $j; } }
		return self::make($Res, $Cnt[$mk]);
	}
}
#
class tbl {
	public static $Data; ## массив данных после генерации
	public static $Head; ## массив данных после генерации
	## Вывод в чистом html коде
	public static function html($ATTR = array()) {
		self::td(); self::tr(); self::cell();
		foreach ( self::$Data as $row => & $Line ) {
			$head = & $Line[0];
			$line = & $Line[1];
			foreach ( $Line as $i => $cell ) if ( $i > 1 ) $line.= $cell;
			$head = spr($head, $line);
			$Line = $Line[0]; }
			return tag::table(j(self::$Data, eol), no, $ATTR); }
	## задать дополнительные параметры для ряда i (tr)
	public static function tr($ATTR = str, $I = array()) {
		$rc = last(grid::$Grid, yes) + 1;
		if ( zero(cnt($I)) ) $I = row($rc + 2, 0);
		foreach ( a($I) as $row ) {
			$line = $row < 0 ? $rc - abs($row + 1) : $row;
			if ( isset(self::$Data[$line][0]) ) {
					self::$Data[$line][0] = spr(self::$Data[$line][0]
						, post(str, $ATTR, spc.idk), idk);
			}
		}
	}

	## задать дополнительные параметры для ячеек i,j (td)
	public static function td($V = str, $I = array(), $J = array(), $_a = no) {
		$rc = last(grid::$Grid, yes) + 1;
		$cc = count(reset(grid::$Grid)) + 1;
		if ( zero(cnt($I)) ) $I = row($rc, 0);
		if ( zero(cnt($J)) ) $J = row($cc, 0);
		foreach ( to::o2i(a($I), $rc) as $i ) {
			foreach ( to::o2i(a($J), $cc) as $j ) {
				if ( isset(self::$Data[$i][++$j]) ) {
					if ( $_a ) self::$Data[$i][$j] = spr(
						self::$Data[$i][$j], to_text($V));
					else self::$Data[$i][$j] = spr(
						self::$Data[$i][$j]
						, post(str, $V, spc.idk), idk); }}}}
	## Задать дополнительные значения для ячеек i,j
	public static function cell($VAL = str, $I = array(), $J = array()) {
		self::td($VAL, $I, $J, yes); }
	## Формирование подстановочной карты self::$Data
	public static function map($AX, $HEAD = array()) {
		if ( ! we($AX) ) return cant;
			else list(self::$Data, self::$Head) = of(ax(), $HEAD);
		foreach ( $AX as $row => $Line ) {
			$fx = $row == 0 ? 'th' : 'td';
			self::$Data[$row][0] = tag::tr(idk, no, yes);
			foreach ( $Line as $i => $cell ) {
				$Attr = array();
				if ( isset(grid::$Col[$row][$i]) && 1 != grid::$Col[$row][$i])
					$Attr['colspan'] = grid::$Col[$row][$i];
				if ( isset(grid::$Row[$row][$i]) && 1 != grid::$Row[$row][$i])
					$Attr['rowspan'] = grid::$Row[$row][$i];
				if ( 'x' == grid::$Grid[$row][$i] ) {
					$Attr = c($Attr) ? attr(_, _, _, yes, $Attr) : yes;
					if ( 0 == $row && clue($i, $HEAD) )
						$cell = $HEAD[$i];
					self::$Data[$row][] = tag::$fx($cell. idk, no, $Attr); }}}
	}
}

function tgap($HEAD, $VAL, $ONE = no, $ATTR = _) {
	$Data = ax();
	if ( $ONE ) $Data[0][ lead($HEAD, yes) ] = $VAL;
		else foreach ($HEAD as $i => $key) $Data[0][$i] = $VAL;
	tbl::map(grid::auto($Data, ak($HEAD)), $HEAD);
	if ( skip($ATTR) ) $ATTR = xarr('border,1');
	return tbl::html($ATTR);
}

/*
$x1 = '0,v10;1,v11;3,v13'; $ax1 = xarr($x1);
$x2 = '1,v21;2,v22;3,v23'; $ax2 = xarr($x2);
$x3 = '1,v31;3,v33'; $ax3 = xarr($x3);
$x4 = '0,v40;1,v41'; $ax4 = xarr($x4);
$x5 = '0,v50;1,v51;2,v52;3,v53'; $ax5 = xarr($x5);
$x6 = '3,v63'; $ax6 = xarr($x6);
$ax = array(1 => $ax1, $ax2, $ax3, $ax4, $ax5, $ax6);
// $r2 = tbl::map(grid::auto($ax));
$r2 = tbl::map(grid::auto(grid::align($ax, str, str, '-')));
// vd('ok!');
// $r3 = tbl::td('ok', 6, 1);
$r3 = tbl::td();
$r3 = tbl::tr();
$r3 = tbl::cell();
tbl::html();
*/
