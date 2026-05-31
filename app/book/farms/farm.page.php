<?php

$self_name = 'PageFarms';

class PageFarms extends NodePage {
	const iam = 'farm';
	public $form_type;
	public $theSender;
	public function build() {
		$this->theSender = $this;
		Sess::set('back', $_SERVER['REQUEST_URI']);
		Legend::set('farm');
		if ( App::x('iUser')->guest() ) return $this->fall('denied');
		$id = Route::pull(0);
		if ( inat($id, poz) ) {
			return zero($id) ? $this->showUnFarm() : $this->showFarms($id);
		} else if ( 'add' == $id ) {
			$this->addFarm();

		}
		Site::title('Список ферм');
		$sql = xsql::get(_, no, 'farms');
		$Cat = DB::run($sql);
		elem(Sys::item('icons', 'elem'));
		$Data = array(
			'id' => 0,
      		'name' => 'Без фермы',
      		'tmpl' => null,
      		'pic1' => '11.jpg',
      		'pic2' => null,
		);
		$Cat[] = $Data;
		foreach ( $Cat as $row => $Flds ) {
			list ($id, $name, $pic) = get(u('id,name,pic1'), $Flds, yes);
			$pic = "/farm/${pic}";
			$link = atag($name, Route::uri($id));
			if ( App::x('iUser')->admin() ) {
				if ( $id != 0 ) {
					$link.= ' | '
					. atag('Редактировать'
						, Route::uri($id, 'edit'));
				}
			}
			$arr = xarr("link,$link;src,$pic;alt,$name;iw,213;ih,160;cfg,pic");
			$arr['deco'] = 'width: 213px; height: auto;';
			elem($arr);
		}
		if ( App::x('iUser')->admin() ) {
			$link = atag('Добавить', Route::uri('add'));
			$arr = xarr("link,$link;src,/farm/new_farm.png;alt,Новая ферма;iw,213;ih,160;cfg,pic");
			$arr['deco'] = 'width: 213px; height: auto;';
			elem($arr);
		}
		return done(Env::stor($this->iam, 'content', elem()));
	}
	public function showFarms($ID) {
		if ( Route::pull(1) == 'edit' ) {
			$this->editFarm($ID);
		}
		Legend::set('fmi');
		if ( App::x('iUser')->guest() ) return $this->fall('denied');
		$sql = xsql::get(u('name,tmpl,pic2'), $ID, 'farms');
		list($name, $tmpl, $pic2) = av(DB::run($sql, _));
		Site::title($name);
		$tmpl1 = Sys::tmpl('farm', 'card');
		$apn = "WHERE A.`id_farm`='${ID}'";
		$theTable = Sys::with('goats', 'table');
		$out['goats_list'] = $theTable->table_main2($apn);
		$out['farm_info'] = $tmpl;
		$out['farm_name'] = $name;
		$out['pic2'] = $pic2;
		$html = tell($tmpl1, $out);
		return done(Env::stor($this->iam, 'content', $html));
	}
	public function showUnFarm() {
		Legend::set('fmi');
		if ( App::x('iUser')->guest() ) return $this->fall('denied');
		Site::title('Животные без привязки к ферме');
		$tmpl1 = Sys::tmpl('farm', 'no');
		$theTable = Sys::with('goats', 'table');
		$apn = "WHERE A.`is_reg`='1' AND (A.`id_farm`='0' OR A.`id_farm` IS NULL)";
		$out['goats_list'] = $theTable->table_main2($apn);
		$html = tell($tmpl1, $out);
		return done(Env::stor($this->iam, 'content', $html));
	}

	public function addFarm() {
		$this->form_type= 'add';
		if ( Req::is_post() ) $this->doFarm();
		$out['back'] = atag('Назад', Route::make('farms'));
		pre_form($out, 'farms', node_uri('add'));
		$title = 'Добавить ферму';
		return std_form($title, $this, $out);
	}

	public function editFarm($ID) {
		if ( Req::is_post() ) $this->doFarm2($ID);
		$this->form_type= 'edit';
		$out = DBF::fetch('farms', $ID);
		$out['back'] = atag('Назад', Route::make('farms', $ID));
		pre_form($out, 'farms', Route::uri('farms', $ID, 'edit'));
		$title = 'Редактирование данных по ферме';
		return std_form($title, $this, $out);
	}

	public function form_type() {
		return $this->form_type;
	}


	function file_farm($N) {
		list ($file, $res) = of(Sess::get("file${N}"), null);
		if ( she($file) ) {
			list ($name, $tmp) = of("farm_${file}", path('tmp', $file, yes));
			if ( copy($tmp, fpath(site_root, 'stuff', 'upload'
				, 'farm', $name)) ) $res = $name;
			unlink($tmp);
		}
		Sess::del("file${N}");
		return $res;
	}

	public function doFarm() {
		$Post = Req::what('doFarms');
		$file = $this->file_farm(1);
		if ( some($file) ) {
			$Post['pic1'] = $file;
		}
		$file = $this->file_farm(2);
		if ( some($file) ) {
			$Post['pic2'] = $file;
		}
		$Post['tmpl'] = $Post['input'];
		unset($Post['input']);
		$sql = xsql::ins($Post, 'farms');
		DB::run($sql);
		home(Route::make('farms'));
	}

	public function doFarm2($ID) {
		$Post = Req::what('doFarms');
		$file1 = $this->file_farm(1);
		if ( some($file1) ) $Post['pic1'] = $file1;
			else unset($Post['pic1']);
		$file2 = $this->file_farm(2);
		if ( some($file2) ) $Post['pic2'] = $file2;
			else unset($Post['pic2']);
		$Post['tmpl'] = $Post['input'];
		unset($Post['input']);
		$sql = xsql::upd($Post, $ID, 'farms');
		DB::run($sql);
		home(Route::make('farms', $ID));
	}

}

return $self_name;

