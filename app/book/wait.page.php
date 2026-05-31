<?php
$self_name = 'PageWait';

class PageWait extends DeadEndPage {
	public function build() {
		$this->master = no;
		Site::title('Подтверждение регистрации');
	}
}

return $self_name;
