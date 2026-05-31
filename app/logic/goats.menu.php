<?php

$self_name = 'GoatsMenu';

class GoatsMenu {
	const fam = 'goats';
	public $is_show = no;
	public $is_form = no;
	public $page;
	public $case = 0;
	public $id_sex;
	public $id_fam;
	public $show_func = str;
#/
	public function is_node() {
		list ( $bre, $type ) = of(Route::pull(1), Route::pull(2));
		if ( here($bre, u('add,fix,del,view,edit,bind,lact,milk,test,born,cert,ml,cml,mv')) ) {
			$this->form_func = $bre;
			return ! ( $this->is_form = yes );
		}
		if ( 'list' == $bre ) {
			$this->show_func = $bre;
			return ! ( $this->is_show = yes );
		} else if ( 'move' == $type ) {
			$this->show_func = $type;
			return ! ( $this->is_show = yes );
		}
		$this->id_fam = nat(know('id', self::fam, 'family', 'name'));
		#` Каталог пород
		if ( dont($bre, $type) ) return done($this->case = 1);
		## прошли каталог пород
		if ( some($bre) ) {
			App::x('iGoat')->breed_alias($bre);
			if ( non(num(App::x('iGoat')->breed_id())) ) return no;
		}
		#` Выбор по полу
		if ( un($type) ) return done($this->case = 2);
		## прошли идентификацию пола
		$this->id_sex = sex2int($type);
		if ( non($this->id_sex) ) return no;
		##
		$sub = Route::pull(3);
		if ( un($sub) ) return done($this->case = 3);
		if ( 'gen' == $sub && un(Route::pull(4)) )
			return done($this->case = 4);
		if ( 'exp' == $sub && un(Route::pull(4)) )
			return done($this->case = 5);
				else return no;
	}
#:
	public function exec($OBJ) {
		$this->theSender = $OBJ;
		$res = done($this->gid = $OBJ->as_name());
		if ( $this->is_node() ) {
			switch ( $this->case ) {
				case 1:	$res = $this->menu_breed();	break;
				case 2:	$res = $this->menu_titul();	break;
				case 3:	$res = $this->menu_regs();	break;
				case 4:	$res = $this->menu_gens();	break;
				case 5:	$res = $this->menu_exps();	break;
			}
		} else if ( here(Route::pull(3), u('dead,reg,age')) ) {
			$this->show_func = Route::pull(3);
			$res = $this->is_show = yes;
		}
		node_uri($this->gid, self::fam, App::x('iGoat')->breed_alias());
		return $res;
	}
#
	public function menu_breed() {
		Site::title('Козоводство');
		$sql = xsql::get(u('alias,name,ico'), $this->id_fam
			, 'breeds', 'id_family', ' ORDER BY `place`');
		$Data = DB::run($sql);
		elem(Sys::item('icons', 'elem'));
		foreach ( $Data as $Flds ) {
			list ($alias, $title, $pic) = av($Flds);
			$link = atag($title, Route::uri($alias));
			elem(xarr("link,${link};src,${pic};alt,${title}"));
		}
		return elem();
	}
#
	public function menu_titul() {
		$sql = xsql::get(_, App::x('iGoat')->breed_alias()
			, 'pictures', 'alias');
		$DbRes = DB::run($sql);
		Legend::set('titul');
		$breed_alias = App::x('iGoat')->breed_alias();
		foreach ( $DbRes as $Line ) {
			$sex = int2sex($Line['sex']);
			$out['img_'. $sex] = '/img/'. $Line['file'];
			$out['link_reg_'. $sex]	= Route::make($this->theSender->as_name()
				, $this::fam, $breed_alias, $sex);
			$out['link_dead_'. $sex] = Route::make($this->theSender->as_name()
				, $this::fam, $breed_alias, $sex, 'dead');
		}
		$out['link_move']	= Route::make($this->theSender->as_name()
				, $this::fam, $breed_alias, 'move');
		$out['goat_breed'] = App::x('iGoat')->breed_name();
		$out['img_dead'] = '/img/noimage.gif';
		return tell(Sys::tmpl($this::fam, 'titul'), $out, $Out1);
	}
#
	public function menu_regs() {
		Legend::set('sex');
		if ( $this->id_sex > 1 ) return $this->menu_child();
		$sex = int2sex($this->id_sex);
		$alias = App::x('iGoat')->breed_alias();
		$sql = xsql::get('file', $this->id_sex, 'pictures', 'sex', " AND `alias` = '${alias}'");
		$file = DB::run($sql, str);
		$out['sex_name0'] = 1 == $this->id_sex ? 'Козлы' : 'Козы';
		$out['sex_name'] = 1 == $this->id_sex ? 'козлов' : 'коз';
		$out['img_goat'] = '/img/'. $file;
		$out['link_tg'] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, $sex, 'reg', 'tg');
		$out['link_gen'] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, $sex, 'gen');
		$out['link_ft'] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, $sex, 'reg', 'ft');
		$out['link_exp'] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, $sex, 'exp');
		return tell(Sys::tmpl($this::fam, 'reg'), $out);
	}
#
	public function menu_exps() {
		Legend::set('exp');
		$out['goat_alias'] = $alias = App::x('iGoat')->breed_alias();
		$sql = xsql::get('file', $this->id_sex, 'pictures', 'sex', " AND `alias` = '${alias}'");
		$out['img_goat'] = '/img/'. DB::run($sql, str);
		$Exps = array(1 => 'ex1', 'ex2', 'ex3');
		for ( $i = 1; $i < 4; $i++ )
			$out['link_exp_'. $i] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, int2sex($this->id_sex), 'reg', $Exps[$i]);
		return tell(Sys::tmpl($this::fam, 'exp'), $out);
	}
#
	public function menu_gens() {
		Legend::set('gen');
		$alias = App::x('iGoat')->breed_alias();
		$sql = xsql::get('file', $this->id_sex, 'pictures', 'sex', " AND `alias` = '${alias}'");
		$file = DB::run($sql, str);
		$out['img_goat'] = '/img/'. $file;
		$out['goat_alias'] = $alias;
		for ( $i = 1; $i < 8; $i++ ) {
			$out['link_'. $i] = Route::make($this->theSender->as_name()
				, $this::fam, $alias, int2sex($this->id_sex), 'reg', "f${i}");
		}
		return tell(Sys::tmpl($this::fam, 'gen'), $out, $Out1);
	}
#
	public function menu_child() {
		Legend::set('kid');
		$alias = App::x('iGoat')->breed_alias();
		$sql = xsql::get('file', $this->id_sex, 'pictures', 'sex', " AND `alias` = '${alias}'");
		$out['img_goat'] = '/img/'. DB::run($sql, str);
		$i = 1;
		foreach ( u('male,female') as $sex ) {
			foreach ( u('3,6,12') as $age )
				$out['link_age'. $i++] = Route::make(
					$this->theSender->as_name()
					, $this::fam, $alias, $sex, 'age', $age);
		}
		return tell(Sys::tmpl($this::fam, 'child'), $out, $Out1);
	}
#@
	public function show_name() { return of(self::fam, 'show'); }
	public function form_name() { return of(self::fam, 'form'); }
	public function is_form() { return $this->is_form; }
	public function is_show() { return $this->is_show; }
	public function show_func() { return $this->show_func; }
	public function form_func() { return $this->form_func; }
}

return $self_name;
