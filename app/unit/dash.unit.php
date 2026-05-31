<?php

$self_name = 'UnitDash';

class UnitDash {
	public function args() { return ; }
	public function __toString() {
		if ( App::x('iUser')->acl() > 9 ) {
			$tmpl = Sys::tmpl('dash', 'unit');
			$out['link_farms'] = Route::make('farms');
			$out['link_add']   = Route::make('catalog', 'goats', 'add');
			$out['link_list']  = Route::make('catalog', 'goats', 'list');
			$out['link_user']  = Route::make('users');
			$out['img_farms']  = '/img/ico_farms.png';
			$out['img_add']    = '/img/ico_add.png';
			$out['img_list']   = '/img/ico_list.png';
			$out['img_user']   = '/img/ico_users.png';
			return tell($tmpl, $out);
		} else return str;
	}
}

return $self_name;
