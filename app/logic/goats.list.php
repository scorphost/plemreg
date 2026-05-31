<?php
$self_name = 'GoatsList';

class GoatsList {
	const tbl_head = 'Кличка,Уникальный код,Пол,Порода,+Потомство,Добавлен,Оператор,Упр';
	const css_hdr = 'style="background-color: #5F2000; color: white;"';
	const css_reg = 'style="background-color: #D7FDB5;"';
	const css_gst = 'style="background-color: #F3F1F1;"';
	const css_die = 'style="background-color: #EF9A9A;"';
	protected function sql() {
		$x = str;
		if ( Req::is_post() ) {
			$find = solo(Req::what('doFind'));
			if ( she($find) ) {
				$Find = arc(u('#name,#id'), of($find, $find));
				$x = qs('WHERE'. wear(xsql::srch($Find, yes)));
				$x = str_replace('`#', 'A.`', $x);
			}
		}
		if ( Req::get($fld, 'q') ) {
			if ( she($x) ) $x.= ' AND '; else $x = ' WHERE ';
			switch ($fld) {
				case 'guest': $x.= "A.`is_reg`='0' "; break;
				case 'reg': $x.= "A.`is_reg`='1' "; break;
				case 'liv': $x.= "A.`status`='1' "; break;
				case 'die': $x.= "A.`status`='0' "; break;
				case 'und': $x.= "A.`status` IS NULL "; break;
			}
		}
		if ( Req::fetch($fld, array('q' => 'dup')) ) {
			$sql = "SELECT
	      		DISTINCT A.`name`,
				A.`id`,
	      		A.`is_reg`,
	      		A.`status`,
	      		G.`ava`,
	      		A.`sex`,
	      		B.`alias`,
	      		A.`time_added`,
	      		U.`login` as user
				FROM `animals` A
				LEFT JOIN `goats_data` G ON G.`id_goat`=A.`id`
				LEFT JOIN `breeds` B ON B.`id`=G.`id_breed`
				LEFT JOIN `users` U ON U.`id`=A.`id_user`
				INNER JOIN `animals` AS t2
  					ON A.`name` = t2.`name`
  					AND A.`id` <> t2.`id`
			    ORDER by A.`time_added` DESC";
		} else {
			$sql = "SELECT
	      		A.`name`,
				A.`id`,
	      		A.`is_reg`,
	      		A.`status`,
	      		G.`ava`,
	      		A.`sex`,
	      		B.`alias`,
	      		A.`time_added`,
	      		U.`login` as user
				FROM `animals` A
				LEFT JOIN `goats_data` G ON G.`id_goat`=A.`id`
				LEFT JOIN `breeds` B ON B.`id`=G.`id_breed`
				LEFT JOIN `users` U ON U.`id`=A.`id_user`
			${x} ORDER by A.`time_added` DESC";
		}
		return DB::run($sql);
	}
#`
	public function exec($OBJ) {
		if ( ! App::x('iUser')->admin() ) {
			return $OBJ->fall('denied');
		}
		Legend::set('lst');
		$out['add'] = atag('Добавить животное', node_uri('add'));
		$out['show_all'] = atag('Показать всех', Route::uri());
		$out['show_guest'] = atag('Показать X', Route::uri('?q=guest'));
		$out['show_reg'] = atag('Показать R', Route::uri('?q=reg'));
		$out['show_dup'] = atag('Показать дубликаты', Route::uri('?q=dup'));
		$out['show_liv'] = atag('Показать живых', Route::uri('?q=liv'));
		$out['show_die'] = atag('Погибшие животные', Route::uri('?q=die'));
		$out['show_und'] = atag('Без статуса', Route::uri('?q=und'));
		$DbRes = $this->sql();
		$out['list'] = we($DbRes)
			? $this->retTable($DbRes) : nodata();
		pre_form($out, 'find');
		$tmpl = Sys::tmpl('goats', 'list');
		return tell($tmpl, $out);
	}

	protected function retTable($TBL) {
		$ColCodes = $ColChild = $Rows1 = $Rows2 = $Rows3 = ax();
		#`
		avaname($TBL);
		foreach ( col($TBL, 'id') as $xy => $id ) {
			$is_reg = col::io($TBL, $xy, 'is_reg');
			if ($is_reg) $Rows1[] = inc(who(0, p($xy)));
				else $Rows2[] = inc(who(0, p($xy)));
			$ColCodes[$xy] = id2reg($id, $is_reg);
			$ColChild[$xy] = '+'. atag('Сын', node_uri('add', 's', $id))
				. '&nbsp+'. atag( 'Дочь', node_uri('add', 'd', $id) );
		}
		foreach ( col($TBL, 'status') as $xy => $val ) {
			if ( zero($val) ) $Rows3[] = inc(who(0, p($xy)));
		}
		#`
		$TBL = col::sh($TBL, 'Р', 'con');
		col::c2l($TBL, 'con', node_uri('fix', idk), 'id');
		$TBL = col::del($TBL, u('id,is_reg,ava,status'));
		$TBL = col::rep($TBL, 'sex', of(0,1), u('Жен,Муж'));
		$TBL = col::sh($TBL, $ColCodes, 'code', 'name');
		$TBL = col::sh($TBL, $ColChild, 'child', 'alias');
		$TBL = cols::was_date($TBL, 'time_added');
		#`
		tbl::map(grid::auto($TBL, yes), u($this::tbl_head));
		tbl::tr($this::css_hdr, 0);
		if ( we($Rows1) ) tbl::tr($this::css_reg, $Rows1);
		if ( we($Rows2) ) tbl::tr($this::css_gst, $Rows2);
		if ( we($Rows3) ) tbl::tr($this::css_die, $Rows3);
		return tbl::html(xarr('border,1;width,100%;class,tbl_list'));
	}

}

return $self_name;
