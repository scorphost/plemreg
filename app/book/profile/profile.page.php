<?php
$self_name = 'PageProfile';

class PageProfile extends NodePage {
	public function build() {
		if ( App::x('iUser')->guest() ) return $this->fall('denied');
		if ( Req::is_post() ) {
			Pass::change(Req::what('doPassChange'));
			home(yes);
		}
		Site::title('Мой профиль');
		Env::stor($this->iam, 'post_uri', Route::uri());
		Env::stor($this->iam, 'login', app::x('iUser')->login());
		Env::stor($this->iam, 'name',  app::x('iUser')->name ());
		Env::stor($this->iam, 'email', app::x('iUser')->email());
		return yes;
	}
}

return $self_name;
