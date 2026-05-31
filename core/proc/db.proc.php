<?php
class id {
	protected static function ret($RES, $RET) {
		if ( ! we($RES) ) return no; else $Res = ax();
		foreach ( $RES as $Line) $Res[] = reset($Line);
		if ( ! arr($RET) ) return nat(o2v($RET, $Res));
			else if ( empty($RET) ) return to::nat($Res);
				else $Res2 = ax();
		foreach ( $RET as $which ) {
			$num = o2v($which, $Res);
			if ( inat($num, pol) ) $Res2[] = $num;
		}
		return $Res2;
	}
	// id из поля ( id <> id_user) [уникальный, порядковый]
	public static function ff($TBL, $ID, $IDF, $RET = array()) {
		$sql = xsql::get('id', $ID, $TBL, $IDF);
		return self::ret(DB::run($sql), $RET);
	}
	// id из условия ( id WHERE ) [уникальный, порядковый]
	public static function where($TBL, $COND, $RET = array()) {
		$sql = xsql::get('id', yes, $TBL, _, $COND);
		return self::ret(DB::run($sql), $RET);
	}
	// всех связанных с этим id (его тоже)
	public static function rel($TBL, $ID, $IDP = 'id_goat', $TAIL = no) {
		if ( yes($IDP) ) list($IDP, $TAIL) = of('id_goat', yes);
		$Data = self::where($TBL, "`${IDP}`='${ID}'");
		if ( ! we($Data) ) {
			if ( ! exst('id', $ID, $TBL) ) return no; else $Data = ax();
		}
		if ( $TAIL ) array_unshift($Data, $ID);
		return $Data;
	}
}

class DBA {
	public static $id_done = array();
	// удаление основной записи и всех ссылок в других таблицах
	public static function clean($ID, $TBLS, $IDS, $IDM = 'id') {
		$Tables = array_reverse($TBLS);
		$master = array_pop($Tables);
		foreach ( a($ID) as $id ) {
			foreach ( $Tables as $tbl ) {
				$sql = "DELETE FROM `${tbl}` WHERE `${IDS}`='${id}'";
				if ( ! ok($ans, DB::run($sql)) ) return no;
			}
			$sql = "DELETE FROM `${master}` WHERE `${IDM}`='${id}'";
			if ( ! ok($ans, DB::run($sql)) ) return no;
		}
		return $Res;
	}
	// редактирование (если есть)/добавление (если нет) строчки


	public static function line($ID, $FLDS, $TBL, $IDF = 'id') {
		if ( 'id' != $IDF ) $FLDS[$IDF] = $ID;
		if ( ok($id, id::ff($TBL, $ID, $IDF, 0)) )
			$is_ins = ino($sql = xsql::upd($FLDS, $id, $TBL));
				else $is_ins = done($sql = xsql::ins($FLDS, $TBL));
		$res = DB::run($sql);
		if ( $res ) self::$id_done[] = $is_ins ? DB::last_id() : $id;
		return $res;
	}
	public static function line2($ROW, $FLDS, $TBL, $ID, $IDF) {
		## ROW всегда должна быть = 1 чтобы избежать дубликатов
		## выбрать все строки с полем IDF = `ID`
		$FLDS[$IDF] = $ID;
		$Id = id::ff($TBL, $ID, $IDF);
		if ( $is_ins = ( ! we($Id) || zero($ROW)) ) {
			$sql = xsql::ins($FLDS, $TBL);
		} else {
			$id = who(--$ROW, $Id);
			$sql = xsql::upd($FLDS, $id, $TBL);
		}
		$res = DB::run($sql);
		if ( $res ) self::$id_done[] = $is_ins ? DB::last_id() : $id;
		return $res;

	}
	// обновление цепочки данных по связывающему ID
	public static function chain($ID, $TBLS, $IDS, $UPD, $IDM = 'id') {
		$i = 0;
		foreach ( $TBLS as $tbl ) {
			$Upd = who($i++, $UPD);
			if ( ! we($Upd) ) continue;
			if ( 1 == $i ) {
				$res = self::line($ID, $Upd, $tbl);
				$ID = self::id_master();
			} else $res = self::line($ID, $Upd, $tbl, $IDS);
			if ( non($res) ) return $res;
		}
		return yes;
	}
	public static function id_master() { return who(0, self::$id_done); }
	public static function id_done() { self::$id_done; }
}

class DBF {
	protected static function no_need($FLD) {
		return 'id' == $FLD || you($FLD, 'time');
	}
	protected static function warp($FLD, $VAL, $F2B = no) {
		if ( $F2B ) {
			if ( set($VAL) ) $VAL = trim(text($VAL));
			if ( zl($VAL) ) return null; ## '' -> NULL
				else if ( here($VAL, of('"', "'")) ) return str; ## '/" -> ''
		} else if ( un($VAL) ) return str; ## NULL -> ''
		## DATES
		if ( you($FLD, 'date') ) {
			if ( $F2B ) { #= 0, dd.mm.yyyy -> yyyy/mm/dd 00:00:00
				return zero($VAL) ? zdate(today()) : zdate($VAL);
			} else { #= yyyy/mm/dd xx:xx:xx -> dd.mm.yyyy
				return today($VAL);
			}
		}
		return $VAL;
	}
	// подготовка данных к записи
	// null, 0,
	public static function prep(& $POST, $TBL, $CALL = _, & $LOST = _) {
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			if ( self::no_need(so($fld, $Line['Field'])) ) continue;
				else $is_required = $Line['Null'] != 'YES';
			if ( ke($fld, $POST) ) {
				$type = $Line['Type'];
				$val = fx($CALL, self::warp($fld, $POST[$fld], yes)
					, $fld, $type);
				if ( set($val) ) {
					if ( you($type, 'varchar') )
						$fx = substr($type, 8, strlen($type) - 9);
						else if ( you($type, 'int') ) $fx = 'nat';
							else if ( you($type, 'float') ) $fx = 'num';
								else $fx = null;
					if ( inat($fx) ) {
						if ( strlen($val) > $fx ) $val = null;
					} else if ( set($fx) ) $val = text($fx($val));
					if ( un($val) ) return ino($LOST = $fld);
				}
				if ( un($val) ) {
					if ( $is_required ) return ino($LOST = $fld);
						else $val = $Line['Default']; }
				$POST[$fld] = $val;
			} else if ( $is_required && un($Line['Default']) )
				return ino($LOST = $fld);
			if ( who('Key', $Line) == 'UNI'
				&& exst($fld, $val, $TBL) ) return ino($LOST = $fld);
		}
		return yes;
	}

	// извлечение значения полей для формы
	public static function fetch($TBL, $ID = skip, $IDF = 'id') {
		$Res = ax();
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			$fld = $Line['Field'];
			if ( self::no_need($fld) ) continue;
				else $Res[$TBL][$fld]['n'] = $fld;
			if ( $Line['Null'] != 'YES' && is($Line['Default']) ) {
				$Res[$TBL][$fld]['v'] = $Line['Default'];
			}
		}
		if ( skip($ID) ) return $Res;
		$Data = DB::run(xsql::get(_, $ID, $TBL, $IDF), _);
		if ( ! we($Data) ) return $Res;
		foreach ( $Data as $k => $v )
			$Res[$TBL][$k]['v'] = self::warp($k, best($v, who($k, $Data)));
		return $Res;
	}
	public static function fetch2($TBL, $ROW, $ID, $IDF = 'id') {
		$Res = ax();
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			$fld = $Line['Field'];
			if ( self::no_need($fld) ) continue;
				else $Res[$TBL][$fld]['n'] = $fld;
			if ( $Line['Null'] != 'YES' && is($Line['Default']) ) {
				$Res[$TBL][$fld]['v'] = $Line['Default'];
			}
		}
		if ( zero($ROW) ) return $Res;
			else $Id = id::ff($TBL, $ID, $IDF);
		if ( ! we($Id) ) return $Res;
			else $id = who(--$ROW, $Id);
		if ( ! inat($id, pol) ) return $Res;
			else $Data = DB::run(xsql::get(_, $id, $TBL), _);
		if ( ! we($Data) ) return $Res;
		foreach ( $Data as $k => $v )
			$Res[$TBL][$k]['v'] = self::warp($k, best($v, who($k, $Data)));
		return $Res;
	}
	// html форма на основе таблицы
	public static function form($TBL) {
		$tmpl0 = '<pre><textarea rows="50" cols="100">%s</textarea></pre>';
		$tmpl1 = '<input type="text" name="{{%s}}" value="{{%s}}"'
			.' placeholder="'. ppm(3). '" />%s';
		$tmpl2 = ppm(3).'<textarea name="{{%s}}" placeholder="'. ppm(3)
			.'" >{{%s}}</textarea>';
		list ($Rems, $Res) = of(DBH::fcom($TBL), ax());
		foreach ( DB::run("EXPLAIN `${TBL}`") as $row => $Line ) {
			$fld = $Line['Field'];
			if ( 'id' == $fld ) continue; else $type = $Line['Type'];
			$tmpl  = you($type, 'text') ? $tmpl2 : $tmpl1;
			$name  = "${TBL}/${fld}/n";
			$val   = "${TBL}/${fld}/v";
			$text  = text(who($fld, $Rems));
			$Res[] = tag::p(spr($tmpl, $name, $val, $text));
		}
		$html = spr($tmpl0, hsc(j($Res, eol(2))));
		die($html);
	}
}
