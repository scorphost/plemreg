<?php
/* Оригинальные расчеты для сайта */

function id2reg($ID, $ISR = no) {
	static $adt = 10000;
	if ( inat($ID, pol) ) return ( $ISR ? 'R' : 'X' ). ( $adt + $ID );
}

function reg2id($REG) {
	static $adt = 10000;
	return nat(sub(cut($REG, 1), $adt));
}

function sex2int($SEX) {
	switch ($SEX) {
		case 'female': return 0;
		case 'male': return 1;
		case 'child': return 2;
	}
	return no;
}

function int2sex($INT) {
	switch ( nat($INT) ) {
		case 0: return 'female';
		case 1: return 'male';
		case 2: return 'child';
	}
	return no;
}

function avaname(& $TBL) {
	static $path_ava = '<img id="imgs%s" src="/stuff/upload/ava/%s" alt="%s" width="20" height="10" onClick="return popup(%s);"/>&nbsp;&nbsp;&nbsp;&nbsp;';
	foreach ( col($TBL, 'id') as $xy => $id ) {
		$name = col::io($TBL, $xy, 'name');
		$ava = col::io($TBL, $xy, 'ava');
		if ( ! she($ava) ) $ava = 'kozovodstvo.png';
		col::io($TBL, $xy, 'name', spr($path_ava, $id, $ava, $name, $id)
			. atag($name, Route::make('catalog', 'goats', 'view', $id)));
	}
}

function fext($FILE) { return o2v(-1, u($FILE, dot)); }

function file_ava() {
	list ($file, $res) = of(Sess::get('file'), null);
	if ( she($file) ) {
		list ($name, $tmp) = of("ava_${file}", path('tmp', $file, yes));
		if ( copy($tmp, fpath(site_root, 'stuff', 'upload', 'ava', $name)) ) $res = $name;
		unlink($tmp);
	}
	Sess::del('file');
	return $res;
}

function post_fail(& $VAR) {
	$na = func_num_args();
	if ( $na > 2 ) {
		if ( $na > 3 ) Sess::set('post_data', func_get_arg(3));
		Sess::set('post_fail', own::some($VAR));
		Sess::set('post_tbl', func_get_arg(1));
		return ino(Sess::set('fail_fld', func_get_arg(2)));
	} else if ( 1 == $na ) {
		$ans = we($Post = Sess::get('post_fail'));
		if ( $ans ) {
			$post_tbl = Sess::get('post_tbl');
			$fail_fld = Sess::get('fail_fld');
			$name = $VAR[$post_tbl][$fail_fld]['n'];
			intr($VAR, p(Sess::get('post_tbl')
				, Sess::get('fail_fld'), 'n'), $name. '" style="background-color: #F1AEAE;');
			intr($VAR, p(Sess::get('post_tbl')
				, Sess::get('fail_fld'), 'v'), str);
			foreach ( $Post as $name => $val )
				intr($VAR, p(Sess::get('post_tbl'), $name, 'v'), $val);
			if ( we($Post2 = Sess::get('post_data')) ) {
				foreach ( $Post2 as $key => $val ) {
					intr($VAR, p($post_tbl, $key, 'v'), $val);
				}
			}
		}
		Sess::del(u('post_fail,post_data,post_tbl,fail_fld'));
			// vd($Post, $_SESSION);
		return ! $ans;
	}

}

function invite_me($ID, $VALID_TO, $GENS) {
	$secret = strrev(md5(strrev(to_text(time())). salt. '-'
		. $ID. $GENS. $VALID_TO));
	$Data = ch($secret);
	shuffle($Data);
	do {
		list ($Token, $k, $j, $tk2) = of(ax(), 1, 0, str);
		for ( $i = 0; $i < 16; $i++ ) {
			$tk2.= one($Data);
			if ( $k++ > 3 ) {
				$Token[$j++] = $tk2;
				list ($k, $tk2) = of(1, str); }}
		$code = j($Token, '-');
	}  while( exst('code', $code, 'invites') );
	$valid = strtotime("Now +$VALID_TO Hours");
	//catalog/goats/view/8e36-b8c7-e18a-3768
	$url = 'http://plemreg.kozovodstvo.center'
		. Route::make('catalog', 'goats', 'view', $code);
	$Flds = array('code' => $code, 'valid_to' => $valid
		, 'id_animal' => $ID, 'gens' => $GENS);
	$sql = xsql::ins($Flds, 'invites');
	if ( DB::run($sql) ) return $url;
}
