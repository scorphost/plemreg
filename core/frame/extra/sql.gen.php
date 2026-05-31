<?php
## @table2;s.15,!user,,User name;*s.30,city+, ,Location;i,acl,0
function xntc($RAW, $DB, $TBL, & $UNDO = null) {
	$wb = new buf(sp(','));
	$wb->p(spr('CREATE TABLE `%s`.`%s` (', $DB, $TBL));
	$wb->p('`id` INT(11) NOT NULL AUTO_INCREMENT,'
		.'`time_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	$wb->cr();
	foreach ( explode(';', $RAW) as $row ) {
		list ($tp, $name, $def, $rem) = safe(u($row), 4, str);
		if ( see(0, $tp) == '@' ) {
			$wb->p(spr('`%s` INT(11) NOT NULL COMMENT \'@%1$s.id\''
				, cut($tp, 1)));
			$wb->cr();
			continue;
		} else list ($type, $len) = safe(u($tp, dot), 2, str);
		if ( $is_null = see(0, $type) == '*' ) $type = cut($type, 1);
		if ( 's' == $type ) {
			$type = 'VARCHAR';
			if ( miss($len) ) $len = 255;
		} else if ( 'i' == $type ) {
			$type = 'INT';
			if ( miss($len) ) $len = 11;
		} else if ( 't' == $type ) {
			$type = 'TEXT';
			$len = str; }
		if ( $is_uniq = see(0, $name) == '!' ) $name = cut($name, 1);
		if ( $is_def = see(-1, $name) == '+' ) $name = cut($name, -1);
		$wb->p("`$name`", spc);
		if ( l($len) ) $len = wear($len);
		$wb->p($type. $len);
		if ( ! $is_null ) $wb->p('NOT');
		$wb->p('NULL');
		if ( $is_uniq ) $wb->p('UNIQUE');
		if ( l($def) || $is_def )  $wb->p(spr("DEFAULT '%s'", $def));
		if ( l($rem) )  $wb->p(spr("COMMENT '%s'", $rem));
		$wb->cr();
	}
	$wb->p('PRIMARY KEY (`id`)');
	$wb->ed();
	$wb->p(') ENGINE = InnoDB;');
	$res = $wb->z();
	unset($wb);
	$UNDO = spr('DROP TABLE IF EXISTS `%s`.`%s`;', $DB, $TBL);
	return $res; }

class xsql {
	public static function sel($TBL, $FLDS = skip) {
		static $tmpl = 'SELECT %s FROM `%s`';
		$Res = ax();
		if ( we($FLDS) )
			foreach ( $FLDS as $field ) $Res[] = "`${TBL}`.`${field}`";
		return spr($tmpl, we($Res) ? j($Res, ',') : '*', $TBL);
	}
	public static function get($FLDS, $VAL = no, $TBL = skip, $UID = 'id'
		, $TAIL = str )
	{
		$wb = new buf(',');
		$wb->p('SELECT', spc);
		if ( we($FLDS) ) {
			foreach ( $FLDS as $fld ) {
				$wb->p(spr("`$TBL`.`%s`", $fld));
				$wb->cr(); }
		} else if ( no($FLDS) ) {
			$Data = DB::run("EXPLAIN ${TBL}");
			foreach ( $Data as $i => $Fields ) {
				$fld = $Fields['Field'];
				$wb->p(spr("`$TBL`.`%s`", $fld));
				$wb->cr(); }
		} else if ( skip($FLDS) ) $wb->p('*');
			else if ( yes($FLDS) ) $wb->p(idk);
				else if ( iword($FLDS) ) $wb->p("`$TBL`.`${FLDS}`");
		$wb->ed(spc);
		$wb->p("FROM `$TBL`");
		if ( we( $VAL ) ) {
			$wb->p("WHERE `$TBL`.`$UID` IN (");
			foreach ( $VAL as $eid ) {
				$wb->p(q($eid));
				$wb->cr(); }
			$wb->ed(spc);
			$wb->p(')');
		} else if ( itext($VAL) ) {
			$wb->p(spr("WHERE `$TBL`.`%s`=%s", $UID, q($VAL)));
		} else if ( skip($VAL) ) {
			$wb->p("WHERE `$TBL`.`${UID}` IS NULL");
		}
		if ( yes($VAL) ) {
			if ( iword($TAIL) ) $wb->p("WHERE ${TAIL}");
				else $wb->p('WHERE %s');
		} else {
			if ( yes($TAIL) ) $wb->p(idk);
				else if ( iword($TAIL) ) $wb->p($TAIL);
		}
		return $wb->z(); }
// UNDO: DELETE FROM `users` WHERE `users`.`id` = 1
	public static function ins($FLDS, $TBL = skip) {
		static $tbl = null;
		$tbl = hello($tbl, $TBL);
		list ($Flds, $Vals) = of(ak($FLDS), av($FLDS));
		fresh($Flds, to::wear($Flds, '`'));
		fresh($Vals, to::q(own::text($Vals)));
		put($Vals, sel::un($Vals), 'NULL');
		$sql = spr('INSERT INTO `%s` (%s) VALUES (%s)', $tbl
			, j($Flds, sp(',')), j($Vals, sp(',')));
		// $last_id = $dbh->insert_id;
		return $sql;
	}
// UPDATE `users` SET `login` = 'ad2', `pass` = '123' WHERE `users`.`id` = 1
// UNDO - SELECT + (RE)UPDATE
	public static function upd($FLDS, $ID, $TBL = skip, $UID = 'id') {
		static $tbl = null;
		$tbl = hello($tbl, $TBL);
		$wb = new buf(',');
		$wb->p("UPDATE `$TBL` SET", spc);
		foreach ( $FLDS as $k => $v ) {
			$v = itext($v) ? $v = q($v) : 'NULL';
			$wb->p("`$k` = $v");
			$wb->cr(); }
		$wb->ed(spc);
		if ( we( $ID ) ) {
			$wb->p("WHERE `$TBL`.`$UID` IN (");
			foreach ( $ID as $eid ) {
				$wb->p(q($eid));
				$wb->cr(); }
			$wb->ed(spc);
			$wb->p(')');
		} else $wb->p("WHERE `$TBL`.`$UID` = '$ID'");
		$sql = $wb->z();
		unset($wb);
		return $sql; }
		public static function srch($X, $OR = no) {
			foreach ( $X as $k => & $v ) {
				if ( zl($v) ) {
					unset($X[$k]);
					continue;
				}
				do {
					$l = strlen($v);
					$v = str_replace(spc(2), spc, $v);
				} while ( $l != strlen($v) );
				$v = str_replace(spc, '%', $v);
				if ( '%' == $v ) {
					unset($X[$k]);
					continue;
				}
				$v = "`${k}` LIKE '${v}'";
			}
			return j($X, qs($OR ? 'OR' : 'AND'));
		}

}

class esql {
	private static function retQ($Q, $TYPE) {
		if ( skip($TYPE) ) return wear($Q);
			else return $TYPE ? " WHERE ${Q}" : $Q; }
	public static function ord($WX, $DSC = no) {
		return qs('ORDER BY'). sp(j(to::wear(a($WX), '`'), sp(',')))
			. ($DSC ? 'DESC' : 'ASC'); }
	public static function eqa($WX, $TYPE = no, $sign = eq, $w = 'AND') {
		list ($Fld, $Val, $Res) = of(to::wear(ak($WX),'`'),to::q(av($WX)),ax());
		foreach ( $Fld as $i => $v) $Res[] = $v. $sign. $Val[$i];
		return self::retQ(j($Res, spc. sp($w)), $TYPE); }
	public static function nea($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, ne); }
	public static function lta($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, lt); }
	public static function gta($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, gt); }
	public static function lea($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, le); }
	public static function gea($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, ge); }
	public static function eqo($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, eq, 'OR'); }
	public static function neo($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, ne, 'OR'); }
	public static function lto($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, lt, 'OR'); }
	public static function gto($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, gt, 'OR'); }
	public static function leo($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, le, 'OR'); }
	public static function geo($WX, $TYPE = no) {
		return self::eqa($WX, $TYPE, ge, 'OR'); }
	public static function hit($X, $LI, $RI, $TYPE = no) {
		return self::retQ( so($x, wear($X, '`')). ge. q(min($LI, $RI))
			. qs('AND'). $x. le. q(max($LI, $RI)), $TYPE); }
	public static function lim($X, $LI, $RI, $TYPE = no) {
		return self::retQ( so($x, wear($X, '`')). gt. q(min($LI, $RI))
			. qs('AND'). $x. lt. q(max($LI, $RI)), $TYPE); }
	public static function out($X, $LI, $RI, $TYPE = no) {
		return self::retQ( so($x, wear($X, '`')). lt. q(min($LI, $RI))
			. qs('AND'). $x. gt. q(max($LI, $RI)), $TYPE); }
	private static function join($ARGS, $OR = no) {
		if ( yes(so($last, last($ARGS, no))) ) {
			$res = qs('WHERE');
			array_pop($ARGS);
		} else $res = str;
		return $res. j($ARGS, qs($OR ? 'OR' : 'AND'));
	}
	public static function jand() { return self::join(func_get_args()); }
	public static function jor() {  return self::join(func_get_args(), yes); }
	public static function like($FLDS, $TBL) {
		foreach ( $FLDS as $fld => & $ptrn ) {
			if ( zl($ptrn) ) {
				unset($FLDS[$fld]);
				continue;
			}
			do {
				$l = strlen($ptrn);
				$ptrn = str_replace(spc(2), spc, $ptrn);
			} while ( $l != strlen($ptrn) );
			$ptrn = str_replace(spc, '%', $ptrn);
			if ( '%' == $ptrn ) {
				unset($FLDS[$fld]);
				continue;
			}
			$ptrn = "`${fld}` LIKE '${ptrn}'";
		}
		$sql = "SELECT * FROM `${TBL}`". qs('WHERE'). j($FLDS, qs('AND'));
		return $sql;
	}
}
