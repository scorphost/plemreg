<?php

$self_name = 'UnitNavi';

class UnitNavi {
	public function args() {}

	public function __toString() {
		$Legend = Legend::get();
		if ( ! we($Legend) ) return str;
		return j(Legend::get(), '&nbsp;&nbsp;&bull;&nbsp;&nbsp;');
	}

}

return $self_name;
