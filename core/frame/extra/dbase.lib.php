<?php

/*
function db2form($DB) {
	DB::run('SET NAMES utf8');
	$Res = array();
	$Data = DB::run("EXPLAIN $DB");
	foreach ( $Data as $Fields ) {
		$pre = $vht = str;
		if ( $Fields['Key'] == 'UNI' ) $pre.= 'u';
		if ( $Fields['Null'] == 'NO' ) $pre.= 'r';
		if ( he($Fields['Type'], 0, $ch) ) $pre.= $ch;
		$nht = $pre. '_'. ($fld = $Fields['Field']);
		$Com = DB::run("SELECT COLUMN_COMMENT FROM "
			."information_schema.`COLUMNS` WHERE TABLE_NAME"
			." = '$DB' AND COLUMN_NAME = '$fld'");
		$vht.= $Fields['Default'];
		if ( isset($Com[0]['COLUMN_COMMENT'])
			&& l($Com[0]['COLUMN_COMMENT']) )
		$vht = long($vht, $Com[0]['COLUMN_COMMENT'], '+');
		$Res[] = ! l($vht) ? 't:n.'. $nht
			: spr('t:n.%s,v.%s', $nht, $vht);
	}
	$Res[] = 's:n.do'. cap(low($DB)).',v.'.hi($DB);
	$form = j($Res, ';');
	$html = xform($form, 'post.php', no, $Proc);
	vd($form, $html, $Proc);
}

*/
