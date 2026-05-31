<?php
$self_name = 'GoatLact';

class GoatLact {
	protected function as_dol($X, $CNT = 1, $PREC = 3) {
		if ( ! inum($X) ) return cant;
		if ( inat($X) ) return $X. dot. many('0', $CNT);
			else return round($X, $PREC);
	}

	public function sect1($LINE, $VIEWER, $ID, $IDD, $I, $FLD) {
		$Lact = ax();
		$Data = of(str);
		$Flds = u('lact_no,lact_days,milk,fat,protein,milk_day');
		foreach ($IDD as $Line) {
			foreach ( $Line as $id => $Info ) {
				$name = $Info[1];
				$sql = xsql::get(_, $id, 'goats_lact', 'id_goat');
				$Lact = DB::run($sql);
				if ( ! we($Lact) ) continue;
				foreach ( $Lact as $row => $Info )
					$Data[ solo(get(of('id'), $Info)) ]
						= $name. slash. j(get($Flds, $Info), slash); } }
		$row = "id_${FLD}_row{$I}";
		$sql = xsql::get($row, $ID, 'goats_cert', 'id_goat');
		$id = DB::run($sql, 0);
		if ( inat($id, pol) ) {
			$id = DB::run($sql, 0);
			$sql = xsql::get(_, $id, 'goats_lact');
			$Lact = DB::run($sql, _);
		} else $id = 0;
		$LINE['which'] = hsel($Data, $row, $id);
		if ( $id > 0 )
			foreach ( $Flds as $key )
				$LINE[$key] = you($key, 'lact') ? $Lact[$key]
					: $this->as_dol($Lact[$key]);
		$LINE['who'] = $VIEWER;
		return $LINE;
	}
	public function build($ID) {
		$Tbl = ax();
		$Line = array(
			'who' => '-',
			'which' => '-',
			'lact_no' => '-',
			'lact_days' => '-',
			'milk' => '-',
			'fat' => '-',
			'protein' => '-',
			'milk_day' => '-',
		);

		$sql = xsql::get(u('id_breed'), yes, 'goats_data');
		ispr($sql, "`goats_data`.`id_goat`=". $ID);
		$Res = DB::run($sql);
		$id_breed = isset($Res[0]['id_breed']) ? $Res[0]['id_breed'] : null;
		if(!$id_breed) {
			$id_breed = false;
		}

		$theTree = Sys::with('goats', 'tree');
		#` Д
		$Rows1 = ax();
		$idD = $theTree->retChild($ID, 0, 0, $id_breed);
		for ( $i = 1; $i < 6; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'П', $ID, $idD, $i, 'i');
			$Rows1[] = $i;
		}
		#` М
		$Rows2 = ax();
		$rid = $theTree->idof($ID, 'M');
		list($idD) = $theTree->retParnt($rid, 0);
		$sid[0] = array($rid => array(1 => 'Своя'));

		$idD = merge($sid, a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'М', $ID, $idD, $i, 'm');
			$Rows2[] = $i + 5;
		}
		#` Б
		$Rows3 = ax();
		$rid = $theTree->idof($ID, 'F');
		list($idD) = $theTree->retParnt($rid, 0);
		$idD = merge(a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'О', $ID, $idD, $i, 'f');
			$Rows3[] = $i + 8;
		}
		#` ММ
		$Rows4 = ax();
		$rid = $theTree->idof($ID, 'MM');
		$sid[0] = array($rid => array(1 => 'Своя'));
		list($idD) = $theTree->retParnt($rid, 0);
		$idD = merge($sid, a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'ММ', $ID, $idD, $i, 'mm');
			$Rows4[] = $i + 11;
		}
		#` БМ
		$Rows5 = ax();
		$rid = $theTree->idof($ID, 'MF');
		list($idD) = $theTree->retParnt($rid, 0);
		$idD = merge(a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'ОМ', $ID, $idD, $i, 'fm');
			$Rows5[] = $i + 14;
		}
		#` МБ
		$rid = $theTree->idof($ID, 'FM');
		$sid[0] = array($rid => array(1 => 'Своя'));
		$Rows6 = ax();
		list($idD) = $theTree->retParnt($rid, 0);
		$idD = merge($sid, a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'МО', $ID, $idD, $i, 'mf');
			$Rows6[] = $i + 17;
		}
		#` ББ
		$rid = $theTree->idof($ID, 'FF');
		$Rows7 = ax();
		list($idD) = $theTree->retParnt($rid, 0);
		$idD = merge(a($idD), a($theTree->retChild($rid, 0, 0, $id_breed)));
		for ( $i = 1; $i < 4; $i++ ) {
			$Tbl[] = $this->sect1($Line, 'ОО', $ID, $idD, $i, 'ff');
			$Rows7[] = $i + 20;
		}
		tbl::map(grid::auto($Tbl, yes), u('Кто,Выбор,Номер лактации,Дней лактации,Удой за лактацию в кг,Жир &percnt;,Белок &percnt;,Среднесуточный удой (кг)'));
		tbl::tr('style="background-color: #F6B8EB;"', $Rows1);
		tbl::tr('style="background-color: #F6DAB8;"', $Rows2);
		tbl::tr('style="background-color: #E89F98;"', $Rows3);
		tbl::tr('style="background-color: #F6ECB8;"', $Rows4);
		tbl::tr('style="background-color: #F6BDB8;"', $Rows5);
		tbl::tr('style="background-color: #F6ECB8;"', $Rows6);
		tbl::tr('style="background-color: #F6BDB8;"', $Rows7);
		$html = tbl::html(xarr('class,table_com'));
		$Map1 = u('МММ,БММ,МБМ,ББМ,ММБ,БМБ,МББ,БББ');
		$Map2 = u('mmm,fmm,mfm,ffm,mmf,fmf,mff,fff');
		list ($Hdr1, $Hdr2) = two($Map1);
		$Tbl = $Row1 = $Row2 = ax();
		$sql = xsql::get(_, $ID, 'goats_cert', 'id_goat');
		$DbRes = DB::run($sql, _);
		$tmpl = '<input type="text" name="%s" value="%s" />';
		for ($i = 0; $i < 4; $i++) {
			$col = $Map2[$i];
			$name = "id_${col}_row1";
			$Row1[ $col ] = spr($tmpl, $name, who($name, $DbRes));
		}
		$Tbl[0] = $Row1;
		tbl::map(grid::auto($Tbl, yes), $Hdr1);
		$html.= tag::p('Сокращенная продуктивность для 3-го поколения:');
		$html.= tbl::html(xarr('class,table_com'));
		for ($i = 0; $i < 4; $i++) {
			$col = $Map2[$i + 4];
			$name = "id_${col}_row1";
			$Row2[ $col ] = spr($tmpl, $name, who($name, $DbRes));
		}
		$Tbl[0] = $Row2;
		tbl::map(grid::auto($Tbl, yes), av($Hdr2));
		$html.= tbl::html(xarr('class,table_com'));
		return $html;
	}
}

return $self_name;
