<?php
$self_name = 'PageConfirm';

class PageConfirm extends NodePage {
	public function build() {
		$token = Route::pull(0);
		$sql = xsql::get(u('secret,id'), $token, 'users', 'token');
		list($secret, $id) = safe(av(DB::run($sql, _)), 2);
		if ( she($secret) ) {
			Cook::set('uid_token', $secret);
			$sql = xsql::upd(array('token' => null), $id, 'users');
			DB::run($sql);
			home(yes);
		} else {
			return $this->fall(page_404);
		}
	}
}

return $self_name;
