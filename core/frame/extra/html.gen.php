<?php
function xsel($COD, & $PROC = _) {
	$AX = r2i($COD, ch('|,/.'));
	if ( ! arr($PROC) ) $PROC = array();
	$Sel = $Opt = $Proc1 = $Proc2 = array();
	foreach ( map($AX) as $p => $i ) {
		if ( slash == $COD{$p} ) {
			list ($Sel, $Opt) = two($AX, $i);
			break;
		} else $i++; }
	$attr1 = $attr2 = $html = str;
	foreach ( $Opt as $p => $v ) {
		if ( so($t, $COD{$p} ) == '|' ) {
			$attr2 = pre(xattr($attr2, no, no, $Proc1), spc);
			$html.= val(tag::option($v, _, _, _, l($attr2)), $attr2). nl;
		} else if ( ',' == $t ) $attr2 = $v;
			else $attr2.= ltrim($t, slash). $v; }
	foreach ( $Sel as $p => $v ) $attr1.= ( l($attr1) ? $COD{$p} : str ). $v;
	$attr1 = pre(xattr($attr1, no, no, $Proc1), spc);
	$k = o2v(0, gkl::str($Proc1));
	foreach ( gkl::nat($Proc1) as $p ) $Proc2[$k.slash.$p] = $Proc1[$p];
	$PROC = merge($PROC, $Proc2);
	return val(tag::select(nl. $html, _, _, _, l($attr1)), $attr1); }

/* $sq = 'o:n.sel1,i.id1/v.opt1|val1,+v.opt2|val2;'
.'c:+n.name1,v.val1|content1;c:n.name2,v.val2|content2;t:n.tn1,v.tv1';
$r = xform($sq, 'file1.php', no, $Proc); */
function xform($COD, $FILE, $GET = no, & $PROC = _) {
	$Arr1 = Array ('t' => 'text', 'h' => 'hidden', 'a' => 'textarea',
		's' => 'submit', 'x' => 'reset', 'b' => 'button',	##buttons
		'c' => 'checkbox', 'r' => 'radio',					##marks
		'o' => 'option');									##select
	$DB = $Res = Array();
	$Data = by($COD, ch(';:'));
	foreach ( $Data as $Part ) {
		unset($html);
		list ($type, $sub) = $Part;
		if ( 'o' !== $type ) {
			list ( $attr, $cont ) = safe(explode('|', $sub), 2, str);
			$attr = xattr('t.'. subst($type, $Arr1, no). ','. $attr
				, yes, no, $PROC);
		} else $html = xsel($sub, $PROC);
		if ( ! isset($html) ) {
			$attr = pre($attr, spc);
			$Res[] = val(tag::input($cont, no, l($attr)), $attr);
		} else $Res[] = $html; }
			// vd($Res);
	return sprintf(tag::form('%s', no, yes), spc. xattr('file.'. $FILE
		. ',method.'. ($GET ? 'GET' : 'POST')), nl. j($Res, nl). nl); }

## $sq = '2div/div2/a';
function xhtml($COD, $PROC = 'tag') {
	$AX = explode(slash, trim($COD, slash));
	$Abc = abc('az'); $Num = abc('09');
	$R = $D = array();
	foreach ( $AX as $id => $tag ) {
		$R[$id + 1] = $id;
		$D[++$id] = $tag; }
	$cid = $id + 1;
	do {
		foreach ( $D as $id => & $tag ) {
			$Live = live($tag, $Abc, $Num);
			$Clo = gkl::less(who(2, $Live), key($Live[1]));
			end($Live[1]);
			$Dup = gkl::more(who(2, $Live), key($Live[1]));
			list ( $need_clo, $need_dup ) = of(we($Clo), we($Dup));
			if ( $need_clo || $need_dup ) {
				list ($j1, $j2) = of(0, 2);
				if ( $need_clo && ! $need_dup ) $j2--;
					else if ( $need_dup && ! $need_clo ) $j1++;
				for ( $j = $j1; $j < $j2; $j++) {
					if ( 0 == $j ) {
						list ($id1, $y) = of($id, 0);
						$l = $c = count($Clo);
					} else {
						$id1 = who(0, scan($id, $R, yes));
						$l = re($c = count($Dup));
						$y = strlen($tag) - $c; }
					$n = dec(substr($tag, $y, $c));
					$D[$id] = $tag = cut($tag, $l);
					for ( $i = 0; $i < $n; $i++ ) {
						list ($rid, $Child) = of($id1, array());
						$R1 = array($cid => $R[$id1]);
						$R2 = array($id1 => $cid);
						do {
							$R[$cid] = $R1[$cid];
							$D[$cid++] = $D[$rid];
							$Child = merge($Child, scan($rid, $R, yes));
							if ( ok($again, we($Child)) ) {
								$rid = array_shift($Child);
								$R2[$rid] = $cid;
								$R1[$cid] = $R2[ $R[$rid] ]; }
						} while ( $again ); }}}}
	} while ( $need_clo || $need_dup );
	unset($tag);
	$Pool = $P = $Res = array();
	foreach ( $D as $id => & $tag )
		if ( icnt(scan($id, $R, yes), $c) && $c > 0 )
			list ($tag, $P[$id]) = of($PROC::$tag(nl. '%s' .nl), $c);
				else list ($tag, $Pool[]) = of(tag::$tag(str). nl, $id);
	while ( done($Nxt = array()) && we($Pool) ) {
		foreach ( $Pool as $i => $id ) {
			if ( so($rid, $R[$id]) == 0 ) continue;
			$c = $P[$rid];
			$D[$rid] = sprintf($D[$rid], j(safe(of($D[$id]), $c--, '%s')));
			if ( ($P[$rid] = $c) == 0 ) $Nxt[] = $rid; }
		$Pool = $Nxt; }
	foreach (sel::zero($R) as $id ) $Res[] = $D[$id];
	return j($Res, nl(2)); }


/* +v.val1,n.name1,i.,.autofocus,onLoad.return true
+ - добавить к аттрибутам checked или selected
key.val - добавить пару ключ - значение
{v|n|i|s} - специальные ключи для value,name,id,style
	пустой ключ - для аттрибутов без ключа
	пустое значение после ключа i - автоидентификатор */
function xattr($COD, $SEL = no, $TAIL = no, & $PROC = _) {
	static $id = 1;
	static $n = 1;
	$Abbr = array('n' => 'name', 'v' => 'value', 'i' => 'id'
		, 'c' => 'class', 's' => 'style', 't' => 'type');
	$Res = $Pat = $CONT = $Pro = array();
	list ($Data, $tsel, $PROC) = of(by($COD, ch(',.')), str, ta($PROC));
	foreach ( $Data as $Part ) {
		list ($key, $val) = $Part;
		if ( ! l($tsel) ) {
			if ( he($key, 0, $ch) && '+' == $ch ) {
				$tsel = ( let($SEL, 'check', 'select') ).'ed';
				$key = cut($key, 1);
			} if ( '-' == $ch ) {
				$tsel = 'disabled';
				$key = cut($key, 1); }}
		if ( l($key) ) {
			switch ( $key ) {
				case 'n':
					$Pro[0] = $val;
					if ( ! l($val) ) $val = 'name'.($n++);
					break;
				case 'v': $Pro[1] = $val; break;
				case 'i': if ( ! l($val) ) $val = $id++; break; }
			$Res[] = subst($key, $Abbr, no);
			list ($Res[], $Pat[]) = of($val, '%s="%s"');
		} else list ($Pat[], $Res[]) = of('%s', $val); }
	$attr = long(vsprintf(j($Pat, spc), $Res), $tsel);
	if ( $TAIL ) $attr.= '%s';
	if ( icnt($Pro, $c) ) {
		if ( 2 == $c ) $PROC[ $Pro[0] ] = $Pro[1];
			else if ( ke(1, $Pro) ) $PROC[] = $Pro[1];
				else if ( ke(0, $Pro) ) $PROC[ $Pro[0] ] = null;
		$Pro = array(); }
	return $attr; }

function hsel($DATA, $NAME, $CURR = skip, $ONE = no, $TAIL = skip) {
	static $tmpl0 = '<select name="%s"%s>%s</select>';
	static $tmpl1 = '<option value="%s"%s>%s</option>';
	$opt = str;
	foreach ( $DATA as $val => $cont ) {
		if ( ! $ONE ) {
			$sw = $val == $CURR ? ' selected' : str;
		} else {
			if ( $val != $CURR ) continue; else $sw = str;
		}
		$opt.= spr($tmpl1, $val, $sw, $cont);
	}
	return spr($tmpl0, $NAME, $TAIL, $opt);
}

function db2sel($NAME, $SQL, $DEF = 'Select...') {
	$DbRes = DB::run($SQL);
	list ($Res, $cmp) = of(ax(), null);
	if ( str($DEF) ) $Res[] = tag::option($DEF, no, 'value="0" selected');
		else if ( is_float($DEF) ) $cmp = 'row';
			else if ( inat($DEF) ) $cmp = 'id';
				else $cmp = no;
	foreach ( $DbRes as $row => $Data ) {
		pure($Data);
		$id = who(0, $Data);
		if ( is($cmp) && $DEF == $$cmp ) $sel =  ' selected';
			else $sel = str;
		$Res[] = tag::option(who(1, $Data), no, spr('value="%s"%s', $id, $sel));
	}
	return tag::select(j($Res), no, "name=\"${NAME}\"");
}

function db2frm($DB) {
	static $tinp = '<input type="text" name="%s" value="%s" placeholder="%s" />';
	static $ttxt = str;
	if ( zl($ttxt) ) $ttxt = tag::textarea(idk
		, no, ' name="%s placeholder="%s"');
	DB::run('SET NAMES utf8');
	$Res = array();
	$Data = DB::run("EXPLAIN $DB");
	// ['Field'] => id ['Type'] => int(11) ['Null'] => NO ['Key'] => PRI ['Default'] => @nul ['Extra'] => auto_increment
	foreach ( $Data as $Fields ) {
		$fld = $Fields['Field'];
		if ( 'id' === $fld ) continue;
		$Com = DB::run("SELECT `COLUMN_COMMENT` FROM information_schema.`COLUMNS` WHERE `TABLE_NAME`='${DB}' AND `COLUMN_NAME`='${fld}'");
		$rem = isset($Com[0]['COLUMN_COMMENT'])
			&& l($Com[0]['COLUMN_COMMENT']) ? $Com[0]['COLUMN_COMMENT'] : str;
		if ( $Fields['Type'] != 'text') {
			$Res[] = tag::p(spr($tinp, $fld, "{{form/${fld}}}", $rem));
		} else {
			$Res[] = tag::p(spr($ttxt, $fld, $rem ,"{{form/${fld}}}"));
		}
	}
	$Res[] = '<input type="submit" name="do{{act}}" value="DO" />';
	$r = tag::form(j($Res, spc(3)), no, ' action="{{route/uri}}" method="POST"');
	vd($r);
}

