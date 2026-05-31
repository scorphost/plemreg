<?php

$self_name = 'PageLogin';

class PageLogin extends NodePage {
	public function build() {
		if ( Req::is_post() && $this->doLogin() ) home(homepage);
			else Site::title('Вход в систему');
		Legend::set('login');
		$out['post'] = Route::uri();
		retry($out);
		if ( ! ke('pass_foc', $out) ) $out['login_foc'] = ' autofocus';
		$html = tell(Sys::tmpl($this->iam, 'form'), $out);
		return done(Env::stor($this->iam, 'content', $html));
	}

	public function doLogin() {
		list ( $login, $pass ) = Req::args('doLogin', 2, str);
		$is_user = all::she($login, $pass)
			&& App::x('iUser')->x2id($login, 'login');
		$is_true = $is_user && App::x('iUser')->pass() === Pass::hash($pass);
		$is_real = $is_true && un(App::x('iUser')->token());
		if ( ! $is_user ) {
			Sess::set('retry', xarr('login_foc,autofocus'));
			return msg_err('Пользователя с таким именем не существует');
		}
		if ( ! $is_true ) {
			Sess::set('retry', xarr("pass_foc,autofocus;login,${login}"));
			return msg_err('Неправильный пароль');
		}
		if ( ! $is_real ) {
			return msg_err('Учетная запись неактивна');
		}
		return done(Cook::set('uid_token', App::x('iUser')->secret()));
	}
}

return $self_name;
