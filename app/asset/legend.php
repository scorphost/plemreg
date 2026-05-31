<?php
class Legend {
	const drop_tmpl = '<ul class="dropdown"><li class="dropdown-top">%s<ul class="dropdown-inside">%s</ul></li></ul>';
	public static $R;
	public static function get() { return self::$R; }
	public static function set($NAME) {
		switch ( $NAME ) {
			case 'home':	self::go_hom(); break;
			case 'catalog':	self::go_cat(); break;
			case 'titul':	self::go_tit(); break;
			case 'sex':		self::go_sex(); break;
			case 'exp':		self::go_exp(); break;
			case 'gen':		self::go_gen(); break;
			case 'kid':		self::go_kid(); break;
			case 'child':	self::go_chi(); break;
			case 'move':	self::go_mov(); break;
			case 'rip':		self::go_rip(); break;
			case 'reg':		self::go_reg(); break;
			case 'farm':	self::go_far(); break;
			case 'fmi':		self::go_fmi(); break;
			case 'lst':		self::go_lst(); break;
			case 'card':	self::go_crd(); break;
			case 'login':	self::go_log(); break;
			case 'rule':	self::go_rul(); break;
			case 'bad':		self::go_bad(); break;
			case '404':		self::go_404(); break;
			case '404':		self::go_acl(); break;
			case 'tech':	self::go_tec(); break;
			case 'dev':		self::go_dev(); break;
			case 'usr':		self::go_usr(); break;
			case 'feed':	self::go_feed(); break;
		}
	}
	public static function go_bad() { self::$R = of('Ошибка'); }
	public static function go_hom($LINK = no) {
		$here = 'Главная';
		self::$R = ! $LINK ? of($here)
			: of(atag($here, Route::make(homepage)));
	}
	public static function go_log($LINK = no) {
		$here = 'Авторизация';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_feed($LINK = no) {
		$here = 'Связь с администрацией';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_usr($LINK = no) {
		$here = 'Список пользователей';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_rul($LINK = no) {
		$here = 'Правила';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_404($LINK = no) {
		$here = 'Страница не найдена';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_acl($LINK = no) {
		$here = 'Ошибка';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_tec($LINK = no) {
		$here = 'Технические работы';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_dev($LINK = no) {
		Site::title('В разработке...');
		$here = 'Страница в разработке';
		self::go_hom(yes);
		self::$R[] = $here;
	}
	public static function go_cat($LINK = no) {
		self::go_hom(yes);
		if ( $LINK ) {
			$sql = xsql::get(u('name,alias'), no, 'breeds'
				, _, 'ORDER BY `place`');
			$Data = DB::run($sql);
			$lMa = $lSl = str;
			foreach ( $Data as $Line ) {
				list ($name, $alias) = av($Line);
				if ( Route::pull(1) == $alias )
					$lMa = atag($name, Route::make('catalog', 'goats'));
					else $lSl.= tag::li(atag($name
						, Route::make('catalog', 'goats', $alias)));
			}
			self::$R[] = spr(self::drop_tmpl, $lMa, $lSl);
		} else self::$R[] = 'Каталог пород';
	}
	public static function go_lst($LINK = no) {
		Site::title($here = 'Журнал добавления');
		self::go_hom(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('goats', 'list'));
	}
	public static function go_far($LINK = no) {
		$here = 'Фермы';
		self::go_hom(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('farms'));
	}
	public static function go_fmi($LINK = no) {
		$id = Route::pull(0);
		$here = $id > 0 ? know('name', $id, 'farms') : 'Без фермы';
		self::go_far(yes);
		self::$R[] = ! $LINK ? $here
			: atag($id, Route::make('farms', $id));
	}

	public static function go_tit($LINK = no) {
		$breed = Route::pull(1);
		self::go_cat(yes);
		if ( $LINK ) {
			$Links = array(
				'Козлы' =>
					Route::make('catalog', 'goats', $breed, 'male'),
				'Козы'	=>
					Route::make('catalog', 'goats', $breed, 'female'),
				'Молодняк' =>
					Route::make('catalog', 'goats', $breed, 'child'));
			$sex = Route::pull(2);
			if ( 'age' === Route::pull(3) ) $sex = 'child';
			switch ( $sex ) {
				case 'male';
					$here = 'Козлы';
					unset($Links[$here]);
				break;
				case 'female';
					$here = 'Козы';
					unset($Links[$here]);
				break;
				case 'child';
					$here = 'Молодняк';
					unset($Links[$here]);
				break;
				default: $here = 'Категории';
			}
			$lSl = str;
			$lMa = atag($here, Route::make('catalog', 'goats', $breed));
			foreach ( $Links as $k => $v ) $lSl.= tag::li(atag($k, $v));
			self::$R[] = spr(self::drop_tmpl, $lMa, $lSl);
		} else self::$R[] = 'Категории';
	}
	public static function go_sex($LINK = no) {
		self::go_tit(yes);
		$here = 'Племреестры';
		if ( $LINK ) {
			list ($breed, $sex) = of(Route::pull(1), Route::pull(2));
			$Links = array(
				'Главный реестр' =>
					Route::make('catalog', 'goats', $breed, $sex, 'reg', 'tg'),
				'Реестр поглотительного скрещивания' =>
					Route::make('catalog', 'goats', $breed, $sex, 'gen'),
				'Реестр по фенотипу' =>
					Route::make('catalog', 'goats', $breed, $sex, 'reg', 'ft'),
				'Реестр экспериментальных животных' =>
					Route::make('catalog', 'goats', $breed, $sex, 'exp'),
			);
			$lSl = str;
			$lMa = atag($here, Route::make('catalog', 'goats', $breed, $sex));
			foreach ( $Links as $k => $v ) $lSl.= tag::li(atag($k, $v));
			self::$R[] = spr(self::drop_tmpl, $lMa, $lSl);
		} else self::$R[] = $here;
	}
	public static function go_exp($LINK = no) {
		list ($breed, $sex) = of(Route::pull(1), Route::pull(2));
		$here = 'Реестр экспериментальных животных';
		self::go_sex(yes);
		self::$R[] = ! $LINK ? $here : atag($here
			, Route::make('catalog', 'goats', $breed, $sex, 'exp'));
	}
	public static function go_gen($LINK = no) {
		list ($breed, $sex) = of(Route::pull(1), Route::pull(2));
		$who = 'male' == $sex ? 'лов' : str;
		$here = 'Реестр поглотительного скрещивания'. $who;
		self::go_sex(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('catalog', 'goats'
				, $breed, $sex, 'gen'));
	}
	public static function go_kid($LINK = no) {
		list ($here, $breed) = of('Потомство до 1 года', Route::pull(1));
		self::go_tit(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('catalog', 'goats', $breed, 'child'));
	}
	public static function go_mov($LINK = no) {
		list ($here, $breed) = of('Перемещение животных', Route::pull(1));
		self::go_tit(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('catalog', 'goats', $breed));
	}
	public static function go_rip($LINK = no) {
		$here = 'Выбывши';
		list ($breed, $sex) = of(Route::pull(1), Route::pull(2));
		list ($l, $who) = 'child' == $sex ? of('й', 'молодняк')
			: of('е', 'male' == $sex ? 'козлы' : 'козы');
		$here = sp($here. $l). $who;
		self::go_tit(yes);
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('catalog', 'goats', $breed));
	}
	public static function go_reg($LINK = no) {
		list ($breed, $sex) = of(Route::pull(1), Route::pull(2));
		list ($who, $gen) = of('male' == $sex ? 'лов' : str, Route::pull(4));
		if ( you($gen, 'e') ) $gen = 'ex';
			else if ( ! here($gen, u('ft,tg')) ) $gen = 'aa';
		switch ( $gen ) {
			case 'tg':
				self::go_sex(yes);
				$here = 'Главный реестр чистопородных коз'. $who;
			break;
			case 'ft':
				self::go_sex(yes);
				$here = 'Реестр по фенотипу';
			break;
			case 'aa':
				self::go_gen(yes);
				$here = sp('Поколение'). hi($gen);
			break;
			case 'ex':
				self::go_exp(yes);
				$here = sp('Реестр'). hi($gen);
		}
		self::$R[] = ! $LINK ? $here
			: atag($here, Route::make('catalog', 'goats', $breed));
	}
	public static function go_chi($LINK = no) {
		self::go_kid(yes);
		$who = 'male' == Route::pull(2) ? 'Козлики' : 'Козочки';
		switch ( Route::pull(4) ) {
			case '3':  $here = sp($who). 'от рождения до 3 месяцев';  break;
			case '6':  $here = sp($who). 'от 3 месяцев до 6 месяцев'; break;
			case '12': $here = sp($who). 'от 6 месяцев до года';
		}
		self::$R[] = ! $LINK  ? $here
			: atag($here, Route::make('catalog', 'goats', 'child'));
	}
	public static function go_crd($LINK = no) {
		list ($here, self::$R) = of('Карточка животного', ax());
		$list = Route::make('catalog', 'goats', 'list');
		if ( App::x('iam')->is_reg() ) {
			$alias = who(App::x('iam')->id_breed(), App::x('iList')->lsAlias());
			if ( App::x('iam')->is_child() ) {
				$log = Route::make('catalog', 'goats'
					, $alias, 'child');
			} else {
				$stdb = know('alias', App::x('iam')->id_stoodbook()
					, 'stoodbook');
				$sex = App::x('iam')->sex() ? 'male' : 'female';
				$log = Route::make('catalog', 'goats'
					, $alias, $sex, 'reg', $stdb);
			}
		} else $log = $list;
		self::$R[] = atag(img('ico_back.png',_,_,'К спискам'), $log);
		self::$R[] = atag('Главная', Route::make(homepage));
		if ( App::x('iUser')->admin() ) {
			self::$R[] = atag('Журнал', $list);
			self::$R[] = atag('Редактировать'
				, Route::make('catalog', 'goats', 'fix', Route::pull(2)));

		}
		self::$R[] = atag('Каталог пород', Route::make('catalog', 'goats'));
		self::$R[] = $here;
	}
}
