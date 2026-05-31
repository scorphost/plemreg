<?php

$self_name = 'DataTables';

class DataTables {
	protected $APP;
	protected $GID;
	protected $Breed = array();
	protected $Stdb = array();
	protected $Farms = array();
	protected $Aliases = array();
	protected $id_fam = 1;
	public function __construct($APP, $GID = 'iList') {
		$this->APP = $APP;
		$APP::hello($this->GID = $GID, $this);
	}
	// автоклонирование структуры
	public function redo($NEW) {
		$class_name = __CLASS__;
		$theObj = new $class_name($this->APP, $NEW);
	}

	public function lsBreed() {
		if ( ! we($this->Breed) ) {
			$sql = xsql::get(u('id,name'), 1, 'breeds', 'id_family'
				, esql::ord('name'));
			foreach ( DB::run($sql) as $Line )
				$this->Breed[ $Line['id'] ] = $Line['name'];
		}
		return $this->Breed;
	}

	public function lsAlias() {
		if ( ! we($this->Aliases) ) {
			$sql = xsql::get(u('id,alias'), 1, 'breeds', 'id_family'
				, esql::ord('name'));
			foreach ( DB::run($sql) as $Line )
				$this->Aliases[ $Line['id'] ] = $Line['alias'];
		}
		return $this->Aliases;
	}

	public function lsStdb() {
		if ( ! we($this->Stdb) ) {
			$sql = xsql::get(u('id,name'), no, 'stoodbook', _, esql::ord('id'));
			foreach ( DB::run($sql) as $Line )
				$this->Stdb[ $Line['id'] ] = $Line['name'];
		}
		return $this->Stdb;
	}
	public function lsFarms() {
		if ( ! we($this->Farms) ) {
			$this->Farms[0] = 'Без фермы';
			$sql = xsql::get(u('id,name'), no, 'farms', _, esql::ord('id'));
			foreach ( DB::run($sql) as $Line )
				$this->Farms[ $Line['id'] ] = $Line['name'];
		}
		return $this->Farms;
	}



}

return $self_name;
