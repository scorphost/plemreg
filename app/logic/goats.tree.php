<?php
$self_name = 'GoatsTree';

class GoatsTree {
	public function retChild($ID, $SEX = skip, $LIM = 0, $id_breed = false) {
		$and_breed = $id_breed ? " AND D.`id_breed`='{$id_breed}'" : '';

		list ($Gen, $Q, $G, $i) = of(ax(), of($ID), array($ID => 0), -1);
		$S[$ID] = know('sex', $ID, 'animals');
		$res = [];
		while ( isset($Q[++$i]) ) {
			$f = 0 == $S[ $Q[$i] ] ? 'mo' : 'fa';
			// $sql = xsql::get(u('id,name,sex,is_reg'), yes, 'animals');
			// ispr($sql, "`animals`.`id_${f}ther`=". q($Q[$i]));

			$sql = "SELECT A.`id`, A.`name`, A.`sex`, A.`is_reg`
						FROM `animals` A
						LEFT JOIN `goats_data` D ON A.`id` = D.`id_goat`
					WHERE A.`id_{$f}ther`=" . q($Q[$i]) . $and_breed;

			$Res = DB::run($sql);
			if ( we($Res) ) {
				foreach ( $Res as $row => $Data ) {
					list ($id, $name, $sex, $is_reg) = av($Data);
					$Q[] = $id;
					$S[$id] = $sex;
					$g = $G[ $Q[$i] ] + 1;
					$G[$id] = $g;
					if ( $LIM > 0 && $g > $LIM ) break;
					$reg_code = id2reg($id);
					$Gen[$g][$id] = array($sex, $name
						, id2reg($id, $is_reg)); }}}
		if ( ! skip($SEX) ) {
			foreach ( $Gen as $row => & $Line ) {
				foreach ( $Line as $id => $Data )
					if ( $Data[0] != $SEX ) unset($Line[$id]);
				if ( ! c($Line) ) unset($Gen[$row]); }}

		return $Gen;
	}
	public function retParnt($ID, $SEX = skip) {
		if ( ! inat($ID, pol) ) return nodes(4);
		list ($Gen, $Q, $i, $G) = of(ax(), of($ID), -1, array($ID => 0));
		$InBre = $InBre2 = $Bsex = ax();
		while ( isset($Q[++$i]) ) {
			$sql = xsql::get(u('id_father,id_mother'), $Q[$i], 'animals');
			$Res = DB::run($sql);
			if ( we($Res) ) {
				foreach ($Res as $row => $Data ) {
					$is_inbre = no;
					if ( arr($G[ $Q[$i] ]) ) {
						$tty = $G[ $Q[$i] ];
						$g = array_shift($G[ $Q[$i] ]) + 1;
					} else $g = $G[ $Q[$i] ] + 1;
					foreach ( u('id_father,id_mother') as $k => $fid ) {
						$pid = $Data[$fid];
						if ( inat($pid, pol) ) {
							$Rel[$g][ $Q[$i] ][ ti( ! tb($k)) ] = $pid;
							$Q[] = $pid;
						} else {
							$Rel[$g][ $Q[$i] ][ ti( ! tb($k)) ] = 0;
							continue;
						}
						if ( clue($pid, $G) ) {
							if ( ! arr($G[$pid]) ) {
								$InBre[] = $pid;
								$is_inbre = yes;
								$G[$pid] = array($G[$pid]);
							}
							$G[$pid][] = $g;
						} else $G[$pid] = $g;
						list ($sex, $name, $is_reg)
							= know(u('sex,name,is_reg'), $pid, 'animals');
						if ( $is_inbre ) {
							$Bsex[$pid] = of($sex, $name);
						}
						$Gen[$g][$pid] = array($sex, $name
							, id2reg($pid, $is_reg)); }}}}
		$Rel2 = ax();
		foreach ( $Rel as $Seq )
			foreach ( $Seq as $id => $Mofa ) $Rel2[$id] = $Mofa;
		if ( ! skip($SEX) ) {
			foreach ( $Gen as $row => & $Line ) {
				foreach ( $Line as $id => $Data )
					if ( $Data[0] != $SEX ) unset($Line[$id]);
				if ( ! c($Line) ) unset($Gen[$row]); }}
		foreach ( $InBre as $i => $id ) {
			foreach ( $Rel2 as $pid => $MoFa ) {
				if ( has::eq($MoFa, $id) && ! here($pid, $InBre) )
					$InBre2[] = $id;
			}
		}
		array_unique($InBre2);
		$Rel3 = roll($Rel2);
		$Pair = $Rel4 = ax();
		foreach ( $InBre2 as $id ) {
			$my_sex = $Bsex[$id][0];
			foreach ( scan($id, $Rel3) as $xy ) {
				$cid = who(0, p($xy));
				$pid = $Rel2[ $cid ][ ti(! tb(nat($my_sex)) ) ];
				$cname = know('name', $cid, 'animals');
				$pname = know('name', $pid, 'animals');
				$my_name = know('name', $id, 'animals');
				$uk1 = j(of($id, $pid, $cid), slash);
				$uk2 = j(of($pid, $id, $cid), slash);
				if ( ! ke($uk1, $Rel4) && ! ke($uk2, $Rel4)) {
					$Pair[] = array('id' => $id, 'name' => $my_name
						, 'pid' => $pid, 'pname' => $pname
						, 'cid' => $cid, 'cname' => $cname );
					$Rel4[$uk1] = $Rel4[$uk2] = yes;
				}
				unset($Rel3[$xy]);
			}
		}
		return of($Gen, $Rel2, $InBre2, $Pair);
	}
	public function bldTree($ID, $GC = null) {
		list ($Gen, $Rel, $InBre) = $this->retParnt($ID);
		if ( ! we($Gen) ) return str;
		$cg = count($Gen);
		$kk = inat($GC, pol) ? $GC : 4;
		foreach ( $Gen as $k => $v ) { if ( $k > $kk ) unset($Gen[$k]); }
		$pk = last($Gen, yes);
		$mr = pow(2, $pk);
		$Mat = $Grid = $Arr = $Res = ax();
		for ( $i = 1; $i <= $pk; $i++ ) {
			list ($x, $k) = of(str, pow(2, $i));
			$p = dec(mod($mr, $k));
			for ( $j = 0; $j < $k; $j++) {
				$x.= 'x';
				for ( $z = 0; $z < $p; $z++ ) $x.= '^'; }
			$Mat[] = $x; }
		for ( $j = 0; $j < $mr; $j++) {
			$Grid[$j] = str;
			for ( $i = 0; $i < $pk; $i++ ) $Grid[$j].= $Mat[$i]{$j}; }
		for ( $i = 1, $z = 0; $i <= $pk; $i++, $z++ ) {
			list ($k, $o) = of(pow(2, $i), 1);
			$p = mod($mr, $k);
			for ( $g = 0; $g < $i; $g++ ) {
				list ($n, $t, $e) = of(0, yes, $o);
				for ($j = 0; $j < $k; $j++) {
					if ( ! isset($Arr[$z][$n]) ) $Arr[$z][$n] = str;
					$Arr[$z][$n].= ts(ti($t));
					$n+= $p;
					if ( 0 == --$e ) list ($t, $e) = of(! $t, $o); }
				$o*= 2; }}
		foreach ( $Arr as $row => & $Line ) {
			foreach ( $Line as $n => $seq ) {
				$i = $ID;
				foreach ( u(strrev($seq), str) as $e ) {
					if ( 0 == $i || ! isset($Rel[$i][$e]) ) $i = 0;
						else $i = $Rel[$i][$e]; }
				$Line[$n] = $i; }}
		$Map = ax(); $bi = 1;
		foreach ( $Arr as $col => & $Line ) {
			foreach ( $Line as $row => $seq ) {

				$Cols = $Gen[$col + 1];
				if ( isset($Cols[$seq]) ) {
					$tmpl = idk.spc(2).idk;
				 	list ($tsex, $tname, $treg) = $Cols[$seq];
				 	if ( all::zero(who(0, $Rel[$seq]), who(0, $Rel[$seq])) ) {
				 		$t1 = tag::b($tname);
				 	} else {
				 		if ( App::x('iUser')->admin() ) {
							$t1 = spr('<a onclick="loadTree(%s)">%s</a>'
								, $seq, tag::b($tname));
				 		} else {
				 			$t1 = tag::b($tname);
				 		}
					}
				 	$t2 = atag(wear($treg)
						, Route::make('catalog', 'goats', 'view', $seq)
				 		, 'style="color: #555555;"');
				 	if ( so($inbr, here($seq, $InBre)) ) {
				 		$r2 = wear($seq, '{');
				 	}
				 	if ( less($tsex, 1) ) {
				 		$t1 = tag::b(dd('М'), no
				 			, 'style="color: #F66DBB;"'). $t1;
 					 		if ( non($inbr) ) $r2 = '{m}';
				 	} else {
				 		$t1 = tag::b(dd('О'), no
				 			, 'style="color: #288EF6;"'). $t1;
				 		if ( non($inbr) ) $r2 = '{f}';
				 	}
				 	$r1 = spr($tmpl, $t1, $t2);
				} else {
					$r1 = tag::span('?', no, 'style="color: #C7A8A8";');
					$r2 = '{?}';
				}
				$Res[$row + 1][$col] = $r1;
				$Map[$row + 1][$col] = $r2;
			}
		}
		ksort($Res);
		ksort($Map);
		$Rows = ax();
		foreach ( row($pk, 1) as $itm ) {
			// $Rows[] = $itm. nbsp(). slash. nbsp(). $cg;
			$Rows[] = '&bull;';
		}
		array_unshift($Res, $Rows);
		array_unshift($Map, $Rows);
		array_unshift($Grid, many('x', $pk));
		grid::hand($Grid);
		tbl::map($Map, ax(), 1);
		$p1 = pow(2, $cg);
		$half = ($p1 / 2) + 1;
		$Data = tbl::$Data;
		tbl::map($Res, ax(), 1);
		$k = -1;
		$Ucl = ax();
		foreach ( u('207,243,213;148,245,162;246,219,228;'
			. '243,202,217;233,233,233', ';') as $v )
		{
			$Ucl[$k--] = $v;
		}
		foreach ( own::like(roll($Data), '\{\d+\}', 'r') as $k => $v ) {
			list ( $row, $col ) = u($k, slash);
			preg_match_all('/\{(\d+)\}/', $v, $Mtch);
			$id = $Mtch[1][0];
			if ( ! ke($id, $Ucl) ) {
				do {
					$Rgb[0] = rand(150, 200);
					$Rgb[1] = rand(150, 200);
					$Rgb[2] = rand(150, 200);
					$rco = j($Rgb, ',');
				} while ( here($rco, $Ucl) );
				$Ucl[$id] = $rco;
			} else $rco = $Ucl[$id];
			tbl::td(spr('style="background-color: rgb%s"', wear($rco))
				, $row, $col - 1);
		}
		foreach ( own::like(roll($Data), '{f}', 'e') as $k => $v ) {
			list ( $row, $col ) = u($k, slash);
			if ( $row < $half ) {
				tbl::td('class="f0"', $row, $col - 1);
			} else {
				tbl::td('class="f1"', $row, $col - 1);
			}
		}
		foreach ( own::like(roll($Data), '{m}', 'e') as $k => $v ) {
			list ( $row, $col ) = u($k, slash);
			if ( $row < $half ) {
				tbl::td('class="m0"', $row, $col - 1);
			} else {
				tbl::td('class="m1"', $row, $col - 1);
			}
		}
		$X1 = own::like(roll($Data), '\{\?\}', 'r');
		foreach ( $X1 as $k => $v ) {
			list ( $row, $col ) = u($k, slash);
			tbl::td('class="e0"', $row, $col - 1);
		}
		return tbl::html('class="pdgr"');
	}
	#` Идентификатор (MFF)
	public function idof($ID, $SEQ) {
		foreach ( ch($SEQ) as $who ) {
			$fld = 'F' == $who ? 'id_father' : 'id_mother';
			$ID = know($fld, $ID, 'animals');
			if ( zero($ID) ) return 0;
		}
		return $ID;
	}
}

return $self_name;
