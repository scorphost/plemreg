<?php

$self_name = 'GoatCard';

function as_dol($X, $CNT = 1, $PREC = 3) {
	if ( ! inum($X) ) return cant;
	if ( inat($X) ) return $X. dot. many('0', $CNT);
		else return round($X, $PREC);
}

function gallery() {
	list ($file, $res) = of(Sess::get('file'), null);
	if ( she($file) ) {
		list ($name, $tmp) = of("pic_${file}", path('tmp', $file, yes));
		if ( copy($tmp, fpath(site_root, 'stuff', 'upload', 'gallery', $name)) )
			$res = $name;
		unlink($tmp);
	}
	Sess::del('file');
	return $res;
}

class GoatCard {
	public $gens;
	public function myParents(& $OUT, $ME, $MOM) {
		list ($key, $fld, $bind) = $MOM ? of('mo', 'id_mother', 'm')
			: of('fa', 'id_father', 'f');
		if ( inat($ME[$fld], pol) ) {
			list ($is_reg, $name)
				= know(u('is_reg,name'), $ME[$fld], 'animals');
			$OUT[$key]['name'] = atag($name
				,node_uri('view', $ME[$fld]));
			$OUT[$key]['reg_code'] = id2reg($ME[$fld], $is_reg);
		} else {
			if ( App::x('iUser')->admin() ) {
				$tmpl = Sys::tmpl('goat', 'bind');
				$out['bind_as'] = $bind;
				$out['bind_to'] = $ME['id'];
				$out['post'] = node_uri('bind', $ME['id']);
				$OUT[$key]['name'] = tell($tmpl, $out);
				$OUT[$key]['reg_code'] = atag('+ Добавить'
					, node_uri('add', $bind, $ME['id']));
			} else {
				$OUT[$key]['name'] = 'Нет';
				$OUT[$key]['reg_code'] = '-';
			}
		}
	}

	public function view_card($ID, $OBJ) {
		$isAdmin = App::x('iUser')->admin();
		$isGuest = no;

		if ( ! inat($ID) ) {
			$sql = xsql::get(u('id_animal,gens'), $ID, 'invites', 'code');
			list ($ID, $this->gens) = DB::args($sql, 2);
			if ( ! inat($ID) ) return $OBJ->fall('denied');
		} else if ( ! $isAdmin ) return $OBJ->fall('denied');

		if ( ! App::x('iam')->identify($ID) ) return $OBJ->fall(page_404);

		$out = array('is_admin' => $isAdmin);

		if ( Req::is_post() ) {
			$Post = array('file' => gallery(), 'id_goat' => $ID);
			$sql = xsql::ins($Post, 'goats_pic');
			DB::run($sql);
		}

		if ( ! $isAdmin && ! App::x('iam')->is_reg() ) {
			return $OBJ->fall('denied');
		}

		$this->theTree = Sys::with('goats', 'tree');
		$tmpl = Sys::tmpl('goat', 'card');
		$sql = xsql::get(_, $ID, 'animals', 'id');
		$ME = DB::run($sql, _);
		$out['site']['title'] = $ME['name'];
		$out['site']['lang'] = Site::lang();
		#` Галерея
		$out['photo']['post'] = Route::uri();
		$DbRes = DB::run(xsql::get('file', $ID, 'goats_pic', 'id_goat'));
		if ( we($DbRes) ) {
			$Tbl = ax();
			$img_tmpl = '<img src="/stuff/upload/gallery/%s" id="%s" width="%s" height="%s" alt="%s" title="%s" onClick="return popup(%s);">';
			$i = $ME['id'] + 1;
			foreach ( $DbRes as $Line ) {
				$id_img = 'imgs'.$i;
				$Tbl[] = tag::td(spr($img_tmpl, $Line['file'], $id_img
					, 100, 50, $ME['name'], $ME['name'], $i++), no
					, array('style'
						=> 'padding:5px;background-color:#b4f4bb;'));
			}
			$out['me']['gallery'] = tag::table(tag::tr(j($Tbl)));
		} else {
			$out['me']['gallery'] = 'Нет изображений';
		}
		#` Блок инфо
		$out['me']['reg_code'] = id2reg($ID, $ME['is_reg']);
		$out['me']['sex'] = 0 == $ME['sex'] ? 'козе' : 'козлу';
		Site::title($out['me']['name'] = $ME['name']);
		$this->MyParents($out, $ME, no);
		$this->MyParents($out, $ME, yes);
		#` Блок таблица
		$this->theTable = Sys::with('goats', 'table');
		$apn = "WHERE A.`id`='${ID}'";
		$out['me']['table'] = $this->theTable->table_main($apn);
		$out['me']['chain'] = $ME['name'];
		$out['js']['pid'] = $ID;
		$out['js']['name'] = $ME['name'];
		#` Блок Родословная
		$out['me']['gen_tree']
			= $this->theTree->bldTree($ID, $this->gens);
		#` Инбридинг
		$out['me']['inbreed'] = $this->inBreed($ID);
		#` Сводная по потомству
		$out['me']['kids'] = $this->myKids($out, $ID);
		#` Сводная по предкам
		#` Блок потомство
		$this->myChild($out, $ID);
		#` Блок лактации
		$is_lact = she($out['me']['lact'] = $this->retLact());
		if ( App::x('iUser')->admin() ) {
			$out['me']['lact_add'] = atag('Добавить'
				, Route::make('catalog', 'goats', 'lact', $ID));
		}
		#` Блок молочной продуктивности
		$out['me']['milk'] = $this->retMilk();
		if ( $isAdmin ) {
			$out['me']['milk_add'] = atag('Добавить'
				, Route::make('catalog', 'goats', 'milk', $ID));
		}
		#` Блок экспертная оценка
		$is_test = she($out['me']['test'] = $this->selfTest());
		if ( App::x('iUser')->admin() ) {
			$out['me']['test_add'] = atag($is_test ? 'Редактировать'
				: 'Добавить'
				, Route::make('catalog', 'goats', 'test', $ID));
		}
		#` Блок сертификат
		if ( $isAdmin ) {
			$out['me']['cert1'] = atag('Сертификат 1'
				, Route::make('catalog', 'goats', 'cert', $ID, 1));
			$out['me']['cert2'] = atag('Сертификат 2'
				, Route::make('catalog', 'goats', 'cert', $ID, 2));
		#` Блок сертификат (лактация)
			$out['me']['cert_post'] = Route::make('catalog', 'goats'
				, 'cert', $ID);
			$out['me']['cert_lact'] = $this->lactCert($ID);
		}
		#` Блок движение
		if ( $isAdmin ) {
			$id_breed = know('id_breed', $ID, 'goats_data', 'id_goat');
			$breed_alias = know('alias', $id_breed, 'breeds');
			$out['me']['move_show'] = atag('Посмотреть движение'
				, Route::make('catalog', 'goats'
					, $breed_alias, 'move', "?id=${ID}"));
			$out['me']['move_add'] = atag('Переместить'
				, Route::make('catalog', 'goats', 'mv', $ID));
		}
		#`
		Env::stor('legend', 'back', '123');
		Legend::set('card');
		$OBJ->html(tell($tmpl, $out));
		return yes;
	}

	public function lactCert($ID) {
		$theLact = Sys::with('goat', 'lact');
		return $theLact->build($ID);
	}

	function inBreed($ID) {
		list ($_x1, $_x2, $_x3, $Pair) = $this->theTree->retParnt($ID);
		if ( ! we($Pair) ) return 'Нет данных!';
		$Pair = col::del($Pair, 'id');
		$Pair = col::del($Pair, 'pid');
		$Pair = col::del($Pair, 'cid');
		tbl::map(grid::auto($Pair, yes), u('Кто,Партнер,Ребенок'));
		return tbl::html(xarr('class,table_com'));
	}

	public function selfTest() {
		$sql = xsql::get(_, App::x('iam')->id(), 'goats_test', 'id_goat');
		$DbRes = DB::run($sql);
		if ( ! we($DbRes) ) return str;
		$Tbl = col::del($DbRes, u('id,id_goat'));
		$Tbl = cols::today2($Tbl, 'date_test');
		col::c2v($Tbl, 'test_type', u('Не проведена,Классическая углубленная,Аттест. молодняка'));
		// tbl::map(grid::auto($Tbl, yes));
		tbl::map(grid::auto($Tbl, yes), u('ФИО Эксперта,Дата проведения аттестации,Тип аттестации,Высота в холке,Высота в крестце,Обхват груди по линии сердца,Косая длина корпуса,Вес животного,Итоговый балл,Класс,Категория'));
		return tbl::html(xarr('class,table_com'));
	}

	public function retLact() {
		$isGuest = un(App::x('iUser')->acl());
		$isUser = ! $isGuest && App::x('iUser')->acl() < 10;
		$isAdmin = ! $isUser && ! $isGuest;

		$sql = xsql::get(u('id_breed'), yes, 'goats_data');
		ispr($sql, "`goats_data`.`id_goat`=". $id);
		$Res = DB::run($sql);
		$id_breed = isset($Res[0]['id_breed']) ? $Res[0]['id_breed'] : null;
		if(!$id_breed) {
			$id_breed = false;
		}

		$All = of($id = App::x('iam')->id());
		$Ldb = ax();


		list ($Res1) = $this->theTree->retParnt($id, 0);
		foreach ($Res1 as $gen => $Data )  {
			if ( ! $isAdmin && $gen > $this->gens ) continue;
			foreach ( $Data as $lid => $Line ) {
				$All[] = $lid;
				$Ldb[$lid] = dd('Пр'). sp(wear($gen))
					. atag($Line[1]
						, Route::make('catalog', 'goats', 'view', $lid));
			}
		}

		$Res2 = $this->theTree->retChild($id, 0, 0, $id_breed);
		foreach ($Res2 as $gen => $Data )  {
			foreach ( $Data as $lid => $Line ) {
				$All[] = $lid;
				$Ldb[$lid] = dd('Пт'). sp(wear($gen))
					. atag($Line[1]
						, Route::make('catalog', 'goats', 'view', $lid));
			}
		}
		$sql = xsql::get(_, uniq($All), 'goats_lact', 'id_goat');
		$DbRes = DB::run($sql);
		if ( ! we($DbRes) ) return str;
		$Tbl = col::sh($DbRes, str, 'no', 0);
		col::c2v($Tbl, 'have_graph', u('Нет,Да'));
		$Tbl = col::sh($Tbl, str, 'edit', -1);
		$Tbl = cols::as_dol($Tbl, 'milk');
		$Cols1 = ax();
		if ( count($Tbl) > 1 ) {
			$sort = col($Tbl, 'milk');
			arsort($sort);
			reset($sort);
			$max = key($sort);
			$Line = $Tbl[0];
			$max_col1 = k2i('milk', $Line) - 2;
			foreach ( col($Tbl, 'milk') as $xy => $lid ) {
				if ( $xy == $max ) $Cols1[] = inc(who(0, p($xy)));
			}
		}
		$Tbl = cols::as_dol($Tbl, 'fat');
		$Cols2 = ax();
		if ( count($Tbl) > 1 ) {
			$sort = col($Tbl, 'fat');
			arsort($sort);
			reset($sort);
			$max = key($sort);
			$Line = $Tbl[0];
			$max_col2 = k2i('fat', $Line) - 2;
			foreach ( col($Tbl, 'fat') as $xy => $lid ) {
				if ( $xy == $max ) $Cols2[] = inc(who(0, p($xy)));
			}
		}
		$Cols3 = ax();
		if ( count($Tbl) > 1 ) {
			$sort = col($Tbl, 'protein');
			arsort($sort);
			reset($sort);
			$max = key($sort);
			$Line = $Tbl[0];
			$max_col3 = k2i('protein', $Line) - 2;
			foreach ( col($Tbl, 'protein') as $xy => $lid ) {
				if ( $xy == $max ) $Cols3[] = inc(who(0, p($xy)));
			}
		}
		$Tbl = cols::as_dol($Tbl, 'fat');
		$Tbl = cols::as_dol($Tbl, 'protein');
		col::anm($Tbl, 'no');

		$i = 1;
		foreach ( col($Tbl, 'id_goat') as $xy => $lid ) {
			if ( $lid != $id ) {
				col::io($Tbl, $xy, 'viewer', $Ldb[$lid]);
			} else {
				col::io($Tbl, $xy, 'edit', atag('...',
					Route::make('catalog', 'goats', 'lact'
						, $id. slash. $i++)));
			}
		}
		$Rows = ax();
		$i = $j = 1;
		$id_lact_show = App::x('iam')->id_lact_show();
		foreach ( col($Tbl, 'id') as $xy => $lid ) {
			if ( $lid == $id_lact_show ) {
				$Rows[] = $i;
			} else {
				col::io($Tbl, $xy, 'no'
					, atag($j, Route::make('catalog', 'goats', 'ml'
						, $id, $lid)));
			}
			$i++;
			$j++;
		}
		$Tbl = col::del($Tbl, u('id,id_goat'));
		$Cc = u('№,Потомок/предок,Номер лактации,Дней лактации,Удой за лактацию в кг,Жир &percnt;,Белок &percnt;,Среднесуточный удой (кг),График лактационной кривой,Испр');
		if ( ! App::x('iUser')->admin() ) {
			array_shift($Cc);
			array_pop($Cc);
			$Tbl = col::del($Tbl, u('no,edit'));
		}
		tbl::map(grid::auto($Tbl, yes), $Cc);
		if ( we($Rows) ) tbl::tr('style="background-color: #93E7F8;"', $Rows);
		if ( we($Cols1) ) {
			tbl::td('style="color: red; font-weight: bold;"'
				, $Cols1, $max_col1);
		}
		if ( we($Cols2) ) {
			tbl::td('style="color: red; font-weight: bold;"'
				, $Cols2, $max_col2);
		}
		if ( we($Cols3) ) {
			tbl::td('style="color: red; font-weight: bold;"'
				, $Cols3, $max_col3);
		}
		return tbl::html(xarr('class,table_com'));
	}
	public function retMilk() {
		$id = App::x('iam')->id();
		$sql = xsql::get(_, App::x('iam')->id(), 'goats_milk', 'id_goat');
		$DbRes = DB::run($sql);
		if ( ! we($DbRes) ) return str;
		$Tbl = col::sh($DbRes, str, 'no', 0);
		$Tbl = col::sh($Tbl, str, 'edit', -2);
		col::anm($Tbl, 'no');
		foreach ( ak(col($Tbl, 'edit')) as $xy ) {
			col::io($Tbl, $xy, 'edit', atag('...',
				Route::make('catalog', 'goats', 'milk'
					, $id. slash. col::io($Tbl, $xy, 'no'))));
		}
		col::c2l($Tbl, 'edit', Route::make(
			'catalog', 'goats', 'milk', idk), 'no');
		col::c2v($Tbl, 'have_graph', u('Нет,Да'));
		$i = 1;
		foreach ( col($Tbl, 'id')  as $xy => $lid ) {
			col::io($Tbl, $xy, 'no'
				, atag($i++, Route::make('catalog', 'goats', 'cml'
					, $id, $lid)));
		}
		$Tbl = cols::as_dol($Tbl, 'par_2');
		$Tbl = cols::as_dol($Tbl, 'par_3');
		$Tbl = cols::as_dol($Tbl, 'par_4');
		$Tbl = cols::as_dol($Tbl, 'par_5');
		$Tbl = cols::as_dol($Tbl, 'par_6');
		$Tbl = cols::as_dol($Tbl, 'par_7');
		$Tbl = cols::as_dol($Tbl, 'par_8');
		$Tbl = cols::as_dol($Tbl, 'par_9');
		$Tbl = cols::today2($Tbl, 'time_added');
		$Tbl = col::del($Tbl, u('id,id_goat'));
		if ( ! App::x('iUser')->admin() ) $Tbl = col::del($Tbl, u('no,edit'));
		$Cols = u('№,Номер лактации,Кол-во дней лактации,Удой (кг),Молочный жир (&percnt;),Молочный белок (&percnt;),Лактоза (&percnt;),Суточный пиковый удой (кг),Среднесуточный удой (кг),Плотность (кг),Скорость молокоотдачи (кг/мин),График лактационной кривой,Источник полученной информации,Испр,Добавлен');
		if ( ! App::x('iUser')->admin() ) {
			array_shift($Cols);
			$Cols[o2k(-2, $Cols)] = $Cols[o2k(-1, $Cols)];
			array_pop($Cols);
		}
		tbl::map(grid::auto($Tbl, yes), $Cols);
		return tbl::html(xarr('class,table_com'));
	}

	public function myKids(& $OUT, $ID) {

		$sql = xsql::get(u('id_breed'), yes, 'goats_data');
		ispr($sql, "`goats_data`.`id_goat`=". $ID);
		$Res = DB::run($sql);
		$id_breed = isset($Res[0]['id_breed']) ? $Res[0]['id_breed'] : null;
		if(!$id_breed) {
			$id_breed = false;
		}


		$Child = $this->theTree->retChild($ID, _, 1, $id_breed);
		// vd($Child);
		$Kids = ax();
		if ( ! we($Child) ) return nodata();
		foreach ( $Child as $Data )
			foreach ( $Data as $id => $Line ) $Kids[] = $id;
		array_unique($Kids);
		if ( ! we($Kids) ) return nodata();
		return $this->theTable->table_main(' WHERE A.`id` IN '
			. wear(j($Kids, ',')));

	}
	public function myRels(& $OUT, $ID) {
		list ($Parnt) = $this->theTree->retParnt($ID);
		$Parents = ax();
		if ( ! we($Parnt) ) return nodata();
		foreach ( $Parnt as $Data )
			foreach ( ak($Data) as $id)
				if ($id != $ID) $Parents[] = $id;
		array_unique($Parents);
		if ( ! we($Parents) ) return nodata();
		return $this->theTable->table_main(' WHERE A.`id` IN '
			. wear(j($Parents, ',')));
	}

	public function myChild(& $OUT, $ID) {

		$sql = xsql::get(u('id_breed'), yes, 'goats_data');
		ispr($sql, "`goats_data`.`id_goat`=". $ID);
		$Res = DB::run($sql);
		$id_breed = isset($Res[0]['id_breed']) ? $Res[0]['id_breed'] : null;
		if(!$id_breed) {
			$id_breed = false;
		}

		$DbChild = $this->theTree->retChild($ID, _, 4, $id_breed);
		// vd($DbChild);
		$TChild = $Child = $Cnt = ax();
		foreach ( $DbChild as $gen => $Data )
			foreach ( $Data as $id => $Line)
				$Child[$gen][reset($Line)][$id] = next($Line);
		foreach ( $Child as $gen => $Data) {
			for ( $i = 1; $i >=0; $i-- )
				if ( isset($Data[$i]) && we($Data[$i]) )
					$Cnt[] = cnt($Data[$i]); }
		$rmax = we($Cnt) ? max($Cnt) : 0;
		if ( zero($rmax) ) $rmax+=1;
		for ( $i = 0; $i < $rmax; $i++ ) {
			$c = 1;
			for ( $j = 1; $j < 5; $j++ ) {
				for ( $k = 1; $k >=0; $k-- ) {
					if ( isset($Child[$j][$k])) {
						$id = o2k($i, $Child[$j][$k]);
						$name = o2v($i, $Child[$j][$k]);
					} else $id = $name = null;
					if ( is($id) ) {
						$TChild[$i]['name'. $c] =
							atag($name, node_uri('view', $id));
					} else $TChild[$i]['name'. $c] = '-';
					$c++; } } }
		tbl::map(grid::auto($TChild, yes)
			, u('Сыновья,Дочери,Внуки,Внучки,Правнуки,Правнучки'
				.',Праправнуки,Праправнучки'));
		$OUT['me']['children'] = tbl::html(xarr('class,table_com'));
	}
}
return $self_name;
