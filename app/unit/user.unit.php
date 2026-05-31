<?php

$self_name = 'UnitUser';

class UnitUser {
	public function args() {}

	public function __toString() {
		$secret = Cook::get('uid_token');
		if ( she($secret) && exst('secret', $secret, 'users') ) {
			$user = app::x('iUser')->login();
			$res = 'Здравствуйте, '
				. atag($user, Route::make('profile'), no
					, array('style' => 'color: green;'))
				. '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'
				. atag('Выйти', Route::make('logout'));
		} else {
			$res = atag('Войти', Route::make('login'))
				. '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'
				. atag('Регистрация', Route::make('register'));
		}
		return $res;
	}

}

return $self_name;
