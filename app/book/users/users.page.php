<?php

$self_name = 'PageUsers';

class PageUsers extends NodePage {
	public function build() {
		Legend::set('usr');

		if ( ! App::x('iUser')->admin() ) {
			return $this->fall('denied');
		}
		$id = Route::pull(0);
		if ( inat($id, pol) ) {
			if ( Req::is_post() ) {
				return $this->user_rec($id);
			}
			return done(Env::stor($this->iam, 'content'
				, $this->user_details($id)));
		}
		$sql = xsql::get(u('id,login,name,email,phone,is_apk,token'), no, 'users', no, 'WHERE `users`.`id` > 3 ORDER BY `time_added` DESC');
		$DbRes = DB::run($sql);
		$html = '<p style="text-align: center; font-weight: bold;">Список зарегистрированных пользователей:</p>';
		if ( we($DbRes) ) {
			$Tbl = $DbRes;
			col::c2l($Tbl, 'login', Route::make('users', idk), 'id');
			$Tbl = col::del($Tbl, u('id'));
			foreach ( col($Tbl, 'token') as $xy => $val ) {
				$v = un($val) ? 1 : 0;
				col::io($Tbl, $xy, 'token', $v);
			}
			col::c2v($Tbl, 'is_apk', u('Нет,Да'));
			col::c2v($Tbl, 'token', u('Нет,Да'));
			tbl::map(grid::auto($Tbl, yes), u('Логин,Ф.И.О,Почта,Телефон,Член АПК,Активный'));
			$html.= tbl::html(xarr('border,1;width,100%'));
		} else $html.= nodata();
		return done(Env::stor($this->iam, 'content', $html));
	}

	public function user_details($ID) {
		$login = know('login', $ID, 'users');
		$html = '<p style="font-weight: bold;">Редактирование информации о пользователе <u>'. $login. '</u></p>';
		$tmpl = Sys::tmpl('user', 'form');
		$out = DBF::fetch2('users', 1, $ID);
		if ( some($out['users']['token']['v']) ) {
			intr($out, 'users/token/v', 0);
		} else {
			intr($out, 'users/token/v', 1);
		}
		$out['post_uri'] = Route::uri();
		$out['post_act'] = 'doUserUpdate';
		intr($out, 'sel/is_apk', hsel(u('Нет,Да')
		, 'is_apk', fld($out, 'users/is_apk')));
		intr($out, 'sel/active', hsel(u('Нет,Да')
		, 'token', fld($out, 'users/token')));
		return $html. tell($tmpl, $out);
	}

	public function user_rec($ID) {
		$Post = Req::what('doUserUpdate');
		$pass = who('newpass', $Post);
		if ( some($pass) ) {
			$Post['pass'] = Pass::hash($pass);
			do { $secret = Pass::secret(); }
				while ( exst('secret', $secret, 'users') );
			$Post['secret'] = $secret;
			unset($Post['newpass']);
		}
		$Post = own::l($Post);
		if ( eqn($Post['token'], 1) ) $Post['token'] = null;
			else unset($Post['token']);
		if ( we($Post) ) {
			$sql = xsql::upd($Post, $ID, 'users');
			DB::run($sql);
		}
		home(Route::make('users'));
	}

}

return $self_name;
