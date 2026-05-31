<?php
$self_name = 'GoatsPost';

class GoatsPost {
	public function doAnimals1($ID = skip) {
		$Post2 = Req::what('doAnimals');
		$Post1 = take($Post2, u('name,sex,is_reg,id_farm,status'));
		$Post3 = take($Post2, u('pid,sid'));
		$Post1['id_user'] = App::x('iUser')->id();
		#` Stage 1: animals
		if ( ! DBF::prep($Post1, 'animals', of($this, 'warp'), $Lost) ) {
			return post_fail($Post1, 'animals', $Lost
				, array('goats_data' => $Post2));
		}
		#` Stage 2: goats_data
		$Post2['id_goat'] = 0;
		$Post2['ava'] = file_ava();
		if ( ! skip($ID) ) {
			if ( ! some($Post2['ava']) ) $Post2['ava']
				= know('ava', $ID, 'goats_data', 'id_goat');
		}
		if ( ! DBF::prep($Post2, 'goats_data', of($this, 'warp'), $Lost) ) {
			return post_fail($Post2, 'goats_data', $Lost
				, array('animals' => $Post1));
		}
		DBA::chain($ID, u('animals,goats_data'), 'id_goat', of($Post1, $Post2));
		#` Stage 3: Tree update
		$sid = $Post3['sid'];
		if ( inat($sid, pol) ) {
			$fld = 0 == know('sex', $sid, 'animals')
				? 'id_mother' : 'id_father';
			$sql = xsql::upd(array($fld => $sid), DBA::id_master(), 'animals');
			DB::run($sql);
		}
		$pid = $Post3['pid'];
		if ( inat($pid, pol) ) {
			$fld = 0 == know('sex', DBA::id_master(), 'animals')
				? 'id_mother' : 'id_father';
			$sql = xsql::upd(array($fld => DBA::id_master()), $pid, 'animals');
			DB::run($sql);
		}
		home(Route::make('catalog', 'goats', 'view', DBA::id_master()));
	}

	public function doBind($ID) {
		list ($bind_as, $code) = Req::args('doBind', 2);
		if ( inat($code, pol) && $code > 10000 ) {
			$pid = $code - 10000;
			if ( exst('id', $pid, 'animals')) {
				$fld = 'f' == $bind_as ? 'id_father' : 'id_mother';
				$sql = xsql::upd(array($fld => $pid), $ID, 'animals');
				DB::run($sql);
			}
		}
		home(Route::make('catalog', 'goats', 'view', $ID));
	}

	public function doLact($ID, $ROW) {
		$Post = Req::what('doLact');
		$Post['id_goat'] = $ID;
		if ( ! DBF::prep($Post, 'goats_lact', _, $Lost) )
			return post_fail($Post, 'goats_lact', $Lost);
		return DBA::line2($ROW, $Post, 'goats_lact', $ID, 'id_goat');
	}

	public function doMilk($ID, $ROW) {
		$Post = Req::what('doMilk');
		$Post['id_goat'] = $ID;
		if ( ! DBF::prep($Post, 'goats_milk', _, $Lost) )
			return post_fail($Post, 'goats_milk', $Lost);
		return DBA::line2($ROW, $Post, 'goats_milk', $ID, 'id_goat');
	}

	public function doTest($ID) {
		$Post = Req::what('doTest');
		$Post['id_goat'] = $ID;
		if ( ! DBF::prep($Post, 'goats_test', _, $Lost) )
			return post_fail($Post, 'goats_test', $Lost);
		return DBA::line2(1, $Post, 'goats_test', $ID, 'id_goat');
	}

	public function doMove($ID) {
		$Post = Req::what('doMove');
		$Post['id_farm_of'] = know('id_farm', $Post['id_goat'], 'animals');
		$id_breed = know('id_breed', $Post['id_goat'], 'goats_data', 'id_goat');
		$alias = know('alias', $id_breed, 'breeds');
		if ( ke('info', $Post) && ! some($Post['info']) )
			unset($Post['info']);
		if ( ke('date_return', $Post) ) {
			if ( ! some($Post['date_return']) )	unset($Post['date_return']);
				else $Post['date_return'] = zdate($Post['date_return']);
		}
		$sql = xsql::ins($Post, 'goats_move');
		DB::run($sql);
		$sql = xsql::upd(array('id_farm' => $Post['id_farm_on'])
			, $ID, 'animals');
		DB::run($sql);
		home(Route::make('catalog', 'goats', $alias
			, 'move', '?id=', $Post['id_goat']));
	}


	public function warp($VAL, $NAME) {
		switch ($NAME) {
			case 'horns_type': return shsel($VAL, yes);
			case 'status': return shsel($VAL, yes);
			default: return $VAL;
		}
	}
}

return $self_name;
