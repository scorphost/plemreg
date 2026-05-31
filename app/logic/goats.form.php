<?php

// при загрузке изображение файл стирается


$self_name = 'GoatsForm';

class GoatsForm {
	const iam = 'goat';
	public $theSender;
	private $child_mode = null;
	private $par_mode = null;

	public function exec($OBJ, $FUNC) {
		$this->theSender = $OBJ;
		$method = "goat_${FUNC}";
		return $this->$method();
	}

	public function goat_add($ID = skip) {
		if ( Req::is_post() ) {
			$thePost = Sys::with('goats', 'post');
			if ( is($thePost->doAnimals1($ID)) ) return yes;
		}
		Sess::set('back', node_uri('list'));
		$out = merge(DBF::fetch('goats_data', $ID, 'id_goat')
			, DBF::fetch('animals', $ID));
		post_fail($out);
		$extra = Route::pull(2);
		if ( here($extra, u('s,d')) ) $this->child_mode = ti('s' == $extra);
		if ( here($extra, u('m,f')) ) $this->par_mode = ti('f' == $extra);
		$this->swAnimals($out);
		pre_form($out, 'animals', node_uri('list'));
		$title = set($ID) ? 'Редактировать животное' : 'Добавить животное';
		return std_form($title, $this, $out);
	}

	public function goat_fix() {
		$id = Route::pull(2);
		if ( Req::is_post() ) {
			if ( ! Req::what('doAnimals', 'is_reg') )
				DB::run("DELETE FROM `goats_guest` WHERE `id_goat`=${id}");
		}
		return $this->goat_add($id);
	}
#
	public function swAnimals(& $OUT) {
		$m = have(nat($this->child_mode), nat($this->par_mode));
		if ( $m > 0 ) {
			$sex = 2 == $m ? $this->par_mode : $this->child_mode;
			$id = Route::pull(3);
			$id_breed = know('id_breed', $id, 'goats_data', 'id_goat');
			intr($OUT, 'sel/sex', hsel(u('Женский,Мужской')
				, 'sex', $sex, yes));
			intr($OUT, 'sel/breed'
				, hsel(App::x('iList')->lsBreed(), 'id_breed'
					, $id_breed, yes));
			if ( inat($this->child_mode) ) $OUT['sid'] = $id;
			if ( inat($this->par_mode) ) $OUT['pid'] = $id;
		} else {
			intr($OUT, 'sel/sex', hsel(u('Женский,Мужской')
				, 'sex', fld($OUT, 'animals/sex')));
			intr($OUT, 'sel/breed'
				, hsel(App::x('iList')->lsBreed(), 'id_breed'
					, fld($OUT, 'goats_data/id_breed')));
		}
		intr($OUT, 'sel/status', hsel(u('No info,Dead,Alive')
			, 'status', shsel(fld($OUT, 'animals/status'))));
		intr($OUT, 'sel/is_reg', hsel(u('Родословная,Реестр')
			, 'is_reg', fld($OUT, 'animals/is_reg')));
		intr($OUT, 'sel/is_abg', hsel(u('Нет,Да')
			, 'is_abg', fld($OUT, 'goats_data/is_abg')));
		intr($OUT, 'sel/have_gen', hsel(u('Нет,Да')
			, 'have_gen', fld($OUT, 'goats_data/have_gen')));
		intr($OUT, 'sel/horns_type', hsel(u(',Комолый,Обезрожен,Рогатый')
			, 'horns_type', shsel(fld($OUT, 'goats_data/horns_type'))));
		intr($OUT, 'sel/stoodbook'
			, hsel(App::x('iList')->lsStdb(), 'id_stoodbook'
				, fld($OUT, 'goats_data/id_stoodbook')));
		intr($OUT, 'sel/farm'
			, hsel(App::x('iList')->lsFarms(), 'id_farm'
				, fld($OUT, 'animals/id_farm')));
	}
#
	public function goat_view() {
		$TheCard = Sys::with('goat', 'card');
		return $TheCard->view_card(Route::pull(2), $this->theSender);
	}
#
public function goat_bind() {
	if ( Req::is_post() ) {
		$thePost = Sys::with('goats', 'post');
		$thePost->doBind(Route::pull(2));
	}
}

public function goat_lact() {
	$id = Route::pull(2);
	$row = Route::pull(3);
	if ( ! inat($row, pol) ) $row = 0;
	if ( Req::is_post() ) {
		$thePost = Sys::with('goats', 'post');
		if ( is($thePost->doLact($id, $row)) )
			home(Route::make('catalog', 'goats', 'view', $id));
	}
	$out = DBF::fetch2('goats_lact', $row, $id, 'id_goat');

	// if ( Req::is_post() ) {
	// 	$thePost = Sys::with('goats', 'post');
	// 	if ( is($thePost->doLact($id)) )
	// 		home(Route::make('catalog', 'goats', 'view', $id));
	// }
	// $out = DBF::fetch('goats_lact', $id, 'id_goat');
	post_fail($out);
	$out['back'] = atag('Назад', Route::make('catalog', 'goats', 'view', $id));
	$out['post'] = Route::uri();
	intr($out, 'sel/have_graph', hsel(u('Нет,Да')
		, 'have_graph', fld($out, 'goats_lact/have_graph')));
	$tmpl = Sys::tmpl('goat', 'lact');
	$Form['site']['title'] = 'Данные по лактации';
	$Form['form']['lang'] = Site::lang();
	$Form['form']['content'] = tell($tmpl, $out);
	$tmpl = Sys::tmpl('form');
	return done($this->theSender->html(tell($tmpl, $Form)));
}
# Сертифкаты
public function goat_cert() {
	$TheCard = Sys::with('goat', 'cert');
	return $TheCard->show_cert(Route::pull(2), Route::pull(3)
		, $this->theSender);
}
# Экспертная оценка
public function goat_test() {
	$id = Route::pull(2);
	if ( Req::is_post() ) {
		$thePost = Sys::with('goats', 'post');
		if ( is($thePost->doTest($id)) )
			home(Route::make('catalog', 'goats', 'view', $id));
	}
	$out = DBF::fetch2('goats_test', 1, $id, $IDF = 'id_goat');

	post_fail($out);
	intr($out, 'sel/test_type', hsel(u('Не проведена,Классическая углубленная,Аттест. молодняка')
		, 'test_type', fld($out, 'goats_test/test_type')));
	$out['back'] = atag('Назад', Route::make('catalog', 'goats', 'view', $id));
	$out['post'] = Route::uri();
	$tmpl = Sys::tmpl('goat', 'test');
	$Form['site']['title'] = 'Данные по лактации';
	$Form['form']['lang'] = Site::lang();
	$Form['form']['content'] = tell($tmpl, $out);
	$tmpl = Sys::tmpl('form');
	return done($this->theSender->html(tell($tmpl, $Form)));
}

public function goat_milk() {
	$id = Route::pull(2);
	$row = Route::pull(3);
	if ( ! inat($row, pol) ) $row = 0;
	$out = DBF::fetch2('goats_milk', $row, $id, 'id_goat');
	if ( Req::is_post() ) {
		$thePost = Sys::with('goats', 'post');
		if ( is($thePost->doMilk($id, $row)) )
			home(Route::make('catalog', 'goats', 'view', $id));
	}
	post_fail($out);
	intr($out, 'sel/have_graph', hsel(u('Нет,Да')
			, 'have_graph', fld($out, 'goats_milk/have_graph')));
	$out['back'] = atag('Назад', Route::make('catalog', 'goats', 'view', $id));
	$out['post'] = Route::uri();
	$tmpl = Sys::tmpl('goat', 'milk');
	$Form['site']['title'] = 'Данные по молочной продуктивности';
	$Form['form']['lang'] = Site::lang();
	$Form['form']['content'] = tell($tmpl, $out);
	$tmpl = Sys::tmpl('form');
	return done($this->theSender->html(tell($tmpl, $Form)));
}
public function goat_ml() {
	$id = Route::pull(2);
	$row = Route::pull(3);
	$sql = xsql::upd(array('id_lact_show' => $row), $id
		, 'goats_data', 'id_goat');
	DB::run($sql);
	home(Route::make('catalog', 'goats', 'view', $id));
}
public function goat_cml() {
	$id_goat = Route::pull(2);
	$id = Route::pull(3);
	$Res1 = DBH::fcom('goats_lact');
	$Res2 = DBH::fcom('goats_milk');
	$Flds = u('par_0,par_1,par_2,par_3,par_4,par_7,have_graph');
	$Ins = u('lact_no,lact_days,milk,fat,protein,milk_day,have_graph');
	$sql = xsql::get($Flds, $id_goat, 'goats_milk', 'id_goat'
		, " AND `id`='${id}'");
	$name = know('name', $id_goat, 'animals');
	$Res = DB::run($sql, _);
	$Data = arc($Ins, av($Res));
	$Data['id_goat'] = $id_goat;
	$Data['viewer'] = $name;
	$sql = xsql::ins($Data, 'goats_lact');
	DB::run($sql);
	home(Route::make('catalog', 'goats', 'view', $id_goat));
}

public function goat_mv() {
	$id = Route::pull(2);
	if ( Req::is_post() ) {
		$thePost = Sys::with('goats', 'post');
		if ( is($thePost->doMove($id)) )
			home(Route::make('catalog', 'goats', 'view', $id));
	}
	$out = array('id' => $id);

	$sql = xsql::get('id_farm', $id, 'animals');
	$my_farm = DB::run($sql, 0);
	$out['id_farm'] = $my_farm;
	$farm_name = know('name', $my_farm, 'farms');
	$out['farm_out'] = $farm_name;

	$sql = xsql::get(_, no, 'reasons');
	$dbReasons = DB::run($sql);
	$lsReasons = ax();
	foreach ( $dbReasons as $Data ) {
		$lsReasons[$Data['id']] = $Data['descr'];
	}
	$out['sel']['reason'] = hsel($lsReasons, 'id_reason');

	$sql = xsql::get(u('id,name'), no, 'farms', yes
		, "WHERE `id`!='0' AND `id`!='${my_farm}'");
	$dbFarms = DB::run($sql);
	$dbFarms = DB::run($sql);
	$lsFarms = ax();
	foreach ( $dbFarms as $Data ) {
		$lsFarms[$Data['id']] = $Data['name'];
	}
	$out['sel']['farms'] = hsel($lsFarms, 'id_farm_on');
	$out['back'] = atag('Назад', Route::make('catalog', 'goats', 'view', $id));
	$out['post'] = Route::uri();
	$tmpl = Sys::tmpl('goat', 'move');
	$Form['site']['title'] = 'Движение животного';
	$Form['form']['lang'] = Site::lang();
	$Form['form']['content'] = tell($tmpl, $out);
	$tmpl = Sys::tmpl('form');
	return done($this->theSender->html(tell($tmpl, $Form)));
}

#!
	public function edit_guest($ID, $URI) {
		$have_data = exst('id_goat', $ID, 'goats_guest');
		if ( Req::is_post() ) {
			$Fld = own::some(Req::what('doGuest'));
			$Fld['id_goat'] = $ID;
			$sql = $have_data ? xsql::upd($Fld, $ID, 'goats_guest', 'id_goat')
				: xsql::ins($Fld, 'goats_guest');
			DB::run($sql);
			home(Route::make($URI, 'view', $ID));
		}
		$tmpl = Env::tmpl('goat', 'guest');
		$me['post_uri'] = Route::make($URI, 'edit', $ID);
		$node_uri = Env::data('igoat', 'node_uri');
		$me['back'] = atag('Назад к списку'
			, Route::make($node_uri, 'view', $ID));
		if ( $have_data ) {
			$Clrs = u('black,red,green,blue');
			$sql = xsql::get(_, $ID, 'goats_guest', 'id_goat');
			$Fld = DB::run($sql, _);
			$me['import_id'] = who('import_id', $Fld);
			for ( $i = 1; $i < 5; $i++ ) {
				$k1 = "line${i}_text";
				$k2 = "line${i}_color";
				if ( some($Fld[$k1]) ) $me[$i]['text'] = $Fld[$k1];
				$me[$i][ $Clrs[ $Fld[$k2] ] ] = spc.'selected';
			}
		}
		$this->theSender->html(tell($tmpl, $me));
		return yes;
	}

}
return $self_name;
