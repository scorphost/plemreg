<?php
$self_name = 'PageRegister';

class PageRegister extends NodePage {
	const site = 'http://plemreg.kozovodstvo.center';
	public function build() {
		// Legend::set('login');
		if ( Req::is_post() ) {
			if ( no($this->doRegister()) ) return no;
		}
		Site::title('Регистрация');
		$tmpl = Sys::tmpl($this->iam, 'form');
		$out['post'] = Route::uri();
		retry($out);
		$html = tell($tmpl, $out);
		return done(Env::stor($this->iam, 'content', $html));
	}
	public function doRegister() {
		$res = ino($Post = Req::what('doRegister'));
		list($login, $email, $name, $phone, $pass1, $pass2)
			= to::trim(get(u('login,email,name,phone,pass1,pass2')
				, $Post, yes));
 		if ( have::zl($login, $email, $name, $phone, $pass1, $pass2) )
			return nil(msg_err('Ошибка регистрации, заполните все поля'));
		if ( $pass1 != $pass2 )
			return nil(msg_err('Ошибка регистрации, пароли должны совпадать'));
		if ( Pass::create($Post) ) {
			$token = know('token', $login, 'users', 'login');
			$link = self::site. Route::make('confirm', $token);
			$url = atag($link, $link);
			$text = spr('Здравствуйте, %s!<br/>Вы регистрируетесь в реестре Ассоциации Племенных Коз.<br />Для подтверждения, проидите пожалуйста по ссылке %s', $login, $url);
			$subj = 'Подтверждение регистрации';
			$res = email($email, $subj, $text);
		} else {
			if ( exst('login', $login, 'users') ) {
				return msg_err('Пользователь с таким именем уже существует!');
			} if ( exst('email', $email, 'users') ) {
				return msg_err('Адрес электронной почты уже существует!');
			} else {
				return msg_err('Ошибка регистрации!');
			}
		}
		return $res ? $this->fall(page_wait)
			: nil(msg_err('Ошибка регистрации!'));
	}
}

return $self_name;
