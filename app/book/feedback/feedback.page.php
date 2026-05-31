<?php

$self_name = 'PageFeedback';

class PageFeedback extends NodePage {
	public function mail($TEXT) {
		$tmpl = '<html><body>%s</body></html>';
		$subj = 'Отзыв по реестру АПК';
    		// "Cc: scorphost@gmail.com\r\n".
		$Headers = "From: info@kozovodstvo.center\r\n".
    		"Reply-To: info@kozovodstvo.center\r\n".
    		"MIME-Version: 1.0\r\n".
    		"Content-type: text/html; charset=utf-8\r\n".
    		'X-Mailer: PHP/' . phpversion();
    	$txt = sprintf($tmpl, $TEXT);
    	mail('anglonubiergoats@gmail.com', $subj, $txt, $Headers);
    	mail('scorphost@gmail.com', $subj, $txt, $Headers);
	}

	public function build() {
		Legend::set('feed');
		if ( Req::is_post() ) $this->doFeed();
		$out['post_uri'] = Route::uri();
		$tmpl = Sys::tmpl($this->iam, 'form');
		$html = tell($tmpl, $out);
		return done(Env::stor($this->iam, 'content', $html));
	}

	public function doFeed() {
		list ($email, $topic, $message) = av(need(u('email,topic,message')
			, to::trim(to::hsc(Req::what('doFeedback'))), str, yes));
		if ( ! l($topic) ) $topic = 'Без темы';
		if ( have::l($email, $message) ) {
			$text = "<b>Отправитель:</b> $email<br /><b>Тема:</b> $topic<br /><b>Сообщение:</b> $message";
			$this->mail($text);
		}
		home(yes);
	}

}

return $self_name;
