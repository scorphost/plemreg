<?php
$self_name = 'GoatsShow';

class GoatsShow {
	public $theSender;
#:
	public function exec($OBJ, $FUNC) {
		if ( un(App::x('iUser')->acl()) ) return $OBJ->fall('denied');
		$this->theSender = $OBJ;
		$method = "show_${FUNC}";
		return $this->$method();
	}
#:
	public function show_list() {
		$theList = Sys::with('goats', 'list');
		return $theList->exec($this->theSender);
	}
#
	public function show_reg() {
		Sess::set('back', $_SERVER['REQUEST_URI']);
		Legend::set('reg');
		$sex = sex2int(Route::pull(2));
		$breed_id = App::x('iGoat')->breed_id();
		$reg_id = know('id', Route::pull(4), 'stoodbook', 'alias');
		$theTable = Sys::with('goats', 'table');
		$apn = "WHERE Di.`id_breed`='${breed_id}'"
			." AND Di.`id_stoodbook`='${reg_id}' AND A.`sex`='${sex}'"
			." AND A.`is_reg`='1' AND (Di.`date_born` IS NULL"
			." OR Di.`date_born` < NOW() - INTERVAL 1 YEAR)";
		return $theTable->table_main($apn, u('sex,breed,cert_serial,cert_no'));
	}
#
	public function show_age() {
		Sess::set('back', $_SERVER['REQUEST_URI']);
		Legend::set('child');
		$sex = sex2int(Route::pull(2));
		$id_breed = App::x('iGoat')->breed_id();
		$theTable = Sys::with('goats', 'table');
		switch ( Route::pull(4) ) {
			case '3':
				$apn1 = 'Di.`date_born`>NOW()-INTERVAL 3 MONTH';
				break;
			case '6':
				$apn1 = '(Di.`date_born`>NOW()-INTERVAL 6 MONTH'
					.' AND Di.`date_born`<NOW()-INTERVAL 3 MONTH)';
				break;
			case '12':
				$apn1 = '(Di.`date_born`>NOW()-INTERVAL 1 YEAR'
					.' AND Di.`date_born`<NOW()-INTERVAL 6 MONTH)';
				break;
			default:
				$apn1 = 'Di.`date_born`>NOW()-INTERVAL 1 YEAR';
		}
		$apn = "WHERE Di.`id_breed`='${id_breed}' AND A.`sex`='${sex}'"
			." AND A.`is_reg`='1' AND ${apn1}";
		return $theTable->table_main($apn, u('sex,cert_serial,cert_no'));
	}
#
	public function show_dead() {
		Legend::set('rip');
		Sess::set('back', $_SERVER['REQUEST_URI']);
		$id_breed = know('id', Route::pull(1), 'breed', 'alias');
		$sex = sex2int(Route::pull(2));
		$theTable = Sys::with('goats', 'table');
		if ( 2 == $sex ) {
			$apn = "WHERE Di.`id_breed`='${id_breed}' AND A.`is_reg`='1'"
				." AND Di.`date_dead` IS NOT NULL AND (Di.`date_born`>"
				."NOW()-INTERVAL 1 YEAR)";
		} else {
			$apn = "WHERE Di.`id_breed`='${id_breed}' AND A.`sex`='${sex}'"
				." AND A.`is_reg_code`='1' AND Di.`date_dead` IS NOT NULL"
				." AND (Di.`date_born` IS NULL OR Di.`date_born`<"
				."NOW()-INTERVAL 1 YEAR)";
		}
		return $theTable->table_main($apn, u('sex,cert_serial,cert_no'));
	}
#
	public function show_move() {
		if ( ! App::x('iUser')->admin() ) {
			return $this->theSender->fall('denied');
		}
		Site::title('Движение животных');
		$breed = Route::pull(1);
		$id_breed = know('id', $breed, 'breeds', 'alias');
		Sess::set('back', $_SERVER['REQUEST_URI']);
		Legend::set('move');
		$sql = "SELECT
			M.`id_goat` as id,
			A.`name` as name,
			F1.`id` as id_f1,
			F1.`name` as f_name1,
			F2.`id` as id_f2,
			F2.`name` as f_name2,
			R.`id` as idr,
			R.`descr` as descr,
			M.`id_reason` as id_reason,
			M.`info` as info,
			D.`id_breed` as breed,
			M.`date_return` as date_ret

			FROM `goats_move` M

				LEFT JOIN `farms` F1 ON M.`id_farm_of`=F1.`id`
				LEFT JOIN `farms` F2 ON M.`id_farm_on`=F2.`id`
				LEFT JOIN `animals` A ON A.`id`=M.`id_goat` 
				LEFT JOIN `reasons` R ON R.`id`=M.`id_reason` 
				LEFT JOIN `goats_data` D ON D.`id_goat`=M.`id_goat` 

			WHERE A.`is_reg`='1' AND D.`id_breed`='3'

			ORDER BY M.`time_added` DESC";
		$DbRes = DB::run($sql);
		// vd($DbRes);
		$link = Route::make('catalog', 'goats', $breed, 'move', '?');
		if ( ! we($DbRes) ) return nodata();
		foreach ( col($DbRes, 'id') as $xy => $val ) {
			$name = atag(col::io($DbRes, $xy, 'name')
				, Route::make('catalog', 'goats', 'view', $val));
			$name.= spr("&nbsp;(%s)", atag('В', $link. "id=${val}"));
			col::io($DbRes, $xy, 'name', $name);
		}
		for ( $i = 1; $i < 3; $i++ ) {
			foreach ( col($DbRes, "id_f${i}") as $xy => $val ) {
				$name = atag(col::io($DbRes, $xy, "f_name${i}")
					, Route::make('farms', $val));
				$name.= spr("&nbsp;(%s&dash;%s&dash;%s)",
			    	atag('В', $link. "f=${val}&m=all"),
			    	atag('П', $link. "f=${val}&m=on"),
			    	atag('У', $link. "f=${val}&m=of"));
				col::io($DbRes, $xy, "f_name${i}", $name);
			}
		}
		foreach ( col($DbRes, 'id_reason') as $xy => $val ) {
			$reason = spr("&nbsp;(%s)", 
			    atag('В', $link. "r=${val}"));
			$descr = col::io($DbRes, $xy, 'descr');
			col::io($DbRes, $xy, 'descr', $descr. $reason);
		}
		$Tbl = col::del($DbRes, u('id,id_f1,id_f2,idr,id_reason,breed'));
		tbl::map(grid::auto($Tbl, yes), u('Кличка,Откуда,Куда,Причина, Доп. информация,Срок возврата'));
		return tbl::html(xarr('class,table_com'));
	}
}

return $self_name;
