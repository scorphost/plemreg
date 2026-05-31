<?php
$self_name = 'PageCatalog';

class PageCatalog extends NodePage {
	public function build() {
		Legend::set('catalog');
		$sub = Route::pull(0);
		if ( 'goats' == $sub ) {
			list ($type, $code) = of(Route::pull(1), Route::pull(2));
			if ( 'view' == $type && some($code) && ! inat($code) ) {
				$valid_to = know('valid_to', $code, 'invites', 'code');
				if ( ! inat($valid_to, pol) || ( time() > $valid_to) ) {
					return $this->fall('denied');
				}
			} else {
				if ( App::x('iUser')->guest() ) return $this->fall('denied');
			}
			$theMenu = Sys::with($sub, 'menu');
			$html = $theMenu->exec($this);
			if ( ! empty($html) ) {
				if ( she($html) ) {
					return done(Env::stor($this->as_name(), 'window', $html));
				}
				if ( $theMenu->is_show() ) {
					if ( App::x('iUser')->not_apk() ) {
						return $this->fall('denied');
					}
					list ($show, $name) = $theMenu->show_name();
					$theShow = Sys::with($show, $name);
					$html = $theShow->exec($this, $theMenu->show_func());
					if ( she($html) ) return done(
						Env::stor($this->as_name(), 'window', $html));
				}
				if ( $theMenu->is_form() ) {
					if ( ! App::x('iUser')->admin() && ! isset($valid_to) ) {
						return $this->fall('denied');
					}
					list ($form, $name) = $theMenu->form_name();
					$theForm = Sys::with($form, $name);
					return is($theForm->exec($this, $theMenu->form_func()));
				}
			}
		}
		return $this->fall(page_404);
	}
}

return $self_name;

