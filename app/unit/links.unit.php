<?php

$self_name = 'UnitLinks';

class UnitLinks {
	public $out;
	public function args($X) {
		switch ($X) {
			case 'f': $this->out = Route::make('farms');
		}
	}



	public function __toString() {
		return $this->out;
		img();
	}

}

return $self_name;
